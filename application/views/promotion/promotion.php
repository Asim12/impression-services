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
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>

            .userNameColorChange{
                color: black;
            }

            .filters_style {
                border-radius: 25px;
                border: 2px solid #e9ecef;
                width: 100%;
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

            /* round button styleShow */

            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }

            .switch input { 
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: #2196F3;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }
            .slider.round {
                border-radius: 34px;
            }
            .slider.round:before {
                border-radius: 50%;
            }
            /* end button style */


            /* navigation bar style */
            .topnav {
                border-radius: 25px;
                border: 2px solid #e9ecef;

                overflow: hidden;
                background-color: white;
                width : 50%;
            }

            .topnav a {
                float: left;
                color: #CDD1D6;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 17px;
            }

            .topnav a.active {
                background-color: #318D8C;
                color: white;
            }
            /* end navigation bar style */
            
            .button{
                float : right;
                margin-top: 13px;
              	border-radius: 25px;
				background-color:  #EC4A2A;
				color: white;
				font-weight: bold;
                height: auto;    
                border: none;
                padding: 8px;
                padding-left: 4%;
                padding-right: 4%;
                text-decoration: none;
                display: inline-block;
                font-size: 18px;
                cursor: pointer;
                width: auto;
            }

            .button1{
                float : right;
              	border-radius: 25px;
				background-color:  #EC4A2A;
				color: white;
				font-weight: bold;
                height: 50%;    
                border: none;
                padding: 8px;
                padding-left: 4%;
                padding-right: 4%;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                width: 26%;
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

            <?php include('includes/topbar.php');?>
            <?php include('includes/sidebar.php');?>

            <div class="content-page">
                <div class="content" style="margin-top: 2%">
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <?php if ($this->session->flashdata('message')) { ?>

                            <div id="message" class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('message'); ?></div>
                        <?php }elseif($this->session->flashdata('error')){ ?>

                            <div id="message" class="alert alert-danger alert-dismissable"><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>

                        <?php $activeNavigationBar = $this->session->userdata('type'); 
                        if($activeNavigationBar == 'video'){

                            $class = 'active';
                        }elseif($activeNavigationBar == 'all'){

                            $class1 = 'active';
                        }elseif($activeNavigationBar == 'image'){

                            $class2 = 'active';
                        }else{
                         
                            $class2 = 'active';
                        }
                        ?>
                        <div class="row" >
                            <div class="col-xl-9">
                                <div class="topnav">
                                    <a class="<?php echo $class1; ?>" href="<?php echo SURL;?>index.php/admin/Promotion/index?type=all">All</a> 
                                    <a class="<?php echo $class2; ?>"  href="<?php echo SURL;?>index.php/admin/Promotion/index?type=images">Images</a>
                                    <a class="<?php echo $class; ?>" href="<?php echo SURL;?>index.php/admin/Promotion/index?type=videos">Videos</a>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <button type="button" class="button" data-toggle="modal" data-target="#myModal">Create new</button>
                            </div>
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content filters_style">
                                        <div class="modal-header">
                                            <h4 class="modal-title">New Advertisement</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">

                                            <form method="POST" action="<?php echo SURL;?>index.php/admin/Promotion/submitPromotion" enctype="multipart/form-data" id= "promotionForm">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <label>Ad Name: </label>
                                                        <input type="text" class="form-control filters_style" id="addName" name="addName" placeholder="Enter ad name" required/>
                                                    </div> 

                                                    <div class="col-xl-6">
                                                        <label>Discription: </label>
                                                        <input type="text" class="form-control filters_style" id="discription" name="discription" placeholder="Enter ad name" required/>
                                                    </div> 
                                                    
                                                    <div class="col-xl-6">
                                                        <label>URL: </label>
                                                        <input type="text" class="form-control filters_style" id="url" name="url" placeholder="Enter ad name" required/>
                                                    </div> 

                                                    <div class="col-xl-6">
                                                        <label>Upload Image or Video: </label>
                                                        <input type="file" name="file" class="form-control filters_style"/> 
                                                    </div>
                                                </div>


                                                <div class="row" style="margin-top:5%">
                                                    <div class="col-xl-6">
                                                        <label>Start Date: </label>
                                                        <input type="date" class="form-control filters_style" id="startDate" name="startDate" required />
                                                    </div> 

                                                    <div class="col-xl-6">
                                                    <label>End Date: </label>
                                                        <input type="date" class="form-control filters_style" id="endDate" name="endDate" required />
                                                    </div>
                                                </div>


                                                <div class="row" style="margin-top:5%">
                                                    <div class="col-xl-4">
                                                        <h5>Publication: </h5>
                                                    </div> 

                                                    <div class="col-xl-4">
                                                    
                                                        <label class="switch">
                                                            <input type="checkbox" checked value="yes" name="publication">
                                                            <span class="slider round"></span> 
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:5%">
                                                    <div class="col-xl-12">
                                                        <div class="modal-footer">
                                                            <button type="submit" class="button1">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- edit model start -->
                        <div class="modal fade" id="myEditModel" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content filters_style">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Advertisement</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">

                                        <form method="POST" action="<?php echo SURL;?>index.php/admin/Promotion/updatePromotions" enctype="multipart/form-data" id= "promotionForm">
                                            <div class="row" id="append">
                                               
                                            </div>

                                            <div class="row" style="margin-top:5%" id="append1">
                                               
                                            </div>


                                            <div class="row" style="margin-top:5%" id= "append2">
                                               
                                            </div>


                                            <div class="row" style="margin-top:5%">
                                                <div class="col-xl-12">
                                                    <div class="modal-footer">
                                                        <button type="submit" class="button">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- edit model end -->

                        <!-- view model start -->
                        <div class="modal fade" id="myModalView" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content filters_style">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Promotions/ Ads</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xl-4">
                                                <div class="card-box boxStyle" style= "border: 1px solid black;">  
                                                    <h5 style= "color: black;font-weight: bold ;font-size: 20px">27K Impressions</h5> 
                                                </div>
                                            </div> <!-- end col -->

                                            <div class="col-xl-4">
                                                <div class="card-box boxStyle" style= "border: 1px solid black;">
                                                    <h5 style = "color: black;font-weight: bold ;font-size: 20px">10K Clicks</h5>
                                                </div>
                                            </div> <!-- end col -->

                                            <div class="col-xl-4">
                                                <div class="card-box boxStyle" style= "border: 1px solid black;">
                                                    <h5 style= "color: black;font-weight: bold;font-size: 20px">30K Views</h5>
                                                </div>
                                            </div> <!-- end col -->
                                        </div>

                                        <div class="row" style="margin-top:5%">
                                            <div class="col-xl-12" id= "tableAppend">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- view model end -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-box boxStyle filters_style" style= "margin-top: 2%">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover mb-0" id="">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">Ad name</th>
                                                    <th class="border-top-0">Discription</th>
                                                    <th class="border-top-0">URL</th>
                                                    <th class="border-top-0">Image/Video</th>
                                                    <th class="border-top-0">Start date</th>
                                                    <th class="border-top-0">End date</th>
                                                    <th class="border-top-0">Statistics</th>
                                                    <th class="border-top-0">Publication</th>
                                                    <th class="border-top-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>   

                                            <?php foreach ($promotions as $value){ ?>
                                                <tr class= "filters_style">
                                                    <td style="color:black;text-align:center"><?php echo $value['add_name']; ?></td>

                                                    <td style="color:black;text-align:center"><?php echo $value['discription']; ?></td>
                                                    <td style="color:black;text-align:center"><?php echo $value['url']; ?></td>
                                                    <td>
                                                        <input type="hidden" value= "<?php echo SURL.$value['image'];?>" />

                                                        <?php if($value['type'] == "video") {?>
                                                            
                                                            <video width="125px" height="125px" controls><source src="<?php echo SURL.$value['image'];?>" ></video>
                                                        <?php }else{ ?>

                                                            <img src="<?php echo SURL.$value['image'];?>" alt="user-image" class="img-rounded" width="125px" height="125px" />
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo $value['start_date']->toDateTime()->format("Y-m-d"); ?> </td>
                                                    <td><?php echo $value['end_date']->toDateTime()->format("Y-m-d"); ?> </td>

                                                    <td>
                                                        <!-- <button type="button" class="button" data-toggle="modal" data-target="#myModal">view</button> -->

                                                        <a href="#" class="view"data-toggle="modal" data-target="#myModalView" type="button">view</a>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" value= "<?php echo $value['publication']; ?>" />
                                                        <?php if($value['publication'] == 'yes'){ ?>

                                                            <label class="switch">
                                                                <input type="checkbox" checked readonly value="yes">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        <?php }else{ ?>
                                                            <label class="switch">
                                                                <input type="checkbox" readonly value="no">
                                                                <span class="slider round"></span>
                                                            </label>

                                                        <?php } ?>
                                                       
                                                    </td>
                                                    <td>
                                                        <input type="hidden" value="<?php echo (string)$value['_id']; ?>" />
                                                        <button type="button" class="click" data-toggle="modal" data-target="#myEditModel"><i class="fa fa-edit" aria-hidden="true"></i></button>
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
            // flash message time out 
            $(document).ready(function() {
                setTimeout(function() {
                    $("#message").hide('blind', {}, 500)
                }, 5000);
            });

            $(document).ready(function() {
                $("#promotionForm")[0].reset()
            });

            $('.click').click(function(){

                var currentRow   =   $(this).closest("tr"); 
                var add_name     =   currentRow.find("td:eq(0)").text();
                var discription  =   currentRow.find("td:eq(1)").text();
                var url          =   currentRow.find("td:eq(2)").text();
                var startDate    =   $.trim(currentRow.find("td:eq(4)").text()); 
                var endDate      =   $.trim(currentRow.find("td:eq(5)").text());
                var reviewId     =   $(this).siblings('input').val();
                var publications =  currentRow.find("td:eq(7) input[type='hidden']").val();
                var image        =   currentRow.find("td:eq(3) input[type='hidden']").val();

                var status ='';
                var value ='no';
                if(publications == 'yes'){

                    status = 'checked';
                    value = 'yes';
                }
                var content  = '<div class="col-xl-6">';
                    content += '<label>Ad Name: </label>';
                    content += '<input type="text" class="form-control filters_style" id="edit_addName" value="'+add_name+'" name="edit_addName" placeholder="Enter ad name" required/>';
                    content += '<input type="hidden" value="'+reviewId+'" name="edit_id"/>';
                    content += '</div> ';


                    content += '<div class="col-xl-6">';
                    content += '<label>Discription: </label>';
                    content += '<input type="text" class="form-control filters_style" id="discription" value="'+discription+'" name="discription" placeholder="Enter ad name" required/>';
                    content += '</div>';

                    content += '<div class="col-xl-6">';
                    content += '<label>URL: </label>';
                    content += '<input type="text" class="form-control filters_style" id="url" value="'+url+'" name="url" placeholder="Enter ad name" required/>';
                    content += '</div>';

                    content += '<div class="col-xl-6">';
                    content += '<label>Upload Image or Video: </label>';
                    content += '<input type="file" name="edit_file" id="edit_file" value="'+image+'"class="form-control filters_style"/>';
                    content += '</div>';

                    var content1 = '<div class="col-xl-6">';
                        content1 += '<label>Start Date: </label>';
                        content1 += '<input type="date" class="form-control filters_style" value="'+startDate+'" id="edit_startDate" name="edit_startDate" required/>';
                        content1 += '</div>'; 
                        content1 += '<div class="col-xl-6">';
                        content1 += '<label>End Date: </label>';
                        content1 += '<input type="date" class="form-control filters_style" value="'+endDate+'" id="edit_endDate" name="edit_endDate" required />';
                        content1 += '</div>';

                    var content2  = '<div class="col-xl-4">';
                        content2 += '<h5>Publication: </h5>';
                        content2 += '</div>'; 
                        content2 += '<div class="col-xl-4">';
                        content2 += '<label class="switch">';
                        content2 += '<input type="checkbox"'+status+'  value="'+value+'" id="edit_publication" name="edit_publication" name="edit_check"/>';
                        content2 += '<span class="slider round"></span>'; 
                        content2 += '</label>';
                        content2 += '</div>';

                $('#append').html(content);
                $('#append1').html(content1);
                $('#append2').html(content2);
            });

            $(document).ready(function() {
                $('.view').click(function(){
            
                    var tableContent =  '<table class="table table-centered table-hover mb-0" id="datatable">';
                        tableContent +=  '<thead>';
                        tableContent +=  '<tr>';
                        tableContent +=  '<th class="border-top-0">User</th>';
                        tableContent +=  '<th class="border-top-0">Email</th>';
                        tableContent +=  '<th class="border-top-0">Phone</th>';
                        tableContent +=  '<th class="border-top-0">Gender</th>';
                        tableContent +=  '</tr>';
                        tableContent +=  '</thead>';
                        tableContent +=  '<tbody>';
                        tableContent +=  '<tr class= "filters_style">';
                        tableContent +=  '<td style="color:black;text-align:center">Asim</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">asim92578@gmail.com</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">03135936985</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">Male</td>';  
                        tableContent +=  '</tr>';
                        tableContent +=  '<tr class= "filters_style">';
                        tableContent +=  '<td style="color:black;text-align:center">Asim</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">asim92578@gmail.com</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">03135936985</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">Male</td>';  
                        tableContent +=  '</tr>';
                        tableContent +=  '<tr class= "filters_style">';
                        tableContent +=  '<td style="color:black;text-align:center">Asim</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">asim92578@gmail.com</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">03135936985</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">Male</td>';  
                        tableContent +=  '</tr>';
                        tableContent +=  '<tr class= "filters_style">';
                        tableContent +=  '<td style="color:black;text-align:center">Asim</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">asim92578@gmail.com</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">03135936985</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">Male</td>';  
                        tableContent +=  '</tr>';
                        tableContent +=  '<tr class= "filters_style">';
                        tableContent +=  '<td style="color:black;text-align:center">Asim</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">asim92578@gmail.com</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">03135936985</td>';  
                        tableContent +=  '<td style="color:black;text-align:center">Male</td>';  
                        tableContent +=  '</tr>';
                        tableContent +=  ' </tbody>';
                        tableContent +=  '</table>';     
                    $('#tableAppend').html(tableContent);
                });
            });
        </script>
    </body>
</html>