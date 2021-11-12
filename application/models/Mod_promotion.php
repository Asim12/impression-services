

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_promotion extends CI_Model {
    function __construct() {

        parent::__construct();
        
    }

    public function saveImpressions($admin_id, $promotion_id, $type){

        $db = $this->mongo_db->customQuery();

        if($type == 'click'){

            $pushData['clicks'] =  $admin_id;
        }elseif($type == 'view'){

            $pushData['view'] =  $admin_id;
        }elseif($type == 'impression'){

            $pushData['Impressions'] =  $admin_id;
        }

        $db->promotion->updateOne(['_id' => $this->mongo_db->mongoId($promotion_id)], ['$push' => $pushData] );
        return true;
    }//end function


    public function getAllPromotions(){
        $db = $this->mongo_db->customQuery();

        $currentDate = $this->mongo_db->converToMongodttime(date('Y-m-d H:i:s'));

        $get         =  $db->promotion->find(['publication' => 'yes', 'end_date' => ['$gte' => $currentDate] ]);
        $promotions  =  iterator_to_array($get);

        return $promotions;
    }//end function
}