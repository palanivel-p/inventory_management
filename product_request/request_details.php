<?php Include("../includes/connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);
$page= $_GET['page_no'];
$pur_id= $_GET['pur_id'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-d');
}
$from_date = date('Y-m-d 00:00:00',strtotime($f_date));
$to_date = date('Y-m-d 23:59:59',strtotime($t_date));
if($pur_id != ""){
    $pur_idSql= " AND purchase_id = '".$pur_id."'";

}
else{
    $pur_idSql ="";
}
if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}
$cookieStaffId = $_COOKIE['staff_id'];
$cookieBranch_Id = $_COOKIE['branch_id'];
if($_COOKIE['role'] == 'Super Admin'){
    $addedBranchSerach = '';
}
else {
    if ($_COOKIE['role'] == 'Admin'){
        $addedBranchSerach = "AND branch_name='$cookieBranch_Id'";

    }
//    else{
//        $addedBranchSerach = "AND added_by='$cookieStaffId' AND branch_name='$cookieBranch_Id'";
//
//    }

}
$intend_id = $_GET['request_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> Request Details</title>

    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon_New.png">
    <link href="../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/chartist/css/chartist.min.css">

    <link href="../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="../vendor/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">



    <link href="../vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">

    <link rel="stylesheet" href="../vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="../vendor/pickadate/themes/default.date.css">
    <link href="../vendor/summernote/summernote.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">


</head>
<style>
    .btn.btn-sm {
        /* Adjust the font size */
        font-size: 12px;
        /* Adjust padding if needed */
        padding: 5px 10px;
    }
    .error {
        color:red;
    }

</style>
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

    $header_name ="Request Details";
    Include ('../includes/header.php') ?>



    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <!--                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>-->
                <!--                <li class="breadcrumb-item active"><a href="javascript:void(0)">Purchase</a></li>-->


            </ol>

        </div>


        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Request Details</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">

                            <!--                            <div class="form-group mx-sm-3 mb-2">-->
                            <!---->
                            <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                            <!--                            </div>-->
                            <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        </form>
                        <!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
                        <!--                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>-->
                        <!--                        <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
                        <!--                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>-->
                        <!--            </span>Excel</button>       -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="padding-bottom:65px" >

                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr style="background-color: darkgray">
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Product Name</strong></th>
                                <th><strong>Qty</strong></th>
                                <th><strong>Product Cost</strong></th>
                                <th><strong>Reason</strong></th>

                            </tr>
                            </thead>
                            <?php
                            //                            if($pur_id == "" ) {
                            ////                                $sql = "SELECT * FROM purchase  ORDER BY id  LIMIT $start,10";
                            //                                $sql = "SELECT * FROM purchase_details WHERE purchase_id = '$purchase_id'  ORDER BY id DESC LIMIT $start, 10";
                            //                            }
                            //                            else {
                            //                                $sql = "SELECT * FROM purchase_details WHERE id > 0 WHERE intend_date  BETWEEN '$from_date' AND '$to_date' $pur_idSql ORDER BY id DESC LIMIT $start,10";
                            //                            }

                            $sql = "SELECT * FROM intend_details WHERE intend_id = '$intend_id'  ORDER BY id DESC LIMIT $start, 10";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $product_name=$row['product_name'];
                            $qty =  $row['qty'];
                            $unit_cost =  $row['unit_cost'];
                            $reasonType =  $row['reasonType'];

                            $sqlamount="SELECT SUM(pay_made) AS pay_made  FROM purchase_payment WHERE purchase_id='purchase_id'";
                            $resamount=mysqli_query($conn,$sqlamount);
                            if(mysqli_num_rows($resamount)>0){
                                $arrayamount=mysqli_fetch_array($resamount);
                                $totalAmount=$arrayamount['pay_made'];
                            }
                            $grand_total= $row['grand_total'];
                            $balance_amount= $grand_total - $totalAmount;

                            $sqlIntend = "SELECT * FROM `purchase` WHERE `intend_id`='$intend_id'";
                            $resIntend = mysqli_query($conn, $sqlIntend);
                            $rowIntend = mysqli_fetch_assoc($resIntend);
                            $purchase_id =  $rowIntend['purchase_id'];

                            if($row['payment_status'] == 1){
                                $statColor = 'success';
                                $statCont = 'Received';
                            }
                            else if($row['payment_status'] == 2){
                                $statColor = 'danger';
                                $statCont = 'partially Pending';
                            }
                            else if($row['payment_status'] == 3){
                                $statColor = 'danger';
                                $statCont = 'Pending';
                            }
                            else{
                                $statColor = 'danger';
                                $statCont = 'Ordered';
                            }
                            $statColors = 'success';
                            $date = $row['intend_date'];
                            $intend_date = date('d-m-Y', strtotime($date));
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $product_name?></td>
                                <td> <?php echo $qty?> </td>
                                <td> <?php echo $unit_cost?> </td>
                                <td> <?php echo $reasonType?> </td>
                            </tr>
                            <?php } }
                            ?>

                            </tbody>
                        </table>
                        <div class="col-12 pl-3" style="display: flex;justify-content: center;">
                            <nav>
                                <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">

                                    <?php

                                    $prevPage=abs($page-1);
                                    if ($prevPage > 0) {
                                        ?>
                                        <li class="page-item page-indicator"><a class="page-link"
                                                                                href="?page_no=<?php echo 1 ?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="fa-solid fa-angles-left"></i></a></li>
                                        <?php
                                    }

                                    if($prevPage==0)
                                    {
                                        ?>
                                        <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }
                                    $sql = "SELECT COUNT(id) as count FROM intend_details WHERE intend_id = '$intend_id'";

                                    //                                    if($pur_id == "" ) {
                                    //                                        $sql = "SELECT COUNT(id) as count FROM purchase";
                                    //                                    }
                                    //                                    else {
                                    //                                        $sql = "SELECT COUNT(id) as count FROM purchase WHERE id > 0 $pur_idSql";
                                    //                                    }
                                    $result = mysqli_query($conn, $sql);


                                    if (mysqli_num_rows($result)) {

                                        $row = mysqli_fetch_assoc($result);
                                        $count = $row['count'];
                                        $show = 10;


                                        $get = $count / $show;


                                        $pageFooter = floor($get);

                                        if ($get > $pageFooter) {
                                            $pageFooter++;
                                        }

                                        for ($i = 1; $i <= $pageFooter; $i++) {

                                            if($i==$page) {
                                                $active = "active";
                                            }
                                            else {
                                                $active = "";
                                            }

                                            if($i<=($pageSql+10) && $i>$pageSql || $pageFooter<=10) {

                                                ?>

                                                <li class="page-item <?php echo $active ?>"><a class="page-link"
                                                                                               href="?page_no=<?php echo $i ?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><?php echo $i ?></a>
                                                </li>
                                                <?php
                                            }
                                        }

                                        $nextPage=$page+1;


                                        if($nextPage>$pageFooter)
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a></li>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="fa-solid fa-greater-than"></i></a></li>
                                            <?php
                                        }
                                        if($nextPage<$pageFooter)
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $pageFooter ?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="fa-solid fa-angles-right"></i></a></li>
                                            <?php
                                        }
                                    }
                                    ?>

                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php Include ('../includes/footer.php') ?>
</div>


<script src="../vendor/global/global.min.js"></script>
<script src="../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../vendor/chart.js/Chart.bundle.min.js"></script>
<script src="../js/custom.min.js"></script>
<script src="../js/dlabnav-init.js"></script>
<script src="../vendor/owl-carousel/owl.carousel.js"></script>
<script src="../vendor/peity/jquery.peity.min.js"></script>
<!--<script src="../vendor/apexchart/apexchart.js"></script>-->
<script src="../js/dashboard/dashboard-1.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../js/plugins-init/jquery.validate-init.js"></script>
<script src="../vendor/moment/moment.min.js"></script>
<script src="../vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../vendor/summernote/js/summernote.min.js"></script>
<script src="../js/plugins-init/summernote-init.js"></script>


<script>

    $( document ).ready(function() {
        $('#search').val('<?php echo $search;?>');
        $('#branch_nameS').val('<?php echo $branch_nameS;?>');

    });
    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&p_id=<?php echo $p_id?>&s_id=<?php echo $s_id?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&p_id=<?php echo $p_id?>&s_id=<?php echo $s_id?>";
    });

    function pdf(purchase_id) {
        window.location.href= '<?php echo $website; ?>/purchase/invoice.php?purchase_id='+purchase_id;
    }
</script>

</body>
</html>
