
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_reviews extends CI_Model {
  
  function __construct() {
    parent::__construct();
    
    // ini_set("display_errors", 1);
    // error_reporting(1);
  }

  
  public function saveReviews($data){

    $db  = $this->mongo_db->customQuery();
    $get = $db->user_reviews->insertOne($data);

    return true;

  }

  public function getPostedReviewsOnMyProfile($admin_id){

    $db  = $this->mongo_db->customQuery();
    $getPendingReview = [
      [
        '$match' => [

          'admin_id'    =>  $admin_id,
          'status'      =>  'new'
        ]
      ],

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
          'admin_id'                      =>  ['$toString' => '$admin_id'],
          'reviwer_admin_id'              =>  ['$toString' => '$reviwer_admin_id'], //jis ko review dia ha us ke id
          'created_date'                  =>  '$created_date',
        ]
      ],

      [
        '$project' => [
          '_id'                           => '$reviwer_admin_id',
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
          '_id'                           => '$_id',
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
          '_id'                   =>  '$_id',
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

          '_id'                   =>  '$_id',
          'reviwer_admin_id'      =>  '$reviwer_admin_id',
          'authticity'            =>  ['$divide' =>[ '$authticity1', '$totalPersonality']],
          'personality'           =>  ['$divide' =>[ '$personality1', '$totalAuthenticity']] ,              
          'data_experience'       =>  ['$divide' =>[ '$data_experience1', '$totalDataExpirenced']],
          'created_date'          =>  '$created_date'
        ]
      ],

      [
        '$project' => [

          '_id'                   =>  '$_id',
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

          '_id'                   =>  '$_id',
          'reviwer_admin_id'      =>  '$reviwer_admin_id',
          'authticity'            =>  '$authticity',
          'personality'           =>  '$personality' ,              
          'data_experience'       =>  '$data_experience',
          'overAllRatting'        =>  ['$divide' => ['$overAllRatting1', 3]],
          'created_date'          =>  '$created_date'
        ]
      ],

      [
          '$lookup' => [
            'from' => 'users',
            'let' => [
              'admin_id' =>    ['$toObjectId' => '$reviwer_admin_id'],
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
                  'bioigraphy'    =>  '$bioigraphy'
                    
                ]
              ],
            ],
            'as' => 'profileOfReviwer'
          ]
      ],
      ['$sort' => ['created_date' => 1]]
    ];

    $get = $db->user_reviews->aggregate($getPendingReview);
    $userReviews = iterator_to_array($get);
    return $userReviews;
  }//end
  

  public function getMyPostedReviews($reviwer_admin_id){
    $db  = $this->mongo_db->customQuery();

    $getPendingReview = [
      [
        '$match' => [

          'reviwer_admin_id'    =>  $reviwer_admin_id,
        ]
      ],

      [
        '$project' =>[
          '_id'                           =>  '$reviwer_admin_id', 
          'your_message'                  =>  '$your_message',
          'admin_id'                      =>  ['$toString' => '$admin_id'],
          'reviwer_admin_id'              =>  ['$toString' => '$reviwer_admin_id'], //jis ko review dia ha us ke id
          'created_date'                  =>  '$created_date',
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
                  
              ]
            ]
          ],
          'as' => 'user_data'
        ]
      ],
      ['$sort' => ['created_date' => -1]]
    ];
    $get = $db->user_reviews->aggregate($getPendingReview);
    $userReviews = iterator_to_array($get);
    return $userReviews;
  }


  public function saveNotifications($data){ 

    $db = $this->mongo_db->customQuery();
    $db->notifications->insertOne($data);
    return true;

  }//end 


  public function getApproveReview($admin_id){
    $db = $this->mongo_db->customQuery();

    $getApprovalReview = [
      [
        '$match' => [
          'status'    =>  'approve',
          'admin_id'  =>  $admin_id
        ]
      ],

      [
        '$project' => [
          '_id'                           => '$_id',
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
          'created_date'                  => '$created_date',
          'your_message'                  => '$your_message',
          'anonymous'                     => '$anonymous'

        ]
      ],
      [
        '$project' => [
          '_id'                   =>  '$_id',
          'reviwer_admin_id'      =>  '$reviwer_admin_id',
          'authticity'            =>  ['$divide' => [ ['$sum' =>  ['$looks',  '$brain' , '$career']], 3 ]],
          'personality'           =>  ['$divide' => [ ['$sum' =>  ['$funny',  '$intelegent' , '$polite']], 3 ]],              
          'data_experience'       =>  ['$divide' => [ ['$sum' =>  ['$recomendation_person_good_date', '$would_you_out_go_again', '$patner_distracted', '$dating_experienced', '$communication_post_date', '$date_start_on_time',  '$how_was_physical_chemistry' , '$did_you_feel_safe', '$did_you_feel_pressured', '$communication_prior']], 10 ]],
          'created_date'          =>  '$created_date',
          'your_message'          =>  '$your_message',
          'anonymous'             =>  '$anonymous'
        ]
      ],

      [
        '$lookup' => [
          'from' => 'user_reviews',
          'let' => [
            'admin_id' =>    '$reviwer_admin_id',
          ],
          'pipeline' => [
            [
              '$match' => [
                '$expr' => [
                  '$eq' => [
                    '$admin_id',
                    '$$admin_id'
                  ]
                ],
                'status' => "approve"
              ],
            ],
            
            [
              '$group' => [
                '_id'                           => '$_id',
                'looks'                         => ['$first' =>  '$looks'],
                'brain'                         => ['$first' =>  '$brain'],
                'career'                        => ['$first' =>  '$career'],
                'date_start_on_time'            => ['$first' =>  '$date_start_on_time'],
                'how_was_physical_chemistry'    => ['$first' =>  '$how_was_physical_chemistry'],
                'did_you_feel_safe'             => ['$first' =>  '$did_you_feel_safe'],
                'did_you_feel_pressured'        => ['$first' =>  '$did_you_feel_pressured'],
                'communication_prior'           => ['$first' =>  '$communication_prior'],
                'communication_post_date'       => ['$first' =>  '$communication_post_date'],
                'dating_experienced'            => ['$first' =>  '$dating_experienced'],
                'patner_distracted'             => ['$first' =>  '$patner_distracted'],
                'would_you_out_go_again'        => ['$first' =>  '$would_you_out_go_again'],
                'recomendation_person_good_date'=> ['$first' =>  '$recomendation_person_good_date'],
                'funny'                         => ['$first' =>  '$funny'],
                'intelegent'                    => ['$first' =>  '$intelegent'],
                'polite'                        => ['$first' =>  '$polite'],
                'created_date'                  => ['$first' => '$created_date'],

              ]
            ],

            [
              '$project' => [
                '_id'                   =>  '$_id',
                'authticity'            =>  ['$divide' =>[ ['$sum' =>  ['$looks',  '$brain' , '$career']], 3]],
                'personality'           =>  ['$divide' =>[ ['$sum' =>  ['$funny',  '$intelegent' , '$polite']], 3]],              
                'data_experience'       =>  ['$divide' =>[ ['$sum' =>  ['$recomendation_person_good_date', '$would_you_out_go_again', '$patner_distracted', '$dating_experienced', '$communication_post_date', '$date_start_on_time',  '$how_was_physical_chemistry' , '$did_you_feel_safe', '$did_you_feel_pressured', '$communication_prior']], 10]],
                'created_date'          =>  '$created_date',
              ]
            ],
      
            [
              '$group' => [
      
                '_id'        =>   null,
                'authticity'        =>   [ '$sum' => '$authticity'],
                'personality'       =>   [ '$sum' => '$personality'],
                'data_experience'   =>   [ '$sum' => '$data_experience'],
                'total'             =>   [ '$sum' => 1]
              ]
            ],

            [
              '$addFields' => [

                'overAll1' =>   ['$sum' => [ '$authticity', '$personality', '$data_experience']]

              ]
            ],
            [
              '$project' => [
      
                '_id'     =>  null,

                'total'  => ['$multiply' => ['$total' , 3]],
                'overAll' => '$overAll1' 
              ]
            ],


            [
              '$project' => [
      
                '_id'     =>  null,
                'overAll' =>  ['$divide' => [ '$overAll', '$total']], 
              ]
            ],
          ],
          'as' => 'reviwerSubmitter_ratting'
        ]
      ],

      [
        '$lookup' => [
          'from' => 'users',
          'let' => [
            'admin_id' =>    ['$toObjectId' => '$reviwer_admin_id'],
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
                'profile_image' => '$profile_image'
                
              ]
            ]
          ],
          'as' => 'reviewer_user_details'
        ]
      ],

      [
        '$sort' => ['created_date' => -1]
      ],
    ];

    $getReview =  $db->user_reviews->aggregate($getApprovalReview);
    $getReviewRes = iterator_to_array($getReview);

    return $getReviewRes;
  }

  public function getPendingReview($admin_id){
    $db = $this->mongo_db->customQuery();

    $getApprovalReview = [
      [
        '$match' => [
          'status'    =>  'new',
          'admin_id'  =>  $admin_id
        ]
      ],
      [
        '$project' => [
          '_id'                           => '$_id',
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
          'created_date'                  => '$created_date',
          'your_message'                  => '$your_message',
          'anonymous'                     => '$anonymous'

        ]
      ],
      [
        '$project' => [
          '_id'                            =>  '$_id',
          'reviwer_admin_id'               =>  '$reviwer_admin_id',
          'authticity'                     =>  ['$divide' => [ ['$sum' =>  ['$looks',  '$brain' , '$career']], 3 ]],
          'personality'                    =>  ['$divide' => [ ['$sum' =>  ['$funny',  '$intelegent' , '$polite']], 3 ]],              
          'data_experience'                =>  ['$divide' => [ ['$sum' =>  ['$recomendation_person_good_date', '$would_you_out_go_again', '$patner_distracted', '$dating_experienced', '$communication_post_date', '$date_start_on_time',  '$how_was_physical_chemistry' , '$did_you_feel_safe', '$did_you_feel_pressured', '$communication_prior']], 10 ]],
          'created_date'                   =>  '$created_date',
          'your_message'                   =>  '$your_message',
          'anonymous'                      =>  '$anonymous',
          'looks'                          =>  '$looks',
          'brain'                          =>  '$brain',
          'career'                         =>  '$career',
          'date_start_on_time'             =>  '$date_start_on_time',
          'how_was_physical_chemistry'     =>  '$how_was_physical_chemistry',
          'did_you_feel_safe'              =>  '$did_you_feel_safe',
          'did_you_feel_pressured'         =>  '$did_you_feel_pressured',
          'communication_prior'            =>  '$communication_prior',
          'communication_post_date'        =>  '$communication_post_date',
          'dating_experienced'             =>  '$dating_experienced',
          'patner_distracted'              =>  '$patner_distracted',
          'would_you_out_go_again'         =>  '$would_you_out_go_again',
          'recomendation_person_good_date' =>  '$recomendation_person_good_date',
          'funny'                          =>  '$funny',
          'intelegent'                     =>  '$intelegent',
          'polite'                         =>  '$polite',
        ]
      ],
      [
        '$lookup' => [
          'from' => 'user_reviews',
          'let' => [
            'admin_id' =>    '$reviwer_admin_id',
          ],
          'pipeline' => [
            [
              '$match' => [
                '$expr' => [
                  '$eq' => [
                    '$admin_id',
                    '$$admin_id'
                  ]
                ],
                'status' => "approve"
              ],
            ],
            
            [
              '$group' => [
                '_id'                           => '$_id',
                'looks'                         => ['$first' =>  '$looks'],
                'brain'                         => ['$first' =>  '$brain'],
                'career'                        => ['$first' =>  '$career'],
                'date_start_on_time'            => ['$first' =>  '$date_start_on_time'],
                'how_was_physical_chemistry'    => ['$first' =>  '$how_was_physical_chemistry'],
                'did_you_feel_safe'             => ['$first' =>  '$did_you_feel_safe'],
                'did_you_feel_pressured'        => ['$first' =>  '$did_you_feel_pressured'],
                'communication_prior'           => ['$first' =>  '$communication_prior'],
                'communication_post_date'       => ['$first' =>  '$communication_post_date'],
                'dating_experienced'            => ['$first' =>  '$dating_experienced'],
                'patner_distracted'             => ['$first' =>  '$patner_distracted'],
                'would_you_out_go_again'        => ['$first' =>  '$would_you_out_go_again'],
                'recomendation_person_good_date'=> ['$first' =>  '$recomendation_person_good_date'],
                'funny'                         => ['$first' =>  '$funny'],
                'intelegent'                    => ['$first' =>  '$intelegent'],
                'polite'                        => ['$first' =>  '$polite'],
                'created_date'                  => ['$first' => '$created_date'],

              ]
            ],

            [
              '$project' => [
                '_id'                   =>  '$_id',
                'authticity'            =>  ['$divide' =>[ ['$sum' =>  ['$looks',  '$brain' , '$career']], 3]],
                'personality'           =>  ['$divide' =>[ ['$sum' =>  ['$funny',  '$intelegent' , '$polite']], 3]],              
                'data_experience'       =>  ['$divide' =>[ ['$sum' =>  ['$recomendation_person_good_date', '$would_you_out_go_again', '$patner_distracted', '$dating_experienced', '$communication_post_date', '$date_start_on_time',  '$how_was_physical_chemistry' , '$did_you_feel_safe', '$did_you_feel_pressured', '$communication_prior']], 10]],
                'created_date'          =>  '$created_date',
              ]
            ],
      
            [
              '$group' => [
      
                '_id'        =>   null,
                'authticity'        =>   [ '$sum' => '$authticity'],
                'personality'       =>   [ '$sum' => '$personality'],
                'data_experience'   =>   [ '$sum' => '$data_experience'],
                'total'             =>   [ '$sum' => 1]
              ]
            ],

            [
              '$addFields' => [

                'overAll1' =>   ['$sum' => [ '$authticity', '$personality', '$data_experience']]

              ]
            ],
            [
              '$project' => [
      
                '_id'     =>  null,

                'total'  => ['$multiply' => ['$total' , 3]],
                'overAll' => '$overAll1' 
              ]
            ],


            [
              '$project' => [
      
                '_id'     =>  null,
                'overAll' =>  ['$divide' => [ '$overAll', '$total']], 
              ]
            ],
          ],
          'as' => 'reviwerSubmitter_ratting'
        ]
      ],

      [
        '$lookup' => [
          'from' => 'users',
          'let' => [
            'admin_id' =>    ['$toObjectId' => '$reviwer_admin_id'],
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
                'profile_image' => '$profile_image'
                
              ]
            ]
          ],
          'as' => 'reviewer_user_details'
        ]
      ],

      [
        '$sort' => ['created_date' => -1]
      ],
    ];

    $getReview =  $db->user_reviews->aggregate($getApprovalReview);
    $getReviewRes = iterator_to_array($getReview);
    return $getReviewRes;
  }


  public function makeReviewApproveRejectFlag($review_id, $type, $reason =''){

    $db = $this->mongo_db->customQuery();
    if($type == 'approve'){   

      $updateData['status'] = 'approve';
    }elseif($type == 'reject'){

      $updateData['status'] = 'reject';
    }elseif($type == 'flag'){

      $updateData['status'] = 'flag';
      $updateData['reason'] =  $reason;
    }else{
      return false;
    }
    $getCount = $db->user_reviews->updateOne(['_id' => $this->mongo_db->mongoId((string)$review_id), 'status' => 'new'],  ['$set' => $updateData]);

    if($getCount->getModifiedCount() > 0){

      return true;
    }else{

      return false;
    }
  }//end


  public function submitNote($insertNotes){
    $db = $this->mongo_db->customQuery();

    $db->notes->insertOne($insertNotes);
    return true;
  }

  public function getNotes($admin_id){
    $db = $this->mongo_db->customQuery();

    // $data      =  $db->notes->find(, ['sort' => ['date' => -1]] );

    $dataQuery = [
      [
        '$match' => [
          'admin_id' => $admin_id
        ]
      ],

      [
        '$sort' => ['date' => -1]
      ]
    ];


    $data      =  $db->notes->aggregate($dataQuery);
    $allNotes  =  iterator_to_array($data);
    return $allNotes;
  }//end 
} 