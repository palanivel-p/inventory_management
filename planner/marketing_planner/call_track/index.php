<?php Include("../../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");
$callDate = date('Y-m-d');
error_reporting(0);
$page= $_GET['page_no'];
//$market_id= $_GET['market_id'];
//$e_category= $_GET['e_category'];
//$s_name= $_GET['s_name'];

$custom_name= $_GET['custom_name'];
$call_person= $_GET['call_person'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-30');
}
$from_date = date('Y-m-d',strtotime($f_date));
$to_date = date('Y-m-d',strtotime($t_date));

if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}


if($custom_name != ""){
    $custom_nameSql= " AND customer_name LIKE '%".$custom_name."%'";
}
else{
    $custom_nameSql ="";
}
if($call_person != ""){
    $call_personSql= " AND meet_person LIKE '%".$call_person."%'";
}
else{
    $call_personSql ="";
}
$added = $_COOKIE['user_id'];
if($added == ''){
    $addBy='Super Admin';
}
else{
    $addBy = $added;
}

$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin' || $_COOKIE['role'] == 'Admin'){
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
    <title>Market Call-Tracking</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://erp.aecindia.net/includes/AEC.png">
    <link href="../../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../vendor/chartist/css/chartist.min.css">
    <link href="../../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../../../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../../css/style.css" rel="stylesheet">
    <link href="../../../vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../../../vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="../../../vendor/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="../../../vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="../../../vendor/pickadate/themes/default.date.css">
    <link href="../../../vendor/summernote/summernote.css" rel="stylesheet">
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
    $header_name ="Market Call-Tracking";
    Include ('../../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Market Call-Tracking</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Market Call-Tracking</h4>
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
                        <!--                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>-->
                        <!--                         <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
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
                                <th style="width: 9%;"><strong>Call Date</strong></th>
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Meet Person</strong></th>
                                <th><strong>Mobile</strong></th>
<!--                                <th><strong>Communication Through</strong></th>-->
                                 <th><strong>Next Follow Date</strong></th>
                                <th><strong>Notes</strong></th>
                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
                            //  $sql = "SELECT * FROM market ORDER BY id  LIMIT $start,10";
                            $currentDate = date('Y-m-d');
                            if($call_person == "" && $custom_name == "") {
                                //   $sql = "SELECT * FROM market WHERE visit_date  BETWEEN '$from_date' AND '$to_date'$addedBy ORDER BY id  LIMIT $start,10";
                                   $sql = "SELECT * FROM marketing WHERE `type` = 'call' $custom_nameSql$call_personSql ORDER BY visit_date DESC LIMIT $start,10";
                              }
                            else{
                            //  $sql = "SELECT * FROM market WHERE visit_date  BETWEEN '$from_date' AND '$to_date' $product_idSql$addedBy ORDER BY id  LIMIT $start,10";
                              $sql = "SELECT * FROM marketing WHERE `type` = 'call' AND visit_date  BETWEEN '$from_date' AND '$to_date'$custom_nameSql$call_personSql ORDER BY visit_date  LIMIT $start,10";
                                 }
                            //    $sql = "SELECT * FROM marketing WHERE `type` = 'call' ORDER BY visit_date DESC LIMIT $start,10";

                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $added_by = $row['added_by'];
                            $sqlUser = "SELECT * FROM `user` WHERE `user_id`='$added_by'";
                            $resUser = mysqli_query($conn, $sqlUser);
                            $rowUser = mysqli_fetch_assoc($resUser);
                            $User_name =  $rowUser['user_name'];
                            if($User_name == ''){
                                $user='Super Admin';
                            }
                            else{
                                $user = $User_name;
                            }
                            $v_date = $row['visit_date'];
                            $visit_date = date('d-m-Y', strtotime($v_date));
                            $n_date = $row['next_date'];
                            $next_date = date('d-m-Y', strtotime($n_date));
                            $customer_name=$row['customer_name'];

                            if($row['payment_status'] == 'paid'){
                                $statColor = 'success';
                                $statCont = 'Paid';
                            }
                            else {
                                $statColor = 'danger';
                                $statCont = 'UnPaid';
                            }
                            if($row['mobile'] == ''){
                                $mobile='NA';
                            }
                            else{
                                $mobile = $row['mobile'];
                            }
                            if($row['added_by'] == ''){
                                $user_name='Super Admin';
                            }
                            else{
                                $assigned_to = $row['added_by'];
                                $sqlCustomer = "SELECT * FROM `user` WHERE `user_id`='$assigned_to'";
                                $resCustomer = mysqli_query($conn, $sqlCustomer);
                                $rowCustomer = mysqli_fetch_assoc($resCustomer);
                                $user_name =  $rowCustomer['f_name'];
                            }
                            if($row['notes'] == ''){
                                $notes='NA';
                            }
                            else{
                                $notes = $row['notes'];
                            }
                            if($row['communication'] == ''){
                                $communication='NA';
                            }
                            else{
                                $communication = $row['communication'];
                            }
                            ?>

                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td> <?php echo $visit_date?> </td>
                                <td> <?php echo $customer_name?> </td>
                                <td> <?php echo $row['meet_person']?> </td>
                                <td> <?php echo $mobile?> </td>
<!--                                <td> --><?php //echo $communication?><!-- </td>-->
                                <td> <?php echo $next_date?> </td>
                                <td> <?php echo $notes?> </td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['marketing_id'];?>')">Edit</a>
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin' || $_COOKIE['role'] == 'Market') {
                                                ?>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&call_person=<?php echo $call_person ?>&custom_name=<?php echo $custom_name ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }
                                    $currentDate = date('Y-m-d');
                                    if($call_person == "" && $custom_name == "") {
                                        //   $sql = "SELECT * FROM market WHERE visit_date  BETWEEN '$from_date' AND '$to_date'$addedBy ORDER BY id  LIMIT $start,10";
                                        $sql = "SELECT COUNT(id) as count FROM marketing WHERE `type` = 'call' $custom_nameSql$call_personSql ORDER BY visit_date DESC";
                                    }
                                    else{
                                        //  $sql = "SELECT * FROM market WHERE visit_date  BETWEEN '$from_date' AND '$to_date' $product_idSql$addedBy ORDER BY id  LIMIT $start,10";
                                        $sql = "SELECT COUNT(id) as count FROM marketing WHERE `type` = 'call' AND visit_date  BETWEEN '$from_date' AND '$to_date'$custom_nameSql$call_personSql ORDER BY visit_date DESC";
                                    }
                                    //                                    $sql = 'SELECT COUNT(id) as count FROM market';
                                    //                                    if($product_id == "") {
//                                    $sql = "SELECT COUNT(id) as count FROM market WHERE next_follow > '$currentDate'";
                                    //                                    }
                                    //                                    {
                                    //                                        $sql = "SELECT COUNT(id) as count FROM market WHERE visit_date  BETWEEN '$from_date' AND '$to_date' $product_idSql$addedBy ";
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
                                                                                               href="?page_no=<?php echo $i ?>&call_person=<?php echo $call_person ?>&custom_name=<?php echo $custom_name ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><?php echo $i ?></a>
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
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&call_person=<?php echo $call_person ?>&custom_name=<?php echo $custom_name ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-greater-than"></i></a></li>
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
                        <h5 class="modal-title" id="title">Call Tracking</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="expense_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-6" id="v_date">
                                        <label>Call Date *</label>
                                        <input type="date" class="form-control" id="call_date" name="call_date" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="market_id" name="market_id">
                                    </div>
                                    <div class="form-group col-md-6" id="c_name">
                                        <label>Customer Name*</label>
                                        <select data-search="true" class="form-control" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black">
                                            <option value=""> Select Customer</option>
                                            <?php
                                            $sqlSupplier = "SELECT * FROM `market_profile`";
                                            $resultSupplier = mysqli_query($conn, $sqlSupplier);
                                            if (mysqli_num_rows($resultSupplier) > 0) {
                                                while ($rowSupplier = mysqli_fetch_array($resultSupplier)) {
                                                    ?>
                                                    <option
                                                        value='<?php echo $rowSupplier['customer_name']; ?>'><?php echo strtoupper($rowSupplier['customer_name']); ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="m_meet">
                                        <label>Call Person *</label>
                                        <input type="text" class="form-control" id="meet" name="meet" placeholder="Call Person" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="s_product">
                                        <label>Mobile *</label>
                                        <input type="number" class="form-control" placeholder="Mobile" id="mobile" name="mobile" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="s_next">
                                        <label>Communication Through</label>
                                        <select data-search="true" class="form-control js-example-disabled-results tail-select w-full" id="communication" name="communication" style="border-color: #181f5a;color: black">
                                            <option value="call"> Call</option>
                                            <option value="visit"> Visit</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="n_date">
                                        <label>Next Follow Date *</label>
                                        <input type="date" class="form-control" placeholder="Mobile" id="next_date" name="next_date" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12" id="s_duscuss">
                                        <label>Notes* </label>
                                        <textarea class="form-control" placeholder="Notes" id="notes" name="notes" style="border-color: #181f5a;color: black"></textarea>
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

    <?php Include ('../../../includes/footer.php') ?>
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
                                    <label> Call From Date </label>
                                    <input type="date"  class="form-control" id="f_date" name="f_date" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Call To Date </label>
                                    <input type="date"  class="form-control" id="t_date" name="t_date" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Customer Name </label>
                                    <input type="text"  class="form-control" placeholder="Customer Name" id="custom_name" name="custom_name" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Call Person </label>
                                    <input type="text"  class="form-control" placeholder="Call Person" id="call_person" name="call_person" style="border-color: #181f5a;color: black">
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


<script src="../../../vendor/global/global.min.js"></script>
<script src="../../../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../../../vendor/chart.js/Chart.bundle.min.js"></script>
<script src="../../../js/custom.min.js"></script>
<script src="../../../js/dlabnav-init.js"></script>
<script src="../../../vendor/owl-carousel/owl.carousel.js"></script>
<script src="../../../vendor/peity/jquery.peity.min.js"></script>
<!--<script src="../../../vendor/apexchart/apexchart.js"></script>-->
<script src="../../../js/dashboard/dashboard-1.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../../../js/plugins-init/jquery.validate-init.js"></script>
<script src="../../../vendor/moment/moment.min.js"></script>
<script src="../../../vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../../vendor/summernote/js/summernote.min.js"></script>
<script src="../../../js/plugins-init/summernote-init.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('next_date').setAttribute('min', today);
    });
    // Get the current date
    var currentDate = new Date();

    // Add one day to the current date
    currentDate.setDate(currentDate.getDate() + 1);

    // Get the year, month, and day of the next date
    var nextDateYear = currentDate.getFullYear();
    var nextDateMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    var nextDateDay = ('0' + currentDate.getDate()).slice(-2);

    // Format the next date in YYYY-MM-DD format
    var nextDate = nextDateYear + '-' + nextDateMonth + '-' + nextDateDay;

    // Set the minimum attribute of the visit_date input to the next date
    $('#plan_date').attr('min', nextDate);

    function addTitle() {
        $("#title").html("Add Call Tracking");
        $('#expense_form')[0].reset();
        $('#api').val("add.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Call-Tracking- "+data);
        $('#expense_form')[0].reset();
        $('#api').val("edit.php");

        $.ajax({
            type: "POST",
            url: "view.php",
            data: 'market_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {
                    $("#call_date").val(res.call_date);
                    $("#customer_name").val(res.customer_name);
                    $("#meet").val(res.meet_person);
                    $('#mobile').val(res.mobile);
                    $('#next_date').val(res.next_date);
                    $('#communication').val(res.communication);
                    $('#notes').val(res.notes);
                    $("#old_pa_id").val(res.market_id);
                    $("#market_id").val(res.market_id);


                    var edit_model_title = "Edit Call-Tracking- "+data;
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
    $("#expense_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                call_date: {
                    required: true
                },
                customer_name: {
                    required: true
                },
                meet: {
                    required: true
                },
                mobile: {
                    required: true
                },
                communication: {
                    required: true
                },
                next_date: {
                    required: true
                },
                notes: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                call_date: "*This field is required",
                customer_name: "*This field is required",
                meet: "*This field is required",
                mobile: "*This field is required",
                next_date: "*This field is required",
                communication: "*This field is required",
                notes: "*This field is required",
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
                        data: 'market_id='+data,
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
        $('#customer_name').val('<?php echo $product_id;?>');
        $('#t_date').val('<?php echo $t_date;?>');
        $('#f_date').val('<?php echo $f_date;?>');

    });

    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&market_id=<?php echo $market_id?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&market_id=<?php echo $market_id?>";
    });
</script>


</body>
</html>
