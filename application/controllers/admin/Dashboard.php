<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	function __construct(){
		parent :: __construct();

        ini_set("display_errors", 1);
        error_reporting(1); 
        
        //model
        $this->load->model('Mod_login');
        $this->load->model('Mod_users');
        $this->load->model('Mod_reviews');
        $this->load->model('Mod_notification');

	}

    public function index(){
        $this->Mod_login->is_user_login();
        $this->session->set_userdata('tabName', 'Dashboard');
        $db = $this->mongo_db->customQuery();
        $countUsers  = $db->users->count(['user_role' => 2]);

        $activeUsers =  $this->Mod_users->getActiveUsers();
        $data['total_users']    = $countUsers;
        $data['active_users']   = $activeUsers;
        $data['inactive_users'] = (float)($countUsers - $activeUsers);
        $data['totalPayment']   = $this->Mod_users->getPayment();
        
        $count  =  $db->user_reviews->count([]);

        $config['base_url'] = SURL . 'index.php/admin/Dashboard/index';
        $config['total_rows'] = $count;
        $config['per_page'] = 12;
        $config['num_links'] = 5;
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['reuse_query_string'] = TRUE;
        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';
        $config['next_link'] = 'Next<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        if($page !=0) 
        {
        $page = ($page-1) * $config['per_page'];
        }
        $data["links"] = $this->pagination->create_links();
        $page = intval($page);
        $getPendingReview = [
          [
            '$project' =>[
    
              '_id'                           =>  '$_id', 
              'looks'                         =>  '$looks',
              'brain'                         =>  '$brain',
              'career'                        =>  '$career',
              'funny'                         =>  '$funny',
              'intelegent'                    =>  '$intelegent',
              'polite'                        =>  '$polite',
              'date_start_on_time'            =>  '$date_start_on_time',
              'how_was_physical_chemistry'    =>  '$how_was_physical_chemistry',
              'did_you_feel_safe'             =>  '$did_you_feel_safe',
              'did_you_feel_pressured'        =>  '$did_you_feel_pressured',
              'communication_prior'           =>  '$communication_prior',
              'communication_post_date'       =>  '$communication_post_date',
              'dating_experienced'            =>  '$dating_experienced',
              'patner_distracted'             =>  '$patner_distracted',
              'would_you_out_go_again'        =>  '$would_you_out_go_again',
              'recomendation_person_good_date'=>  '$recomendation_person_good_date',
              'your_message'                  =>  '$your_message',
              'admin_id'                      =>  '$admin_id',
              'reviwer_admin_id'              =>  '$reviwer_admin_id', //jis ko review dia ha us ke id
              'personality'                   =>  '$personality',
              'data_experience'               =>  '$data_experience',
              'authenticity'                  =>  '$authenticity',
              'created_date'                  =>  '$created_date',
              'status'                        =>  '$status',
            ]
          ],
          [
            '$lookup' => [
              'from' => 'users',
              'let' => [
                'admin_id' =>    ['$toObjectId' => '$reviwer_admin_id'],
                // {$convert: {input: '$Company', to : 'objectId', onError: '',onNull: ''}}

              ],
              'pipeline' => [
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
                    '_id'           =>  1,
                    'full_name'     => '$full_name',
                    'profile_image' => '$profile_image',
                    'gender'        => '$gender',
                    
                  ]
                ]
              ],
              'as' => 'profileOfReviwer'
            ]
          ],
          [
            '$lookup' => [
              'from' => 'users',
              'let' => [
                'admin_id' =>    ['$toObjectId' => '$admin_id'],
              ],
              'pipeline' => [
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
                    '_id'           =>  1,
                    'full_name'     => '$full_name',
                    'profile_image' => '$profile_image',
                    'gender'        => '$gender',
    
                  ]
                ]
              ],
              'as' => 'reviwerSubmitter'
            ]
          ],
          [
            '$lookup' => [
              'from' => 'user_reviews',
              'let' => [
                'admin_id' =>    ['$toString' => '$reviwer_admin_id'],
              ],
              'pipeline' => [
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
                  '$project' => [
                    '_id'                           => ['$toString' => '$reviwer_admin_id'],
                    'reviwer_admin_id'              => '$reviwer_admin_id',
                    'looks'                         => ['$sum' =>  '$looks'],
                    'brain'                         => ['$sum' =>  '$brain'],
                    'career'                        => ['$sum' =>  '$career'],
                    'date_start_on_time'            => ['$sum' =>  '$date_start_on_time'],
                    'how_was_physical_chemistry'    => ['$sum' =>  '$how_was_physical_chemistry'],
                    'did_you_feel_safe'             => ['$sum' =>  '$did_you_feel_safe'],
                    'did_you_feel_pressured'        => ['$sum' =>  '$did_you_feel_pressured'],
                    'communication_prior'           => ['$sum' =>  '$communication_prior'],
                    'communication_post_date'       => ['$sum' =>  '$communication_post_date'],
                    'dating_experienced'            => ['$sum' =>  '$dating_experienced'],
                    'patner_distracted'             => ['$sum' =>  '$patner_distracted'],
                    'would_you_out_go_again'        => ['$sum' =>  '$would_you_out_go_again'],
                    'recomendation_person_good_date'=> ['$sum' =>  '$recomendation_person_good_date'],
                    'funny'                         => ['$sum' =>  '$funny'],
                    'intelegent'                    => ['$sum' =>  '$intelegent'],
                    'polite'                        => ['$sum' =>  '$polite'],
                    'total'                         => ['$sum' => 1],
                    'created_date'                  => '$created_date'
                  ]
                ],
                [
                  '$group' => [
                    '_id'                           => ['$toString' => '$_id'],
                    'reviwer_admin_id'              => ['$first' => '$reviwer_admin_id'],
                    'looks'                         => ['$sum' =>  '$looks'],
                    'brain'                         => ['$sum' =>  '$brain'],
                    'career'                        => ['$sum' =>  '$career'],
                    'date_start_on_time'            => ['$sum' =>  '$date_start_on_time'],
                    'how_was_physical_chemistry'    => ['$sum' =>  '$how_was_physical_chemistry'],
                    'did_you_feel_safe'             => ['$sum' =>  '$did_you_feel_safe'],
                    'did_you_feel_pressured'        => ['$sum' =>  '$did_you_feel_pressured'],
                    'communication_prior'           => ['$sum' =>  '$communication_prior'],
                    'communication_post_date'       => ['$sum' =>  '$communication_post_date'],
                    'dating_experienced'            => ['$sum' =>  '$dating_experienced'],
                    'patner_distracted'             => ['$sum' =>  '$patner_distracted'],
                    'would_you_out_go_again'        => ['$sum' =>  '$would_you_out_go_again'],
                    'recomendation_person_good_date'=> ['$sum' =>  '$recomendation_person_good_date'],
                    'funny'                         => ['$sum' =>  '$funny'],
                    'intelegent'                    => ['$sum' =>  '$intelegent'],
                    'polite'                        => ['$sum' =>  '$polite'],
                    'total'                         => ['$sum' => 1],
                    'created_date'                  => ['$first' => '$created_date']
                  ]
                ],
                [
                  '$project' => [
                    '_id'                   =>  ['$toString' => '$_id'],
                    'reviwer_admin_id'      =>  '$reviwer_admin_id',
                    'totalPersonality'      =>  ['$multiply' => ['$total', 3]],
                    'totalAuthenticity'     =>  ['$multiply' => ['$total', 3]],
                    'totalDataExpirenced'   =>  ['$multiply' => ['$total', 10]],
                    'authticity1'           =>  ['$sum' =>  ['$looks',  '$brain' , '$career']],
                    'personality1'          =>  ['$sum' =>  ['$funny',  '$intelegent' , '$polite']],              
                    'data_experience1'      =>  ['$sum' =>  ['$recomendation_person_good_date', '$would_you_out_go_again', '$patner_distracted', '$dating_experienced', '$communication_post_date', '$date_start_on_time',  '$how_was_physical_chemistry' , '$did_you_feel_safe', '$did_you_feel_pressured', '$communication_prior']],
                    'created_date'          =>  '$created_date'
                  ]
                ],
          
                [
                  '$project' => [
          
                    '_id'                   =>  ['$toString' => '$_id'],
                    'reviwer_admin_id'      =>  '$reviwer_admin_id',
                    'authticity'            =>  ['$divide' =>[ '$authticity1', '$totalPersonality']],
                    'personality'           =>  ['$divide' =>[ '$personality1', '$totalAuthenticity']] ,              
                    'data_experience'       =>  ['$divide' =>[ '$data_experience1', '$totalDataExpirenced']],
                    'created_date'          =>  '$created_date'
                  ]
                ],
          
                [
                  '$project' => [
          
                    '_id'                   =>  ['$toString' => '$_id'],
                    'reviwer_admin_id'      =>  '$reviwer_admin_id',
                    'authticity'            =>  '$authticity',
                    'personality'           =>  '$personality' ,              
                    'data_experience'       =>  '$data_experience',
                    'overAllRatting1'       =>  ['$sum' => ['$authticity', '$personality', '$data_experience']],
                    'created_date'          =>  '$created_date'
                  ]
                ],
          
                [
                  '$project' => [
          
                    '_id'                   =>  ['$toString' => '$_id'],
                    'reviwer_admin_id'      =>  '$reviwer_admin_id',
                    'authticity'            =>  '$authticity',
                    'personality'           =>  '$personality' ,              
                    'data_experience'       =>  '$data_experience',
                    'overAllRatting'        =>  ['$divide' => ['$overAllRatting1', 3]],
                    'created_date'          =>  '$created_date'
                  ]
                ],
              ],
              'as' => 'reviwerSubmitter_ratting'
            ]
          ],
          [
            '$sort' => ['created_date' => -1 ]
          ],
          [
            '$skip' => intval($page)
          ],
          [
            '$limit'  => intval($config['per_page'])
          ],
          
        ];
    
        $pendingReviews     =  $db->user_reviews->aggregate($getPendingReview);
        $pendingReviewsRes  =  iterator_to_array($pendingReviews);
        unset($pendingReviews);
        $data['pendingReviews'] = $pendingReviewsRes;
        unset($pendingReviewsRes);
        $this->load->view('admin/admin_dashboard', $data);
    }

    public function approveReview(){
        $this->Mod_login->is_user_login();

        $db = $this->mongo_db->customQuery();
        $id  = $this->input->post('id');
        $db->user_reviews->updateOne(['_id' => $this->mongo_db->mongoId($id)], ['$set' => ['status' => 'approved']]);
        return true;
        exit;
    }
    
    public function rejectReview(){
        $this->Mod_login->is_user_login();
        $db = $this->mongo_db->customQuery();
        $id  = $this->input->post('id');
        $db->user_reviews->updateOne(['_id' => $this->mongo_db->mongoId($id)], ['$set' => ['status' => 'reject']]);
        return true;
        exit;
    }

    public function flagReview(){
        $this->Mod_login->is_user_login();
        $db = $this->mongo_db->customQuery();
        $id  = $this->input->post('id');
        $db->user_reviews->updateOne(['_id' => $this->mongo_db->mongoId($id)], ['$set' => ['status' => 'flag']]);
        return true;
        exit;
    }

    public function markAllReadss(){
      $db = $this->mongo_db->customQuery();
      $db->admin_notification->updateMany(['status' => 'pending'], ['$set' => ['status' => 'read']]);
      return true;
    }//end 
}