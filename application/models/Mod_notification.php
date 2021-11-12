<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_notification extends CI_Model {
    function __construct() {

        parent::__construct();
        
    }
    public function markAsRead($adminId = '', $notificationId= ''){
        $db = $this->mongo_db->customQuery();

        if(!empty($notificationId)){

            $db->notifications->updateOne(['_id' => $this->mongo_db->mongoId($notificationId)], ['$set' => ['status' => 'read']]);
            
        }else{

            $db->notifications->updateMany(['reciver_admin_id' => $adminId], ['$set' => ['status' => 'read']]);
        }
        return true;
    }//end
}