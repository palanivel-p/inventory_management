<?php
Include("../../includes/connection.php");
$websites='https://erp.aecindia.net/field_app';
if(isset($_GET['logout'])==1) {


    setcookie("field_api_key", "", time() + (3600 * 1), "/"); // To empty the cookie
//    setcookie("user_id", "", time() + (3600 * 1), "/"); // To empty the cookie
//    setcookie("role", '', time() + (3600 * 1), "/"); // To set Login for 1 hr
//    setcookie("user_name", '', time() + (3600 * 1), "/"); // To set Login for 1 hr
//
//
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard</title>

    <link rel="icon" type="image/png" sizes="16x16" href="../../images/favicon_New.png">
    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../vendor/chartist/css/chartist.min.css">

    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        .nav-control{
            display: none;
        }.nav-header{
            display: none;
        }
        .outer {
            width: 1px;
            height: 100%;
            margin: auto;
            position: relative;
            overflow: hidden;
        }
        .inner {
            position: absolute;
            width:100%;
            height: 40%;
            background: grey;
            top: 30%;
            box-shadow: 0px 0px 30px 20px grey;
        }
    </style>
</head>

<body>


<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>




<div id="main-wrapper">


    <?php
//    $header_name='Dashboard';
//    Include ('../../includes/header.php');
    Include ('../../includes/connection.php');

    /*************** statistics ************/


    /******* Transaction Data*****/

    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d');






    ?>
    <div class="nav-header" style="background-color: #ffffff">
        <a href="<?php echo $website; ?>/dashboard/" class="brand-logo">
            <!-- <img class="logo-abbr" src="https://bhims.ca/piloting/img/favicon_New.png" alt="">-->
            <!--            <img class="logo-abbr" src="<?php echo $website; ?>/includes/AEC.png" style="width: 70px;height: 42px" alt="">-->
            <!--            <img class="logo-compact" src="<?php echo $website; ?>/includes/AEC.png" alt="">-->
            <img class="brand-title" src="<?php echo $website; ?>/includes/AEC.png" style="height: 100px;width: 222px" alt="">
        </a>
        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </div>
        </div>
    </div>


    <div class="header">
        <img src="<?php echo $website; ?>/includes/AEC.png" style="height: 87px;width: 200px;z-index: 1">

        <!--        <img src="--><?php //echo $website; ?><!--/includes/AEC.png">-->
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left">
                        <div class="dashboard_bar">
<!--                            --><?php //echo  $header_name; ?>
                        </div>
                    </div>
                    <ul class="navbar-nav header-right">
                        <li class="nav-item">

                        </li>
                        <li class="nav-item dropdown notification_dropdown">

                        </li>
                        <li class="nav-item dropdown notification_dropdown">

                        </li>
                        <li class="nav-item dropdown header-profile" style="margin-top: -86px">


                            <a class="nav-link" href="javascript:void(0)" role="button" data-toggle="dropdown">
                                <img src="<?php echo $website; ?>/img/avatar.jpg" width="20" alt="" >
                                <div class="header-info">
                                    <?php
                                    if($_COOKIE['role'] == 'Super Admin'){
                                        ?>
                                        <span class="text-black">Super Admin</span>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($_COOKIE['role'] != 'Super Admin'){
                                        ?>
                                        <span class="text-black"><?php echo $_COOKIE['role']; ?></span>
                                        <span class="text-black"><?php echo $_COOKIE['user_name']; ?></span>
                                        <?php
                                    }
                                    ?>
                                    <p class="fs-12 mb-0">
                                        <?php

                                        date_default_timezone_set("Asia/Calcutta");

                                        if (date("H") < 12) {

                                            echo "Good Morning !";

                                        } elseif (date("H") > 11 && date("H") < 17) {

                                            echo "Good Afternoon !";

                                        } elseif (date("H") > 16) {

                                            echo "Good Evening !";
                                        }

                                        ?>
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">

                                <a class="dropdown-item ai-icon">
                                    <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                         width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="ml-2"><?php  echo $_COOKIE['role'];?></span>
                                </a>
                                <a href="<?php echo $websites; ?>/login?logout=1" class="dropdown-item ai-icon">
                                    <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                         width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    <span class="ml-2">Logout </span>
                                </a>
                            </div>

                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="content-body">

        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="row">


<!--                                <div class="col-xl-3 col-lg-6 col-sm-6" style="visibility:hidden">-->
<!--                                    <div class="widget-stat card bg-danger">-->
<!--                                        <div class="card-body  p-4">-->
<!--                                            <div class="media">-->
<!--                                                <div class="media-body text-white">-->
<!--                                                    <p class="mb-1" style="text-align: center;">Total Purchase</p>-->
<!--                                                    <h3 class="text-white">--><?php ////echo $total_visits; ?><!--</h3> -->
<!--                                                    <h3 class="text-white" style="margin-top: 30px; text-align: center;">--><?php ////echo $totalPurchase; ?><!--</h3>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->

                            <div class="col-xl-6 col-lg-12 col-sm-12" onclick="login('logIn')">
                                <div class="widget-stat card bg-success">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
<!--                                                <p class="mb-1" style="text-align: center;">Total Sale</p>-->
                                                 <h3 class="text-white text-center pt-4" id="logi">LogIn</h3>
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php //echo $totalSale; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="col-xl-6 col-lg-12 col-sm-12" onclick="login('logOut')">
                            <div class="widget-stat card bg-info">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="media-body text-white">
<!--                                            <p class="mb-1" style="text-align: center;">Purchase Return</p>-->
                                             <h3 class="text-white text-center pt-4" id="logo">LogOut</h3>
                                            <h3 class="text-white"style="margin-top: 30px; text-align: center;"><?php //echo $totalPurchaseReturn == ''?0:$totalPurchaseReturn; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-12 col-sm-12" onclick="login('breakOut')" >
                            <div class="widget-stat card bg-success">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="media-body text-white">
                                            <!--                                            <p class="mb-1" style="text-align: center;">Purchase Return</p>-->
                                            <h3 class="text-white text-center pt-4" id="breako">BreakOut</h3>
                                            <h3 class="text-white"style="margin-top: 30px; text-align: center;"><?php //echo $totalPurchaseReturn == ''?0:$totalPurchaseReturn; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 col-sm-12" onclick="login('breakIn')">
                            <div class="widget-stat card bg-info">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="media-body text-white">
                                            <!--                                                <p class="mb-1" style="text-align: center;">Total Sale</p>-->
                                            <h3 class="text-white text-center pt-4" id="breaki">BreakIn</h3>
                                            <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php //echo $totalSale; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


<!--                        <div class="col-xl-3 col-lg-6 col-sm-6" style="visibility: hidden">-->
<!--                            <div class="widget-stat card bg-primary">-->
<!--                                <div class="card-body p-4">-->
<!--                                    <div class="media">-->
<!--                                        <div class="media-body text-white">-->
<!--                                            <p class="mb-1" style="text-align: center;">Sales Return</p>-->
<!--                                            <h3 class="text-white" style="margin-top: 30px; text-align: center;">--><?php ////echo $totalSaleReturn == ''?0:$totalSaleReturn; ?><!--</h3>-->
<!--                                                                                       <hr>-->
<!--                                                                                       <p class="mb-1" style="text-align: center;">Monthly Collection</p>-->
<!--                                                                                       <h3 class="text-white" style="margin-top: 30px; text-align: center;">--><?php ////echo $totaloffline == ''?0:$totaloffline; ?><!--</h3>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
                <!--                <div class="col-xl-12">-->
                <!--                    <div class="row">-->
                <!--                        <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">-->
                <!--                            <div class="card">-->
                <!--                                <div class="card-header">-->
                <!--                                    <h4 class="card-title">Total Doner for Month - --><?php //echo date('Y'); ?><!--</h4>-->
                <!--                                </div>-->
                <!--                                <div class="card-body">-->
                <!--                                    <div id="currYearGraph" style="width: 100%; height: auto;"></div>-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->



            </div>
        </div>
    </div>


<!--    --><?php //Include ('../includes/footer.php') ?>



</div>



<script src="../../vendor/global/global.min.js"></script>
<script src="../../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../../vendor/chart.js/Chart.bundle.min.js"></script>
<script src="../../js/custom.min.js"></script>
<script src="../../js/dlabnav-init.js"></script>
<script src="../../vendor/owl-carousel/owl.carousel.js"></script>

<script src="../../vendor/peity/jquery.peity.min.js"></script>

<!--<script src="../vendor/apexchart/apexchart.js"></script>-->

<!--<script src="../js/dashboard/dashboard-1.js"></script>-->
<script src="../../js/highCharts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.7/sweetalert2.all.js" integrity="sha512-Qo6dQU9dpDBYXyy7qKSpMed3lfQE8FgAofWIrUgSZGIUSSu96oyU4MbYvWK6u6DinnMBbTadWc5Rtn6JaWXA/w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function login(type) {

        if(type=='logIn'){
            document.getElementById('logi').innerHTML='<i class="fa-solid fa-spinner fa-spin-pulse fa-spin-pulse"></i>';
        }else if(type=='logOut'){
            document.getElementById('logo').innerHTML='<i class="fa-solid fa-spinner fa-spin-pulse fa-spin-pulse"></i>';
        }
        else  if(type=='breakIn'){
            document.getElementById('breaki').innerHTML='<i class="fa-solid fa-spinner fa-spin-pulse fa-spin-pulse"></i>';
        }else{
            document.getElementById('breako').innerHTML='<i class="fa-solid fa-spinner fa-spin-pulse fa-spin-pulse"></i>';
        }


        function getLocations() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPositions, showErrors);
            } else {
                console.log("The Browser Does not Support Geolocation");
            }
        }

        function showPositions(position) {
            var lat = position.coords.latitude;
            var long = position.coords.longitude;

            console.log(lat);
            console.log(long);

            $.ajax({
                type: "POST",
                url: "attendance.php", // Wrap your URL in quotes
                data: {
                    type: type,
                    lat: lat,
                    lng: long
                },
                dataType: "json",

                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire({
                            title: "Success",
                            text: res.msg,
                            icon: "success",
                            button: "OK",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            closeOnClickOutside: false,
                        }).then((value) => {
                            window.location.reload();
                        });
                    }
                }
            });
        }

        function showErrors(error) {
            console.log("Error occurred: " + error.message);
        }

        getLocations(); // Call the function to get geolocation when login is initiated
    }

</script>


</body>
</html>

