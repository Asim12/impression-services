
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	function __construct()
	{
		parent :: __construct();

        // ini_set("display_errors", 1);
        // error_reporting(1);
        $this->load->model('Mod_login');
        $this->load->model('Mod_users');
	}

    public function users(){
        $this->Mod_login->is_user_login();
        $this->session->set_userdata('tabName', 'Users');
        $db  =  $this->mongo_db->customQuery();
        $users    =  $db->users->find(['user_role' => 2]);
        $getUsers =  iterator_to_array($users);

        $config['base_url'] = SURL . 'index.php/admin/users/users';
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

        $condition = array('sort' => array('created_date' => -1), 'limit' => intval($config['per_page']), 'skip' =>  intval($page));

        $getAgain  =  $db->users->find(['user_role' => 2], $condition);
        $records   =  iterator_to_array($getAgain);
        $data['users']          =   $records;

        $activeUsers =  $this->Mod_users->getActiveUsers();

        $data['totalUsers']     =   count($getUsers); 
        $data['active_users']   =   $activeUsers;
        $data['inactive_users'] =   (float)(count($getUsers) - $activeUsers);

        $this->load->view('users/users', $data);
    }
}
