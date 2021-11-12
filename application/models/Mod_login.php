

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_login extends CI_Model {
    function __construct() {

        parent::__construct();
        
    }
    public function is_user_login(){

        $db = $this->mongo_db->customQuery();
        $admin_id = $this->session->userdata('admin_id');
        if( !empty($admin_id) ) {
            // $userGet    = $db->users->find([ '_id' => $this->mongo_db->mongoId((string) $admin_id ), 'login_status' => true ]);
            // $countCheck = iterator_to_array($userGet);

            // if(count($countCheck) > 0){

                
            //     return true;
            // }else{

            // redirect(base_url() . 'index.php/admin/Login');
            return true;
            // }

        }else{

            redirect(base_url() . 'index.php/admin/Login');

        }

    }
}
