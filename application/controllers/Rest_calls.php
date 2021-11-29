<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH .'libraries/REST_Controller.php';
// use Twilio\Rest\Client;
/**
* This is an example of a few basic user interaction methods you could use
* all done with a hardcoded array
*
* @package         CodeIgniter
* @subpackage      Rest Server
* @category        Controller
* @author          Phil Sturgeon, Chris Kacerguis
* @license         MIT
* @link            https://github.com/chriskacerguis/codeigniter-restserver
*/
use Twilio\Rest\Client;

Class Rest_calls extends REST_Controller {
   
  function __construct(){
    parent::__construct();
    $this->load->model('Mod_isValidUser');
    $this->load->model('Mod_users');
    $this->load->model('Mod_reviews');
    $this->load->model('Mod_promotion');
    $this->load->model('Mod_notification');
    $this->load->model('Mod_ticket');
    // ini_set("display_errors", 1);
    // error_reporting(1);
  }  

  private function setupTwilio(){
    $sid    = $this->config->item('sid');
    $token  = $this->config->item('twilio_token');
    $twilio = new Client($sid, $token);
    return $twilio;
  }

	public function SendVerificationCode_post(){ // done

        $db = $this->mongo_db->customQuery();
        $usernameAuth = md5($this->input->server('PHP_AUTH_USER'));
        $passwordAuth = md5($this->input->server('PHP_AUTH_PW'));

        $validateCredentials = varify_basic_auth($passwordAuth, $usernameAuth);

        // echo $validateCredentials;
        if($validateCredentials == true || $validateCredentials == 1){

            if($this->post('phoneNumber') ){
                
                $phoneNumber = $this->post('phoneNumber');
                $twilio = $this->setupTwilio();
                $verification = $twilio->verify->v2->services($this->config->item('service_id'))
                                        ->verifications
                                        ->create($phoneNumber, "sms");
                if($verification->sid){

                    $response_array['status'] =  'Successfully  Sended!';
                    $response_array['type']   =   '200'; 
                    $this->set_response($response_array, REST_Controller::HTTP_CREATED);
                }else{


                    $response_array['status'] =  'Sending Failed';
                    $response_array['type']   =   '404';
                    $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
                }
                
            }else{

                $response_array['status'] =  'Playload Missing';
                $response_array['type']   =   '404';
                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }

        }else{

            $response_array['status'] =  'Authorization Failed !!!!!!!!!!!';
            $response_array['validateCredentials'] =  $validateCredentials;
            $response_array['type']   =   '404';

            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}

	public function verifyCode_post(){  //done

		$db = $this->mongo_db->customQuery();
		$usernameAuth = md5($this->input->server('PHP_AUTH_USER'));
		$passwordAuth = md5($this->input->server('PHP_AUTH_PW'));

		$validateCredentials = varify_basic_auth($passwordAuth, $usernameAuth);

		if($validateCredentials == true || $validateCredentials == 1){

			$code   = $this->post('code');
			$phone  = $this->post('phoneNumber');

			$twilio = $this->setupTwilio();
			$verification_check = $twilio->verify->v2->services($this->config->item('service_id'))->verificationChecks
														->create($code, // code
															["to" => $phone]);

			if($verification_check->status == 'approved'){

				$response_array['status'] =  'Phone Verified Successfully';
				$response_array['type']   =   '200';
				$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
			}
			else{

				$response_array['status'] =  'Phone not Vverified';
				$response_array['type']   =   '404';
				$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
			}

		}else{

			$response_array['status'] =  'Authorization Failed !!!!!!!!!!!';
			$response_array['type']   =   '404';
			$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function loginMobile_post(){ 

		$db = $this->mongo_db->customQuery();
		$usernameAuth = md5($this->input->server('PHP_AUTH_USER'));
		$passwordAuth = md5($this->input->server('PHP_AUTH_PW'));

		if($this->post('email_address') && $this->post('password') ) {

			$validateCredentials = varify_basic_auth($passwordAuth, $usernameAuth);

			if($validateCredentials == true || $validateCredentials == 1){

					$email     =  strtolower(trim($this->post('email_address')));
					$password  =  md5(trim($this->post('password')));

					$aggregateQuery = [

						[ 
							'$match' => [
                'email_address'     =>  $email,
                'user_role'        =>  2,
							]
						],
			
						[
							'$project' => [
							'_id'           =>  ['$toString' => '$_id'],
							'email_address' =>  '$email_address',
							'full_name'     =>  '$full_name',
							'occupation'    =>  '$occupation',
							'age'           =>  '$age',
							'location'      =>  '$location',
							'civil_status'  =>  '$civil_status',
							'profile_image' =>  '$profile_image',
							'biography'     =>  '$biography',
							'first_name'    =>  '$first_name', 
							'last_name'     =>  '$last_name',
							'phone_number'  =>  '$phone_number',
							'country'       =>  '$country',
							'password'      =>  '$password',
							'signup_source' =>  '$signup_source',
              'gender'        =>  '$gender',
              'package'       =>  '$package'
							]
						],
            [
              '$limit' => 1
            ]
					];
					$getUser  = $db->users->aggregate($aggregateQuery);
					$userData = iterator_to_array($getUser);

					$userData = $userData[0];

					if( count($userData) > 0 ){

						if( $password  ==   $userData['password']){

							makeLoginStatusTrue($email);
							$token = $this->Mod_isValidUser->GenerateJWT((string)$userData['_id']);
							$response_array = [

                'data'      =>   $userData,
                'status'    =>   'successfully Login!!!!!!!!!!',
                'token'     =>   $token,
                'type'      =>   '200'
							];

							$this->set_response($response_array, REST_Controller::HTTP_CREATED);
						}else{

							$response_array['status'] =  'Incorrect Password!!!!!!!';
							$response_array['type']   =   '404';
							$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
						}
					}else{

						$response_array['status'] =  'Wrong Credentials!';
						$response_array['type']   =   '404';
						$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
					}
			}else{

					$response_array['status'] =  'Authorization Failed !!!!!!!!!!!';
					$response_array['validateCredentials'] =  $validateCredentials;
					$response_array['type']   =   '404';

					$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
			}
		}else{

			$response_array['status'] =  'PayLoad is Missing!!!!!!!!!';
			$response_array['type']   =   '404';
			$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}

	}//end controller

  //user signup
	public function RegisterUser_post(){

		$db = $this->mongo_db->customQuery();

        $usernameAuth = md5($this->input->server('PHP_AUTH_USER'));
        $passwordAuth = md5($this->input->server('PHP_AUTH_PW'));

        $validateCredentials = varify_basic_auth($passwordAuth, $usernameAuth);

        if($validateCredentials == true || $validateCredentials == 1){

            $checkEmailStatus = isEmailExists($this->post('email'));
            $checkPhoneStatus = isPhoneExists((string)$this->post('phone_number'));

            if($checkEmailStatus == true || $checkEmailStatus == 1  || $checkPhoneStatus == true || $checkPhoneStatus == 1){

                $response_array = [
                    'status' =>  'Email or Phone Number Already Exists Please Try With Another Email or Phone Number!',
                    'type'   =>  400
                ];

                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }else{
                //user information check
                $userLocationData = $this->Mod_users->getUserLocation(); 

                $signupData = [

                  'first_name' 			    =>  trim((string)$this->post('first_name')),
                  'last_name'  			    =>  trim((string)$this->post('last_name')),
                  'full_name'           =>  trim($this->post('first_name')).' '.trim($this->post('last_name')),
                  'email_address' 		  =>  strtolower(trim((string)$this->post('email'))),
                  'phone_number'   		  =>  (string)$this->post('phone_number'),
                  'password'     			  =>  md5((string)$this->post('password')),
                  'user_role'     		  =>  2,
                  'created_date'  		  =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                  'login_status'   		  =>  true,
                  'profile_image'   		=>  $this->post('profile_image'),
                  'last_login_time'  	  =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),  
                  'status'     			    =>  'user',
                  'location'            =>  $userLocationData['city'] . ',' . $userLocationData['region'] . ', ' . $userLocationData['country'],
                  'package'             =>  'free',
                  'created_date'        =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                ];

                $checkStatus =  $db->users->insertOne($signupData);
                
                if($checkStatus->getInsertedId() ){

                    $token = $this->Mod_isValidUser->GenerateJWT((string)$checkStatus->getInsertedId() );

                    $userData = [

                        '_id'                =>  (string)$checkStatus->getInsertedId(),
                        'first_name' 			   =>  (string)$this->post('first_name'),
                        'last_name'  			   =>  (string)$this->post('last_name'),
                        'email_address' 		 =>  (string)$this->post('email'),
                        'phone_number'   		 =>  (string)$this->post('phone_number'),
                        'user_role'     		 =>  2,
                        'created_date'  		 =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                        'login_status'   		 =>  true,
                        'profile_image'   	 =>  $this->post('profile_image'),
                        'last_login_time'  	 =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),  
                        'status'     			   =>  'user',
                        'status_update_time' =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                        'location'           =>  $userLocationData['city'] . ',' . $userLocationData['region'] . ', ' . $userLocationData['country'],
                        'package'            =>  'free'
                      ];

                    $response_array = [
                        
                      'data'   =>  $userData,
                      'status' =>  'Your Account is Successfully Created',
                      'type'   =>  200,
                      'token'  =>  $token,

                    ];
                    $this->set_response($response_array, REST_Controller::HTTP_CREATED);
                }else{

                    $response_array = [ 
                        'status' =>  'SomeThing Wrong With your DB',
                        'type'   =>  400
                    ];

                    $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }else{

          $response_array['status'] =  'Authorization Failed !!!!!!!!!!!';
          $response_array['type']   =   '404';
          $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }


	}//end signup 


	public function logoutMobile_post(){

        $db  = $this->mongo_db->customQuery();

        $usernameAuth = md5($this->input->server('PHP_AUTH_USER'));
        $passwordAuth = md5($this->input->server('PHP_AUTH_PW'));

        $validateCredentials = varify_basic_auth($passwordAuth, $usernameAuth);

        if($validateCredentials == true || $validateCredentials == 1){

		$admin_id = $this->post('admin_id');

            if(!empty($admin_id)){

                $db->users->updateOne([ '_id' => $this->mongo_db->mongoId($admin_id) ],  ['$set' => ['login_status' => false] ] );

                $response_array = [
                    'status' =>  'SucessFully Logout',
                    'type'   =>   200
                ];
                $this->set_response($response_array, REST_Controller::HTTP_CREATED);


            }else{

                $response_array = [ 
                    'status' =>  'admin_id is messing in your payload',
                    'type'   =>  404
                ];

                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }

        }else{

            $response_array['status'] =  'Authorization Failed !!!!!!!!!!!';
            $response_array['validateCredentials'] =  $validateCredentials;
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }

	}


	public function updatePassword_post(){

        $db = $this->mongo_db->customQuery();
        $usernameAuth = md5($this->input->server('PHP_AUTH_USER'));
        $passwordAuth = md5($this->input->server('PHP_AUTH_PW'));

        if($this->post('phone_number') && $this->post('password') && $this->post('confirmed_password')){

            $checkUser =  isUserExists($this->post('phone_number'));

            if($checkUser == true || $checkUser == 1) {

                $validateCredentials = varify_basic_auth($passwordAuth, $usernameAuth);
                if($validateCredentials == true || $validateCredentials == 1){

                    $phone_number      = (string)$this->post('phone_number'); 
                    $password          = md5($this->post('password'));  
                    $admin_id          = (string)$this->post('admin_id');
                    $confirmed_passwrd = md5($this->post('confirmed_password'));

                    if($password == $confirmed_passwrd){
                      if(empty($admin_id)){

                        $check = $db->users->updateOne(['phone_number' => $phone_number], ['$set' => ['password' => $password ] ]);
                      }else{

                        $check = $db->users->updateOne(['phone_number' => $phone_number, '_id' => $this->mongo_db->mongoId($admin_id)], ['$set' => ['password' => $password ] ]);
                      }

                      if($check->getModifiedCount() > 0){

                        $message = 'Your Password Is Successfully Updated!!!!!!!!!!!';
                        $type = '200';
                      }elseif($check->getModifiedCount() == 0){

                        $message = 'Password not updated!!';
                        $type = '400';
                      }else{

                        $message = 'This phone number is not Associated with your account';
                        $type = '400';
                      }

                      $response_array = [
                          'status' =>   $message,
                          'type'   =>   $type
                      ];
                      $this->set_response($response_array, REST_Controller::HTTP_CREATED);

                    }else{

                        $response_array  = [
                            'status' =>  'Password and Confirmed_Password are not Matched!!!!!!!!!!!',
                            'type'   =>   '404'
                        ];
                        $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
                    }

                }else{

                    $response_array = [
                        'status' =>  'Authorization Failled!!!!!!!!!!!',
                        'type'   =>   '404',
                    ];
                    $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $response_array = [
                    'status' =>  'User Not Exists Against This Number',
                    'type'   =>   '404'
                ];
                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }

        }else{

            $response_array = [
                'status' =>  'Payload is Missing!!!!!!!!!!!',
                'type'   =>  '404',
            ];
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }

	}
  //social signup


	public function RegisterUserUsingSocial_post(){
        
		$usernameAuth = md5($this->input->server('PHP_AUTH_USER'));
		$passwordAuth = md5($this->input->server('PHP_AUTH_PW'));

		$validateCredentials = varify_basic_auth($passwordAuth, $usernameAuth);
		if($validateCredentials == true || $validateCredentials == 1){

			$db = $this->mongo_db->customQuery();
			$checkEmailStatus = socialExistsCheck((string)$this->post('email_address'), (string)$this->post('signup_source'));

			if($checkEmailStatus['status'] == true){

				$response_array = [
					'status' =>  'Email Already Exists Please Try With Another Email',
					'type'   =>  '400'
				];

				$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
			}else{

				//user information check
				$userLocationData = $this->Mod_users->getUserLocation(); 

				$signupData = [
					'first_name' 			 =>  (string)$this->post('first_name'),
					'last_name'  			 =>  (string)$this->post('last_name'),
					'email_address' 	 =>  (string)$this->post('email_address'),
					'full_name'        =>  $this->post('first_name').' '.$this->post('last_name'),
					'signup_source'    =>  (string)$this->post('signup_source'), //google / facebook
					'user_role'     	 =>  2,
					'created_date'  	 =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
					'login_status'   	 =>  true,
					'profile_image'    =>  $this->post('profile_image'),
					'last_login_time'  =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),  
					'status'     			 =>  'user',
					'location'         =>  $userLocationData['city'] . ',' . $userLocationData['region'] . ', ' . $userLocationData['country'],
          'created_date'     =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
				];

				$where['email_address']  =  (string)$this->post('email_address');

        $contUsers =  $db->users->count($where);
        $checkStatus = '';
        if($contUsers <= 0){

          $checkStatus =  $db->users->insertOne($signupData);
        }				
				if($checkStatus){

					$admin_id = (string)$checkStatus->InsertedId();
				}else{

					$admin_id = $checkEmailStatus['id'];
				}

				if(!empty($admin_id)){

					$token = $this->Mod_isValidUser->GenerateJWT((string)$admin_id);

					$userData = [

						'_id'               =>  $admin_id,
						'first_name' 			  =>  (string)$this->post('first_name'),
						'last_name'  			  =>  (string)$this->post('last_name'),
						'email_address' 		=>  (string)$this->post('email_address'),
						'phone_number'   		=>  $checkEmailStatus['phone_number'],
						'user_role'     		=>  2,
						'created_date'  		=>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
						'login_status'   		=>  true,
						'profile_image'   	=>  $checkEmailStatus['profile_image'],
						'last_login_time'  	=>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),  
						'status'     			  =>  'user',
						'signup_source'     =>  $this->post('signup_source'),
						'location'          =>  $userLocationData['city'] . ',' . $userLocationData['region'] . ', ' . $userLocationData['country'],
            'age'   		        =>  $checkEmailStatus['age'],  
            'biography'   		  =>  $checkEmailStatus['biography'],  
						'civil_status'   		=>  $checkEmailStatus['civil_status'],
						'gender'   		      =>  $checkEmailStatus['gender'],
						'jobPosition'   		=>  $checkEmailStatus['jobPosition'],
						'occupation'   		  =>  $checkEmailStatus['occupation'],
					];
				
					$response_array = [
						
						'data'   =>  $userData,
						'status' =>  'Your Account is Successfully Created',
						'type'   =>  '200',
						'token'  =>  $token,

					];
					$this->set_response($response_array, REST_Controller::HTTP_CREATED);
				}else{

					$response_array = [ 
						'status' =>  'SomeThing Wrong With your DB',
						'type'   =>  '404'
					];

					$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
					}	
				}	
		}else{
			
			$response_array = [ 
				'status' =>  'Authorization Failed!!',
				'type'   =>  '404'
			];
			$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}//end signup 


	public function contactUs_post(){

        if( !empty($this->input->request_headers('Authorization')) ){ 

            $received_Token_Array = $this->input->request_headers('Authorization');
            $received_Token = '';
            $received_Token = $received_Token_Array['authorization'];
            if($received_Token == '' || $received_Token == null || empty($received_Token)){
                $received_Token = $received_Token_Array['Authorization'];
    
            }
            $token          =   trim(str_replace("Token: ", "", $received_Token));
            $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

            if( $tokenArray == true  ||  $tokenArray == 1 ){

                $details = [
                    'email'     =>  strtolower(trim($this->post('email'))),
                    'message'   =>  $this->post('message'),
                    'name'      =>  $this->post('name'),
                    'admin_id'  =>  (string)$this->post('admin_id'),
                ];

                $this->Mod_users->saveContactUs($details);

                $response_array['status'] =  'Successfully Posted';
                $response_array['type']   =   '200';
                $this->set_response($response_array, REST_Controller::HTTP_CREATED);
               
            }else{

                $response_array['status'] =  'Authorization Failed!!';
                $response_array['type']   =   '404';
                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }

        }else{

            $response_array['status'] =  'Headers Are Missing!!!!!!!!!!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end 


	public function searchReview_post(){

    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
      $received_Token = $received_Token_Array['Authorization'];

    }
    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $phone       =   $this->post('phone');
      $full_name   =   $this->post('full_name');

      $data = $this->Mod_users->searchUser($phone, $full_name);
      if(empty($data['reviews']) ){

        $status = 'Record Not Found';
      }else{

        $status = 'Record Found';
      }     
    
      $response_array['status']         =   $status;
      $response_array['users']          =   $data['reviews']; 
      $response_array['countReviews']   =   $data['countReviews']; 
      $response_array['pending_review'] =   $data['pending_review']; 
      
      $response_array['type']   =   '200';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);

    
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =   '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
	}//end


	public function submitReviews_post(){

    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
      $received_Token = $received_Token_Array['Authorization'];

    }
    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $admin_id  =  (string)$this->post('reviwer_admin_id'); //current user id (jis na dia ha review us ke id)
      $getUserPakage = getPakage($admin_id);

      // print_r($getUserPakage);
      $countReview   = getPerWeekReviewCount($admin_id);
      // echo "<br>";print_r($countReview);
      if($getUserPakage  == 'Standard' &&  $countReview >= 8 ){

        $response_array['status'] =  'You are using Standard package you can not submit review more than 8 in a week!';
        $response_array['type']   =   '400';
        $this->set_response($response_array, REST_Controller::HTTP_CREATED);
        return ;
      }elseif($getUserPakage  == 'free' || $getUserPakage  == 'Free' ||  empty($getUserPakage)){

        if($countReview >= 3){

          $response_array['status'] =  'You are using free package you can not submit review more than 3 in a week!';
          $response_array['type']   =   '400';
          $this->set_response($response_array, REST_Controller::HTTP_CREATED);
          return ;
        }
      }

      $data = [
        'looks'                       =>  $this->post('looks'), 
        'brain'                       =>  $this->post('brain'),
        'career'                      =>  $this->post('career'),
        'funny'                       =>  $this->post('funny'),
        'intelegent'                  =>  $this->post('intelegent'),
        'polite'                      =>  $this->post('polite'),
        'date_start_on_time'          =>  $this->post('date_start_on_time'),
        'how_was_physical_chemistry'  =>  $this->post('how_was_physical_chemistry'),
        'did_you_feel_safe'           =>  $this->post('did_you_feel_safe'),
        'did_you_feel_pressured'      =>  $this->post('did_you_feel_pressured'),
        'communication_prior'         =>  $this->post('communication_prior'),
        'communication_post_date'     =>  $this->post('communication_post_date'),
        'dating_experienced'          =>  $this->post('dating_experienced'),
        'patner_distracted'           =>  $this->post('patner_distracted'),
        'would_you_out_go_again'      =>  $this->post('would_you_out_go_again'),
        'recomendation_person_good_date'  =>  $this->post('recomendation_person_good_date'),
        'your_message'                =>  $this->post('your_message'),
        'admin_id'                    =>  (string)$this->post('admin_id'), //current user id (jis na dia ha review us ke id)
        'reviwer_admin_id'            =>  (string)$this->post('reviwer_admin_id'),   //jis ko reviw dena ha us ke id
        'created_date'                =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
        'status'                      =>  'new',
        'review_date'                 =>   $this->mongo_db->converToMongodttime(date($this->post('review_date')))
      ];

      if(!empty($this->post('anonymous'))){

        $data['anonymous'] = $this->post('anonymous');
      }

      $this->Mod_reviews->saveReviews($data);

      $reciver_admin_id   =  (string)$this->post('admin_id'); // reciver id
      $message            =  'give you review';
      $type               =  'review';
      $sender_admin_id    =  (string)$this->post('reviwer_admin_id'); //current user id

      $this->Mod_users->sendNotification($reciver_admin_id, $message, $type, $sender_admin_id );

      $response_array['status'] =  'Successfully Submitted';
      $response_array['type']   =   '200';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =   '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
	}//end


	public function postedReviewsOnMyProfile_post(){

        $received_Token_Array = $this->input->request_headers('Authorization');
        $received_Token = '';
        $received_Token = $received_Token_Array['authorization'];
        if($received_Token == '' || $received_Token == null || empty($received_Token)){
            $received_Token = $received_Token_Array['Authorization'];

        }

        $token          =   trim(str_replace("Token: ", "", $received_Token));
        $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

        if( $tokenArray == true  ||  $tokenArray == 1 ){

            $admin_id    = (string)$this->post('admin_id');

            $reviews =  $this->Mod_reviews->getPostedReviewsOnMyProfile($admin_id);
            $response_array['status']  =  'Fetched Successfully';
            $response_array['type']    =   '200';
            $response_array['reviews'] =   $reviews;
            $this->set_response($response_array, REST_Controller::HTTP_CREATED);
        }else{

            $response_array['status'] =  'Authorization Failed!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end


	public function sendInvitationMessage_post(){

        if( !empty($this->input->request_headers('Authorization')) ){ 

            $received_Token_Array = $this->input->request_headers('Authorization');
            $received_Token = '';
            $received_Token = $received_Token_Array['authorization'];
            if($received_Token == '' || $received_Token == null || empty($received_Token)){
                $received_Token = $received_Token_Array['Authorization'];
    
            }

            $token          =   trim(str_replace("Token: ", "", $received_Token));
            $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

            if( $tokenArray == true  ||  $tokenArray == 1 ){

                $sender_admin_id       =  $this->post('sender_admin_id');
                $reciver_phone_number  =  $this->post('reciver_phone_number');
                $name                  =  $this->post('full_name');       

                $twilio = $this->setupTwilio();
                $message = $twilio->messages->create($reciver_phone_number, // to
                    ["body" => "Greetings from Impressions! Your date $name wants to send  you a review.", "from" => "+16199401291"]

                );

                if($message->sid){

                    $data = [

                        'sender_admin_id'       =>  $sender_admin_id,
                        'reciver_phone_number'  =>  $reciver_phone_number,
                        'name'                  =>  $name,
                        'created_date'  		=>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'))      
                    ];

                    $this->Mod_reviews->saveNotifications($data);

                    $response_array['status'] =  'Notification Send Successfully';
                    $response_array['type']   =   '200';
                    $this->set_response($response_array, REST_Controller::HTTP_CREATED);
                }else{

                    $response_array['status'] =  'Notification Sending Failed!';
                    $response_array['type']   =   '404';
                    $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $response_array['status'] =  'Authorization Failed!!';
                $response_array['type']   =   '404';
                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }

        }else{

            $response_array['status'] =  'Headers Are Missing!!!!!!!!!!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end


	public function profileUpdate_post(){

        $db = $this->mongo_db->customQuery();
        if( !empty($this->input->request_headers('Authorization')) ){ 

            $received_Token_Array = $this->input->request_headers('Authorization');
            $received_Token = '';
            $received_Token = $received_Token_Array['authorization'];
            if($received_Token == '' || $received_Token == null || empty($received_Token)){
                $received_Token = $received_Token_Array['Authorization'];
    
            }
            $token          =   trim(str_replace("Token: ", "", $received_Token));
            $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

            if( $tokenArray == true  ||  $tokenArray == 1 ){

                $admin_id  =  (string)$this->post('admin_id');
                
                $checking = isUserExistsUsingAdminId($admin_id);

                if($checking == true || $checking == 1){

                    if(!empty($this->post('location'))){
                        $UpdationData['location'] = $this->post('location');
                    }
                    if( !empty($this->post('civil_status'))){
                        $UpdationData['civil_status'] = $this->post('civil_status');

                    }
                    if(!empty($this->post('age'))){
                        $UpdationData['age'] = $this->post('age');

                    }
                    if(!empty($this->post('occupation'))){
                      $UpdationData['occupation'] = $this->post('occupation');

                    }
                    if(!empty($this->post('bioigraphy'))){
                      $UpdationData['biography'] = $this->post('bioigraphy');

                    }
                    if(!empty($this->post('jobPosition'))){
                        $UpdationData['jobPosition'] = $this->post('jobPosition');

                    }
                    if(!empty($this->post('gender'))){
                        $UpdationData['gender'] = $this->post('gender');

                    }
                   
                    $this->Mod_users->updateProfileData($admin_id, $UpdationData);
    
                    $aggregateQuery = [
                        [ 
                          '$match' => [
  
                            '_id'  =>  $this->mongo_db->mongoId($admin_id)
                          ]
                        ],
                
                        [
                          '$project' => [
                            '_id'           =>  ['$toString' => '$_id'],
                            'email_address' =>  '$email_address',
                            'full_name'     =>  '$full_name',
                            'occupation'    =>  '$occupation',
                            'age'           =>  '$age',
                            'location'      =>  '$location',
                            'civil_status'  =>  '$civil_status',
                            'profile_image' =>  '$profile_image',
                            'biography'     =>  '$biography',
                            'first_name'    =>  '$first_name', 
                            'last_name'     =>  '$last_name',
                            'phone_number'  =>  '$phone_number',
                            'country'       =>  '$country',
                            'password'      =>  '$password',
                            'signup_source' =>  '$signup_source',
                            'gender'        =>  '$gender',
                            
                          ]
                        ],
                    ];
                    $getUser  = $db->users->aggregate($aggregateQuery);
                    $userData = iterator_to_array($getUser);
    
                    $userData = $userData[0];
                    $token = $this->Mod_isValidUser->GenerateJWT((string)$userData['_id']);
                    $response_array = [

                      'data'      =>   $userData,
                      'status'    =>   'Your Profile is Successfully Updated',
                      'token'     =>   $token,
                      'type'      =>   '200'
                    ];
                    $this->set_response($response_array, REST_Controller::HTTP_CREATED);
                }else{

                    $response_array['status'] =  'User Not Exists';
                    $response_array['type']   =   '404';
                    $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
                }
            }else{

                $response_array['status'] =  'Authorization Failed!!';
                $response_array['type']   =   '404';
                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }
        }else{

            $response_array['status'] =  'Headers Are Missing!!!!!!!!!!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end


	public function addCard_post(){

        if( !empty($this->input->request_headers('Authorization')) ){ 

            $received_Token_Array = $this->input->request_headers('Authorization');
            $received_Token = '';
            $received_Token = $received_Token_Array['authorization'];
            if($received_Token == '' || $received_Token == null || empty($received_Token)){
                $received_Token = $received_Token_Array['Authorization'];
    
            }

            $token          =   trim(str_replace("Token: ", "", $received_Token));
            $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

            if( $tokenArray == true  ||  $tokenArray == 1 ){


                //code here

            }else{

                $response_array['status'] =  'Authorization Failed!!';
                $response_array['type']   =   '404';
                $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
            }
        }else{

            $response_array['status'] =  'Headers Are Missing!!!!!!!!!!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end


	public function submitUsersImpressionClicksViews_post(){

		if( !empty($this->input->request_headers('Authorization')) ){ 

			$received_Token_Array = $this->input->request_headers('Authorization');
			$received_Token = '';
			$received_Token = $received_Token_Array['authorization'];
			if($received_Token == '' || $received_Token == null || empty($received_Token)){
				$received_Token = $received_Token_Array['Authorization'];
	
			}

			$token          =   trim(str_replace("Token: ", "", $received_Token));
			$tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

			if( $tokenArray == true  ||  $tokenArray == 1 ){

				if($this->post('admin_id') && $this->post('promotion_id') && $this->post('type') ){

					$admin_id      =   (string)$this->post('admin_id');
					$promotion_id  =   (string)$this->post('promotion_id');
					$type          =   (string)$this->post('type');
					
					$this->Mod_promotion->saveImpressions($admin_id, $promotion_id, $type);

					$response_array['status'] =  'Successfully Submited!';
					$response_array['type']   =   '200';
					$this->set_response($response_array, REST_Controller::HTTP_CREATED);
				}else{

					$response_array['status'] =  'Payload Missing!';
					$response_array['type']   =   '404';
					$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
				}
					
			}else{

				$response_array['status'] =  'Authorization Failed!!';
				$response_array['type']   =   '404';
				$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
			}
		}else{

			$response_array['status'] =  'Headers Are Missing!!!!!!!!!!!';
			$response_array['type']   =   '404';
			$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}//end


	public function myPostedReviews_post(){

		$received_Token_Array = $this->input->request_headers('Authorization');
		$received_Token = '';
		$received_Token = $received_Token_Array['authorization'];
		if($received_Token == '' || $received_Token == null || empty($received_Token)){
			
			$received_Token = $received_Token_Array['Authorization'];
		}

		$token          =   trim(str_replace("Token: ", "", $received_Token));
		$tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

		if( $tokenArray == true  ||  $tokenArray == 1 ){

			$admin_id    = (string)$this->post('admin_id');

			$reviews =  $this->Mod_reviews->getMyPostedReviews($admin_id);
			$response_array['status']  =  'Fetched Successfully';
			$response_array['type']    =   '200';
			$response_array['reviews'] =   $reviews;
			$this->set_response($response_array, REST_Controller::HTTP_CREATED);
		}else{

		$response_array['status'] =  'Authorization Failed!!';
		$response_array['type']   =   '404';
		$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}//end


	public function getPromotions_post(){

		$received_Token_Array = $this->input->request_headers('Authorization');
		$received_Token = '';
		$received_Token = $received_Token_Array['authorization'];
		if($received_Token == '' || $received_Token == null || empty($received_Token)){
			
			$received_Token = $received_Token_Array['Authorization'];
		}

		$token          =   trim(str_replace("Token: ", "", $received_Token));
		$tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

		if( $tokenArray == true  ||  $tokenArray == 1 ){

		$allPromotions = $this->Mod_promotion->getAllPromotions();
	
		$response_array['status']       =  'Data Fetched!';
		$response_array['promotions']   =   $allPromotions;
		$response_array['type']         =   '200';

		$this->set_response($response_array, REST_Controller::HTTP_CREATED);
		
		}else{

		$response_array['status'] =  'Authorization Failed!!';
		$response_array['type']   =   '404';
		$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}//end function


	public function getApprovedReviews_post(){
		$received_Token_Array = $this->input->request_headers('Authorization');
		$received_Token = '';
		$received_Token = $received_Token_Array['authorization'];
		if($received_Token == '' || $received_Token == null || empty($received_Token)){
			
			$received_Token = $received_Token_Array['Authorization'];
		}

		$token          =   trim(str_replace("Token: ", "", $received_Token));
		$tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

		if( $tokenArray == true  ||  $tokenArray == 1 ){

		$admin_id = (string)$this->post('admin_id');
		$reviews  = $this->Mod_reviews->getApproveReview($admin_id);

		$response_array['status']  =  'Successfully Approved!';
		$response_array['type']    =  '200';
		$response_array['reviews'] =  $reviews;

		$this->set_response($response_array, REST_Controller::HTTP_CREATED);

		}else{

		$response_array['status'] =  'Authorization Failed!!';
		$response_array['type']   =   '404';
		$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}//end function


	public function getPendingReviews_post(){
		$received_Token_Array = $this->input->request_headers('Authorization');
		$received_Token = '';
		$received_Token = $received_Token_Array['authorization'];
		if($received_Token == '' || $received_Token == null || empty($received_Token)){
			
			$received_Token = $received_Token_Array['Authorization'];
		}

		$token          =   trim(str_replace("Token: ", "", $received_Token));
		$tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

		if( $tokenArray == true  ||  $tokenArray == 1 ){
			$admin_id = (string)$this->post('admin_id');
			$reviews  = $this->Mod_reviews->getPendingReview($admin_id);
			
			$response_array['status']  =  'Authorization Failed!!';
			$response_array['type']    =  '200';
			$response_array['reviews'] =  $reviews;

			$this->set_response($response_array, REST_Controller::HTTP_CREATED);

		}else{

			$response_array['status'] =  'Authorization Failed!!';
			$response_array['type']   =   '404';
			$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}//end function


	public function reviewStatusChanged_post(){
		$received_Token_Array = $this->input->request_headers('Authorization');
		$received_Token = '';
		$received_Token = $received_Token_Array['authorization'];
		if($received_Token == '' || $received_Token == null || empty($received_Token)){
			
			$received_Token = $received_Token_Array['Authorization'];
		}
		$token          =   trim(str_replace("Token: ", "", $received_Token));
		$tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

		if( $tokenArray == true  ||  $tokenArray == 1 ){

			$review_id  =  (string)$this->post('review_id');
			$type       =  $this->post('type');
			if($type == 'flag'){

        $reason = $this->post('reason'); 
			}
			$reviews  = $this->Mod_reviews->makeReviewApproveRejectFlag($review_id, $type, $reason);
			$stausCheckReview = '';
			if($type == 'flag'){
				$stausCheckReview = 'Flag';
        
			}elseif($type == 'reject'){
				$stausCheckReview = 'Reject';
			}else{
				$stausCheckReview = 'Approved';
			}

			$db = $this->mongo_db->customQuery();
			$reviewData    =  $db->user_reviews->find([ '_id' => $this->mongo_db->mongoId($review_id) ]);
			$reviewDataRes =  iterator_to_array($reviewData);

			$reciver_admin_id =  $reviewDataRes[0]['reviwer_admin_id']; 
			$sender_admin_id  =  $reviewDataRes[0]['admin_id'];
			$message          =  $stausCheckReview.' your review';
			$type             =  'review';
			$this->Mod_users->sendNotification($reciver_admin_id, $message, $type, $sender_admin_id );

			if($reviews == true){

        $response_array['status']  =  'Updated Successfully!';
			}else{

        $response_array['status']  =  'You can not Change the Status Twice!';
			}
			$response_array['type']    =  '200';
			$response_array['reviews'] =  $reviews;
			$this->set_response($response_array, REST_Controller::HTTP_CREATED);
		}else{

			$response_array['status'] =  'Authorization Failed!!';
			$response_array['type']   =   '404';
			$this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
		}
	}//end function


	public function submitMyNotes_post(){
        $received_Token_Array = $this->input->request_headers('Authorization');
        $received_Token = '';
        $received_Token = $received_Token_Array['authorization'];
        if($received_Token == '' || $received_Token == null || empty($received_Token)){
            
            $received_Token = $received_Token_Array['Authorization'];
        }

        $token          =   trim(str_replace("Token: ", "", $received_Token));
        $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

        if( $tokenArray == true  ||  $tokenArray == 1 ){

            $insertNotes = [
                'date'          =>   $this->mongo_db->converToMongodttime(date($this->post('date'))),
                'created_date'  =>   $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                'admin_id'      =>   (string)$this->post('admin_id'),
                'notes'         =>   (string)$this->post('notes'),
            ];

            $this->Mod_reviews->submitNote($insertNotes);
            $response_array['type']   =   '200';
            $response_array['status']   =   'Successfully Submitted!!';
            $this->set_response($response_array, REST_Controller::HTTP_CREATED);
        }else{

            $response_array['status'] =  'Authorization Failed!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end function


	public function getMyNotes_post(){
        $received_Token_Array = $this->input->request_headers('Authorization');
        $received_Token = '';
        $received_Token = $received_Token_Array['authorization'];
        if($received_Token == '' || $received_Token == null || empty($received_Token)){
            
            $received_Token = $received_Token_Array['Authorization'];
        }

        $token          =   trim(str_replace("Token: ", "", $received_Token));
        $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

        if( $tokenArray == true  ||  $tokenArray == 1 ){

            $admin_id  = (string)$this->post('admin_id');

            $notes  =  $this->Mod_reviews->getNotes($admin_id);
            $response_array['status'] =   'Fetched!';
            $response_array['type']   =   '200';
            $response_array['notes']  =   $notes;
            $this->set_response($response_array, REST_Controller::HTTP_CREATED);
        }else{

            $response_array['status'] =  'Authorization Failed!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end function


	public function saveDeviceToken_post(){
        $received_Token_Array = $this->input->request_headers('Authorization');
        $received_Token = '';
        $received_Token = $received_Token_Array['authorization'];
        if($received_Token == '' || $received_Token == null || empty($received_Token)){
            
            $received_Token = $received_Token_Array['Authorization'];
        }

        $token          =   trim(str_replace("Token: ", "", $received_Token));
        $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

        if( $tokenArray == true  ||  $tokenArray == 1 ){

            $admin_id     =  (string)$this->post('admin_id');
            $device_token =  (string)$this->post('device_token');

            $this->Mod_users->saveDeviceTken($admin_id, $device_token);
            $response_array['status'] =   'Token updated!';
            $response_array['type']   =   '200';
            $this->set_response($response_array, REST_Controller::HTTP_CREATED);
        }else{

            $response_array['status'] =  'Authorization Failed!!';
            $response_array['type']   =   '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
	}//end


	public function notificationTesting_post(){

    $reciver_admin_id  =  $this->post('reciver_admin_id');
    $message           =  $this->post('message');
    $type              =  $this->post('status');
    $sender_admin_id   =  $this->post('sender_admin_id');

    $this->Mod_users->sendNotification($reciver_admin_id, $message, $type, $sender_admin_id );
    $this->set_response(['status' => 'sended'], REST_Controller::HTTP_CREATED);
  }


  public function getNotifications_post(){
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }

    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $admin_id   =  (string)$this->post('admin_id');

      $getData = $this->Mod_users->getNotification($admin_id);

      $response_array['status']       =   'Fetched!';
      $response_array['type']         =   '200';
      $response_array['notitication'] =   $getData;
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =   '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
	}//end


	public function updateSubscriptionPlan_post(){
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }

    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $adminId          =  (string)$this->post('admin_id');
      $SubscriptionPlan =  str_replace(" (Impressions)","",$this->post('SubscriptionPlan')); 
      $amount           =  (float)$this->post('amount');
      $currency         =  (float)$this->post('currency');
      $new_transaction  =  (float)$this->post('new_transaction');

      $this->Mod_users->UpdatePlan($adminId, $SubscriptionPlan, $amount, $currency, $new_transaction);

      $getfullName   =  $this->Mod_users->getUserName($adminId);
      $insertAdminNotification = [

        'admin_id'          =>  $adminId,
        'message'           =>  ($getfullName.' has buy '.$SubscriptionPlan.' Subscription'),
        'status'            =>  'pending',
        'created_date'      =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
        'SubscriptionPlan'  =>  $SubscriptionPlan
      ];

      $db = $this->mongo_db->customQuery();
      $db->admin_notification->insertOne($insertAdminNotification);
      $response_array['status'] =  'Subscription Plan Updated';
      $response_array['type']   =   '200';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);

    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
	}//end 


	public function updatePhoneNumber_post(){
        $db = $this->mongo_db->customQuery();
        $received_Token_Array = $this->input->request_headers('Authorization');
        $received_Token = '';
        $received_Token = $received_Token_Array['authorization'];
        if($received_Token == '' || $received_Token == null || empty($received_Token)){
            
          $received_Token = $received_Token_Array['Authorization'];
        }

        $token          =   trim(str_replace("Token: ", "", $received_Token));
        $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

        if( $tokenArray == true  ||  $tokenArray == 1 ){

            $adminId       =  (string)$this->post('admin_id');
            $phoneNumber   =  $this->post('phoneNumber');

            $status = $this->Mod_users->UpdatePhone($adminId, $phoneNumber);
            if($status == true || $status == 1){

              $message = 'Phone Number is Updated!';
              $type    = '200';
            }else{

              $message = 'Phone Number already exists!';
              $type    = '400';
            }

            $aggregateQuery = [

                [ 
                    '$match' => [
                      '_id' => $this->mongo_db->mongoId($adminId)

                    ]
                ],
        
                [
                  '$project' => [
                    '_id'           =>  ['$toString' => '$_id'],
                    'email_address' =>  '$email_address',
                    'full_name'     =>  '$full_name',
                    'occupation'    =>  '$occupation',
                    'age'           =>  '$age',
                    'location'      =>  '$location',
                    'civil_status'  =>  '$civil_status',
                    'profile_image' =>  '$profile_image',
                    'bioigraphy'    =>  '$bioigraphy',
                    'first_name'    =>  '$first_name', 
                    'last_name'     =>  '$last_name',
                    'phone_number'  =>  '$phone_number',
                    'country'       =>  '$country',
                    'password'      =>  '$password',
                    'signup_source' =>  '$signup_source'
                  ]
                ],
        
                [
                  '$lookup' => [
                    "from" => "user_reviews",
                    "let" => [
                      "admin_id" =>  '$_id'
                    ],
                    "pipeline" => [
                      [
                        '$match' => [
                          '$expr' => [
                            '$eq' => [
                              '$admin_id',
                              '$$admin_id'
                            ]
                          ],
                          
                        ],
                      ],
        
                      [
                        '$project' => [
                          '_id'              =>   '$admin_id',
                          'reviwer_admin_id' =>   '$reviwer_admin_id',
                          'personality'      =>   ['$sum' => '$personality'],
                          'data_experience'  =>   ['$sum' => '$data_experience'],
                          'authenticity'     =>   ['$sum' => '$authenticity'],
                          'total'            =>   ['$sum' => 1]
                        ]
                      ],
                      [
                        '$project' => [
                          '_id'              =>   '$_id',
                          'personality'      =>   ['$divide' => [ '$personality', '$total']],
                          'data_experience'  =>   ['$divide' => [ '$data_experience', '$total']] ,
                          'authenticity'     =>   ['$divide' => [ '$authenticity', '$total']],
                          'reviwer_admin_id' =>   '$reviwer_admin_id',
                        ]
                      ],
                      [
                        '$lookup' => [
                          "from" => "users",
                          "let" => [
                            "admin_id" =>  [ '$toObjectId' => '$reviwer_admin_id']
                          ],
                          "pipeline" => [
                            [
                              '$match' => [
                                '$expr' => [
                                  '$eq' => [
                                    '$_id',
                                    '$$admin_id'
                                  ]
                                ],
                                
                              ],
                            ],
              
                            [
                              '$project' => [
                                '_id'           =>  ['$toString' => '$_id'],
                                'email_address' =>  '$email_address',
                                'full_name'     =>  '$full_name',
                                'occupation'    =>  '$occupation',
                                'age'           =>  '$age',
                                'location'      =>  '$location',
                                'civil_status'  =>  '$civil_status',
                                'profile_image' =>  '$profile_image',
                                'bioigraphy'    =>  '$bioigraphy'
                              ]
                            ],
        
                            [
                              '$lookup' => [
                                "from" => "user_reviews",
                                "let" => [
                                  "admin_id" =>  '$_id'
                                ],
                                "pipeline" => [
                                  [
                                    '$match' => [
                                      '$expr' => [
                                        '$eq' => [
                                          '$reviwer_admin_id',
                                          '$$admin_id'
                                        ]
                                      ],
                                      
                                    ],
                                  ],
                    
                                  [
                                    '$group' => [
                                      '_id'           =>  '$reviwer_admin_id',
                                      'rating'        =>  ['$first' => '3']
                                    ]
                                  ],
                                ],
                                'as' => 'reviewer_reviews'
                              ]
                            ],
                          ],
                          'as' => 'reviewer_profile'
                        ]
                      ],
        
                    ],
                    'as' => 'reviews'
                  ]
                ],
            ];
            $getUser  = $db->users->aggregate($aggregateQuery);
            $userData = iterator_to_array($getUser);

            $token = $this->Mod_isValidUser->GenerateJWT((string)$userData[0]['_id']);
            $response_array = [

              'data'      =>   $userData[0],
              'status'    =>   $message,
              'token'     =>   $token,
              'type'      =>   $type
            ];

            $this->set_response($response_array, REST_Controller::HTTP_CREATED);
        }else{

            $response_array['status'] =  'Authorization Failed!!';
            $response_array['type']   =  '404';
            $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
        }
  }//end 


  public function markNotificationAsRead_post(){
    $db = $this->mongo_db->customQuery();
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }

    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $adminId        =  (string)$this->post('admin_id');
      $notificationId =  (string)$this->post('notificationId');
      
      $this->Mod_notification->markAsRead($adminId, $notificationId); 

      $response_array['status'] =  'Notification Status Updated!';
      $response_array['type']   =  '200';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
  }//end


	public function updateProfileImage_post(){
    $db = $this->mongo_db->customQuery();
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }

    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $adminId       =  (string)$this->post('admin_id');
      $profile_image =  $this->post('profile_image');

      $this->Mod_users->UpdateProfileImage($adminId, $profile_image);
      $aggregateQuery = [

        [ 
          '$match' => [
            '_id' => $this->mongo_db->mongoId($adminId)

          ]
        ],
  
        [
          '$project' => [
            '_id'           =>  ['$toString' => '$_id'],
            'email_address' =>  '$email_address',
            'full_name'     =>  '$full_name',
            'occupation'    =>  '$occupation',
            'age'           =>  '$age',
            'location'      =>  '$location',
            'civil_status'  =>  '$civil_status',
            'profile_image' =>  '$profile_image',
            'bioigraphy'    =>  '$bioigraphy',
            'first_name'    =>  '$first_name', 
            'last_name'     =>  '$last_name',
            'phone_number'  =>  '$phone_number',
            'country'       =>  '$country',
            'password'      =>  '$password',
            'signup_source' =>  '$signup_source'
          ]
        ],
  
        [
          '$lookup' => [
            "from" => "user_reviews",
            "let" => [
              "admin_id" =>  '$_id'
            ],
            "pipeline" => [
              [
                '$match' => [
                  '$expr' => [
                    '$eq' => [
                      '$admin_id',
                      '$$admin_id'
                    ]
                  ],
                  
                ],
              ],

              [
                '$project' => [
                  '_id'              =>   '$admin_id',
                  'reviwer_admin_id' =>   '$reviwer_admin_id',
                  'personality'      =>   ['$sum' => '$personality'],
                  'data_experience'  =>   ['$sum' => '$data_experience'],
                  'authenticity'     =>   ['$sum' => '$authenticity'],
                  'total'            =>   ['$sum' => 1]
                ]
              ],
              [
                '$project' => [
                  '_id'              =>   '$_id',
                  'personality'      =>   ['$divide' => [ '$personality', '$total']],
                  'data_experience'  =>   ['$divide' => [ '$data_experience', '$total']] ,
                  'authenticity'     =>   ['$divide' => [ '$authenticity', '$total']],
                  'reviwer_admin_id' =>   '$reviwer_admin_id',
                ]
              ],
              [
                '$lookup' => [
                  "from" => "users",
                  "let" => [
                    "admin_id" =>  [ '$toObjectId' => '$reviwer_admin_id']
                  ],
                  "pipeline" => [
                    [
                      '$match' => [
                        '$expr' => [
                          '$eq' => [
                            '$_id',
                            '$$admin_id'
                          ]
                        ],
                        
                      ],
                    ],
      
                    [
                      '$project' => [
                        '_id'           =>  ['$toString' => '$_id'],
                        'email_address' =>  '$email_address',
                        'full_name'     =>  '$full_name',
                        'occupation'    =>  '$occupation',
                        'age'           =>  '$age',
                        'location'      =>  '$location',
                        'civil_status'  =>  '$civil_status',
                        'profile_image' =>  '$profile_image',
                        'bioigraphy'    =>  '$bioigraphy'
                      ]
                    ],

                    [
                      '$lookup' => [
                        "from" => "user_reviews",
                        "let" => [
                          "admin_id" =>  '$_id'
                        ],
                        "pipeline" => [
                          [
                            '$match' => [
                              '$expr' => [
                                '$eq' => [
                                  '$reviwer_admin_id',
                                  '$$admin_id'
                                ]
                              ],
                              
                            ],
                          ],
            
                          [
                            '$group' => [
                              '_id'           =>  '$reviwer_admin_id',
                              'rating'        =>  ['$first' => '3']
                            ]
                          ],
                        ],
                        'as' => 'reviewer_reviews'
                      ]
                    ],
                  ],
                  'as' => 'reviewer_profile'
                ]
              ],

            ],
            'as' => 'reviews'
          ]
        ],
      ];
      $getUser  = $db->users->aggregate($aggregateQuery);
      $userData = iterator_to_array($getUser);

      $token = $this->Mod_isValidUser->GenerateJWT((string)$userData[0]['_id']);
      $response_array = [

        'data'      =>   $userData[0],
        'status'    =>   'Phone Number is Updated!',
        'token'     =>   $token,
        'type'      =>   '200'
      ];

      $this->set_response($response_array, REST_Controller::HTTP_CREATED);
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
  }//end 


  public function createTicket_post(){
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }

    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $admin_id =  (string)$this->post('admin_id');
      $insertTicket = [
        'subject'      =>  $this->post('subject'),
        'image'        =>  $this->post('image'),
        'admin_id'     =>  $admin_id,
        'message'      =>  $this->post('message'),
        'subject'      =>  $this->post('subject'),
        'status'       =>  'pending',
        'created_date' =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
      ];

      $this->Mod_ticket->create($insertTicket, $admin_id);

      $response_array['status'] =  'Ticket is Submitted';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
  }//end


  public function ticketReply_post(){
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }

    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $tickerReply = [

        'ticket_id'   =>  $this->post('ticket_id'),
        'admin_id'    =>  $this->post('admin_id'),
        'message'     =>  $this->post('message'),
        'status'      =>  'new',
        'created_date'=>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
      ];
      $ticketResponseData = $this->Mod_ticket->sendMessage($tickerReply);

      $response_array['status'] =  'Reply Send';
      $response_array['data']   =  $ticketResponseData;
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);

    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
  }//end


  public function changeTicketStatus_post(){

    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }

    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $ticket_id =  (string)$this->post('ticket_id');
      $status    =  (string)$this->post('status');
      $this->Mod_ticket->changeTicketStatus($ticket_id, $status);

      $response_array['status'] =  'Updated Successfully!';
      $response_array['type']   =  '200';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
  }//end


  public function getAllTicket_post(){
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }
    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $admin_id  =  $this->post('admin_id');
      $tData = $this->Mod_ticket->getTickts($admin_id);

      $response_array['data']   =  $tData;
      $response_array['type']   =  '200';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);
    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
  }//end

  public function getUserTransaction_post(){
    $received_Token_Array = $this->input->request_headers('Authorization');
    $received_Token = '';
    $received_Token = $received_Token_Array['authorization'];
    if($received_Token == '' || $received_Token == null || empty($received_Token)){
        
      $received_Token = $received_Token_Array['Authorization'];
    }
    $token          =   trim(str_replace("Token: ", "", $received_Token));
    $tokenArray     =   $this->Mod_isValidUser->jwtDecode((string)$token);

    if( $tokenArray == true  ||  $tokenArray == 1 ){

      $admin_id = (string)$this->post('admin_id');
      $trasections = $this->Mod_users->getTrasection($admin_id);

      $response_array['data']   =  $trasections;
      $response_array['type']   =  '200';
      $this->set_response($response_array, REST_Controller::HTTP_CREATED);

    }else{

      $response_array['status'] =  'Authorization Failed!!';
      $response_array['type']   =  '404';
      $this->set_response($response_array, REST_Controller::HTTP_NOT_FOUND);
    }
  }//end
}//end controller                                