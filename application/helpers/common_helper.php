
<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('varify_basic_auth')) {

    function varify_basic_auth($password, $username) {
        //username : impressions; 
        //Password : asim92578@gmail.com;
        $password1 = md5('asim92578@gmail.com');
        $username1 = md5('impressions');

        if($password == 'ced76f20478999896e60789d279360ab' && $username == '87e980e6e1c878a6d0529f59775d350e'){

            return true;

        }else{
            
            return false;
        }
       
    }

} //end varificatin auth

if(!function_exists('isEmailExists')){
    function isEmailExists($email){
        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();
        
        $getUserData = $db->users->find(['email_address' => $email ]);
        $countRec    = iterator_to_array($getUserData);

        if(count($countRec) > 0 ){

            return true;
        }else{

            return false;
        }
        
    }
}

if(!function_exists('isPhoneExists')){
    function isPhoneExists($phone_number){

        // echo "<br>".$phone_number;

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

        $getData  = $db->users->find([ 'phone_number' => $phone_number ]);
        $response = iterator_to_array($getData);

        // echo "<br>count".count($response);

        if(count($response) > 0){
            
            return true;
        }else{

            return false;
        }
    }
}

if(!function_exists('makeLoginStatusTrue')){
    function makeLoginStatusTrue($email){

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();
        $whereUpdate['email_address']  = $email;
        $db->users->updateOne($whereUpdate, ['$set' => ['login_status' => true, 'last_login_time' => $CI->mongo_db->converToMongodttime(date('Y-m-d H:i:s')) ]]);
        return true;
    }
}

if(!function_exists('isUserExistsUsingAdminId')){
    function isUserExistsUsingAdminId($admin_id){

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();
        $checkUserExists['_id']        =  $CI->mongo_db->mongoId($admin_id);
        $checkUserExists['user_role']  =  2;

        $count = $db->users->find($checkUserExists);
        $data  = iterator_to_array($count);

        if(count($count) > 0){

            return true ;
        }else{

            return false;
        }

    }
}

if(!function_exists('isUserExists')){
    function isUserExists($phone){

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

        $user = $db->users->find([ 'phone_number' =>  $phone]);
        $getData = iterator_to_array($user);

        if(count($getData) > 0){

            return true ;
        }else{

            return false;
        }
    }
} 

if (!function_exists('hitCurlRequest')) {
	function hitCurlRequest($req) {

        // $req_params = $req['req_params'];
        $url       =   $req['url'];
        $req_type  =   $req['req_type'];

        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_PORT => "3000",
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $req_type,
        CURLOPT_POSTFIELDS => "",
        ]);

        $response  =  curl_exec($curl);
        $err       =  curl_error($curl);
        $http_code =  curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
		$response = json_decode($response, TRUE);
        return $response;
	}
} //end num

if(!function_exists('socialExistsCheck')){
    function socialExistsCheck($email, $source){
        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

        $getUserData = $db->users->find( ['email_address'  =>  $email] );
        $countRec    = iterator_to_array($getUserData);
        if(count($countRec) > 0 ){

            if(isset($countRec[0]['signup_source']) && $countRec[0]['signup_source']  ==  $source){

                return [
                    'status' => false,
                    'id' => (string)$countRec[0]['_id'],
                    'phone_number' =>  $countRec[0]['phone_number'],
                    'biography'    =>  $countRec[0]['biography'],    
                    'civil_status' =>  $countRec[0]['civil_status'],    
                    'gender'       =>  $countRec[0]['gender'],    
                    'jobPosition'  =>  $countRec[0]['jobPosition'],   
                    'occupation'   =>  $countRec[0]['occupation'],
                    'age'          =>  $countRec[0]['age'],
                    'profile_image'=>  $countRec[0]['profile_image'],
                    
                ];
            }else{

                return [
                    'status'       =>  true, 
                    'id'           =>  '',
                    'phone_number' =>  '',
                    'biography'    =>  '',    
                    'civil_status' =>  '',    
                    'gender'       =>  '',    
                    'jobPosition'  =>  '',   
                    'occupation'   =>  '',
                    'age'          =>  '',
                    'profile_image'=>  '',

                ];
            }
        }else{

            return [
                'status' => false,
                'id'           =>  '',
                'phone_number' =>  '',
                'biography'    =>  '',    
                'civil_status' =>  '',    
                'gender'       =>  '',    
                'jobPosition'  =>  '',   
                'occupation'   =>  '', 
                'age'          =>  '',  
                'profile_image'=>  '',

            ];
        }
    }
}

if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $timezone, $full = false) {
        $CI = &get_instance();
        $datetime2 = date("Y-m-d g:i:s A");
        $timezone = $timezone;
        $date = date_create($datetime2);
        date_timezone_set($date, timezone_open($timezone));
        $now1 = date_format($date, 'Y-m-d g:i:s A');
        $now = new DateTime($now1);
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
} //end

if(!function_exists('getAdminNotification')){
    function getAdminNotification(){
        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

        $startDate  =  $CI->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-3 months')));

        $aggregateLookup = [
            [
                '$match' => [
                    'created_date' => ['$gte' => $startDate]
                ]
            ],
            [
                '$project' => [
                    '_id'           =>  ['$toString' => '$_id'],
                    'created_date'  =>  '$created_date',
                    'status'        =>  '$status',
                    'message'       =>  '$message',
                    'name'          =>  '$name',
                    'admin_id'      =>  '$admin_id',
                    'order_id'      =>  '$order_id',
                ]
            ],
            [
                '$lookup' => [
                  "from" => "users",
                  "let" => [
        
                    'adminId' => ['$toObjectId' => '$admin_id'],
                  ],
                  "pipeline" => [
                    [
                      '$match' => [
                        '$expr' => [
                          '$eq' => [
                            '$_id',
                            '$$adminId'
                          ]
                        ],
                      ],
                    ],
                    
                    [
                      '$project' => [
                        '_id'  => ['$toString' => '$_id'],
                       'profile_image'   =>   '$profile_image', 
                      ]
                    ]
                  ],
                  'as' => 'userData'
                ]
            ],
            [
                '$sort' => [ 'created_date' => -1]
            ]
        ];

        $notification       =  $db->admin_notification->aggregate($aggregateLookup);
        $notificationRes    =  iterator_to_array($notification);
        return $notificationRes;
    }
}//endon

if(!function_exists('countAdminNotification')){
    function countAdminNotification(){
        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();
        $startDate          =  $CI->mongo_db->converToMongodttime(date('Y-m-d H:i:s', strtotime('-3 months')));
        $aggregateLookup = [
            [
                '$match' => [
                    'created_date' => ['$gte' => $startDate],
                    'status'       => 'pending'
                ]
            ],
            [
                '$group' => [
                    '_id'   =>  null,
                   'count'  =>  ['$sum' => 1]
                ]
            ],
        ];
        $notificationCount       =  $db->admin_notification->aggregate($aggregateLookup);
        $notificationCountRes    =  iterator_to_array($notificationCount);
        return $notificationCountRes[0]['count'];
    }
}//end


if(!function_exists('getPakage')){
    function getPakage($admin_id){

        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();
        $getAggregate = [
            [
                '$match' => [
                    '_id' => $CI->mongo_db->mongoId($admin_id)
                ]
            ],
            [
                '$project' => [
                    '_id'     => ['$toString' => '$_id'],
                    'package' => '$package' 
                ]
            ],
            [
                '$sort' => [ 'created_date' => -1]
            ],
            [
                '$limit' => 1, 
            ],
        ];
        $pkg = $db->users->aggregate($getAggregate);
        $pkgRes = iterator_to_array($pkg);
        return $pkgRes[0]['package'];
    }
}//end

if(!function_exists('getPerWeekReviewCount')){
    function getPerWeekReviewCount($admin_id){
        $CI = &get_instance();
        $db = $CI->mongo_db->customQuery();

        $endTime   = $CI->mongo_db->converToMongodttime(date('Y-m-d 23:59:59'));
        $startTime = $CI->mongo_db->converToMongodttime(date('Y-m-d 00:00:00', strtotime('- 7 days')));

        $countReview =  $db->user_reviews->find(['admin_id' => (string)$admin_id, 'created_date' => ['$gte' => $startTime,   '$lte' => $endTime]]);
        $reviewCount =  iterator_to_array($countReview);
        return count($reviewCount);
    }
}