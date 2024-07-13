<?php Include("../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
//$purchase_payment_id= $_GET['purchase_payment_id'];
$e_category= $_GET['e_category'];
$s_name= $_GET['sts_name'];
$pay_mode= $_GET['pay_mode'];
$ref_no= $_GET['ref_no'];
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

if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}


if($purchase_payment_id != ""){
//    $purchase_payment_idSql= " AND purchase_payment_id = '".$purchase_payment_id."'";
    $purchase_payment_idSql = " AND purchase_payment_id LIKE '%" . $purchase_payment_id . "%'";

}
else{
    $purchase_payment_idSql ="";
}

if($e_category != ""){
    $e_categorySql= " AND purchase_payment_type = '".$e_category."'";
//    $e_categorySql = " AND purchase_payment_type LIKE '%" . $e_category . "%'";

}
else{
    $e_categorySql ="";
}

if($s_name != ""){
    $s_nameSql= " AND payment_status = '".$s_name."'";
//    $s_nameSql = " AND payment_status LIKE '%" . $s_name . "%'";

}
else{
    $s_nameSql ="";
}

if($pay_mode != ""){
    $pay_modeSql= "AND payment_mode	 = '".$pay_mode."'";
//    $pay_modeSql = " AND payment_mode LIKE '%" . $pay_mode . "%'";

}
else {
    $pay_modeSql = "";
}
if($ref_no != ""){
//    $ref_noSql= "AND reference_no = '".$ref_no."'";
    $ref_noSql = " AND reference_no LIKE '%" . $ref_no . "%'";

}
else {
    $ref_noSql = "";
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
    <title>Purchase DC Payment</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://bhims.ca/piloting/img/favicon_New.png">
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
    $header_name ="Purchase DC Payment";
    Include ('../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Purchase DC Payment</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Purchase DC Payment List</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">
                            <!--                        <div class="form-group mx-sm-3 mb-2">-->
                            <!--                                <label>Children Type</label>-->
                            <!--                                <select data-search="true" class="form-control tail-select w-full" id="child_type" name="child_type" style="border-radius:20px;color:black;border:1px solid black;">-->
                            <!--                                    <option value='all'>All</option>-->
                            <!--                                    <option value='current project'>current project</option>-->
                            <!--                                    <option value='completed project'>completed project</option>-->
                            <!--                                </select>-->
                            <!--                            </div>-->
                            <!--                            <div class="form-group mx-sm-3 mb-2">-->
                            <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                            <!--                            </div>-->
                            <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        </form>
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>-->
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
<!--                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>-->
                        <!-- <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
<!--                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>-->
<!--                           </span>Excel</button>-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Payment Date</strong></th>
                                <th><strong>Purchase Id</strong></th>
<!--                                <th><strong>Supplier Name</strong></th>-->
                                <th><strong>Payment Mode</strong></th>
                                <th><strong>Paid Amount</strong></th>
<!--                                <th><strong>Due Amount</strong></th>-->
                                <th><strong>Reference No</strong></th>
                                <th><strong>Action</strong></th>
                            </tr>
                            </thead>
                            <?php
//                            if($e_category == "" && $s_name== "" && $pay_mode == "" && $ref_no=="" && $f_date =="" && $t_date =="") {
                                $sql = "SELECT * FROM purchase_dc ORDER BY id DESC LIMIT $start,10";
//                            }
//                            else
//                            {
//                                $sql = "SELECT * FROM purchase_dc_payment WHERE purchase_payment_date  BETWEEN '$from_date' AND '$to_date' $e_categorySql$s_nameSql$pay_modeSql$ref_noSql$addedBy ORDER BY id DESC LIMIT $start,10";
//                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $purchase_id = $row['purchase_id'];
                            $purchase_id = $row['purchase_id'];

                            $sqlpurchase = "SELECT * FROM `purchase_shipment` WHERE `purchase_id`='$purchase_id'";
                            $respurchase = mysqli_query($conn, $sqlpurchase);
                            $rowpurchase = mysqli_fetch_assoc($respurchase);
                            $g_total =  $rowpurchase['shipping_amount'];
                            $shipping_id =  $rowpurchase['shipping_id'];

                            $sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                            $resSupplier = mysqli_query($conn, $sqlSupplier);
                            $rowSupplier = mysqli_fetch_assoc($resSupplier);
                            $Supplier =  $rowSupplier['supplier_name'];

//                          echo  $sqlamount="SELECT SUM(pay_made) AS pay_made  FROM purchase_dc WHERE repayment_id='$shipping_id'";
//                            $resamount=mysqli_query($conn,$sqlamount);
//                            if(mysqli_num_rows($resamount)>0){
//                                $arrayamount=mysqli_fetch_array($resamount);
//                                $totalAmount=$arrayamount['pay_made'];
//                            }
                            $totalAmount=$row['pay_made'];
                            $balance_amount= $g_total - $totalAmount;

                            $e_date = $row['repayment_date'];
                            $expence_date = date('d-m-Y', strtotime($e_date));
                            $d_date=$row['due_date'];
                            $due_date = date('d-m-Y', strtotime($d_date));

                            if($row['ref_no'] == ''){
                                $ref_no = $row['ref_no_c'];
                            }
                            else {
                                $ref_no = $row['ref_no'];
                            }
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $expence_date?></td>
                                <td><?php echo $row['purchase_id']?></td>
                                <td> <?php echo $row['repayment_mode']?> </td>
<!--                                <td> --><?php //echo $g_total?><!-- </td>-->
                                <td> <?php echo $totalAmount?> </td>
                                <td> <?php echo $ref_no?> </td>

                    </div>

                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="modal" data-target="#repayment_list" style="cursor: pointer" onclick="repayment('<?php echo $row['repayment_id'];?>','<?php echo $g_total?>','<?php echo $balance_amount?>','<?php echo $totalAmount ?>')">Edit</a>
                                <?php
                                if($_COOKIE['role'] == 'Super Admin') {
                                    ?>
                                    <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $row['repayment_id'];?>')">Delete</a>
                                    <?php
                                }
                                ?>
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
                                if($prevPage==0)
                                {
                                    ?>
                                    <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&e_category=<?php echo $e_category ?>&s_name=<?php echo $s_name ?>&pay_mode=<?php echo $pay_mode?>&ref_no=<?php echo $ref_no?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>"><i class="fa-solid fa-less-than"></i></a></li>
                                    <?php
                                }
//                                if($e_category == "" && $s_name== "" && $pay_mode == "" && $ref_no=="" && $f_date =="" && $t_date =="") {
//                                        $sqls = "SELECT COUNT(id) as count FROM purchase_dc_payment WHERE id>0 $addedBy";
                                    $sqls = "SELECT COUNT(id) as count FROM purchase_dc ";

//                                }
//                                else
//                                {
//                                    $sqls = "SELECT COUNT(id) as count FROM purchase_dc_payment WHERE purchase_payment_date  BETWEEN '$from_date' AND '$to_date'$e_categorySql$s_nameSql$pay_modeSql$ref_noSql$addedBy";
//                                        $sqls = "SELECT COUNT(id) as count FROM purchase_dc_payment";
//                                }

                                $results = mysqli_query($conn, $sqls);

                                if (mysqli_num_rows($results)) {

                                    $rows = mysqli_fetch_assoc($results);
                                    $count = $rows['count'];
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
                                                                                           href="?page_no=<?php echo $i ?>&e_category=<?php echo $e_category ?>&s_name=<?php echo $s_name ?>&pay_mode=<?php echo $pay_mode?>&ref_no=<?php echo $ref_no?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>"><?php echo $i ?></a>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&e_category=<?php echo $e_category ?>&s_name=<?php echo $s_name ?>&pay_mode=<?php echo $pay_mode?>&ref_no=<?php echo $ref_no?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>"><i class="fa-solid fa-greater-than"></i></a></li>
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
                                    <input type="hidden" class="form-control"  id="api" name="api">
                                    <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                    <input type="hidden" class="form-control"  id="repayment_id" name="repayment_id">
                                    <input type="hidden" class="form-control"  id="pur_id" name="pur_id">
                                </div>
                                <div class="form-group col-md-6" id="re">
                                    <label>Shipping Id</label>
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
                                <div class="form-group col-md-6" id="bank_names">
                                    <label>Bank Name</label>
                                    <select onchange="getchequename(this.value)"  class="form-control" id="bank_name" name="bank_name" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                        <option value="">Select Bank Name </option>
                                        <?php
                                        $sqlCheque = "SELECT * FROM `cheque`";
                                        $resultCheque = mysqli_query($conn, $sqlCheque);
                                        if (mysqli_num_rows($resultCheque) > 0) {
                                            while ($rowCheque = mysqli_fetch_array($resultCheque)) {
                                                ?>
                                                <option
                                                    value='<?php echo $rowCheque['bank_name']; ?>'><?php echo strtoupper($rowCheque['bank_name']); ?></option>
                                                <?php
                                            }
                                        }
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
                                <label>From Date </label>
                                <input type="date"  class="form-control" id="f_date" name="f_date" style="border-color: #181f5a;color: black">
                            </div>
                            <div class="form-group col-md-12">
                                <label>To Date </label>
                                <input type="date"  class="form-control" id="t_date" name="t_date" style="border-color: #181f5a;color: black">
                            </div>
                            <!--                                <div class="form-group col-md-12">-->
                            <!--                                    <label>purchase_dc_payment Id </label>-->
                            <!--                                    <input type="text"  class="form-control" placeholder="purchase_dc_payment Id" id="purchase_payment_id" name="purchase_payment_id" style="border-color: #181f5a;color: black">-->
                            <!--                                </div>-->
                            <div class="form-group col-md-12">
                                <label>purchase_payment Category </label>
                                <!--                                    <label>Category Type</label>-->
                                <select  class="form-control" id="e_category" name="e_category" style="border-color: #181f5a;color: black">
                                    <option value="">All</option>
                                    <?php
                                    $sqlDevice = "SELECT * FROM `purchase_payment_category`";
                                    $resultDevice = mysqli_query($conn, $sqlDevice);
                                    if (mysqli_num_rows($resultDevice) > 0) {
                                        while ($rowDevice = mysqli_fetch_array($resultDevice)) {
                                            ?>
                                            <option
                                                value='<?php echo $rowDevice['category_id']; ?>'><?php echo strtoupper($rowDevice['category_name']); ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <!--                                    <input type="text"  class="form-control" placeholder="purchase_dc_payment Category" id="e_category" name="e_category" style="border-color: #181f5a;color: black">-->
                            </div>
                            <div class="form-group col-md-12">
                                <label>payment Status</label>
                                <select data-search="true" class="form-control tail-select w-full" id="sts_name" name="sts_name" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <option value=''>All </option>
                                    <option value='paid'>Paid </option>
                                    <option value='unpaid'>Unpaid</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Payment mode *</label>
                                <select  class="form-control" id="pay_mode" name="pay_mode" style="border-color: #181f5a;color: black">
                                    <option value=''>Select Payment mode</option>
                                    <option value='Cheque'>Cheque</option>
                                    <option value='Cash'>Cash</option>
                                    <option value='NEFT'>NEFT</option>
                                    <option value='RTGS'>RTGS</option>
                                    <option value='UPI'>UPI</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Reference No </label>
                                <input type="text"  class="form-control" placeholder="Reference No" id="ref_no" name="ref_no" style="border-color: #181f5a;color: black">
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
                bank_names.style.display = 'none';
            }
        }
    });

    // function repayment(p_id,grand_total,due_amount,paid_amount) {
    //     $("#title").html("Add Payment " + p_id);
    //     $('#repayment_form')[0].reset();
    //     $('#g_total').val(grand_total)
    //     $('#due_amount').val(due_amount)
    //     $('#paid_amount').val(paid_amount)
    //
    //
    //     // Check if g_total is equal to or greater than paid_amount
    //     if (parseFloat(grand_total) <= parseFloat(paid_amount)) {
    //         $('#pay_made').prop('readonly', true).attr('max', grand_total - paid_amount);  // Set pay_made field to readonly and set max attribute to the allowed maximum value
    //     } else {
    //         $('#pay_made').prop('readonly', false).removeAttr('max');  // Remove readonly and max attribute if it was set
    //     }
    //
    //     // Event listener for pay_made input
    //     $('#pay_made').on('input', function() {
    //         let payMade = parseFloat($(this).val());
    //         let grandTotal = parseFloat($('#g_total').val());
    //         let due_amount = parseFloat($('#due_amount').val());
    //
    //         if (payMade > due_amount) {
    //             alert("Payment made cannot be greater than the grand total.");
    //             $(this).val('');  // Set the input value to the grand total
    //         }
    //     });
    // }

    ////add data
    //$('#repay_btn').click(function () {
    //    $("#repayment_form").valid();
    //    if($("#repayment_form").valid()==true) {
    //        var api = $('#apii').val();
    //        //var loan_id = "<?php ////echo $loan_id?>////";
    //        // var loan_id = 56
    //        this.disabled = true;
    //        this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    //        $.ajax({
    //
    //            type: "POST",
    //            url: "edit_api.php",
    //            data: $('#repayment_form').serialize(),
    //            // data: $('#repayment_form').serialize()+ '&' +$.param({loan_id:loan_id}),
    //            dataType: "json",
    //            success: function (res) {
    //                if (res.status == 'success') {
    //                    Swal.fire(
    //                        {
    //                            title: "Success",
    //                            text: res.msg,
    //                            icon: "success",
    //                            button: "OK",
    //                            allowOutsideClick: false,
    //                            allowEscapeKey: false,
    //                            closeOnClickOutside: false,
    //                        }
    //                    )
    //                        .then((value) => {
    //                            window.window.location.reload();
    //                        });
    //                } else if (res.status == 'failure') {
    //
    //                    Swal.fire(
    //                        {
    //                            title: "Failure",
    //                            text: res.msg,
    //                            icon: "warning",
    //                            button: "OK",
    //                            allowOutsideClick: false,
    //                            allowEscapeKey: false,
    //                            closeOnClickOutside: false,
    //                        }
    //                    )
    //                        .then((value) => {
    //
    //                            document.getElementById("repay_btn").disabled = false;
    //                            document.getElementById("repay_btn").innerHTML = 'Add';
    //                        });
    //
    //                }
    //            },
    //            error: function () {
    //
    //                Swal.fire('Check Your Network!');
    //                document.getElementById("repay_btn").disabled = false;
    //                document.getElementById("repay_btn").innerHTML = 'Add';
    //            }
    //
    //        });
    //
    //    } else {
    //        document.getElementById("repay_btn").disabled = false;
    //        document.getElementById("repay_btn").innerHTML = 'Add';
    //
    //    }
    //
    //});


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
    //delete model


    function repayment(p_id,grand_total,due_amount,paid_amount) {
        // alert('test');
        $('#api').val("edit_api.php");
        $("#title").html("Edit Payment " + p_id);
        $('#repayment_form')[0].reset();
        $('#g_total').val(grand_total)
        $('#due_amount').val(due_amount)
        $('#paid_amount').val(paid_amount)

        // Check if g_total is equal to or greater than paid_amount
        if (parseFloat(grand_total) <= parseFloat(paid_amount)) {
            $('#pay_made').prop('readonly', true).attr('max', grand_total - paid_amount);  // Set pay_made field to readonly and set max attribute to the allowed maximum value
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
        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'repayment_id=' + p_id,
            dataType: "json",
            success: function (res) {
                if (res.status == 'success') {
                    $("#repayment_date").val(res.repayment_date);
                    $("#Purch_id").val(res.repayment_id);
                    $("#repayment_date").val(res.repayment_date);
                    $("#pay_made").val(Number(res.pay_made));
                    $("#bank_name").val(res.bank_name);
                    $("#ref_no_c").val(res.ref_no_c);

                    $("#repayment_mode").val(res.repayment_mode);
                    $('#ref_no').val(res.ref_no);
                    $('#notes').val(res.notes);
                    $("#repayment_id").val(res.repayment_id);


                    // Show or hide reference number field based on repayment mode
                    if (res.repayment_mode == "Cheque") {
                        $("#ref_nos").hide();
                        $("#ref_nos_c").show();
                    } else {
                        $("#ref_nos").show();
                        $("#ref_nos_c").hide();
                    }

                    var edit_model_title = "Edit Payment - " + p_id;
                    $('#title').html(edit_model_title);
                    $('#repay_btn').html("Save");
                    $('#repayment_list').modal('show');
                } else if (res.status == 'wrong') {
                    swal("Invalid", res.msg, "warning")
                        .then((value) => {
                            window.location.reload();
                        });
                } else if (res.status == 'failure') {
                    swal("Failure", res.msg, "error")
                        .then((value) => {
                            window.location.reload();
                        });
                }
            },
            error: function () {
                swal("Check your network connection");
                window.location.reload();
            }
        });
    }

    $('#repay_btn').click(function () {

        $("#repayment_form").valid();

        if($("#repayment_form").valid()==true) {

            var api = $('#api').val();

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: api,
                data: $('#repayment_form').serialize(),
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
                        data: 'purchase_payment_id='+data,
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
        $("#purchase_payment_name").trigger("change");
        $("#pay_mode").trigger("change");
        $("#s_name").trigger("change");

        $("#purchase_payment_type").change(function(){
            var purchase_payment_type = this.value;
            // alert(this.value);
            $("#purchase_payment_name option").remove();
            if(purchase_payment_type != ''){
                purchase_payment_type_fun(purchase_payment_type);
            }
        });
    });

    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&purchase_payment_id=<?php echo $purchase_payment_id?>&e_category=<?php echo $e_category?>&s_name=<?php echo $s_name?>&pay_mode=<?php echo $pay_mode?>&ref_no = <?php echo $ref_no?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&purchase_payment_id=<?php echo $purchase_payment_id?>&e_category=<?php echo $e_category?>&s_name=<?php echo $s_name?>&pay_mode=<?php echo $pay_mode?>&ref_no = <?php echo $ref_no?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>";
    });
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

                    //         document.getElementById('purchase_payment_name').appendChild(opt);
                    //     }
                    //     if(category_sub != undefined){
                    //         $("#purchase_payment_name").val(purchase_payment_name);

                    //     }
                    //     $("#purchase_payment_name").trigger("change");
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


</script>


</body>
</html>
