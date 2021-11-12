<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
        
        ini_set("display_errors", 1);
        error_reporting(1);
        $this->load->model('Mod_login');

	}

    public function index(){
        $this->Mod_login->is_user_login();
        $this->session->set_userdata('tabName', 'Promotions/ Ads');
        $db = $this->mongo_db->customQuery();
        $search = [];
        if($this->input->get('type') == 'videos'){

            $this->session->set_userdata('type', 'video');
			$search['type'] = 'video';

        }elseif($this->input->get('type') == 'images'){

			$search['type'] = 'image';
            $this->session->set_userdata('type', 'image');
        }else{
            $this->session->set_userdata('type', 'all');   
        }
        $count =  $db->promotion->find($search);
        $countData =  iterator_to_array($count);

        $config['base_url'] = SURL . 'index.php/admin/Promotion/index';
        $config['total_rows'] = count($countData);
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

        $condition =  [ ['sort' => ['created_date' => -1]], ['skip' => intval($page) ], ['limit' => intval($config['per_page'])] ];
        
        $getPromotions = $db->promotion->find($search, $condition);
        $promotions    = iterator_to_array($getPromotions);
        $data['promotions'] = $promotions;;
        $this->load->view('promotion/promotion', $data);
    }

    public function submitPromotion(){
        $this->Mod_login->is_user_login();
        $this->session->set_userdata('tabName', 'Promotions/ Ads');
        $db = $this->mongo_db->customQuery();
        $start_date =  $this->mongo_db->converToMongodttime($this->input->post('startDate'));
        $end_date   =  $this->mongo_db->converToMongodttime($this->input->post('endDate'));
        
        $config['upload_path']  =   FCPATH.'assets/uploads';
        $config1['upload_path']  =   FCPATH.'assets/uploads';

        $inArray = ['image/gif', 'image/jpg','image/jpeg','image/png', 'image/pdf', 'image/bmp'];

        if (is_array($_FILES) && in_array($_FILES['file']['type'], $inArray) ) {

            $config['allowed_types']        =   'gif|jpg|jpeg|png|pdf|bmp';// $_FILES['file']['type']; 
            $config['max_size']             =   $_FILES['file']['size'];
            $config['max_width']            =   1024;
            $config['max_height']           =   768;
            $config['encrypt_name']         =   TRUE;
            $type = 'image';
            $this->load->library('upload', $config);
        }else {
           
            $config1['allowed_types']   =  'avi|flv|wmv|mp3|wma|mp4|webm|video/webm|video/mp4'; 
            $config1['max_size']        =  '50240';
            $confi1['encrypt_name']     =  TRUE;
            $type                       = 'video';
            $this->load->library('upload', $config1);
        }
        if ( ! $this->upload->do_upload('file')){

            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error['error']);
            redirect(base_url() . 'index.php/admin/Promotion/index');
        }
        else{

            $data = array('upload_data' => $this->upload->data());
            
            $imagePath =  str_replace("/var/www/html/impressions-services/","", $data['upload_data']['full_path']);
            $insertArray = [
                'image'         =>  $imagePath,
                'add_name'      =>  $this->input->post('addName'),
                'start_date'    =>  $start_date,
                'end_date'      =>  $end_date,
                'created_date'  =>  $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s')),
                'status'        =>  'new',
                'publication'   =>  ($this->input->post('publication') ) ? 'yes' : 'no',
                'type'          =>  $type,
                'clicks'        =>  [],
                'view'          =>  [],
                'Impressions'   =>  [],
                'url'           =>  $this->input->post('url'),  
                'discription'   =>  $this->input->post('discription'),  
            ];

            $db->promotion->insertOne($insertArray);
            $this->session->set_flashdata('message', 'Promotion successfully submitted!');
            redirect(base_url() . 'index.php/admin/Promotion/index');
        }
       
    }

    public function updatePromotions(){
        $db = $this->mongo_db->customQuery();
        $this->session->set_userdata('tabName', 'Promotions/ Ads');

        if($this->input->post('edit_addName')){

            $updateArray['add_name'] = $this->input->post('edit_addName'); 
        }


        if($this->input->post('discription')){

            $updateArray['discription'] = $this->input->post('discription'); 
        }


        if($this->input->post('url')){

            $updateArray['url'] = $this->input->post('url'); 
        }

        if($this->input->post('edit_startDate')){
         
            $updateArray['start_date'] = $this->mongo_db->converToMongodttime($this->input->post('edit_startDate')); 
        }
        if($this->input->post('edit_endDate')){
            
            $updateArray['end_date'] = $this->mongo_db->converToMongodttime($this->input->post('edit_endDate')); 
        }
        $updateArray['publication'] = ($this->input->post('edit_publication')) ? 'yes' : 'no';
        $getStatus = $db->promotion->updateOne(['_id' => $this->mongo_db->mongoId($this->input->post('edit_id'))],   ['$set' => $updateArray]);

        if($getStatus->getModifiedCount() > 0 ){

            $this->session->set_flashdata('message', 'Promotion successfully Updated!');
        }else{

            $this->session->set_flashdata('error', 'Promotion is not Updated due to some database issue!');
        }
        redirect(base_url() . 'index.php/admin/Promotion/index');
    }

}