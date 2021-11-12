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
        <link rel="shortcut icon" href="<?php echo SURL;?>assets/images/favicon.png">

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

            .userNameColorChange{
                color: black;
            }

            .filters_style {
                border-radius: 25px;
                border: 2px solid #e9ecef;
                width: 100%;
            }

            .filters_style_input {
                border-radius: 25px;
                border: 2px solid #e9ecef;
                width: 100%;
                background-color : #F9F9F9;
                /* height: 100%; */
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                /* border: 1px solid #dddddd; */
                border-bottom: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            .styleHeader{

                color: black;
                font-weight : bold
            }
            
            /* paggination  */
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

            .styleShow{
                color       : black;
                font-weight : bold;
                margin-left : -11%;
            }

            .buttonNew{
              	border-radius: 25px;
				background-color:  #57B0AF;
				color: white;
				font-weight: bold;
                text-decoration: none;
                display: inline-block;
                cursor: pointer;
                width: 44%;
            }

            .buttonReset{
                border-radius: 25px;
				color: white;
				font-weight: bold;
                text-decoration: none;
                display: inline-block;
                cursor: pointer;
                width: 44%;
                background-color:  #ff0e0e;
                text-align: center;
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
                <div class="content" style="margin-top: 2%">
                    <!-- Start Content-->
                    <div class="container-fluid">
                    <?php $filter_user_data = $this->session->userdata('filter_data');?>                
                        <form method="POST" action="<?php echo base_url();?>index.php/admin/Trasection/index">
                            <div class="row">
                                <div class="col-xl-3">
                                    <label>From:</label>
                                    <input type="date" class="form-control filters_style_input" placeholder="start date" name="start_date"  value="<?=(!empty($filter_user_data['start_date']) ? $filter_user_data['start_date'] : "")?>" />
                                </div> <!-- end col -->

                                <div class="col-xl-3">
                                    <label>To:</label>
                                  <input type="date" class="form-control filters_style_input" placeholder="end date"  name="end_date"  value="<?=(!empty($filter_user_data['end_date']) ? $filter_user_data['end_date'] : "")?>" />
                                </div> <!-- end col -->

                                <div class="col-xl-3">
                                    <label>Package: </label>
                                    <select id="package" name="package" type="text" class="form-control filters_style_input filter">
                                        <option value="" selected>All</option>
                                        <option value="free"<?=(($filter_user_data['package'] == "free") ? "selected" : "")?>>Free</option>
                                        <option value="Standard (Impressions)"<?=(($filter_user_data['package'] == "Standard (Impressions)") ? "selected" : "")?>>Standard</option>
                                        <option value="Premium (Impressions)"<?=(($filter_user_data['package'] == "Premium (Impressions)") ? "selected" : "")?>>Premium</option>
                                    </select>   
                                </div> <!-- end col -->

                                <div class="col-xl-3">  
                                    <label style="display: block;">Search: </label>
                                    <input type="submit" class="form-control filters_style_input filter buttonNew" value="Filter" />
                                    <a class= "form-control filters_style_input filter buttonReset"href="<?php echo base_url();?>index.php/admin/Trasection/resetFilter">Reset</a>
                                </div> <!-- end col -->
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-box boxStyle filters_style" style= "margin-top: 3%">
                                    <div class="table-responsive"> 
                                        <table class="table table-centered table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">User<?php echo count($trasection); ?></th>
                                                    <th class="border-top-0">Email</th>
                                                    <th class="border-top-0">Date</th>
                                                    <th class="border-top-0">Subscription Plan</th>
                                                    <th class="border-top-0">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($trasection as $trasectionValue) {?>
                                                    <tr>
                                                        <td>   
                                                            <?php if(empty($trasectionValue['profile_image']) || $trasectionValue['profile_image'] == ''|| is_null($trasectionValue['profile_image']) ){ 
                                                                if($trasectionValue['gender'] == 'female'){
                                                                    
                                                                    $imageSource = SURL.'assets/images/female.png';
                                                                }elseif($trasectionValue['gender'] == 'male'){

                                                                    $imageSource = SURL.'assets/images/male.png';
                                                                }else{
                                                                    $imageSource = SURL.'assets/images/male.png';
                                                                }
                                                            }else{

                                                                $imageSource = $trasectionValue['profile_image'];
                                                            } ?>

                                                            <img src="<?php echo $imageSource; ?>" alt="" class="rounded-circle images avatar-sm bx-shadow-lg image2">
                                                            <span style="padding-left: 2%; color:black"> <?php echo $trasectionValue['full_name']; ?></span>
                                                        </td>
                                                        <td><?php echo $trasectionValue['email_address']; ?></td>
                                                        <td>
                                                           <?php echo $date = ($trasectionValue['pakage_buy_date']) ? $trasectionValue['pakage_buy_date']->toDateTime()->format("Y-m-d") : '---'; ?>
                                                        </td>
                                                        <td><?php echo ($trasectionValue['package']) ? $trasectionValue['package'] : 'free'; ?></td>
                                                        <td><?php echo $trasectionValue['packageDetails'][0]['currency'].$trasectionValue['packageDetails'][0]['amount']; ?> </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="pagination"><?php echo $links; ?></div>
                                    </div>

                                </div> 
                            </div> 
                        </div> <!-- end row -->

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

        <script>
            $("#checkAll").click(function(){
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        </script>

    </body>
</html>