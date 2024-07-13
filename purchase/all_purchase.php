<?php Include("../includes/connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);
$page= $_GET['page_no'];
$pur_id= $_GET['purs_id'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

//if($f_date != ''){
//    $from_date = date('Y-m-d 00:00:00',strtotime($f_date));
//}
//if($t_date != ''){
//    $to_date = date('Y-m-d 23:59:59',strtotime($t_date));
//
//}
if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-d');
}
$from_date = date('Y-m-d 00:00:00',strtotime($f_date));
$to_date = date('Y-m-d 23:59:59',strtotime($t_date));
if($pur_id != ""){
//    $pur_idSql= " AND purchase_id = '".$pur_id."'";
    $pur_idSql= " AND purchase_id LIKE '%".$pur_id."%'";

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
$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin'){
    $addedBy = "";
}
else{
    $addedBy = " AND added_by='$added_by'";
}


$pusrchase_sts = $_GET['purchase_sts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>All Purchase profile</title>

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
    #add_btn {
        background-color: green;
        color: white;
        cursor: pointer;
        padding: 10px;
        border-radius:15px;
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

    $header_name ="All Purchase";
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
                    <h4 class="card-title">Purchase List</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">

                            <!--                            <div class="form-group mx-sm-3 mb-2">-->
                            <!---->
                            <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                            <!--                            </div>-->
                            <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        </form>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>
                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>
                        <!--                        <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>
            </span>Excel</button>                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="padding-bottom:65px" >

                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>PO Id</strong></th>
                                <th><strong>Supplier</strong></th>
                                <th><strong>Grand Total</strong></th>
                                <th><strong>Due</strong></th>
                                <th><strong>Pay Status</strong></th>
                                <th><strong>Purchase Status</strong></th>
                                <th><strong>Approval Status</strong></th>
                                <th><strong>Invoice</strong></th>
                                <th><strong>View</strong></th>
                                <?php
                                if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Sales') {
                                    ?>
                                    <th><strong>ACTION</strong></th>
                                    <?php
                                }
                                ?>
                            </tr>
                            </thead>
                            <?php
                            if($pur_id == "" && $f_date =="" && $t_date =="") {
                                $sql = "SELECT * FROM purchase  ORDER BY id DESC LIMIT $start, 10";
                            }
                            else {
                                $sql = "SELECT * FROM purchase  WHERE id > 0 AND purchase_date  BETWEEN '$from_date' AND '$to_date' $pur_idSql ORDER BY id DESC LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $supplier_id=$row['supplier'];
                            $purchase_id =  $row['purchase_id'];
                            $payment_status =  $row['payment_status'];

                            if($payment_status == 1){
                                $pay_status ='Received';
                                $statColors = 'success';
                            }
                            elseif ($payment_status == 2){
                                $pay_status ='partially Received';
                                $statColors = 'info';
                            }
                            elseif ($payment_status == 3){
                                $pay_status ='partially Pending';
                                $statColors = 'danger';
                            }
                            elseif ($payment_status == 4){
                                $pay_status ='Pending';
                                $statColors = 'danger';
                            }
                            elseif ($payment_status == 0){
                                $pay_status ='Order';
                                $statColors = 'warning';
                            }
                            $sqlamount="SELECT SUM(pay_made) AS pay_made  FROM purchase_payment WHERE purchase_id='$purchase_id'";
                            $resamount=mysqli_query($conn,$sqlamount);
                            if(mysqli_num_rows($resamount)>0){
                                $arrayamount=mysqli_fetch_array($resamount);
                                $totalAmount=$arrayamount['pay_made'];
                            }
                            $grand_total= $row['grand_total'];
                            $balance_amount= $grand_total - $totalAmount;
                            $grand_total_formatted = number_format($grand_total, 2, '.', '');
                            $balance_amount_formatted = number_format($balance_amount, 2, '.', '');
                            if($balance_amount == 0){
                                $due_status ='Paid';
                                $dueColors = 'success';
                            }
                            elseif ($balance_amount > 0 && $balance_amount != $grand_total){
                                $due_status ='Partially Paid';
                                $dueColors = 'info';
                            }
                            elseif ($balance_amount == $grand_total){
                                $due_status ='UnPaid';
                                $dueColors = 'danger';
                            }
                            $sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                            $resSupplier = mysqli_query($conn, $sqlSupplier);
                            $rowSupplier = mysqli_fetch_assoc($resSupplier);
                            $Supplier =  $rowSupplier['supplier_name'];

                            $sqljsonterm = "SELECT * FROM `purchase_term` WHERE `purchase_id`='$purchase_id'";
                            $resjsonterm = mysqli_query($conn, $sqljsonterm);
                            $rowjsonterm = mysqli_fetch_assoc($resjsonterm);

                            $jsonterm =  base64_encode($rowjsonterm['jsonterm']);

                            $sqlStatus = "SELECT * FROM `status` WHERE `purchase_id`='$purchase_id'";
                            $resStatus = mysqli_query($conn, $sqlStatus);
                            $rowStatus = mysqli_fetch_assoc($resStatus);
                            $Status =  $rowStatus['status_type'];

                            if($Status == 1){
                                $pay_status ='Received';
                                $statColors = 'success';
                            }
                            elseif ($Status == 2){
                                $pay_status ='partially Received';
                                $statColors = 'info';
                            }
                            elseif ($Status == 3){
                                $pay_status ='partially Pending';
                                $statColors = 'danger';
                            }
                            elseif ($Status == 4){
                                $pay_status ='Pending';
                                $statColors = 'danger';
                            }
                            elseif ($Status == 0){
                                $pay_status ='Order';
                                $statColors = 'warning';
                            }

                            if($row['request'] == 0){
                                $received_status = 'No';
                            }
                            else{
                                $received_status = 'Yes';
                            }
                            $date = $row['purchase_date'];
                            $purchase_date = date('d-m-Y', strtotime($date));
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $purchase_date?></td>
                                <td> <?php echo $purchase_id?> </td>
                                <td> <?php echo $Supplier?> </td>
                                <td> <?php echo $grand_total_formatted?> </td>
                                <td> <?php echo $balance_amount_formatted?> </td>
                                <td> <span class="badge badge-pill badge-<?php echo $dueColors?>"><?php echo $due_status?></span></td>
                                <td> <span class="badge badge-pill badge-<?php echo $statColors?>"><?php echo $pay_status?></span></td>
                                <td> <?php echo $received_status?> </td>
                                <td> <a href="invoice.php?purchase_id=<?php echo $purchase_id ?>"><span class="badge badge-pill badge-primary">Download</span></a></td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="<?php echo $website?>/purchase/show_file.php?purchase_id=<?php echo $row['purchase_id']?>"
                                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-eye"></i></a>
                                    </div>
                                </td>
                                <!--                                <td><a href="invoice.php?purchase_id:--><?php //echo $row['purchase_id'] ?><!--.pdf" target="_blank" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Download</a></td>-->
                                <!--                                <td><a href="invoice.php?purchase_id=--><?php //echo $row['purchase_id'] ?><!--" target="_blank" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Download</a></td>-->
                                <?php
                                if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                                    ?>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                                <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php
                                                if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin') {
                                                    ?>
                                                    <a class="dropdown-item" href="<?php echo $website; ?>/purchase/edit.php?purchase_id=<?php echo $purchase_id?>" style="cursor: pointer" >Edit</a>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#term_list" style="cursor: pointer" onclick="term('<?php echo $purchase_id?>','<?php echo $Supplier ?>','<?php echo $jsonterm?>')">Term&Condition</a>
                                                    <?php
                                                }
                                                ?>
                                                <!--                                            <a class="dropdown-item" href="<?php echo $website; ?>/email/index.php?purchase_id=--><?php //echo $purchase_id?><!--" style="cursor: pointer" >email</a>-->
                                                <?php
                                                if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                                                    ?>
                                                    <?php
                                                    if($row['request'] == 1){
                                                        ?>
                                                        <a class="dropdown-item"
                                                           href="<?php echo $website; ?>/purchase/return.php?purchase_id=<?php echo $purchase_id ?>"
                                                           style="cursor: pointer">Return</a>

                                                        <a class="dropdown-item" data-toggle="modal" data-target="#career_list"
                                                           style="cursor: pointer"
                                                           onclick="status_fun('<?php echo $purchase_id; ?>')">Status</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin') {
                                                    ?>
                                                    <?php
                                                    if($row['request'] == 1){
                                                        ?>
                                                        <a class="dropdown-item" data-toggle="modal"
                                                           data-target="#purchase_list" style="cursor: pointer"
                                                           onclick="shipping('<?php echo $purchase_id; ?>')">Shipment</a>
                                                        <?php
                                                        if($balance_amount == 0){
                                                            ?>
                                                            <a class="dropdown-item disabled" style="cursor: not-allowed; pointer-events: none;">Create Payment</a>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#repayment_list" style="cursor: pointer" onclick="repayment('<?php echo $purchase_id?>','<?php echo $row['grand_total']?>','<?php echo $totalAmount?>','<?php echo $balance_amount?>')">Create Payment</a>
                                                            <?php
                                                        }
                                                        ?>

                                                        <a class="dropdown-item" data-toggle="modal" data-target="#email_list" style="cursor: pointer" onclick="email('<?php echo $purchase_id?>')">Send Email</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                if($_COOKIE['role'] == 'Super Admin') {
                                                    ?>
                                                    <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $purchase_id;?>')">Delete</a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php
                                }
                                ?>
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
                                                                                href="?page_no=<?php echo 1 ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-angles-left"></i></a></li>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }
                                    //                                    $sql = "SELECT COUNT(id) as count FROM purchase";

                                    if($pur_id == "" && $f_date =="" && $t_date =="") {
//                                $sql = "SELECT * FROM purchase  ORDER BY id  LIMIT $start,10";
                                        $sql = "SELECT COUNT(id) as count FROM purchase WHERE id>0$addedBy";

                                    }
                                    else {
                                        $sql = "SELECT COUNT(id) as count FROM purchase WHERE id > 0 AND purchase_date  BETWEEN '$from_date' AND '$to_date' $pur_idSql$addedBy";
                                    }
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
                                                                                               href="?page_no=<?php echo $i ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>"><?php echo $i ?></a>
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
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>"><i class="fa-solid fa-greater-than"></i></a></li>
                                            <?php
                                        }
                                        if($nextPage<$pageFooter)
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $pageFooter ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>"><i class="fa-solid fa-angles-right"></i></a></li>
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

        <div class="modal fade" id="purchase_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titles">Unit</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="purchase_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label>Date *</label>
                                        <input type="date" class="form-control" placeholder="Date" id="date" name="date" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control" placeholder="purchase Id" id="p_id" name="p_id" value="<?php echo $purchase_id?>">                                  <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="shipping_id" name="shipping_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Terms of Delivery*</label>
                                        <select data-search="true" class="form-control tail-select w-full" id="payment_terms" name="payment_terms" style="border-color: #181f5a;color: black">
                                            <option value=''>Select Day</option>
                                            <option value='0'>Immidiate</option>
                                            <option value='15'>15 Days</option>
                                            <option value='30'>30 Days</option>
                                            <option value='45'>45 Days</option>
                                            <option value='60'>60 Days</option>
                                            <option value='75'>75 Days</option>
                                            <option value='90'>90 Days</option>
                                            <option value='105'>105 Days</option>
                                            <option value='120'>120 Days</option>

                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Expected Delivery Date *</label>
                                        <input type="date" class="form-control"  id="d_date" name="d_date" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Dispatched through *</label>
                                        <input type="text" class="form-control" placeholder="Dispatched through" id="d_through" name="d_through" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Destination *</label>
                                        <input type="text" class="form-control" placeholder="Destination" id="destination" name="destination" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Motor Vehicle No *</label>
                                        <input type="text" class="form-control" placeholder="Motor Vehicle No" id="vehicle_no" name="vehicle_no" style="border-color: #181f5a;color: black">
                                    </div>
                                    <!--                                    <div class="form-group col-md-6">-->
                                    <!--                                        <label>Terms of Delivery </label>-->
                                    <!--                                        <input type="text" class="form-control" placeholder="Terms of Delivery" id="t_delivery" name="t_delivery" style="border-color: #181f5a;color: black">-->
                                    <!--                                    </div>-->
                                    <div class="form-group col-md-6">
                                        <label>Shipping Amount *</label>
                                        <input type="number" class="form-control" placeholder="Shipping Amount" id="shipping_amount" name="shipping_amount" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Other Charges</label>
                                        <input type="number" class="form-control" placeholder="Other Charges" id="other_charges" name="other_charges" style="border-color: #181f5a;color: black">
                                    </div>

                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;">Close</button>
                        <button type="button" class="btn btn-primary" id="addbtn">ADD</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="email_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Email</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="email_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-12" id="payment_date">
                                        <label>Purchase Id</label>
                                        <input type="text" class="form-control" id="pr_id" name="pr_id" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12" id="payment_date">
                                        <label>Email *(comma separated)</label>
                                        <input type="text" class="form-control" placeholder="eg: abc@gmail.com,def@gmail.com" id="email" name="email" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="apii" name="apii">
                                        <input type="hidden" class="form-control"  id="email_id" name="email_id">
                                    </div>


                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="email_btn">ADD</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="term_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="term_title">Term And Condition</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="term_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Purchase id</label>
                                        <input type="text" class="form-control" id="term_purchase_id" name="term_purchase_id" readonly style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="term_id" name="term_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Supplier Name</label>
                                        <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Supplier Name" readonly style="border-color: #181f5a;color: black">
                                    </div>
                                    <!--                                    <div class="form-group col-md-12">-->
                                    <!--                                        <label>Notes</label>-->
                                    <!--                                        <textarea class=" summernote form-control" id="term_condition" name="term_condition"></textarea>-->
                                    <!--                                    </div>-->
                                    <div class="form-group col-md-8">
                                        <label>Notes</label>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <span class="" id="add_btn" onclick="addterms()">ADD</span>
<!--                                        <span class="" id="add_btn" onclick="addterms()" style="background-color: green; color: white; cursor: pointer;">ADD</span>-->
                                    </div>

                                    <!--                                    <div class="form-group col-md-8">-->
<!--                                        <button class="btn btn-danger" id="remove_btn" onclick="removeterms()">Remove</button>-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-12">-->
<!--                                        <textarea class="form-control termtextarea" data-id="1" id="term_condition1" name="term_condition[]" style="border-color: #181f5a;color: black"></textarea>-->
<!--                                    </div>-->

                                </div>
                                <div class="form-row termsappend">
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="term_btn">ADD</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="repayment_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Repayment</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="repayment_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-6" id="payment_date">
                                        <label>Payment Date *</label>
                                        <input type="date" class="form-control" id="repayment_date" name="repayment_date" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="apii" name="apii">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="repayment_id" name="repayment_id">
                                        <input type="hidden" class="form-control"  id="pur_id" name="pur_id">
                                    </div>
                                    <div class="form-group col-md-6" id="re">
                                        <label>Purchase Id</label>
                                        <input type="text" class="form-control"  id="Purch_id" name="Purch_id" readonly style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="ref_no">
                                        <label>Grand Total</label>
                                        <input type="text" class="form-control"  id="g_total" name="g_total" readonly style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="ref_n">
                                        <label>Due Amount</label>
                                        <input type="text" class="form-control"  id="due_amount" name="due_amount" readonly style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="ref_">
                                        <label>Paid Amount</label>
                                        <input type="text" class="form-control"  id="paid_amount" name="paid_amount" readonly style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="pay_mades">
                                        <label>Payment Made *</label>
                                        <input type="number" class="form-control" placeholder="Payment Made" id="pay_made" name="pay_made" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="repayment_modes">
                                        <label>Payment Mode </label>
                                        <select  class="form-control" id="repayment_mode" name="repayment_mode" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <option value=''>Select Pay Mode</option>
                                            <option value='Cheque'>Cheque</option>
                                            <option value='Cash'>Cash</option>
                                            <option value='NEFT'>NEFT</option>
                                            <option value='RTGS'>RTGS</option>
                                            <option value='UPI'>UPI</option>
                                        </select>
                                    </div>
<!--                                    <div class="form-group col-md-6" id="bank_names">-->
<!--                                        <label>Bank Name</label>-->
<!--                                        <select onchange="getchequename(this.value)"  class="form-control" id="bank_name" name="bank_name" style="border-color: #181f5a;color: black;text-transform: uppercase;">-->
<!--                                            <option value="">Select Bank Name </option>-->
<!--                                            --><?php
//                                            $sqlCheque = "SELECT * FROM `cheque`";
//                                            $resultCheque = mysqli_query($conn, $sqlCheque);
//                                            if (mysqli_num_rows($resultCheque) > 0) {
//                                                while ($rowCheque = mysqli_fetch_array($resultCheque)) {
//                                                    ?>
<!--                                                    <option-->
<!--                                                            value='--><?php //echo $rowCheque['bank_name']; ?><!--'>--><?php //echo strtoupper($rowCheque['bank_name']); ?><!--</option>-->
<!--                                                    --><?php
//                                                }
//                                            }
//                                            ?>
<!--                                        </select>-->
<!--                                    </div>-->
                                    <div class="form-group col-md-6" id="bank_names">
                                        <label>Bank Name *</label>
                                        <select onchange="getchequename(this.value)"  class="form-control" id="bank_name" name="bank_name" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <option value="">Select Bank Name </option>
                                            <?php
                                            $sqlCheque = "SELECT * FROM `company_profile`";
                                            $resCheque = mysqli_query($conn, $sqlCheque);
                                            $rowCheque = mysqli_fetch_assoc($resCheque);
                                            $bank_name =  $rowCheque['bank_name'];
                                            $bank_name2 =  $rowCheque['bank_name2'];
                                            ?>
                                            <option value='<?php echo $bank_name; ?>'><?php echo strtoupper($bank_name); ?></option>
                                            <option value='<?php echo $bank_name2; ?>'><?php echo strtoupper($bank_name2); ?></option>
                                            <?php

                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="ref_nos_c">
                                        <label>Cheque Reference No</label>
                                        <select  class="form-control" id="ref_no_c" name="ref_no_c" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <option value="">Select Cheque No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="ref_nos">
                                        <label>Reference No</label>
                                        <input type="text" class="form-control" placeholder="Reference No" id="ref_no" name="ref_no" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="Notess">
                                        <label>Notes</label>
                                        <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" style="border-color: #181f5a;color: black">
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="repay_btn">ADD</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Status-->
<!--        <div class="modal fade" id="status_list"  data-keyboard="false" data-backdrop="static">-->
<!--            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">-->
<!--                <div class="modal-content">-->
<!--                    <div class="modal-header">-->
<!--                        <h5 class="modal-title" id="titless">Status</h5>-->
<!--                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>-->
<!--                        </button>-->
<!--                    </div>-->
<!--                    <div class="modal-body">-->
<!---->
<!--                        <div class="basic-form" style="color: black;">-->
<!--                            <form id="status_form" autocomplete="off">-->
<!--                                <div class="form-row">-->
<!--                                    <div class="form-group col-md-4" id="pay_mades">-->
<!--                                        <label>Bill No *</label>-->
<!--                                        <input type="text" class="form-control" id="bl_no" name="bl_no"  placeholder="Bill No" style="border-color: #181f5a;color: black">-->
<!--                                        <input type="hidden" class="form-control prs_id"  id="prss_id" name="prss_id"  value="--><?php //echo $pusrchase_sts ?><!--">-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-4">-->
<!--                                        <label>Status *</label>-->
<!--                                        <select data-search="true" class="form-control tail-select w-full" id="pro_sts" name="pro_sts" style="border-color: #181f5a;color: black">-->
<!--                                            <option value=''>Select Status</option>-->
<!--                                            <option value='1'>Received</option>-->
<!--                                            <option value='2'>Partially Received</option>-->
<!--                                            <option value='3'>Partially Pending</option>-->
<!--                                            <option value='4'>Pending</option>-->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-4" id="pay_mades">-->
<!--                                        <label>Date *</label>-->
<!--                                        <input type="date" class="form-control" id="sts_date" name="sts_date"  style="border-color: #181f5a;color: black">-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                --><?php
//                                $pusrchase_sts = $_GET['purchase_sts'];
//
//                                $sql = "SELECT * FROM purchase_details WHERE purchase_id = '$pusrchase_sts'";
//                                $result = mysqli_query($conn, $sql);
//                                if (mysqli_num_rows($result)>0) {
//                                    $sNo = 1;
//                                    while($row = mysqli_fetch_assoc($result)) {
//                                        $sNo++;
//                                        $product_id = $row['product_id'];
//                                        $product_qty = $row['qty'];
//                                        $sqlproduct_id = "SELECT * FROM `product` WHERE `product_id`='$product_id'";
//                                        $resproduct_id = mysqli_query($conn, $sqlproduct_id);
//                                        $rowproduct_id = mysqli_fetch_assoc($resproduct_id);
//                                        $product_name = $rowproduct_id['product_name'];
//                                        $product_ids = $rowproduct_id['product_id'];
//
//                                        $sqlSubCategory = "SELECT * FROM `purchase` WHERE `purchase_id`='$pusrchase_sts'";
//                                        $resSubCategory = mysqli_query($conn, $sqlSubCategory);
//                                        $rowSubCategory = mysqli_fetch_assoc($resSubCategory);
//                                        $product_material = $rowSubCategory['material'];
//                                        ?>
<!--                                        <div class="form-row formrow">-->
<!---->
<!--                                            <div class="form-group col-md-3">-->
<!--                                                <label>Product Name </label>-->
<!--                                                <input type="text" class="form-control p_name" placeholder="Product Name" id="pro_name" name="pro_name" readonly value="--><?php //echo $product_name ?><!--" style="border-color: #181f5a;color: black">-->
<!--                                                <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">-->
<!--                                                <input type="hidden" class="form-control pro_id"  id="pro_id" name="pro_id"  value="--><?php //echo $product_ids ?><!--">-->
<!--                                                <input type="hidden" class="form-control pro_id"  id="prs_id" name="prs_id"  value="--><?php //echo $purchase_id ?><!--">-->
<!--                                            </div>-->
<!--                                            <div class="form-group col-md-2">-->
<!--                                                <label>Quantity </label>-->
<!--                                                <input type="number" class="form-control p_qty" placeholder="Qty" id="pro_qty" name="pro_qty" readonly  value="--><?php //echo $product_qty ?><!--" style="border-color: #181f5a;color: black">-->
<!--                                            </div>-->
<!--                                            <div class="form-group col-md-2">-->
<!--                                                <label>Received</label>-->
<!--                                                <input type="number" class="form-control p_mat" placeholder="Received" id="pro_mat" name="pro_mat"  style="border-color: #181f5a;color: black">-->
<!--                                            </div>-->
<!--                                            <div class="form-group col-md-2">-->
<!--                                                <label>Remaining</label>-->
<!--                                                <input type="number" class="form-control rem" placeholder="Remaining" id="remaining" name="remaining" style="border-color: #181f5a;color: black">-->
<!--                                            </div>-->
<!--                                            <div class="form-group col-md-3">-->
<!--                                                <label>Batch No</label>-->
<!--                                                <input type="text" class="form-control bat" placeholder="batch" id="batch" name="batch" style="border-color: #181f5a;color: black">-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    --><?php //} }
//                                ?>
<!--                            </form>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
<!--                    <div class="modal-footer">-->
<!--                        <button type="button" class="btn btn-danger light" data-dismiss="modal" id="closeButton" style="background-color: red; color: white;text-transform: uppercase">Close</button>-->
<!--                        <button type="button" class="btn btn-primary"  id="statusbtn">ADD</button>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <!-- Status Modal -->
        <div class="modal fade" id="status_list" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titless">Status</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="status_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-4" id="pay_mades">
                                        <label>Bill No *</label>
                                        <input type="text" class="form-control" id="bl_no" name="bl_no" placeholder="Bill No" style="border-color: #181f5a; color: black">
                                        <input type="hidden" class="form-control prs_id" id="prss_id" name="prss_id" value="<?php echo $purchase_id; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Status *</label>
                                        <select data-search="true" class="form-control tail-select w-full" id="pro_sts" name="pro_sts" style="border-color: #181f5a; color: black">
                                            <option value=''>Select Status</option>
                                            <option value='1'>Received</option>
                                            <option value='2'>Partially Received</option>
                                            <option value='3'>Partially Pending</option>
                                            <option value='4'>Pending</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4" id="pay_mades">
                                        <label>Date *</label>
                                        <input type="date" class="form-control" id="sts_date" name="sts_date" style="border-color: #181f5a; color: black">
                                    </div>
                                </div>
                                <div id="dynamic_fields"></div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" id="closeButton" style="background-color: red; color: white; text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="statusbtn">ADD</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php Include ('../includes/footer.php') ?>
    <div class="modal fade" id="invoice_filter"  data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title" id="title">pay Details</h5> -->

                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form" style="color: black;">
                        <form id="filter_form" autocomplete="off">
                            <div class="form-row">


                                <div class="form-group col-md-12">
                                    <label>Purchase Id </label>
                                    <input type="text"  class="form-control" placeholder="Purchase Id" id="purs_id" name="purs_id" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>From Date </label>
                                    <input type="date"  class="form-control" id="f_date" name="f_date" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>To Date </label>
                                    <input type="date"  class="form-control" id="t_date" name="t_date" style="border-color: #181f5a;color: black">
                                </div>


                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </div>
                        </form>
                    </div>

                </div>
                <!--                <div class="modal-footer">-->
                <!--                    <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;">Close</button>-->
                <!--                    <button type="submit" class="btn btn-primary" id="filter">Filter</button>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
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
    // var received_status = $('#pro_sts').val();
    $(document).ready(function(){
        // Disable dropdown if 'Received' is selected
        $('#pro_sts').change(function(){
            if($(this).val() === '1'){
                $(this).attr('disabled', true);
            }
        });

        // Handle keyup event on Received Material input
        $(document).on('keyup', ".p_mat", function() {
            var $container = $(this).closest('.form-row');
            const inputQtyValue = parseFloat($container.find(".p_qty").val());
            const materialReceived = parseFloat($(this).val());
            var remaining = inputQtyValue - materialReceived;
            if (remaining < 0) {
                remaining = 0;
            }
            $container.find('.rem').val(remaining);
        });

        // Handle change event on Status dropdown
        $("#pro_sts").change(function() {
            if ($(this).val() === '1') {
                $(".p_mat").each(function() {
                    var $container = $(this).closest('.form-row');
                    const inputQtyValue = parseFloat($container.find(".p_qty").val());
                    $(this).val(inputQtyValue);
                    $container.find('.rem').val(0);
                });
            }
        });
    });

    // $(document).ready(function(){
    //     $('#pro_sts').change(function(){
    //         var received_status = $('#pro_sts').val();
    //         if($(this).val() === '1'){ // If value is '1' (Received)
    //             $(this).attr('disabled', true); // Disable the dropdown
    //         }
    //     });
    // });



    //due date
    $(document).ready(function() {
        $("#payment_terms").change(function() {
            const inputDateValue = document.getElementById("date").value;
            const payment_days = parseInt(document.getElementById("payment_terms").value);
            if (!isNaN(payment_days)) {
                const d = new Date(inputDateValue);
                d.setDate(d.getDate() + payment_days);
                const formattedDate = d.toISOString().split('T')[0];
                $('#d_date').val(formattedDate);
            }
        });
    });

    // $(document).ready(function() {
    //     // Function to handle keyup event on Received Material input
    //     $(".p_mat").keyup(function() {
    //         var $container = $(this).closest('.form-row');
    //         const inputQtyValue = parseFloat($container.find(".p_qty").val()); // Get the quantity value
    //         const materialReceived = parseFloat($(this).val()); // Get the material received value
    //         var remaining = inputQtyValue - materialReceived;
    //         if (remaining < 0) {
    //             remaining = 0; // If material received is more than quantity, set remaining to 0
    //         }
    //         $container.find('.rem').val(remaining); // Set the remaining value
    //     });
    //
    //     // Function to handle change event on Status dropdown
    //     $("#pro_sts").change(function() {
    //         if ($(this).val() === '1') { // If status is "Received"
    //             $(".p_mat").each(function() {
    //                 var $container = $(this).closest('.form-row');
    //                 const inputQtyValue = parseFloat($container.find(".p_qty").val()); // Get the quantity value
    //                 $container.find('.p_mat').val(inputQtyValue); // Set received material value to quantity value
    //                 $container.find('.rem').val(0); // Set remaining to 0
    //             });
    //         }
    //     });
    // });
    //close status
    document.getElementById("closeButton").addEventListener("click", function() {
        window.location.href = "../purchase/all_purchase.php";
    });
    //Status
    // function status_fun(data) {
    //     window.location.href="../purchase/all_purchase.php?purchase_sts="+data;
    // }
    //status close

    //var sts = '<?php //echo  $_GET['purchase_sts']?>//';
    //if(sts != ''){
    //    var edit_model_title = "Add Status - "+sts;
    //    $('#titless').html(edit_model_title);
    //    $('#statusbtn').html("Save");
    //    $('#p_id').val(sts);
    //    $('#status_list').modal('show');
    //}
    function status_fun(purchase_id) {
        $("#titless").html("Add Status - " + purchase_id);
        $('#status_form')[0].reset();
        $('#dynamic_fields').empty();

        $.ajax({
            type: "POST",
            url: "status_value_api.php",
            data: {status_id: purchase_id},
            dataType: "json",
            success: function(res) {
                if(res.status == 'success') {
                    var dynamicFields = '';
                    res.data.forEach(function(item) {
                        dynamicFields += `
                        <div class="form-row formrow">
                            <div class="form-group col-md-3">
                                <label>Product Name</label>
                                <input type="text" class="form-control p_name" placeholder="Product Name" readonly value="${item.product_name}" style="border-color: #181f5a; color: black">
                                <input type="hidden" class="form-control pro_id" name="pro_id[]" value="${item.product_id}">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Quantity</label>
                                <input type="number" class="form-control p_qty" placeholder="Qty" readonly value="${item.qty}" style="border-color: #181f5a; color: black">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Received</label>
                                <input type="number" class="form-control p_mat" name="received[]" placeholder="Received" style="border-color: #181f5a; color: black">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Remaining</label>
                                <input type="number" class="form-control rem" name="remaining[]" placeholder="Remaining" style="border-color: #181f5a; color: black">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Batch No</label>
                                <input type="text" class="form-control bat" name="batch[]" placeholder="Batch" style="border-color: #181f5a; color: black">
                            </div>
                        </div>`;
                    });
                    $('#dynamic_fields').html(dynamicFields);
                    $('#status_list').modal('show');
                } else {
                    alert(res.msg);
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            }
        });
    }

    //status ajax
    $('#statusbtn').click(function () {
        $("#status_form").valid();
        if($("#status_form").valid()==true) {
            // var api = $('#api').val();
            var status = $("#pro_sts").val();
            var date = $("#sts_date").val();
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            var received_status = $('#pro_sts').val();
            var formDataArray = [];

            // Loop through each form row
            $(".formrow").each(function() {
                var $container = $(this);
                // Get values from form fields
                var productId = $container.find(".pro_id").val();
                var productName = $container.find(".p_name").val();
                var quantity = $container.find(".p_qty").val();
                var receivedMaterial = $container.find(".p_mat").val();
                var remaining = $container.find(".rem").val();
                var batch = $container.find(".bat").val();

                // Create an object to store form data
                var formData = {
                    productId: productId,
                    productName: productName,
                    quantity: quantity,
                    receivedMaterial: receivedMaterial,
                    remaining: remaining,
                    batch: batch,
                };

                // Push the object into the formDataArray
                formDataArray.push(formData);
            });


            $.ajax({

                type: "POST",
                url: "status_api.php",
                // data: $('#status_form').serialize() + "&tableData=" + JSON.stringify(formDataArray) + "selectedValue" + selectedValue,
                data: $('#status_form').serialize() + "&tableData=" + JSON.stringify(formDataArray) + "&received_status=" + received_status,
                // data: {
                //     pro_sts: status,
                //     sts_date: date,
                //     tableData: JSON.stringify(formDataArray)
                // },
                dataType: "json",
                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire(
                            {
                                title: "Success",
                                text: res.msg,
                                icon: "success",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {
                                window.location.href="../purchase/all_purchase.php";
                            });
                    } else if (res.status == 'failure') {

                        Swal.fire(
                            {
                                title: "Failure",
                                text: res.msg,
                                icon: "warning",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {

                                document.getElementById("statusbtn").disabled = false;
                                document.getElementById("statusbtn").innerHTML = 'Add';
                            });
                    }
                },
                error: function (error) {
                    console.error("Error sending data:", error);
                }
            });

        } else {
            document.getElementById("statusbtn").disabled = false;
            document.getElementById("statusbtn").innerHTML = 'Add';
        }

    });
    //to validate status form
    $("#status_form").validate({
        ignore: '.ignore',
        rules: {
            pro_sts: { required: true },
            sts_date: { required: true },
            bl_no: { required: true }
        },
        messages: {
            pro_sts: "*This field is required",
            sts_date: "*This field is required",
            bl_no: "*This field is required",
        }
    });


    //Edit shipmentt
    function shipping(data) {
        $("#titles").html("Add Shipment- "+data);
        $('#purchase_form')[0].reset();
        // $('#api').val("edit_api.php");
        $('#api').val("shipping.php");
        var edit_model_title = "Add Shipment - "+data;
        $('#titles').html(edit_model_title);
        $('#addbtn').html("Save");
        $('#p_id').val(data);
        $('#purchase_list').modal('show');

    }

    //to validate form
    $("#purchase_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {

                date: {
                    required: true
                },
                payment_terms: {
                    required: true
                },
                d_note: {
                    required: true
                },
                d_date: {
                    required: true

                },
                d_through: {
                    required: true
                },
                destination: {
                    required: true,
                },
                vehicle_no: {
                    required: true,
                },
                shipping_amount: {
                    required: true
                },



            },
            // Specify validation error messages
            messages: {
                date: "*This field is required",
                payment_terms: "*This field is required",
                d_note: "*This field is required",
                d_date: "*This field is required",
                d_through: "*This field is required",
                destination: "*This field is required",
                bl_no: "*This field is required",
                vehicle_no:  "*This field is required",
                shipping_amount: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#addbtn').click(function () {

        $("#purchase_form").valid();

        if($("#purchase_form").valid()==true) {


            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "shipment_api.php",
                data: $('#purchase_form').serialize() ,
                dataType: "json",
                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire(
                            {
                                title: "Success",
                                text: res.msg,
                                icon: "success",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {
                                window.location.href = "https://erp.aecindia.net/purchase/all_purchase.php";
                            });
                    } else if (res.status == 'failure') {

                        Swal.fire(
                            {
                                title: "Failure",
                                text: res.msg,
                                icon: "warning",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {

                                document.getElementById("addbtn").disabled = false;
                                document.getElementById("addbtn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("addbtn").disabled = false;
                    document.getElementById("addbtn").innerHTML = 'Add';
                }

            });



        } else {
            document.getElementById("addbtn").disabled = false;
            document.getElementById("addbtn").innerHTML = 'Add';

        }


    });
    //Email function
    function email(data) {
        $("#title").html("Add Email -"+ data);
        $('#email_form')[0].reset();
        // $('#apii').val("email_api.php")
        $('#pr_id').val(data)

        // $('#game_id').prop('readonly', false);
    }
    //add data
    $('#email_btn').click(function () {
        $("#email_form").valid();
        if($("#email_form").valid()==true) {
            var api = $('#apii').val();
            //var loan_id = "<?php //echo $loan_id?>//";
            // var loan_id = 56
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "<?php echo $website; ?>/email/index.php",
                data: $('#email_form').serialize(),
                // data: $('#repayment_form').serialize()+ '&' +$.param({loan_id:loan_id}),
                dataType: "json",
                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire(
                            {
                                title: "Success",
                                text: res.msg,
                                icon: "success",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {
                                window.window.location.reload();
                            });
                    } else if (res.status == 'failure') {

                        Swal.fire(
                            {
                                title: "Failure",
                                text: res.msg,
                                icon: "warning",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {

                                document.getElementById("repay_btn").disabled = false;
                                document.getElementById("repay_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("repay_btn").disabled = false;
                    document.getElementById("repay_btn").innerHTML = 'Add';
                }

            });

        } else {
            document.getElementById("repay_btn").disabled = false;
            document.getElementById("repay_btn").innerHTML = 'Add';

        }

    });


    //to validate form
    $("#email_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                // email: {
                //     required: true
                // },

            },
            // Specify validation error messages
            messages: {
                email: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });



    document.addEventListener('DOMContentLoaded', function () {
        const repayment_mode = document.getElementById('repayment_mode');
        const payment_date = document.getElementById('payment_date');
        const pay_mades = document.getElementById('pay_mades');
        const repayment_modes = document.getElementById('repayment_modes');
        const ref_nos_c = document.getElementById('ref_nos_c');
        const ref_nos = document.getElementById('ref_nos');
        const Notess = document.getElementById('Notess');
        const re = document.getElementById('re');
        const ref_no = document.getElementById('ref_no');
        const ref_n = document.getElementById('ref_n');
        const ref_ = document.getElementById('ref_');
        const bank_names = document.getElementById('bank_names');

        // Add an event listener to the dropdown
        repayment_mode.addEventListener('change', function () {

            a(repayment_mode.value);

        });

        function a(values) {
            if (values === 'Cheque') {
                // Hide the input field when 'Hide Input Field' is selected
                ref_nos.style.display = 'none';
                payment_date.style.display = 'block';
                pay_mades.style.display = 'block';
                repayment_modes.style.display = 'block';
                Notess.style.display = 'block';
                ref_nos_c.style.display = 'block';
                re.style.display = 'block';
                ref_no.style.display = 'block';
                ref_n.style.display = 'block';
                ref_.style.display = 'block';
                bank_names.style.display = 'block';
            }
            else {
                // Show the input field for other selections
                ref_nos_c.style.display = 'none';
                payment_date.style.display = 'block';
                pay_mades.style.display = 'block';
                repayment_modes.style.display = 'block';
                Notess.style.display = 'block';
                ref_nos.style.display = 'block';
                re.style.display = 'block';
                ref_no.style.display = 'block';
                ref_n.style.display = 'block';
                ref_.style.display = 'block';
                bank_names.style.display = 'block';
            }
        }
    });
    // function repayment(data,gtotal,pamount,damount) {
    //     $("#title").html("Add Payment "+ data);
    //     $('#repayment_form')[0].reset();
    //     $('#apii').val("repayment_api.php")
    //     $('#pur_id').val(data)
    //     $('#g_total').val(gtotal)
    //     $('#paid_amount').val(pamount)
    //     $('#due_amount').val(damount)
    //     $('#Purch_id').val(data)
    //     // $('#game_id').prop('readonly', false);
    // }
    function repayment(data, gtotal, pamount, damount) {
        $("#title").html("Add Payment " + data);
        $('#repayment_form')[0].reset();
        $('#apii').val("repayment_api.php")
        $('#pur_id').val(data)
        $('#g_total').val(gtotal)
        $('#paid_amount').val(pamount)
        $('#due_amount').val(damount)
        $('#Purch_id').val(data)

        // Check if g_total is equal to or greater than paid_amount
        if (parseFloat(gtotal) <= parseFloat(pamount)) {
            $('#pay_made').prop('readonly', true).attr('max', gtotal - pamount);  // Set pay_made field to readonly and set max attribute to the allowed maximum value
        } else {
            $('#pay_made').prop('readonly', false).removeAttr('max');  // Remove readonly and max attribute if it was set
        }

        // Event listener for pay_made input
        $('#pay_made').on('input', function() {
            let payMade = parseFloat($(this).val());
            let grandTotal = parseFloat($('#g_total').val());
            let due_amount = parseFloat($('#due_amount').val());

            if (payMade > due_amount) {
                alert("Payment made cannot be greater than the grand total.");
                $(this).val('');  // Set the input value to the grand total
            }
        });
    }




    //add data
    $('#repay_btn').click(function () {
        $("#repayment_form").valid();
        if($("#repayment_form").valid()==true) {
            var api = $('#apii').val();
            //var loan_id = "<?php //echo $loan_id?>//";
            // var loan_id = 56
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "repayment_api.php",
                data: $('#repayment_form').serialize(),
                // data: $('#repayment_form').serialize()+ '&' +$.param({loan_id:loan_id}),
                dataType: "json",
                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire(
                            {
                                title: "Success",
                                text: res.msg,
                                icon: "success",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {
                                window.window.location.reload();
                            });
                    } else if (res.status == 'failure') {

                        Swal.fire(
                            {
                                title: "Failure",
                                text: res.msg,
                                icon: "warning",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {

                                document.getElementById("repay_btn").disabled = false;
                                document.getElementById("repay_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("repay_btn").disabled = false;
                    document.getElementById("repay_btn").innerHTML = 'Add';
                }

            });

        } else {
            document.getElementById("repay_btn").disabled = false;
            document.getElementById("repay_btn").innerHTML = 'Add';

        }

    });


    //to validate form
    $("#repayment_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                repayment_date: {
                    required: true
                },
                pay_made: {
                    required: true
                },
                repayment_mode: {
                    required: true
                },
                bank_name: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                repayment_date: "*This field is required",
                pay_made: "*This field is required",
                repayment_mode: "*This field is required",
                ref_no: "*This field is required",
                bank_name: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    function term(term_purchase_id,supplier_name,terms) {
        $("#term_title").html("Add Term And Condition " + term_purchase_id);
        $('#term_form')[0].reset();
        // $('#apii').val("repayment_api.php")
        $('#term_purchase_id').val(term_purchase_id);
        $('#supplier_name').val(supplier_name);
        var jsonterm =  jQuery.parseJSON(window.atob(terms));
        // alert(jsonterm[0]['termtextarea']);
        for(i=0;i<jsonterm.length;i++){
            var n= i+1;
            if(i!=0){
                addterms();
            }
            $('#term_condition'+n).val(jsonterm[i]['termtextarea']);

        }
        // $('#term_condition').val(terms);

    }
    //add Term And Condition
    $('#term_btn').click(function () {
        $("#term_form").valid();
        if($("#term_form").valid()==true) {
            var termid= 1;
            var termjson = [];
            $( ".termtextarea" ).each(function() {
                var item={};
                item['dataid'] = termid;
                item['termtextarea'] = $(this).val();
                termjson.push(item);
                termid++;
            });
            var jsonterm=window.btoa(JSON.stringify(termjson));
            alert(jsonterm);
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "term_api.php",
                data: $('#term_form').serialize()+'&jsonterm='+jsonterm,
                // data: $('#term_form').serialize() + '&' + 'term_json =' + jsonterm,
                // $('#term_form').serialize()+'&'+jsonterm
                // data: $('#repayment_form').serialize()+ '&' +$.param({loan_id:loan_id}),
                dataType: "json",
                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire(
                            {
                                title: "Success",
                                text: res.msg,
                                icon: "success",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {
                                window.window.location.reload();
                            });
                    } else if (res.status == 'failure') {

                        Swal.fire(
                            {
                                title: "Failure",
                                text: res.msg,
                                icon: "warning",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {

                                document.getElementById("term_btn").disabled = false;
                                document.getElementById("term_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("term_btn").disabled = false;
                    document.getElementById("term_btn").innerHTML = 'Add';
                }

            });

        } else {
            document.getElementById("term_btn").disabled = false;
            document.getElementById("term_btn").innerHTML = 'Add';

        }

    });

    //to validate form
    $("#term_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                term_purchase_id: {
                    required: true
                },
                supplier_name: {
                    required: true
                },
                term_condition: {
                    required: true
                },



            },
            // Specify validation error messages
            messages: {
                term_purchase_id: "*This field is required",
                supplier_name: "*This field is required",
                term_condition: "*This field is required",

            }
            // Make sure the form is submitted to the destination defined
        });
    function delete_model(data) {

        Swal.fire({
            title: "Delete",
            text: "Are you sure want to delete the record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            closeOnClickOutside: false,
            showCancelButton: true,
            cancelButtonText: 'Cancel'
        })
            .then((value) => {
                if(value.isConfirmed) {

                    $.ajax({

                        type: "POST",
                        url: "delete_api.php",
                        data: 'purchase_id='+data,
                        dataType: "json",
                        success: function(res){
                            if(res.status=='success')
                            {
                                Swal.fire(
                                    {
                                        title: "Success",
                                        text: res.msg,
                                        icon: "success",
                                        button: "OK",
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        closeOnClickOutside: false,
                                    }
                                )
                                    .then((value) => {
                                        window.window.location.reload();

                                    });
                            }
                            else if(res.status=='failure')
                            {
                                swal(
                                    {
                                        title: "Failure",
                                        text: res.msg,
                                        icon: "warning",
                                        button: "OK",
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        closeOnClickOutside: false,
                                    }
                                )
                                    .then((value) => {
                                        window.window.location.reload();                             });

                            }
                        },
                        error: function(){
                            swal("Check your network connection");

                        }

                    });

                }

            });

    }

    $( document ).ready(function() {
        $('#search').val('<?php echo $search;?>');
        //$('#branch_nameS').val('<?php //echo $branch_nameS;?>//');

    });
    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>";
    });

    function pdf(purchase_id) {
        window.location.href= '<?php echo $website; ?>/purchase/invoice.php?purchase_id='+purchase_id;
    }

    function getchequename(bankname) {
// alert('hi');return false;
        if(bankname != ''){
            $.ajax({
                type: "POST",
                url: "getchequeno_api.php",
                data: 'bankname='+bankname,
                success: function(res){
                    $('#ref_no_c').html(res);
                    // alert(res);
                    // if(res.status=='success')
                    // {
                    //     var chequeno = res.cheque_no;


                    //     for(let i = 0;i<chequeno.length;i++){
                    //         var opt = document.createElement('option');
                    //         opt.value = chequeno[i];
                    //         opt.innerHTML = chequeno[i];

                    //         document.getElementById('expense_name').appendChild(opt);
                    //     }
                    //     if(category_sub != undefined){
                    //         $("#expense_name").val(expense_name);

                    //     }
                    //     $("#expense_name").trigger("change");
                    // }
                    // else if(res.status=='failure')
                    // {
                    //     Swal.fire("Invalid",  res.msg, "warning")
                    //     // .then((value) => {
                    //     //     window.window.location.reload();
                    //     // });
                    // }
                    // else if(res.status=='failure')
                    // {
                    //     Swal.fire("Failure",  res.msg, "error")
                    //     // .then((value) => {
                    //     //     window.window.location.reload();
                    //     //
                    //     // });
                    // }
                },
                error: function(){
                    Swal.fire("Check your network connection");
                    // window.window.location.reload();
                }
            });
        }
    }
    function addterms() {
        var count = $('.termtextarea').length + 1; // Get the total number of existing terms and increment
        var newFormGroup = $('<div class="form-group col-md-12"></div>');
        var newLabel = $('<label class="term-label" data-termid="' + count + '" for="term_condition' + count + '">Term & Condition ' + count + '</label>'); // Updated label with count and data-termid
        var newTextarea = $('<textarea class="form-control termtextarea" data-id="' + count + '" id="term_condition' + count + '" name="term_condition[]" style="border-color: #181f5a;color: black"></textarea>');
        var removeButton = $('<button type="button" class="btn btn-danger remove-btn">Remove</button>');

        newFormGroup.append(newLabel, newTextarea, removeButton);

        // $('#form-container').append(newFormGroup);
        $('.termsappend').append(newFormGroup);

        removeButton.click(function() {
            newFormGroup.remove();
            updateLabels(); // Call the function to update labels after removal
        });
    }

    // Function to update labels after removal
    function updateLabels() {
        $('.term-label').each(function(index) {
            var newId = index + 1;
            $(this).attr('data-termid', newId); // Update data-termid attribute
            $(this).text('Term & Condition ' + newId); // Update label text
        });
    }



</script>

</body>
</html>
