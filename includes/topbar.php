
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .newStyle{
            left: 16%;
            font-style: normal;
            font-weight: normal;
            line-height: 21px;
        }
        .searchBar {
            color: black;
            border-radius: 25px;
            border: 2px solid #fff;
        }
        .badge{
            position: absolute;
            top: 13px;
            margin-left: -1%;
            right: 6px;
            padding: 5px 7px;
            border-radius: 50%;
            background: red;
            color: black;
        }
    </style>
</head>
<div class="navbar-custom" style="-webkit-box-shadow: none;box-shadow: none; background-color: #eee;height: 80px; ">
    <ul class="list-unstyled topnav-menu float-right mb-0">
        <li class="app-search d-none d-md-block">
            <form>
                <input style="color:black; background-color: #fff" type="text" placeholder="Search..." class="form-control searchBar">
                <button type="submit" class="sr-only"></button>
            </form>
        </li>
        <?php
            $userArray      =  $this->session->userdata('user_data');
            $profile_image  =  $userArray['profile_image'];
            if(empty($profile_image)){
                $profile_image = SURL."assets/images/male.png";
            }
            $notificationData  =  getAdminNotification();
            $notificationCount =  countAdminNotification();
        ?>
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="dripicons-bell noti-icon text-dark"></i>
                <span class="badge"><?php echo $notificationCount; ?></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5 class="m-0">
                        <span class="float-right">
                            <a href="#"  id="markAllRead" class="text-dark">
                                <small>Read all</small>
                            </a>
                        </span>Notification
                    </h5>
                </div>

                <div class="slimscroll noti-scroll" style="position: relative; overflow: hidden; width: auto; max-height:445px">

                    <?php foreach ($notificationData as $notification) { 

                        $time_zone = date_default_timezone_get();
                        $join_date = $notification['created_date']->toDateTime()->format("Y-m-d H:i:s");                                                                
                        $last_time_ago = time_elapsed_string($join_date , $time_zone);

                        if(empty($notification['userData'][0]['profile_image'])){
                            
                            $profile_img = SURL.'assets/images/male.png';
                        }else{
                            
                            $profile_img = $notification['userData'][0]['profile_image'];
                        }
                    ?>
                        <a href="javascript:void(0);" class="dropdown-item notify-item active">
                            <div class="notify-icon">
                                <img src="<?php echo $profile_img; ?>" class="img-fluid rounded-circle" alt="" />
                            </div>
                            <p class="notify-details"><?php echo $notification['message']; ?>
                                <small title="<?php echo $join_date;?>" class="text-muted"><?php echo $last_time_ago;?>
                                </small>
                            </p>
                        </a>

                    <?php } ?>
                </div>
            </div>
        </li>

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="<?php echo $profile_image; ?>" alt="user-image" class="rounded-circle">
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <div class="dropdown-item noti-title">
                    <h6 class="m-0">
                        Welcome <?php echo $userArray['first_name']; ?>
                    </h6>
                </div>
                <div class="dropdown-divider"></div>

                <a href= "<?php echo base_url();?>index.php/admin/Login/logoutUser" class="dropdown-item notify-item">
                    <i class="dripicons-power"></i>
                    <span>Logout</span>
                </a>
            </div>
        </li>
    </ul>
    <ul class="list-unstyled menu-left mb-0">
        <li class="float-left">
            <a href="<?php echo base_url();?>index.php/admin/login/index" class="logo">
                <span class="logo-lg" style="background-color: #f8f8f8">
                    <img src="<?php echo SURL;?>assets/images/logo2.png" alt="" height="1%" width="50%" style = "margin-top: 7%">
                </span>
                <span class="logo-sm" style="background-color: #f8f8f8">
                    <img src="<?php echo SURL;?>assets/images/logo2.png" alt="" height="1%" width="50%" style = "margin-top: 7%">
                </span>
            </a>
        </li>
        <li class="float-left">
            <a class="button-menu-mobile navbar-toggle" style = "background-color: #eeeeee">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
        </li>
        <?php  
            $tabName   = $this->session->userdata('tabName');
            if(empty($tabName)){
            $tabName = 'Dashboard';
            }
        ?>

        <li class="float-left" style="position:fixed; margin-left:1%;display:inline-block">
            <h3 class="pt-2" style = "font-weight: bold; color: black; "><?php echo $tabName; ?></h3> 
            <h5 class="newStyle">Morning <?php echo $userArray['first_name']; ?>, Welcome to Impressions Dashboard</h5>
        </li>
    </ul>
</div> 

<script>
    $(document).ready(function(){
        $('#markAllRead').click(function(){
            $.ajax({
                'url': '<?php echo base_url();?>index.php/admin/Dashboard/markAllReadss',
                'type': 'POST',
                'data': "",
                'success': function (response) {
                    $('.badge').remove();
                }
            });
        });
    });
</script>