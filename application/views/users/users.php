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

        <style>

            .cardDisplay{
                display: inline-block;
                width: max-content;
            }
            .boxStyle {
                border-radius: 25px;
                border: 2px solid #e9ecef;
                width: 100%;
                border-style: groove;
            }

            .boxStyle2 {
                border-radius: 25px;
                border: 2px solid #e9ecef;
                width: 100%;
                border: 1px solid black;
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
                                <div class="card-box boxStyle2">  
                                    
                                    <h5 style= "color: black;font-weight: bold ;font-size: 20px"><?php echo $totalUsers; ?></h5> 
                                    <h4 style= "color: black" class="header-title" style="font-size:21px">Signed Up Users</h4>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-xl-4">
                                <div class="card-box boxStyle">
                                    <h5 class="text-muted" style="font-weight: bold ;font-size: 20px"><?php echo $active_users; ?></h5>
                                    <h4 class="header-title text-muted" style="font-size:21px">Active Users</h4>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-xl-4">
                                <div class="card-box boxStyle">
                                    <h5 class="text-muted" style="font-weight: bold;font-size: 20px"><?php echo $inactive_users; ?></h5>
                                    <h4 class="header-title text-muted" style="font-size:21px">Inactive Users</h4>
                                </div>
                            </div> <!-- end col -->
                        </div>

                        <!-- end row -->


                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-box boxStyle" style= "margin-top: 1%">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0" id="">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">User</th>
                                                    <th class="border-top-0">Email</th>
                                                    <th class="border-top-0">Phone</th>
                                                    <th class="border-top-0">Gender</th>
                                                    <th class="border-top-0">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php foreach ($users as $user){ ?>

                                                    <tr>
                                                        <td style="color:black">

                                                            <?php if(empty($user['profile_image']) || $user['profile_image'] == ''|| is_null($user['profile_image']) ){ 
                                                                if($user['gender'] == 'female'){
                                                                    
                                                                    $imageSource = SURL.'assets/images/female.png';
                                                                }elseif($user['gender'] == 'male'){

                                                                    $imageSource = SURL.'assets/images/male.png';;
                                                                }else{
                                                                    $imageSource = SURL.'assets/images/male.png';;
                                                                }

                                                            }else{

                                                                $imageSource = $user['profile_image'];
                                                            } ?>
                                                            
                                                            <img src="<?php echo $imageSource; ?>" alt="" class="rounded-circle images avatar-sm bx-shadow-lg image2">
                                                            <span style="padding-left: 2%"> <?php echo $user['full_name']; ?></span>                                                    
                                                        </td>
                                                        <td><?php echo $user['email_address']; ?></td>
                                                        <td><?php echo $user['phone_number']; ?></td>
                                                        <td><?php echo $user['gender']; ?></td>
                                                        <td><?php echo str_replace('(Impressions)', '', $user['package']) ; ?></td> 
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
    </body>
</html>