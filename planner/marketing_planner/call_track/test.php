<?php Include("../../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");
$callDate = date('Y-m-d');
error_reporting(0);
$page= $_GET['page_no'];
//$market_id= $_GET['market_id'];
//$e_category= $_GET['e_category'];
//$s_name= $_GET['s_name'];

$customer_name= $_GET['product_id'];
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


if($customer_name != ""){
    $customer_nameSql= " AND customer_name LIKE '%".$customer_name."%'";
}
else{
    $customer_nameSql ="";
}


$added_by = $_COOKIE['user_id'];
$user_name = $_COOKIE['user_name'];
if($user_name == ''){
    $user = 'Super Admin';
}
else{
    $user = $user_name;
}
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
    <title>Market Call Tracking</title>
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
    $header_name ="Market Call Tracking";
    Include ('../../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Market Call Tracking</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Market Call Tracking</h4>
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
                                <th><strong>Meet Person</strong></th>
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Mobile </strong></th>
                                <th><strong>Next FollowUp Date</strong></th>
                                <th><strong>Communication Through</strong></th>
                                <th><strong>Notes</strong></th>
<!--                                <th><strong>Action</strong></th>-->
                            </tr>
                            </thead>
                            <?php
                            $currentDate = date('Y-m-d');
                            if($customer_name == "" && $f_date !="" && $t_date !="") {
                                $sql = "SELECT * FROM call_track ORDER BY id DESC LIMIT $start,10";
                            }
                            else if ($customer_name != "" && $f_date !="" && $t_date !=""){
                                $sql = "SELECT * FROM call_track where call_date BETWEEN '$f_date' AND '$t_date' AND  customer_name = '$customer_name' GROUP BY customer_id  ORDER BY id DESC LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result))  {

                            $sNo++;
                            $customer_id =$row["customer_id"];
                            $call_track_id =$row["call_track_id"];
                            $customer_name =$row["customer_name"];
                            $notes =$row["notes"];
                            $communication =$row["communication"];
                            $mobile =$row["mobile"];
                            $meet_whom =$row["meet_whom"];
                            $call_date =$row["call_date"];
                            $c_date = date('d-m-Y', strtotime($call_date));
                            $next_date =$row["next_date"];
                            $n_date = date('d-m-Y', strtotime($next_date));
                            $sqlCustomer = "SELECT * FROM `customer` WHERE `customer_id`='$customer_id'";
                            $resCustomer = mysqli_query($conn, $sqlCustomer);
                            $rowCustomer = mysqli_fetch_assoc($resCustomer);
                            $customer_names =  $rowCustomer['customer_name'];
                            ?>

                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td> <?php echo $c_date?> </td>
                                <td> <?php echo $meet_whom?> </td>
                                <td> <?php echo $customer_name?> </td>
                                <td> <?php echo $mobile?> </td>
                                <td> <?php echo $n_date?> </td>
                                <td> <?php echo $communication?> </td>
                                <td> <?php echo $notes?> </td>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&product_id=<?php echo $product_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }
                                    if($customer_name == "" && $f_date !="" && $t_date !="") {
                                        $sql = "SELECT COUNT(id) as count FROM call_track ";
                                    }
                                    else if ($customer_name != "" && $f_date !="" && $t_date !=""){
                                        $sql = "SELECT COUNT(id) as count FROM call_track where call_date BETWEEN '$f_date' AND '$t_date' AND  customer_name = '$customer_name'";
                                    }
                                    //                                    $sql = 'SELECT COUNT(id) as count FROM market';
                                    //                                    if($product_id == "") {
//                                 //   $sql = "SELECT COUNT(id) as count FROM market WHERE next_follow > '$currentDate'";
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
                                                                                               href="?page_no=<?php echo $i ?>&product_id=<?php echo $product_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><?php echo $i ?></a>
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
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&product_id=<?php echo $product_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-greater-than"></i></a></li>
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

                                    <div class="form-group col-md-6" id="v_date">
                                        <label>Call Date *</label>
                                        <input type="date" class="form-control" id="visit_date" name="visit_date" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="market_id" name="market_id">
                                    </div>
                                    <div class="form-group col-md-6" id="m_meet">
                                        <label>Meet Person *</label>
                                        <input type="text" class="form-control" id="meet" name="meet" placeholder="Meet Person" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12" id="c_name">
                                        <label>Customer Name *</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="s_product">
                                        <label>Mobile *</label>
                                        <input type="number" class="form-control" placeholder="Mobile" id="mobile" name="mobile" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="s_qty">
                                        <label>Added By*</label>
                                        <input type="text" class="form-control" placeholder="Added By" id="assigned" name="assigned" value="<?php echo $user ?>" readonly style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="c_through">
                                        <label>Communication Through</label>
                                        <select data-search="true" class="form-control js-example-disabled-results tail-select w-full" id="communication" name="communication" style="border-color: #181f5a;color: black">
                                            <option value="visit"> Visit</option>
                                            <option value="call"> Call</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="s_next">
                                        <label>Next FollowUp Date*</label>
                                        <input type="date" class="form-control" id="next_date" name="next_date" style="border-color: #181f5a;color: black">
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
        <div class="modal fade" id="call_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titles">Call Tracking</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="call_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-12" id="payment_date">
                                        <label>Call Date *</label>
                                        <input type="date" class="form-control" id="call_date" name="call_date" min="<?php echo $currentDate?>" max="<?php echo $currentDate?>" value="<?php echo $currentDate?>" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="next" name="next">
                                        <input type="hidden" class="form-control"  id="apiis" name="apiis">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="call_id" name="call_id">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Customer Name *</label>
                                        <input type="text" class="form-control" id="customer" name="customer" readonly style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="customer_id" name="customer_id">
                                    </div>
                                    <div class="form-group col-md-12" id="Notess">
                                        <label>Notes *</label>
                                        <textarea class="form-control" placeholder="Note" id="note" name="note" readonly style="border-color: #181f5a; color: black"></textarea>

                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Next FollowUp Date*</label>
                                        <input type="date" class="form-control" id="next_date" name="next_date" style="border-color: #181f5a;color: black">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="call_btn">ADD</button>
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
                                    <input type="text"  class="form-control" placeholder="Customer Name" id="product_id" name="product_id" style="border-color: #181f5a;color: black">
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
    $('#next_date').attr('min', nextDate);

    function addTitle() {
        $("#title").html("Add Market");
        $('#expense_form')[0].reset();
        $('#api').val("add.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Market- "+data);
        $('#expense_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'market_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {
                    $("#visit_date").val(res.next_follow);
                    $("#customer_name").val(res.customer_name);
                    $("#meet").val(res.meet_whom);
                    $('#mobile').val(res.mobile);
                    $('#assigned').val(res.assigned_to);
                    $('#communication').val(res.communication);
                    $('#notes').val(res.notes);
                    $('#next_date').val(res.next_date);
                    $("#old_pa_id").val(res.market_id);
                    $("#market_id").val(res.market_id);


                    var edit_model_title = "Edit Market - "+data;
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
                visit_date: {
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
                assigned: {
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
                visit_date: "*This field is required",
                customer_name: "*This field is required",
                meet: "*This field is required",
                mobile: "*This field is required",
                assigned: "*This field is required",
                next_date: "*This field is required",
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

    function repayment(customerName, notes,callDate,next) {
        $('#call_list').modal('show');
        $("#titles").html("Call Details");
        $('#call_form')[0].reset();
        $('#customer').val(customerName);
        $('#note').val(notes);
        $('#call_date').val(callDate);
        $('#next_dates').val(next);

    }

    //add data
    $('#call_btn').click(function () {
        $("#call_form").valid();
        if($("#call_form").valid()==true) {
            var api = $('#apiis').val();
            //var loan_id = "<?php //echo $loan_id?>//";
            // var loan_id = 56
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "call_api.php",
                data: $('#call_form').serialize(),
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

                                document.getElementById("call_btn").disabled = false;
                                document.getElementById("call_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("call_btn").disabled = false;
                    document.getElementById("call_btn").innerHTML = 'Add';
                }

            });

        } else {
            document.getElementById("call_btn").disabled = false;
            document.getElementById("call_btn").innerHTML = 'Add';

        }

    });


    //to validate form
    $("#call_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                notes: {
                    required: true
                },

            },
            // Specify validation error messages
            messages: {
                notes: "*This field is required",
                pay_made: "*This field is required",
                repayment_mode: "*This field is required",
                ref_no: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
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
        $('#product_id').val('<?php echo $product_id;?>');
        $('#t_date').val('<?php echo $t_date;?>');
        $('#f_date').val('<?php echo $f_date;?>');

    });

    $(document).on("click", ".excel_download", function () {
        //window.location.href = "excel_download.php?&product_id=<?php //echo $customer_name?>//&f_date=<?php //echo $f_date?>//&t_date=<?php //echo $t_date?>//";
        window.location.href = "excel_download_test.php?&product_id=<?php echo $customer_name?>&f_date=<?php echo $f_date?>&t_date=<?php echo $t_date?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&market_id=<?php echo $market_id?>";
    });
</script>


</body>
</html>
