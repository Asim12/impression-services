<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Twilio\Rest\Client;
class Mod_isValidUser extends CI_Model {
    
    function __construct() {

        parent::__construct();
    }

	public function getUserData($email = null, $password){

        $db = $this->mongo_db->customQuery();

        $where['email_address']  =  $email;
        $where['user_role']      =  1;

        $record  =  $db->users->find($where);
        $data    =  iterator_to_array($record);

        if(count($data) > 0 ){

            if($data[0]['password'] == md5($password) ){

                $userData =[

                    'full_name'             =>  $data[0]['full_name'],
                    'first_name' 			=>  $data[0]['first_name'],
                    'last_name'  			=>  $data[0]['last_name'],
                    'email_address' 		=>  $data[0]['email_address'],
                    'phone_number'   		=>  $data[0]['phone_number'],
                    'login_status'   		=>  true,
                    'profile_image'   		=>  $data[0]['profile_image'],
                    'status'     			=>  'user',
                    'country'   		    =>  $data[0]['country'],
                    'address'			    =>  $data[0]['address'],
                ] ;
                $admin_id =   (string) $data[0]['_id'] ;
			    $this->session->set_userdata('admin_id', $admin_id );

                $data_arr['user_data'] = $userData;
			    $this->session->set_userdata($data_arr);

                $updateRecord = [
    
                    'last_login_time'  => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),  //$this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                    'login_status'     => true,
                ];
    
                $db->users->updateOne($where, ['$set' => $updateRecord]);

                return true;
            }else{

                return false;
            }
        }
        
        return false;
    }

    //generate JWT token
    public function GenerateJWT($user_id){
		$payload = array(

			'admin_id'   => $user_id,
			'date'       => date('Y-m-d H:i:s'),
			'iat'		 => $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('+20 days'))),
		);

		$jwt = JWT::encode($payload, jwt_key);
		return 'Token: '.$jwt;
	}

    //decode JWT
    public function jwtDecode($token){

		$decoded = JWT::decode($token, jwt_key, array('HS256'));
        $db = $this->mongo_db->customQuery();
        if(!empty($decoded->admin_id)){

            $Where['_id'] =  $this->mongo_db->mongoId((string)$decoded->admin_id);
            $getUser   =  $db->users->find($Where );
            $userCount =  iterator_to_array($getUser);
            
            if(count($userCount)>0 ){

                return true;
            }else{

                return false;
            }
            
        }else{

            return false;
        }
        // return $decoded;
	}

}