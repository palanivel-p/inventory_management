<?php Include("../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);
$page= $_GET['page_no'];
$l_id= $_GET['l_id'];
$b_name= $_GET['b_name'];
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

if($l_id != ""){
    $l_idSql= " AND loan_id = '".$l_id."'";

}
else{
    $l_idSql ="";
}

if($b_name != ""){
    $b_nameSql = " AND borrower LIKE '%" . $b_name . "%'";
}
else{
    $b_nameSql ="";
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
    $addedBy = "AND added_by='$added_by'";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Loan profile</title>

    <link rel="icon" type="image/png" sizes="16x16" href="../../images/favicon_New.png">
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

    $header_name ="Loan";
    Include ('../../includes/header.php') ?>



    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Loan</a></li>


            </ol>

        </div>


        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
<h2>Loan list</h2>
                    <div style="display: flex;justify-content: flex-end;">

                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>
                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>
                        <!--                        <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
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
                                <th><strong>Loan Id</strong></th>
                                <th><strong>Loan Date</strong></th>
                                <th><strong>Borrower</strong></th>
                                <th><strong>Loan Amount</strong></th>
                                <th><strong>Paid Amount</strong></th>
                                <th><strong>Balance Amount</strong></th>
                                <th><strong>Tenure</strong></th>
                                <th><strong>Reason</strong></th>
                                <th><strong>View</strong></th>
                                <th><strong>ACTION</strong></th>

                            </tr>
                            </thead>
                            <?php
                            if($b_name == "" ) {
                                $sql = "SELECT * FROM loan WHERE loan_date  BETWEEN '$from_date' AND '$to_date'$addedBy ORDER BY id DESC LIMIT $start,10";
                            }
                            else {
                                $sql = "SELECT * FROM loan WHERE loan_date  BETWEEN '$from_date' AND '$to_date' $b_nameSql$addedBy ORDER BY id DESC LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $date = $row['loan_date'];
                            $dates = date('d-m-Y', strtotime($date));
//                            $dates = date('d-F-Y', strtotime($date));
                            $loan_id =   $row['loan_id'];

                            $sqlamount="SELECT SUM(repayment_amount) AS repayment_amount  FROM repayment WHERE loan_id='$loan_id'";
                            $resamount=mysqli_query($conn,$sqlamount);
                            if(mysqli_num_rows($resamount)>0){
                                $arrayamount=mysqli_fetch_array($resamount);
                                $amount=$arrayamount['repayment_amount'];
                                if($amount == ''){
                                    $amount = 0;
                                }
                            }
                            $loan_amount= $row['amount'];
                            $balance_amount= $loan_amount - $amount;

//                               $loan_id =   $row['loan_id'];
                            //   $career_date =   date('d-F-Y');

                            if($row['access_status'] == 1){
                                $statColor = 'success';
                                $statCont = 'Active';
                            }
                            else {
                                $statColor = 'danger';
                                $statCont = 'In Active';
                            }
                            $branchId = $row['branch_name'];
                            if($row['reason'] == ''){
                                $reason = 'NA';
                            }
                            else{
                                $reason = $row['reason'];
                            }
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $loan_id?></td>
                                <td><?php echo $dates?></td>

                                <td> <?php echo $row['borrower']?> </td>
                                <td> <?php echo $loan_amount?> </td>
                                <td> <?php echo $amount?> </td>
                                <td> <?php echo $balance_amount?> </td>
                                <td> <?php echo $row['tenure']?> </td>
                                <td> <?php echo $reason?> </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="<?php echo $website?>/Ledgers/loan/show_file.php?loan_id=<?php echo $row['loan_id']?>"
                                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-eye"></i></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">

                                            <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $loan_id;?>')">Edit</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#repayment_list" style="cursor: pointer" onclick="repayment('<?php echo $loan_id;?>')">Repayment</a>
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin') {
                                            ?>
                                            <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $loan_id;?>')">Delete</a>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="fa-solid fa-less-than"></i></li>
                                        <?php
                                    }

                                    if($b_name == "" ) {
                                        $sql = "SELECT COUNT(id) as count FROM loan WHERE loan_date  BETWEEN '$from_date' AND '$to_date'$addedBy";
                                    }
                                    else {
                                        $sql = "SELECT COUNT(id) as count FROM loan WHERE loan_date  BETWEEN '$from_date' AND '$to_date' $b_nameSql$addedBy";
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



        <div class="modal fade" id="career_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Loan Details</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="staff_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-6" id="ldate">
                                        <label>Loan Date *</label>
                                        <input type="date" class="form-control" id="loan_date" name="loan_date" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="bow">
                                        <label>Borrower *</label>
                                        <input type="text" class="form-control" placeholder="Borrower" id="borrower" name="borrower" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="loan_id" name="loan_id ">
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

                                    <div class="form-group col-md-6" id="ten">
                                        <label>Tenure *</label>
                                        <input type="number" class="form-control" placeholder="Tenure" id="tenure" name="tenure" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="reas">
                                        <label>Reason </label>
                                        <input type="text" class="form-control" placeholder="Reason" id="reason" name="reason" style="border-color: #181f5a;color: black">
                                    </div>
                        </div>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="add_btn">ADD</button>
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
                                    <div class="form-group col-md-6">
                                        <label>Loan Id *</label>
                                        <input type="text" class="form-control" placeholder="Loan Id" id="loan_id_rep" name="loan_id_rep" readonly  style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Repayment Date *</label>
                                        <input type="date" class="form-control" id="repayment_date" name="repayment_date" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="apii" name="apii">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="repayment_id" name="repayment_id">

                                    </div>
                                    <?php
                                    $sqlRepay = "SELECT id FROM repayment ORDER BY id DESC";
                                    $resultRepay =   mysqli_query($conn, $sqlRepay);
                                    $rowRepay = mysqli_fetch_assoc($resultRepay);
                                    $conID = $rowRepay['id'] + 1;
                                    if(strlen($conID)==1)
                                    {
                                        $conID='00'.$conID;
                                    }elseif(strlen($conID)==2)
                                    {
                                        $conID='0'.$conID;
                                    }
                                    $repayment_id='R'.($conID);
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label>Repayment Id *</label>
                                        <input type="text" class="form-control" placeholder="Repayment Id" id="repay_id" name="repay_id" readonly value="<?php echo $repayment_id?>" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Repayment Amount *</label>
                                        <input type="number" class="form-control" placeholder="Repayment Amount" id="repayment_amount" name="repayment_amount" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Repayment Mode</label>
                                        <select  class="form-control" id="repayment_modes" name="repayment_mode" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <option value='Cash'>Cash</option>
                                            <option value='Cheque'>Cheque</option>
                                            <option value='NEFT'>NEFT</option>
                                            <option value='RTGS'>RTGS</option>
                                            <option value='UPI'>UPI</option>
                                            <option value='others'>others</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Reference No*</label>
                                        <input type="text" class="form-control" placeholder="Reference No" id="ref_no" name="ref_no" style="border-color: #181f5a;color: black">
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
                                <div class="form-group col-md-12">
                                    <label>Borrower Name </label>
                                    <input type="text"  class="form-control" placeholder="Borrower Name" id="b_name" name="b_name" style="border-color: #181f5a;color: black">
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
        const ldate = document.getElementById('ldate');
        const repayment_modes = document.getElementById('repayment_modes');
        const bank_names = document.getElementById('bank_names');
        const ref_nos_c = document.getElementById('ref_nos_c');
        const ref_nos = document.getElementById('ref_nos');
        const amt = document.getElementById('amt');
        const ten = document.getElementById('ten');
        const reas = document.getElementById('reas');


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
                ldate.style.display = 'block';
                repayment_modes.style.display = 'block';
                bank_names.style.display = 'block';
                ref_nos_c.style.display = 'block';
                amt.style.display = 'block';
                ten.style.display = 'block';
                reas.style.display = 'block';
            }
            else {
                // Show the input field for other selections
                ref_nos.style.display = 'block';
                repayment_modes.style.display = 'block';
                repayment_mode.style.display = 'block';
                ldate.style.display = 'block';
                repayment_modes.style.display = 'block';
                bank_names.style.display = 'block';
                ref_nos_c.style.display = 'none';
                amt.style.display = 'block';
                ten.style.display = 'block';
                reas.style.display = 'block';


            }
        }
    });

    function addTitle() {
        $("#title").html("Add Loan");
        $('#staff_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Loan- "+data);
        $('#staff_form')[0].reset();
        $('#api').val("edit_api.php");
        $('#loan_id').val(data);

        $.ajax({

            type: "POST",
            url: "view_api.php",
            data: 'loan_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {

                    $("#borrower").val(res.borrower);
                    $("#amount").val(res.amount);
                    $("#tenure").val(res.tenure);
                    $("#loan_date").val(res.loan_date);
                    $("#reason").val(res.reason);
                    $("#bank_name").val(res.bank_name);
                    $("#old_pa_id").val(res.loan_id);
                    $("#loan_id").val(res.loan_id);


                    var edit_model_title = "Edit Loan - "+data;
                    $('#title').html(edit_model_title);
                    $('#add_btn').html("Save");
                    $('#career_list').modal('show');


                }
                else if(res.status=='wrong')
                {
                    swal("Invalid",  res.msg, "warning")
                        .then((value) => {
                            window.window.location.reload();
                        });

                }
                else if(res.status=='failure')
                {
                    swal("Failure",  res.msg, "error")
                        .then((value) => {
                            window.window.location.reload();

                        });
                }
            },
            error: function(){
                swal("Check your network connection");
                window.window.location.reload();
            }
        });
    }

    //to validate form
    $("#staff_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                tenure: {
                    required: true
                },
                amount: {
                    required: true
                },
                borrower: {
                    required: true
                },
                loan_date: {
                    required: true
                },
                bank_name: {
                    required: true
                },

            },
            // Specify validation error messages
            messages: {
                loan_date: "*This field is required",
                borrower: "*This field is required",
                amount: "*This field is required",
                tenure: "*This field is required",
                bank_name: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data
    $('#add_btn').click(function () {
        $("#staff_form").valid();
        if($("#staff_form").valid()==true) {
            var api = $('#api').val();

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: api,
                data: $('#staff_form').serialize(),
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
                        data: 'loan_id='+data,
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
                                        window.window.location.reload();
                                    });

                            }
                        },
                        error: function(){
                            swal("Check your network connection");
                        }
                    });
                }
            });
    }

    function repayment(data) {
        $("#title").html("Add Repayment "+ data);
        $('#repayment_form')[0].reset();
        $('#apii').val("repayment_api.php")
        $('#loan_id_rep').val(data)
        // $('#game_id').prop('readonly', false);
    }
    //add data
    $('#repay_btn').click(function () {
        $("#repayment_form").valid();
        if($("#repayment_form").valid()==true) {
            var api = $('#apii').val();
            var loan = "test";

            var formData = $('#repayment_form').serialize();
            // Append the loan_id to the serialized form data
            formData += '&loan=' + loan;

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "repayment_api.php",
                data: formData,
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
                repayment_amount: {
                    required: true
                },
                ref_no: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                repayment_date: "*This field is required",
                ref_no: "*This field is required",
                repayment_amount: "*This field is required",
                // tenure: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });


    $( document ).ready(function() {
        $('#search').val('<?php echo $search;?>');
        $('#branch_nameS').val('<?php echo $branch_nameS;?>');

    });

    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&l_id=<?php echo $l_id?>&b_name=<?php echo $b_name?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&l_id=<?php echo $l_id?>&b_name=<?php echo $b_name?>";
    });

</script>

</body>
</html>
