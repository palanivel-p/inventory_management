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
$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin'){
    $addedBy = "";
}
else{
    $addedBy = " AND added_by='$added_by'";
}


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
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
<!--                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>-->
<!--                                                <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
<!--                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>-->
<!--            </span>Excel</button>                    -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>Purchase Id</strong></th>
                                <th><strong>Supplier</strong></th>
                                <th><strong>Grand Total</strong></th>
                                <th><strong>Paid</strong></th>
                                <th><strong>Due</strong></th>
                                <th><strong>Payment Status</strong></th>
                                <th><strong>Invoice</strong></th>

                                <th><strong>ACTION</strong></th>

                            </tr>
                            </thead>
                            <?php
                            if($pur_id == "" ) {
//                                $sql = "SELECT * FROM purchase  ORDER BY id  LIMIT $start,10";
                                $sql = "SELECT * FROM purchase WHERE purchase_date  BETWEEN '$from_date' AND '$to_date'$addedBy ORDER BY id DESC LIMIT $start, 10";

                            }
                            else {
                                $sql = "SELECT * FROM purchase WHERE id > 0 WHERE purchase_date  BETWEEN '$from_date' AND '$to_date' $pur_idSql$addedBy ORDER BY id DESC LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $supplier_id=$row['supplier'];
                           $purchase_id =  $row['purchase_id'];

                            $sqlamount="SELECT SUM(pay_made) AS pay_made  FROM purchase_payment WHERE purchase_id='$purchase_id'";
                            $resamount=mysqli_query($conn,$sqlamount);
                            if(mysqli_num_rows($resamount)>0){
                                $arrayamount=mysqli_fetch_array($resamount);
                                $totalAmount=$arrayamount['pay_made'];
                            }
                            $grand_total= $row['grand_total'];
                            $balance_amount= $grand_total - $totalAmount;

                            $sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                            $resSupplier = mysqli_query($conn, $sqlSupplier);
                            $rowSupplier = mysqli_fetch_assoc($resSupplier);
                            $Supplier =  $rowSupplier['supplier_name'];

                            //$date = $row['dob'];
                            //$dates = date('d-F-Y', strtotime($date));

                            //   $career_dates =   $row['career_date'];
                            //   $career_date =   date('d-F-Y');

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
                            $date = $row['purchase_date'];
                            $purchase_date = date('d-m-Y', strtotime($date));
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $purchase_date?></td>
                                <td> <?php echo $purchase_id?> </td>
                                <td> <?php echo $Supplier?> </td>
                                <td> <?php echo $row['grand_total']?> </td>
                                <td> <?php echo $totalAmount?> </td>
                                <td> <?php echo $balance_amount?> </td>
                                <td> <span class="badge badge-pill badge-<?php echo $statColor?>"><?php echo $statCont?></span></td>
                                <td> <a href="invoice.php?purchase_id=<?php echo $purchase_id ?>"><span class="badge badge-pill badge-primary">Download</span></a></td>
<!--                                <td><a href="invoice.php?purchase_id:--><?php //echo $row['purchase_id'] ?><!--.pdf" target="_blank" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Download</a></td>-->
<!--                                <td><a href="invoice.php?purchase_id=--><?php //echo $row['purchase_id'] ?><!--" target="_blank" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Download</a></td>-->
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">

                                            <a class="dropdown-item" href="<?php echo $website; ?>/purchase/edit.php?purchase_id=<?php echo $purchase_id?>" style="cursor: pointer" >Edit</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#purchase_list" style="cursor: pointer" onclick="shipping('<?php echo $purchase_id;?>')">Shipment</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="status_fun('<?php echo $purchase_id;?>')">Status</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#repayment_list" style="cursor: pointer" onclick="repayment('<?php echo $purchase_id?>')">Payment</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#email_list" style="cursor: pointer" onclick="email('<?php echo $purchase_id?>')">Send Email</a>
                                            <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $purchase_id;?>')">Delete</a>

                                        </div>
                                    </div>
                                </td>
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
//                                    $sql = "SELECT COUNT(id) as count FROM purchase$addedBy";
                                    if($pur_id == "" ) {
//                                $sql = "SELECT * FROM purchase  ORDER BY id  LIMIT $start,10";
                                        $sql = "SELECT COUNT(id) as count FROM purchase WHERE purchase_date  BETWEEN '$from_date' AND '$to_date'$addedBy";

                                    }
                                    else {
                                        $sql = "SELECT COUNT(id) as count FROM purchase WHERE id > 0 WHERE purchase_date  BETWEEN '$from_date' AND '$to_date' $pur_idSql$addedBy";
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
                                        <label>Bill of Lading/LR-RR No </label>
                                        <input type="text" class="form-control" placeholder="Bill of Lading/LR-RR No" id="bl_no" name="bl_no" style="border-color: #181f5a;color: black">
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
                                    <div class="form-group col-md-6" id="ref_nos_c">
                                        <label>Cheque Reference No</label>
                                        <select  class="form-control" id="ref_no_c" name="ref_no_c" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <option value="">Select Cheque No</option>
                                                <?php
                                                $sqlCheque = "SELECT * FROM `cheque`";
                                                $resultCheque = mysqli_query($conn, $sqlCheque);
                                                if (mysqli_num_rows($resultCheque) > 0) {
                                                    while ($rowCheque = mysqli_fetch_array($resultCheque)) {
                                                        ?>
                                                        <option
                                                                value='<?php echo $rowCheque['cheque_no']; ?>'><?php echo strtoupper($rowCheque['cheque_no']); ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
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
        <div class="modal fade" id="status_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titless">Status</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="status_form" autocomplete="off">
                                <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Status *</label>
                                    <select data-search="true" class="form-control tail-select w-full" id="pro_sts" name="pro_sts" style="border-color: #181f5a;color: black">
                                        <option value=''>Select Status</option>
                                        <option value='1'>Received</option>
                                        <option value='2'>Partially Received</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="pay_mades">
                                    <label>Date *</label>
                                    <input type="date" class="form-control" id="sts_date" name="sts_date"  style="border-color: #181f5a;color: black">
                                </div>
                                </div>
                                <?php
                                $pusrchase_sts = $_GET['purchase_sts'];

                                    $sql = "SELECT * FROM purchase_details WHERE purchase_id = '$pusrchase_sts'";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result)>0) {
                                $sNo = 1;
                                while($row = mysqli_fetch_assoc($result)) {
                                $sNo++;
                                   $product_id = $row['product_id'];
                                    $product_qty = $row['qty'];
                                    $sqlproduct_id = "SELECT * FROM `product` WHERE `product_name`='$product_id'";
                                    $resproduct_id = mysqli_query($conn, $sqlproduct_id);
                                    $rowproduct_id = mysqli_fetch_assoc($resproduct_id);
                                    $product_name = $rowproduct_id['product_name'];
                                    $product_ids = $rowproduct_id['product_id'];

                                    $sqlSubCategory = "SELECT * FROM `purchase` WHERE `purchase_id`='$pusrchase_sts'";
                                    $resSubCategory = mysqli_query($conn, $sqlSubCategory);
                                    $rowSubCategory = mysqli_fetch_assoc($resSubCategory);
                                   $product_material = $rowSubCategory['material'];
                                ?>
                                <div class="form-row formrow">

                                    <div class="form-group col-md-3">
                                        <label>Product Name </label>
                                        <input type="text" class="form-control p_name" placeholder="Product Name" id="pro_name" name="pro_name"readonly value="<?php echo $product_name ?>" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control pro_id"  id="pro_id" name="pro_id"  value="<?php echo $product_ids ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Quantity </label>
                                        <input type="number" class="form-control p_qty" placeholder="Qty" id="pro_qty" name="pro_qty" readonly  value="<?php echo $product_qty ?>" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Received Material</label>
                                        <input type="number" class="form-control p_mat" placeholder="Received Material" id="pro_mat" name="pro_mat"  style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Remaining</label>
                                        <input type="number" class="form-control rem" placeholder="Remaining" id="remaining" name="remaining" style="border-color: #181f5a;color: black">
                                    </div>
                                </div>
                                <?php } }
                                ?>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary"  id="statusbtn">ADD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Include ('../../includes/footer.php') ?>
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
                                    <input type="text"  class="form-control" placeholder="Purchase Id" id="pur_id" name="pur_id" style="border-color: #181f5a;color: black">
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

    $(document).ready(function() {
        // Function to handle keyup event on Received Material input
        $(".p_mat").keyup(function() {
            var $container = $(this).closest('.form-row');
            const inputQtyValue = parseFloat($container.find(".p_qty").val()); // Get the quantity value
            const materialReceived = parseFloat($(this).val()); // Get the material received value
            var remaining = inputQtyValue - materialReceived;
            if (remaining < 0) {
                remaining = 0; // If material received is more than quantity, set remaining to 0
            }
            $container.find('.rem').val(remaining); // Set the remaining value
        });

        // Function to handle change event on Status dropdown
        $("#pro_sts").change(function() {
            if ($(this).val() === '1') { // If status is "Received"
                $(".p_mat").each(function() {
                    var $container = $(this).closest('.form-row');
                    const inputQtyValue = parseFloat($container.find(".p_qty").val()); // Get the quantity value
                    $container.find('.p_mat').val(inputQtyValue); // Set received material value to quantity value
                    $container.find('.rem').val(0); // Set remaining to 0
                });
            }
        });
    });

    //Status
    function status_fun(data) {
            window.location.href="../purchase/all_purchase.php?purchase_sts="+data;
    }
    var sts = '<?php echo  $_GET['purchase_sts']?>';
    if(sts != ''){
        var edit_model_title = "Add Status - "+sts;
        $('#titless').html(edit_model_title);
        $('#statusbtn').html("Save");
        $('#p_id').val(sts);
        $('#status_list').modal('show');
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

                // Create an object to store form data
                var formData = {
                    productId: productId,
                    productName: productName,
                    quantity: quantity,
                    receivedMaterial: receivedMaterial,
                    remaining: remaining
                };

                // Push the object into the formDataArray
                formDataArray.push(formData);
            });


            $.ajax({

                type: "POST",
                url: "status_api.php",
                // data: $('#status_form').serialize() + "&tableData=" + JSON.stringify(formDataArray),
                data: {
                    pro_sts: status,
                    sts_date: date,
                    tableData: JSON.stringify(formDataArray)
                },
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
    $("#status_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                pro_sts: {
                    required: true
                },
                sts_date: {
                    required: true
                },

            },
            // Specify validation error messages
            messages: {
                pro_sts: "*This field is required",
                sts_date: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
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
        $('#apii').val("email_api.php")

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
                url: "email_api.php",
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
                email: {
                    required: true
                },

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

        }
        else {
            // Show the input field for other selections
            ref_nos_c.style.display = 'none';
            payment_date.style.display = 'block';
            pay_mades.style.display = 'block';
            repayment_modes.style.display = 'block';
            Notess.style.display = 'block';
            ref_nos.style.display = 'block';

        }
    }
    });
    function repayment(data) {
        $("#title").html("Add Payment "+ data);
        $('#repayment_form')[0].reset();
        $('#apii').val("repayment_api.php")
        $('#pur_id').val(data)
        // $('#game_id').prop('readonly', false);
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
                // ref_no: {
                //     required: true
                // },


            },
            // Specify validation error messages
            messages: {
                repayment_date: "*This field is required",
                pay_made: "*This field is required",
                repayment_mode: "*This field is required",
                ref_no: "*This field is required",
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
