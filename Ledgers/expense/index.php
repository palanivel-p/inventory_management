<?php Include("../../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
$expense_id= $_GET['expense_id'];
$e_category= $_GET['e_category'];
$s_name= $_GET['sts_name'];
$pay_mode= $_GET['pay_mode'];
$ref_no= $_GET['ref_no'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

//if($f_date == ''){
//    $f_date = date('Y-m-01');
//}
//if($t_date == ''){
//    $t_date = date('Y-m-d');
//}
//$from_date = date('Y-m-d 00:00:00',strtotime($f_date));
//$to_date = date('Y-m-d 23:59:59',strtotime($t_date));

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

if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}


if($expense_id != ""){
//    $expense_idSql= " AND expense_id = '".$expense_id."'";
    $expense_idSql = " AND expense_id LIKE '%" . $expense_id . "%'";

}
else{
    $expense_idSql ="";
}

if($e_category != ""){
    $e_categorySql= " AND expense_type = '".$e_category."'";
//    $e_categorySql = " AND expense_type LIKE '%" . $e_category . "%'";

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
    <title>Expense</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://bhims.ca/piloting/img/favicon_New.png">
    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../vendor/chartist/css/chartist.min.css">
    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../../vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="../../vendor/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="../../vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="../../vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="../../vendor/pickadate/themes/default.date.css">
    <link href="../../vendor/summernote/summernote.css" rel="stylesheet">
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
    $header_name ="Expense";
    Include ('../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Expense</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Expense List</h4>
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
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>
                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>
                        <!-- <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>
                           </span>Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Expense Date</strong></th>
                                <!--<th><strong>Expense Id</strong></th>-->
                                <th><strong>Expense Category</strong></th>
                                <!--  <th><strong>Sub Category</strong></th>-->
                                <th><strong>Amount</strong></th>
                                <th><strong>Credit Days</strong></th>
                                <th><strong>Due Date</strong></th>
                                <th><strong>Payment Status</strong></th>
                                <th><strong>View</strong></th>
                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
                            // $sql = "SELECT * FROM expense ORDER BY id  LIMIT $start,10";

                            if($e_category == "" && $s_name== "" && $pay_mode == "" && $ref_no=="" && $f_date =="" && $t_date =="") {
                                $sql = "SELECT * FROM expense WHERE id>0 $addedBy ORDER BY id DESC LIMIT $start,10";
                            }
                            else
                            {
                                $sql = "SELECT * FROM expense WHERE expense_date  BETWEEN '$from_date' AND '$to_date' $e_categorySql$s_nameSql$pay_modeSql$ref_noSql$addedBy ORDER BY id DESC LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $e_date = $row['expense_date'];
                            $expence_date = date('d-m-Y', strtotime($e_date));
                            //                            $expence_date = date('d-m-Y', $e_date);
                            $d_date=$row['due_date'];
                            $due_date = date('d-m-Y', strtotime($d_date));
                            //                            $due_date = date('d-m-Y', $d_date);
                            $expense_type=$row['expense_type'];

                            //                               $sqlExpenseType = "SELECT * FROM `expense_category` WHERE `category_id`='$expense_id'";
                            //                            $resExpenseType = mysqli_query($conn, $sqlExpenseType);
                            //                            $rowExpenseType = mysqli_fetch_assoc($resExpenseType);
                            //                            $ExpenseType =  $rowExpenseType['category_type'];
                            //
                            $sqlExpenseType = "SELECT * FROM `expense_category` WHERE `category_id`='$expense_type'";
                            $resExpenseType = mysqli_query($conn, $sqlExpenseType);
                            $rowExpenseType = mysqli_fetch_assoc($resExpenseType);
                            $ExpenseType =  $rowExpenseType['category_name'];

                            if($row['payment_status'] == 'paid'){
                                $statColor = 'success';
                                $statCont = 'Paid';
                            }
                            else {
                                $statColor = 'danger';
                                $statCont = 'UnPaid';
                            }
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $expence_date?></td>
                                <!--                                <td> --><?php //echo $row['expense_id']?><!-- </td>-->
                                <td> <?php echo $ExpenseType?> </td>
                                <!--                                <td> --><?php //echo $ExpenseSub?><!-- </td>-->
                                <td> <?php echo $row['amount']?> </td>
                                <td> <?php echo $row['credit_days']?> </td>
                                <td> <?php echo $due_date?> </td>
                                <td> <span class="badge badge-pill badge-<?php echo $statColor?>"><?php echo $statCont?></span></td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="<?php echo $website?>/Ledgers/expense/show_file.php?expense_id=<?php echo $row['expense_id']?>"
                                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-eye"></i></a>
                                    </div>
                                </td>
                    </div>

                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['expense_id'];?>')">Edit</a>
                                <?php
                                if($_COOKIE['role'] == 'Super Admin') {
                                    ?>
                                    <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $row['expense_id'];?>')">Delete</a>
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
                                if($e_category == "" && $s_name== "" && $pay_mode == "" && $ref_no=="" && $f_date =="" && $t_date =="") {
//                                        $sqls = "SELECT COUNT(id) as count FROM expense WHERE id>0 $addedBy";
                                    $sqls = "SELECT COUNT(id) as count FROM expense WHERE  id>0 $addedBy";

                                }
                                else
                                {
                                    $sqls = "SELECT COUNT(id) as count FROM expense WHERE expense_date  BETWEEN '$from_date' AND '$to_date'$e_categorySql$s_nameSql$pay_modeSql$ref_noSql$addedBy";
//                                        $sqls = "SELECT COUNT(id) as count FROM expense";
                                }

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


    <div class="modal fade" id="career_list"  data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title">Expense</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="basic-form" style="color: black;">
                        <form id="expense_form" autocomplete="off">
                            <div class="form-row">

                                <div class="form-group col-md-6" id="ex_date">
                                    <label>Expense Date *</label>
                                    <input type="date" class="form-control" id="expense_date" name="expense_date" style="border-color: #181f5a;color: black">
                                    <input type="hidden" class="form-control"  id="api" name="api">
                                    <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                    <input type="hidden" class="form-control"  id="expense_id" name="expense_id">
                                </div>
                                <div class="form-group col-md-6" id="c_type">
                                    <label>Category Type</label>
                                    <select  class="form-control" id="category_type" name="category_type" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                        <option value="">Select Category Type </option>
                                        <?php
                                        $sqlCheque = "SELECT * FROM `expense_category`";
                                        $resultCheque = mysqli_query($conn, $sqlCheque);
                                        if (mysqli_num_rows($resultCheque) > 0) {
                                            while ($rowCheque = mysqli_fetch_array($resultCheque)) {
                                                ?>
                                                <option
                                                        value='<?php echo $rowCheque['category_id']; ?>'><?php echo strtoupper($rowCheque['category_name']); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="s_name">

                                    <label>Supplier Name</label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" placeholder="Supplier Name" style="border-color: #181f5a;color: black">

                                </div>

                                <div class="form-group col-md-6" id="repayment_modes">
                                    <label>Payment Mode *</label>
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
                                    <input type="text" class="form-control" placeholder="Reference No" id="ref_noss" name="ref_noss" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-6" id="amt">
                                    <label>Amount *</label>
                                    <input type="number" class="form-control" placeholder="Amount" id="amount" name="amount" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-6" id="pay_status">
                                    <label>Payment Status </label>
                                    <select  class="form-control" id="payment_status" name="payment_status" style="border-color: #181f5a;color: black">
                                        <option value='unpaid'>UnPaid</option>
                                        <option value='paid'>Paid</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="c_day">
                                    <label>credit days *</label>
                                    <input type="number" class="form-control" placeholder="Credit days" id="credit_days" name="credit_days" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-6" id="d_date">
                                    <label>Due date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12" id="detail">
                                    <label>Details</label>
                                    <textarea class="form-control" placeholder="Details" id="details" name="details" cols="70" rows="4" style="border: 1px solid black;color: black;"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;">Close</button>
                    <button type="button" class="btn btn-primary" id="add_btn">ADD</button>
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
                                <label>From Date </label>
                                <input type="date"  class="form-control" id="f_date" name="f_date" style="border-color: #181f5a;color: black">
                            </div>
                            <div class="form-group col-md-12">
                                <label>To Date </label>
                                <input type="date"  class="form-control" id="t_date" name="t_date" style="border-color: #181f5a;color: black">
                            </div>
                            <!--                                <div class="form-group col-md-12">-->
                            <!--                                    <label>Expense Id </label>-->
                            <!--                                    <input type="text"  class="form-control" placeholder="Expense Id" id="expense_id" name="expense_id" style="border-color: #181f5a;color: black">-->
                            <!--                                </div>-->
                            <div class="form-group col-md-12">
                                <label>Expense Category </label>
                                <!--                                    <label>Category Type</label>-->
                                <select  class="form-control" id="e_category" name="e_category" style="border-color: #181f5a;color: black">
                                    <option value="">All</option>
                                    <?php
                                    $sqlDevice = "SELECT * FROM `expense_category`";
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
                                <!--                                    <input type="text"  class="form-control" placeholder="Expense Category" id="e_category" name="e_category" style="border-color: #181f5a;color: black">-->
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


<script src="../../vendor/global/global.min.js"></script>
<script src="../../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../../vendor/chart.js/Chart.bundle.min.js"></script>
<script src="../../js/custom.min.js"></script>
<script src="../../js/dlabnav-init.js"></script>
<script src="../../vendor/owl-carousel/owl.carousel.js"></script>
<script src="../../vendor/peity/jquery.peity.min.js"></script>
<!--<script src="../../vendor/apexchart/apexchart.js"></script>-->
<script src="../../js/dashboard/dashboard-1.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../../js/plugins-init/jquery.validate-init.js"></script>
<script src="../../vendor/moment/moment.min.js"></script>
<script src="../../vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../vendor/summernote/js/summernote.min.js"></script>
<script src="../../js/plugins-init/summernote-init.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        const repayment_mode = document.getElementById('repayment_mode');
        const ref_noss = document.getElementById('ref_noss');
        const pay_mode = document.getElementById('pay_mode');
        const repayment_modes = document.getElementById('repayment_modes');
        const ex_date = document.getElementById('ex_date');
        const c_type = document.getElementById('c_type');
        const s_name = document.getElementById('s_name');
        const ref_nos_c = document.getElementById('ref_nos_c');
        const ref_nos = document.getElementById('ref_nos');
        const amt = document.getElementById('amt');
        const pay_status = document.getElementById('pay_status');
        const c_day = document.getElementById('c_day');
        const d_date = document.getElementById('d_date');
        const detail = document.getElementById('detail');
        const bank_names = document.getElementById('bank_names');


        // Add an event listener to the dropdown
        repayment_mode.addEventListener('change', function () {

            a(repayment_mode.value);

        });

        function a(values) {
            if (values === 'Cheque') {
                // Hide the input field when 'Hide Input Field' is selected
                ref_nos.style.display = 'none';
                repayment_modes.style.display = 'block';
                repayment_mode.style.display = 'block';
                ref_nos_c.style.display = 'block';
                amt.style.display = 'block';
                pay_status.style.display = 'block';
                c_day.style.display = 'block';
                d_date.style.display = 'block';
                detail.style.display = 'block';
                c_type.style.display = 'block';
                ex_date.style.display = 'block';
                pay_mode.style.display = 'block';
                ref_noss.style.display = 'block';
                s_name.style.display = 'block';
                bank_names.style.display = 'block';
            }
            else {
                // Show the input field for other selections
                ref_nos_c.style.display = 'none';
                s_name.style.display = 'block';
                // ref_nos_c.style.display = 'block';
                repayment_modes.style.display = 'block';
                repayment_mode.style.display = 'block';
                ref_nos.style.display = 'block';
                amt.style.display = 'block';
                pay_status.style.display = 'block';
                c_day.style.display = 'block';
                d_date.style.display = 'block';
                detail.style.display = 'block';
                c_type.style.display = 'block';
                ex_date.style.display = 'block';
                pay_mode.style.display = 'block';
                ref_noss.style.display = 'block';
                bank_names.style.display = 'block';


            }
        }
    });
    $(document).ready(function() {
        $("#credit_days").on("keyup", function () {
            const inputDateValue = $("#expense_date").val();
            const payment_days = parseInt($(this).val());
            if (!isNaN(payment_days)) {
                const d = new Date(inputDateValue);
                d.setDate(d.getDate() + payment_days);
                const formattedDate = d.toISOString().split('T')[0];
                $("#due_date").val(formattedDate);
            }
        });
    });

    function expense_type_fun(expense_type,expense_name) {
        if(expense_type != ''){
            $.ajax({
                type: "POST",
                url: "category_api.php",
                data: 'expense_type='+expense_type,
                dataType: "json",
                success: function(res){
                    if(res.status=='success')
                    {
                        var category_sub = res.category_sub;
                        var category_sub_id = res.category_sub_id;


                        for(let i = 0;i<category_sub.length;i++){
                            var opt = document.createElement('option');
                            opt.value = category_sub_id[i];
                            opt.innerHTML = category_sub[i];

                            document.getElementById('expense_name').appendChild(opt);
                        }
                        if(category_sub != undefined){
                            $("#expense_name").val(expense_name);

                        }
                        $("#expense_name").trigger("change");
                    }
                    else if(res.status=='failure')
                    {
                        Swal.fire("Invalid",  res.msg, "warning")
                        // .then((value) => {
                        //     window.window.location.reload();
                        // });
                    }
                    else if(res.status=='failure')
                    {
                        Swal.fire("Failure",  res.msg, "error")
                        // .then((value) => {
                        //     window.window.location.reload();
                        //
                        // });
                    }
                },
                error: function(){
                    Swal.fire("Check your network connection");
                    // window.window.location.reload();
                }
            });
        }
    }

    function addTitle() {
        $("#title").html("Add Expense");
        $('#expense_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {
        $("#title").html("Edit Expense- " + data);
        $('#expense_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'expense_id=' + data,
            dataType: "json",
            success: function (res) {
                if (res.status == 'success') {
                    $("#expense_date").val(res.expense_date);
                    $("#details").val(res.details);
                    $("#supplier").val(res.supplier);
                    $("#amount").val(res.amount);
                    $("#ref_no_c").val(res.ref_no_c);
                    $("#bank_name").val(res.bank_name);

                    // Set the selected option for "Category Type"
                    $("#category_type").val(res.expense_type); // Assuming res.expense_name contains the category_id

                    $("#repayment_mode").val(res.repayment_mode);
                    $('#ref_noss').val(res.reference_no);
                    $('#credit_days').val(res.credit_days);
                    $('#due_date').val(res.due_date);
                    $('#payment_status').val(res.payment_status);
                    $("#old_pa_id").val(res.expense_id);
                    $("#expense_id").val(res.expense_id);

                    $("#expense_name option").remove();
                    expense_type_fun(res.expense_type, res.expense_name);

                    $("#expense_name").trigger("change");

                    // Show or hide reference number field based on repayment mode
                    if (res.repayment_mode == "Cheque") {
                        $("#ref_nos").hide();
                        $("#ref_nos_c").show();
                    } else {
                        $("#ref_nos").show();
                        $("#ref_nos_c").hide();
                    }

                    var edit_model_title = "Edit Ledger - " + data;
                    $('#title').html(edit_model_title);
                    $('#add_btn').html("Save");
                    $('#career_list').modal('show');
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



    //to validate form
    $("#expense_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                expense_date: {
                    required: true
                },
                payment_mode: {
                    required: true
                },
                category_type: {
                    required: true
                },
                amount: {
                    required: true
                },
                repayment_mode: {
                    required: true
                },
                credit_days: {
                    required: true
                },
                supplier: {
                    required: true
                },
                reference_no: {
                    required: true
                },
                bank_name: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                category_type: "*This field is required",
                amount: "*This field is required",
                repayment_mode: "*This field is required",
                payment_mode: "*This field is required",
                expense_date: "*This field is required",
                credit_days: "*This field is required",
                supplier: "*This field is required",
                reference_no: "*This field is required",
                bank_name: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {

        $("#expense_form").valid();

        if($("#expense_form").valid()==true) {

            var api = $('#api').val();

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: api,
                data: $('#expense_form').serialize(),
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

                                document.getElementById("add_btn").disabled = false;
                                document.getElementById("add_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("add_btn").disabled = false;
                    document.getElementById("add_btn").innerHTML = 'Add';
                }

            });



        } else {
            document.getElementById("add_btn").disabled = false;
            document.getElementById("add_btn").innerHTML = 'Add';

        }


    });

    //
    //delete model

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
                        data: 'expense_id='+data,
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
        $("#expense_name").trigger("change");
        $("#pay_mode").trigger("change");
        $("#s_name").trigger("change");

        $("#expense_type").change(function(){
            var expense_type = this.value;
            // alert(this.value);
            $("#expense_name option").remove();
            if(expense_type != ''){
                expense_type_fun(expense_type);
            }
        });
    });

    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&expense_id=<?php echo $expense_id?>&e_category=<?php echo $e_category?>&s_name=<?php echo $s_name?>&pay_mode=<?php echo $pay_mode?>&ref_no = <?php echo $ref_no?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&expense_id=<?php echo $expense_id?>&e_category=<?php echo $e_category?>&s_name=<?php echo $s_name?>&pay_mode=<?php echo $pay_mode?>&ref_no = <?php echo $ref_no?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>";
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


</script>


</body>
</html>
