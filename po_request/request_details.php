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
$request_id = $_GET['request_id'];

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
    table.dom th{
        width: 35%;
    }
    table.dom td{
        word-wrap: break-word;
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


        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h4 class="card-title">Request Details</h4>
                        <div style="display: flex;justify-content: flex-end;">
                            <a href="<?php echo $website?>/po_request/"><button class="btn btn-danger" type="button">Close</button></a>

                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                        <table class="table table-responsive-md dom" style="text-align: center;">
                            <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>Purchase Id</strong></th>
                                <th><strong>Product Name</strong></th>
                                <th><strong>Qty</strong></th>
                                <th><strong>Product Cost</strong></th>
                                <th><strong>Stock</strong></th>
                                <th><strong>Supplier</strong></th>

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

                            $sql = "SELECT * FROM purchase_details WHERE intend_id = '$request_id'  ORDER BY id DESC LIMIT $start, 10";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $product_name=$row['product_name'];
                            $purchase_id=$row['purchase_id'];
                            $qty =  $row['qty'];
                            $unit_cost =  $row['unit_cost'];
                            $reasonType =  $row['reasonType'];
                            $product_id =  $row['product_id'];

                            $sqlProduct = "SELECT * FROM `product` WHERE `product_id`='$product_id'";
                            $resProduct = mysqli_query($conn, $sqlProduct);
                            $rowProduct = mysqli_fetch_assoc($resProduct);
                            $stock_qty =  $rowProduct['stock_qty'];

                            $sqlamount="SELECT SUM(pay_made) AS pay_made  FROM purchase_payment WHERE purchase_id='purchase_id'";
                            $resamount=mysqli_query($conn,$sqlamount);
                            if(mysqli_num_rows($resamount)>0){
                                $arrayamount=mysqli_fetch_array($resamount);
                                $totalAmount=$arrayamount['pay_made'];
                            }
                            $grand_total= $row['grand_total'];
                            $balance_amount= $grand_total - $totalAmount;

                            $sqlIntend = "SELECT * FROM `purchase` WHERE `intend_id`='$request_id'";
                            $resIntend = mysqli_query($conn, $sqlIntend);
                            $rowIntend = mysqli_fetch_assoc($resIntend);
                            $purchase_id =  $rowIntend['purchase_id'];
                            $supplier_id =  $rowIntend['supplier'];

                            $sqlSupply = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                            $resSupply = mysqli_query($conn, $sqlSupply);
                            $rowSupply = mysqli_fetch_assoc($resSupply);
                            $supplier_name =  $rowSupply['supplier_name'];


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
                                <td><?php echo $purchase_id?></td>
                                <td><?php echo $product_name?></td>
                                <td> <?php echo $qty?> </td>
                                <td> <?php echo $unit_cost?> </td>
                                <td> <?php echo $stock_qty?> </td>
                                <td> <?php echo $supplier_name?> </td>
<!--                                <td> --><?php //echo $reasonType?><!-- </td>-->
                            </tr>
                            <?php } }
                            ?>

                            </tbody>
                        </table>

                                </div></div></div>

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
    var elems = document.querySelectorAll('.active_link');
    [].forEach.call(elems, function(el){
        el.classList.remove("active_link");
    });


    var liElement = document.getElementById("active33");
    var aElement = document.getElementById("link33");

    // Add classes to the elements
    liElement.classList.add("mm-active", "active_link");
    aElement.classList.add("mm-active", "active_link");
</script>

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
        window.location.href= 'https://erp.aecindia.net/purchase/invoice.php?purchase_id='+purchase_id;
    }
</script>

</body>
</html>
