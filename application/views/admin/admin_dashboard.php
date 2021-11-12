<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Dashboard | Impressions</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Impressions" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo SURL;?>assets/images/logo2.png">

        <!-- jvectormap -->
        <link href="<?php echo SURL;?>assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
        <!-- DataTables -->
        <link href="<?php echo SURL;?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo SURL;?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
        <!-- App css -->
        <link href="<?php echo SURL;?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo SURL;?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo SURL;?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <style>
            .cardDisplay{
                display: inline-block;
                width: max-content;
            }
            .boxStyle {
                border-radius: 25px;
                border: 2px solid #e9ecef;
                width: 100%;
            }

            .button1{
                height: 30px;
                width: 119px;
                left: 1334px;
                top: 1188px;
                border-radius: 30.30092430114746px;
				background-color:  #B3F0CB;
				color: #00CC52;
				font-weight: bold;
                border: none;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
            }
            .button2{
				background-color:  #FFE2E2;
				color: #FF5C5F;
				font-weight: bold;
                border: none;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                margin-top : 6%;
                height: 30px;
                width: 119px;
                left: 1334px;
                top: 1188px;
                border-radius: 30.30092430114746px;
            }

            .button3{
				background-color:  #FF6A6A;
				color: white;
				font-weight: bold;
                border: none;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                margin-top : 6%;
                height: 30px;
                width: 119px;
                left: 1334px;
                top: 1188px;
                border-radius: 30.30092430114746px;
            }
            .starFilled {
                height: 12.349213600158691px;
                width: 12.468695640563965px;
                left: 916.500244140625px;
                top: 1126.89990234375px;
                border-radius: 0px;
                color: #57B0AF; 
            }
            .unfilledStar{
                height: 12.349213600158691px;
                width: 12.468695640563965px;
                left: 916.500244140625px;
                top: 1126.89990234375px;
                border-radius: 0px;
                color: rgba(87, 176, 175, 0.5);
            }

            .images{ 
                height: 92.63783264160156px;
                width: 88.85176849365234px;
                left: 382.917724609375px;
                top: 1115.6809692382812px;
                border-radius: 0px;
            }

            .textStyle{ 
                /* height: 17px; */
                /* width: 122.76869201660156px; */
                /* left: 689.000244140625px; */
                /* top: 1125px; */
                /* border-radius: nullpx; */
                color:black;
            }

            .nameStyle{ 
                height: 17px;
                width: 148.66522216796875px;
                left: 480.0958251953125px;
                top: 1124px;
                border-radius: nullpx;
                font-family: Gilroy;
                font-size: 20px;
                font-style: normal;
                font-weight: 700;
                line-height: 17px;
                letter-spacing: -0.18896104395389557px;
            }

            .timeAgo{
                position: absolute;
                width: 121.81px;
                height: 14px;
                left: 37%;
                top: 22%;
                font-family: Gilroy;
                font-style: normal;
                font-weight: normal;
                font-size: 14px;
                line-height: 16px;
                letter-spacing: -0.0944805px;
                color: #8C8C8C;
            }
            .parent {
                position: relative;
                top: 0;
                left: 0;
            }
            .image2 {
                position: relative;
                top: 0;
                left: 0;
                border: 1px white solid;
            }
            .image1 {
                position: absolute;
                top: 54%;
                left: 20%;
                width: 59px;
                height: 56px;
                border: 1px white solid;
            }


            .pagination a {
                color: black;
                float: left;
                padding: 8px 16px;
                text-decoration: none;
            }

            .pagination a.active {
                background-color: #4CAF50;
                color: white;
                border-radius: 5px;
            }

            .pagination a:hover:not(.active) {
                background-color: #ddd;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Topbar Start -->
            <?php include('includes/topbar.php');?>
            <!-- end Topbar -->
            <!-- ========== Left Sidebar Start ========== -->
            <?php include('includes/sidebar.php');?>
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <div class="content">
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card-box boxStyle">  
                                    <img src="<?php echo SURL;?>assets/images/3 User.png" alt="money" class="rounded-circle avatar-lg bx-shadow-lg" style= "background-color: rgba(0, 204, 82, 0.3)"/>
                                    
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <h5 style= "color: black;font-weight: bold ;font-size: 20px;margin-left : 6%;"><?php echo $total_users; ?></h5> 
                                        </div>
                                        <div class="col-xl-9" style="float:right;margin-top: 3%;">
                                              <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $total_users;?>%; background: linear-gradient(270deg, #B3F0CB 0%, #00CC52 112.41%);">
                                                  <span class="sr-only"><?php echo $total_users.'%'; ?></span>
                                                </div>
                                              </div>
                                        </div>
                                    </div>

                                    <h4 class="header-title text-muted" style="font-size:21px;margin-left:1%;">Signed Up Users</h4>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-xl-4">
                                <div class="card-box boxStyle">
                                    <img src="<?php echo SURL;?>assets/images/Group 46417.png" alt="money" class="rounded-circle avatar-lg bx-shadow-lg" />

                                    <div class="row">
                                        <div class="col-xl-3">
                                            <h5 style = "color: black;font-weight: bold ;font-size: 20px; margin-left:10%;"><?php echo $active_users; ?></h5>
                                        </div>
                                        <div class="col-xl-9" style="float:right;margin-top: 3%;">

                                              <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $active_users;?>%; background: linear-gradient(270deg, #EEBEFF 0%, #C726FF 112.41%);">
                                                  <span class="sr-only"><?php echo $active_users.'%'; ?></span>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                    <h4 class="header-title text-muted" style="font-size:21px; margin-left:2%;">Active Users</h4>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-xl-4">
                                <div class="card-box boxStyle">
                                    <img src="<?php echo SURL;?>assets/images/Group 46418.png" alt="money" class="rounded-circle avatar-lg bx-shadow-lg" />
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <h5 style= "color: black;font-weight: bold;font-size: 20px; margin-left : 15%;"><?php echo $inactive_users; ?></h5>
                                        </div>
                                        <div class="col-xl-9" style="float:right;margin-top: 3%;">

                                              <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $inactive_users;?>%; background: linear-gradient(270deg, #FFC1D3 0%, #FF2F6D 112.41%);">
                                                  <span class="sr-only"><?php echo $inactive_users.'%'; ?></span>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                    <h4 class="header-title text-muted" style="font-size:21px; margin-left : 4%;">Inactive Users</h4>
                                </div>
                            </div> <!-- end col -->
                        </div>

                        <!-- end row -->

                        <div class= "row">
                            <div class="col-xl-9">
                                <div class="card-box boxStyle">
                                    <h4 class="header-title mb-3" style="color: black; font-size: 30px">Active Users</h4>
                                    <div class="boxStyle" style ="max-width: 95%">
                                        <canvas id="activeUsers" width="800" height="300"></canvas>
                                    </div>
                                </div>
                            </div>   

                            <div class="col-xl-3 card-box boxStyle" style= "background-color : #57B0AF; height: fit-content">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <h4 class="header-title" style="color:white; font-size :20px; font-weight:bold">30 Days</h4>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-12">
                                                <h5 style= "color: white; font-size: 12px;">Money Make Last</h5>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-12" >
                                                <h5 style= "color: white; font-weight: bold; font-size: 45px"><?php echo '$'.$totalPayment; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <img src="<?php echo SURL;?>assets/images/Group.png" alt="money" class="rounded-circle avatar-lg bx-shadow-lg"style="float:right;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-box boxStyle">
                                    <h4 class="header-title mb-4">User Reviews</h4>                                    
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0" id="">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">User Name</th>
                                                    <th style="width:17%" class="border-top-0">Review</th>
                                                    <th class="border-top-0">Comment</th>
                                                    <th class="border-top-0">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php 
                                                foreach ($pendingReviews as $value){ ?>
                                                    <tr class= "filters_style"> 
                                                        <td>
                                                            <?php 
                                                                $time_zone = date_default_timezone_get();
                                                                $join_date = $value['created_date']->toDateTime()->format("Y-m-d H:i:s");                                                                
                                                                $last_time_ago = time_elapsed_string($join_date , $time_zone);
                                                            ?>

                                                            <div class="parent">
                                                                <span style="display: inline-flex">
                                                                <?php if(empty($value['profileOfReviwer'][0]['profile_image']) || $value['profileOfReviwer'][0]['profile_image'] == ''|| is_null($value['profileOfReviwer'][0]['profile_image']) ){ 
                                                                    if($value['profileOfReviwer'][0]['gender'] == 'female'){
                                                                        
                                                                        $imageSource = SURL.'assets/images/female.png';
                                                                    }elseif($value['profileOfReviwer'][0]['gender'] == 'male'){

                                                                        $imageSource = SURL.'assets/images/male.png';;
                                                                    }else{
                                                                        $imageSource = SURL.'assets/images/male.png';;
                                                                    }
                                                                }else{
                                                                    $imageSource = $value['profileOfReviwer'][0]['profile_image'];
                                                                } ?>
                                                                <?php if(empty($value['profileOfReviwer'][0]['reviwerSubmitter']) || $value['profileOfReviwer'][0]['reviwerSubmitter'] == ''|| is_null($value['profileOfReviwer'][0]['reviwerSubmitter']) ){ 
                                                                    if($value['profileOfReviwer'][0]['gender'] == 'female'){
                                                                        
                                                                        $imageSourceOther = SURL.'assets/images/female.png';
                                                                    }elseif($value['profileOfReviwer'][0]['gender'] == 'male'){

                                                                        $imageSourceOther = SURL.'assets/images/male.png';;
                                                                    }else{
                                                                        $imageSourceOther = SURL.'assets/images/male.png';;
                                                                    }
                                                                }else{
                                                                    $imageSourceOther = $value['profileOfReviwer'][0]['reviwerSubmitter'];
                                                                } ?>
                                                                    <?php 
                                                                        $value['profileOfReviwer'][0]['profile_image'];  //is ke profile pa review dia 
                                                                        $value['profileOfReviwer'][0]['reviwerSubmitter']; // is na review dia
                                                                    ?>
                                                                    <img src="<?php echo $imageSource;?>" alt="user-pic" class="rounded-circle images avatar-sm bx-shadow-lg image2" />
                                                                    <span class="nameStyle"><?php echo $value['profileOfReviwer'][0]['full_name']; ?></span><br>
                                                                    <span class="timeAgo" title="<?php echo $join_date;?>"> <?php echo $last_time_ago;?> </span>
                                                                </span>
                                                                <img src="<?php echo $imageSourceOther;?>" alt="user-pic" class="rounded-circle images avatar-sm bx-shadow-lg image1" />
                                                            </div>

                                                        </td>
                                                        <td class = "cardDisplay">
                                                            <div class="row">
                                                                <div class="col-xl-4">
                                                                    <span class="textStyle">Authenticity</span>   
                                                                </div>
                                                                <div class="col-xl-2">
                                                                    <span style="color: #57B0AF;text-align: center"><?php echo number_format($value['reviwerSubmitter_ratting'][0]['authticity'],1); ?></span>
                                                                </div>
                                                                <div class="col-xl-6">
                                                                    <?php if($value['reviwerSubmitter_ratting'][0]['authticity'] >= 0 && $value['reviwerSubmitter_ratting'][0]['authticity'] < 1 ) { ?> 
                                                                        <span style="color: #57B0AF;">  
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['authticity'] >= 1 && $value['reviwerSubmitter_ratting'][0]['authticity'] < 2 ){ ?>
                                                                        <span style="color: #57B0AF;">  
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['authticity'] >= 2 && $value['reviwerSubmitter_ratting'][0]['authticity'] < 3 ){ ?>
                                                                        <span style="color: #57B0AF;">  
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['authticity'] >= 3 && $value['reviwerSubmitter_ratting'][0]['authticity'] < 4 ){ ?>
                                                                        <span style="color: #57B0AF;">  
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['authticity'] >= 4 && $value['reviwerSubmitter_ratting'][0]['authticity'] < 5){ ?>
                                                                        <span style="color: #57B0AF;">  
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['authticity'] >= 5){ ?>
                                                                        <span style="color: #57B0AF;">  
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                        </span>
                                                                    <?php }?>
                                                                </div>
                                                            </div>
                                                            

                                                            <div class="row">
                                                                <div class="col-xl-4">
                                                                    <span class="textStyle"> Personality </span>   
                                                                </div>
                                                                <div class="col-xl-2">
                                                                    <span style="color: #57B0AF; text-align: center"><?php echo number_format($value['reviwerSubmitter_ratting'][0]['personality'],1); ?></span>
                                                                </div>
                                                                <div class="col-xl-6">
                                                                    <?php if($value['reviwerSubmitter_ratting'][0]['personality'] >= 0 && $value['reviwerSubmitter_ratting'][0]['personality'] < 1 ) { ?> 
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['personality'] >= 1 && $value['reviwerSubmitter_ratting'][0]['personality'] < 2 ){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['personality'] >= 2 && $value['reviwerSubmitter_ratting'][0]['personality'] < 3 ){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['personality'] >= 3 && $value['reviwerSubmitter_ratting'][0]['personality'] < 4 ){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['personality'] >= 4 && $value['reviwerSubmitter_ratting'][0]['personality'] < 5){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['personality'] >= 5){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                        </span>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-xl-4">
                                                                    <span class="textStyle">Data Experience </span> 
                                                                </div>
                                                                <div class="col-xl-2">
                                                                    <span style="color: #57B0AF;text-align: center"><?php echo number_format($value['reviwerSubmitter_ratting'][0]['data_experience'],1); ?></span>
                                                                </div>
                                                                <div class="col-xl-6">
                                                                    <?php if($value['reviwerSubmitter_ratting'][0]['data_experience'] >= 0 && $value['reviwerSubmitter_ratting'][0]['data_experience'] < 1 ) { ?> 
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['data_experience'] >= 1 && $value['reviwerSubmitter_ratting'][0]['data_experience'] < 2 ){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['data_experience'] >= 2 && $value['reviwerSubmitter_ratting'][0]['data_experience'] < 3 ){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['data_experience'] >= 3 && $value['reviwerSubmitter_ratting'][0]['data_experience'] < 4 ){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['data_experience'] >= 4 && $value['reviwerSubmitter_ratting'][0]['data_experience'] < 5){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star unfilledStar"></span>
                                                                        </span>
                                                                    <?php }elseif($value['reviwerSubmitter_ratting'][0]['data_experience'] >= 5){ ?>
                                                                        <span style="color: #57B0AF; "> 
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                            <span class="fa fa-star starFilled"></span>
                                                                        </span>
                                                                    <?php } ?>

                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td> 
                                                           <p> <?php echo $value['your_message']; ?> </p>
                                                        </td>
                                                        <td>

                                                            <?php if($value['status'] == 'new') {

                                                                $class  =  "button1";
                                                                $status =  'Pending';
                                                            }elseif($value['status'] == 'approve'){

                                                                $class  =  "button1";
                                                                $status =  'Approved';
                                                            }elseif($value['status'] == 'reject'){

                                                                $class  =  "button2";
                                                                $status =  'Reject';
                                                            }elseif($value['status'] == 'flag'){

                                                                $class  =  "button3";
                                                                $status =  'Flag';
                                                            }else{

                                                                $class  =  "button2";
                                                                $status =  '---';
                                                            } ?>
                                                            <input  type='hidden' class = 'id' value = '<?php echo (string)$value['_id']; ?>' >
                                                            <button type="button" class="<?php echo $class; ?>"><?php echo $status; ?></button><br>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="pagination"><?php echo $links; ?></div>
                                    </div>

                                </div> 
                            </div> 
                        </div>

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('includes/footer.php');?>
                <!-- end Footer -->
            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <!-- Vendor js -->
        <script src="<?php echo SURL;?>assets/js/vendor.min.js"></script>
        <!-- KNOB JS -->
        <script src="<?php echo SURL;?>assets/libs/jquery-knob/jquery.knob.min.js"></script>
        <!-- Chart JS -->
        <script src="<?php echo SURL;?>assets/libs/chart-js/Chart.bundle.min.js"></script>
        <!-- Jvector map -->
        <script src="<?php echo SURL;?>assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="<?php echo SURL;?>assets/libs/jqvmap/jquery.vmap.usa.js"></script>
        <!-- Datatable js -->
        <script src="<?php echo SURL;?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo SURL;?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo SURL;?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo SURL;?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <!-- Dashboard Init JS -->
        <script src="<?php echo SURL;?>assets/js/pages/dashboard.init.js"></script>
        <!-- App js -->
        <script src="<?php echo SURL;?>assets/js/app.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script>

            window.onload = function() {

                new Chart(document.getElementById("activeUsers"), {
                    type: 'bar',
                    data: {
                        labels: ['12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM','7AM','8AM', '12AM','12AM','12AM','12AM','12AM','12AM','12AM','12AM'],
                        datasets: [
                            { 
                                data: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 , 13, 14, 1, 3,20],
                                // label: "",
                                backgroundColor: ["#0E144A" ,"#0E144A" ,"#0E144A","#0E144A","#0E144A", "#0E144A","#0E144A", "#0E144A","#0E144A","#0E144A","#0E144A", "#0E144A", "#0E144A", "#0E144A", "#0E144A", "#0E144A", "#0E144A","#0E144A", "#0E144A", "#0E144A"],


                                label: "users",
                                borderColor: "white",
                                fill: false,
                                display:false,
                            }
                        ]
                    },
                    options: {
                        title: {
                            // display: true,
                            // text: 'Last 24 Hours',
                            // fontColor: "white",
                        }, scales: {
                        yAxes: [{
                            ticks: {
                            },gridLines: {
                                display: false
                            }
                        }],
                        xAxes: [{
                            ticks: {
                            },gridLines: {
                                display: false
                            }    
                        }]
                    }
                    }
                });
            
            }

            $(document).ready(function() {

                // $(document).on("click",".button1",function(e){
                
                //     var result = confirm("Are you sure you want approve the review!");
                //     console.log(result);

                //     if(result == true){
                //         var id =  $(this).siblings('input.id').val();
                //         console.log(id);

                //         $.ajax({
                //             'url' : '<?php echo SURL;?>index.php/admin/Dashboard/approveReview',
                //             'type': 'POST',
                //             'data': {id : id},
                //             'success': function (response) {
                            
                //                 $(this).closest('tr').remove();
                //                 location.reload();
                //             }
                //         });
                //     }
                // });


                // $(document).on("click",".button2",function(e){

                //     var result = confirm("Are you sure you want reject the review!");
                //     console.log(result);

                //     if(result === true) {

                //         var id =  $(this).siblings('input.id').val();
                //         console.log(id);
                //         $.ajax({
                //             'url' : '<?php echo SURL;?>index.php/admin/Dashboard/rejectReview',
                //             'type': 'POST',
                //             'data': {id : id},
                //             'success': function (response) {
                            
                //                 $(this).closest('tr').remove();
                //                 location.reload();
                //             }
                //         });  
                //     }
                // });

                // $(document).on("click",".button3",function(e){

                //     var result = confirm("Are you sure you want flag the review!");
                //     console.log(result);

                //     if(result == true) {
                //         var id =  $(this).siblings('input.id').val();
                //         console.log(id);

                //         $.ajax({
                //             'url' : '<?php echo SURL;?>index.php/admin/Dashboard/flagReview',
                //             'type': 'POST',
                //             'data': {id : id},
                //             'success': function (response) {

                //                 $(this).closest('tr').remove();
                //                 location.reload();
                //             }
                //         });
                //     }
                // });
            });

        </script>
    </body>
</html>