


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trasection extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
        
      // ini_set("display_errors", 1);
      // error_reporting(1);
      $this->load->model('Mod_login');
      $this->load->model('Mod_users');
	}

  public function index(){
    $this->Mod_login->is_user_login();
    $this->session->set_userdata('tabName', 'Payments');
    $db  =  $this->mongo_db->customQuery();
    $search['user_role'] = 2;
    if($this->input->post()){
      $data_arr['filter_data'] = $this->input->post();
			$this->session->set_userdata($data_arr);
    }
    $filterData = $this->session->userdata('filter_data');

    if( $filterData['start_date'] != ""  && $filterData['end_date'] != "" ){
      $startDate = $this->mongo_db->converToMongodttime(date($filterData['start_date']));
      $endDate   = $this->mongo_db->converToMongodttime(date($filterData['end_date']));

      $search['pakage_buy_date']  =   ['$gte' => $startDate, '$lte' => $endDate];
    }

    if($filterData['package'] != ""){

      $search['package']  = $filterData['package'];
    }
    
    $search['user_role'] = 2;
    // echo "<pre>";print_r($search);
    $users    =  $db->users->find($search);
    $getUsers =  iterator_to_array($users);

    // echo "<br>Count :".count($getUsers);
    $config['base_url'] = SURL . 'index.php/admin/Trasection/index';
    $config['total_rows'] = count($getUsers);
    $config['per_page'] = 15;
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

    $queryLookup = [
      [
        '$match' => $search
      ],
  
      [
        '$project' => [
          '_id'             =>  ['$toString' => '$_id'],
          'full_name'       => '$full_name',
          'email_address'   => '$email_address',
          'package'         => '$package',
          'profile_image'   => '$profile_image',
          'gender'          => '$gender',
          'pakage_buy_date' => '$pakage_buy_date'
        ]
      ],
  
      [
        '$lookup' => [
          'from' => 'subscriptions',
          'let' => [
            'user_id' =>  '$_id' ,
          ],
          'pipeline' => [
            [
              '$match' => [
                '$expr' => [
                  '$eq' => [
                    '$admin_id',
                    '$$user_id'
                  ]
                ],
              ],
            ],
            
            [
              '$project' => [
                '_id'             =>  ['$toString'  =>  '$_id'],
                'created_date'    =>  '$created_date',
                'amount'          =>  '$amount',
                'currency'        =>  '$currency'
              ]
            ],
          ],
          'as' => 'packageDetails'
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

    $getAgain     =  $db->users->aggregate($queryLookup);
    $trasection   =  iterator_to_array($getAgain);

    $data['trasection'] = $trasection;
    $this->load->view('trasection/payment', $data);
  }

  public function resetFilter(){

    $this->Mod_login->is_user_login();
    $this->session->unset_userdata('filter_data');
    $this->index();
  }
}