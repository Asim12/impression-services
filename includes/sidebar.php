
<style>
    .nameStyle{
        color: black;
        font-weight : bold;
        font-size   : 15px
    }
    #sidebar-menu > ul > li > a.active{
        height: 47px;
        background: #57B0AF;
        border-radius: 16px;
        color: white;
        margin-right: 6%;
        margin-left: 2%;
    }
    #sidebar-menu > ul > li > a{
        bottom: 83.22%;
        font-size: 14px;
        line-height: 20px;
        color: #8E8E8E;
    }
</style>
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <?php         
            $userArray = $this->session->userdata('user_data');
            $tabName   = $this->session->userdata('tabName');
            $Paymentclass = '';
            $class = '';
            if($tabName == 'Users'){

                $class = 'active';
            }
            if($tabName == 'Payments'){

                $Paymentclass = 'active';
            }
        ?>
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="mt-3">
                    <a href="<?php echo base_url();?>index.php/admin/Dashboard/index">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url();?>index.php/admin/users/users" class= "<?php echo $class;?>">
                        <i class="mdi mdi-account-outline" medium></i>
                        <span> Users </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url();?>index.php/admin/Trasection/index"  class= "<?php echo $Paymentclass;?>">
                        <i class="far fa-credit-card"></i>
                        <span> Payments </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url();?>index.php/admin/Support/index"> 
                        <i class="mdi mdi-chat"></i>
                        <span> Support </span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url();?>index.php/admin/Promotion/index">
                        <i class="mdi mdi-clipboard-text-outline"></i>
                        <span> Promotion </span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->
</div>
<script>


    // $(document).ready(function () {
    //     $('.metismenu li a').click(function(e) {

    //         $('.metismenu li.active').removeClass('active');

    //         var $parent = $(this).parent();
    //         $parent.addClass('active');
    //         e.preventDefault();
    //     });
    // });


</script>