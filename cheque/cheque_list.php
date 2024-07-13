<?php Include("../includes/connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);
$page= $_GET['page_no'];
$cheque_id= $_GET['cheque_id'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];
$b_name = $_GET['b_name'];
$acc_no = $_GET['acc_no'];
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
if($c_no != ""){
    $c_noSql= " AND category_id = '".$c_no."'";

}
else{
    $c_noSql ="";
}

if($b_name != ""){
    $b_nameSql= " AND primary_category = '".$b_name."'";

}
else{
    $b_nameSql ="";
}

if($acc_no != ""){
    $acc_noSql= " AND sub_category = '".$acc_no."'";

}
else{
    $acc_noSql ="";
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
    <title>Cheque List</title>

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

    $header_name ="Cheque List";
    Include ('../includes/header.php') ?>



    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
<!--                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>-->
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Cheque List</a></li>


            </ol>

        </div>


        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">

                        </form>
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>-->
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
<!--                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>-->
                        <!--                        <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
<!--                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>-->
<!--            </span>Excel</button>-->
                        <!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" style="margin-left: 20px;" onclick="addTitle()">ADD</button>-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <!--                                <th><strong>Cheque Id</strong></th>-->
                                <th><strong>Cheque No</strong></th>
                                <th><strong>Account No</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>Due Date</strong></th>
                                <th><strong>Customer Name</strong></th>
                                <th><strong> Cheque Status</strong></th>
                                <th><strong> Cheque Usage</strong></th>
                                <th><strong> View</strong></th>


                            </tr>
                            </thead>
                            <?php
//                            if($acc_no == "" && $b_name == "" && $c_no == "") {
//                                $sql = "SELECT * FROM cheque_list ORDER BY id LIMIT $start,10";
//                            }
//                            else {
//                                $sql = "SELECT * FROM cheque_list WHERE id>0 AND '$to_date' $acc_noSql$b_nameSql$c_noSql ORDER BY id  LIMIT $start,10";
//                            }
                            $sql = "SELECT * FROM cheque_list WHERE cheque_id = '$cheque_id' ORDER BY id LIMIT $start,10";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            //$date = $row['dob'];
                            //$dates = date('d-F-Y', strtotime($date));

                            //   $career_dates =   $row['career_date'];
                            //   $career_date =   date('d-F-Y');

                            if($row['cheque_used'] == ''){
                                $cheque_used = 'NA';

                            }
                            else {
                                $cheque_used = $row['cheque_used'];
                            }

                            $sqlPurchase = "SELECT * FROM `purchase` WHERE `purchase_id`='$cheque_used'";
                            $resPurchase = mysqli_query($conn, $sqlPurchase);
                            $rowPurchase = mysqli_fetch_assoc($resPurchase);
                            $supplier_id =  $rowPurchase['supplier'];
                            $purchase_date =  $rowPurchase['purchase_date'];
                            $p_date = date('d-m-Y', strtotime($purchase_date));
                            $due_date =  $rowPurchase['due_date'];
                            $d_date = date('d-m-Y', strtotime($due_date));

                            $sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                            $resSupplier = mysqli_query($conn, $sqlSupplier);
                            $rowSupplier = mysqli_fetch_assoc($resSupplier);
                            $supplier_name =  $rowSupplier['supplier_name'];

                            $branchId = $row['branch_name'];
                            if($row['cheque_status'] == 1){
                                $statColors = 'danger';
                                $statConts = 'USED';
                            }
                            else {
                                $statColors = 'success';
                                $statConts = 'NOT USED';
                            }
                            $cs_name = $row['cs_name'];

                            $p_date = $row['purchase_date'];
                            $purchase_date = date('d-m-Y', strtotime($p_date));
                            $d_date = $row['due_date'];
                            $due_date = date('d-m-Y', strtotime($d_date));

                            if($purchase_date=='30-11--0001'){
                                $pp_date = 'NA';
                            }
                            else{
                                $pp_date = $purchase_date;
                            }
                            if($due_date=='30-11--0001'){
                                $dd_date = 'NA';
                            }
                            else{
                                $dd_date = $due_date;
                            }
                            if($cs_name==''){
                                $c_name = 'NA';
                            }
                            else{
                                $c_name = $cs_name;
                            }
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td> <?php echo $row['cheque_no']?> </td>
                                <td> <?php echo $row['acc_no']?> </td>
                                <td> <?php echo $pp_date?> </td>
                                <td> <?php echo $dd_date?> </td>
                                <td> <?php echo $c_name?> </td>
                                <td> <span class="badge badge-pill badge-<?php echo $statColors?>"><?php echo $statConts?></span>
                                </td>
                                <td> <?php echo $cheque_used?> </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="<?php echo $website?>/cheque/cheque_view.php?cheque_list_id=<?php echo $row['cheque_list_id']?>&cheque_id=<?php echo $cheque_id?>"
                                           class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-eye"></i></a>
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
                                                                                href="?page_no=<?php echo 1 ?>&cheque_id=<?php echo $cheque_id ?>"><i class="fa-solid fa-angles-left"></i></a></li>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&cheque_id=<?php echo $cheque_id ?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }

//                                    if($acc_no == "" && $b_name == "" && $c_no == "") {
//                                        $sql = "SELECT COUNT(id) as count FROM cheque_list";
//                                    }
//                                    else {
//                                        $sql = "SELECT COUNT(id) as count FROM cheque_list  WHERE id>0 AND '$to_date' $acc_noSql$b_nameSql$c_noSql";
//                                    }
                                    $sql = "SELECT COUNT(id) as count FROM cheque_list WHERE cheque_id = '$cheque_id'";
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
                                                                                               href="?page_no=<?php echo $i ?>&cheque_id=<?php echo $cheque_id ?>"><?php echo $i ?></a>
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
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&cheque_id=<?php echo $cheque_id ?>"><i class="fa-solid fa-greater-than"></i></a></li>
                                            <?php
                                        }
                                        if($nextPage<$pageFooter)
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $pageFooter ?>&cheque_id=<?php echo $cheque_id ?>"><i class="fa-solid fa-angles-right"></i></a></li>
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
                        <h5 class="modal-title" id="title">>User Role Details</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="staff_form" autocomplete="off">
                                <div class="form-row">
                                    <?php
                                    $sqlRepay = "SELECT id FROM cheque_list ORDER BY id DESC";
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
                                    $cid='CH'.($conID);
                                    ?>
                                    <div class="form-group col-md-12">
                                        <label>Bank Name *</label>
                                        <input type="text" class="form-control" placeholder="Bank Name" id="bank_name" name="bank_name" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="cheque_id" name="cheque_id">
                                        <input type="hidden" class="form-control"  id="c_id" name="c_id" value="<?php echo $cid?>">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Account No *</label>
                                        <input type="text" class="form-control" placeholder="Account No" id="acc_no" name="acc_no" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Cheque From No *</label>
                                        <input type="number" class="form-control" placeholder="Cheque From No" id="from_no" name="from_no" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Cheque To No *</label>
                                        <input type="number" class="form-control" placeholder="Cheque To No" id="to_no" name="to_no" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Notes </label>
                                        <input type="text" class="form-control" placeholder="Notes" id="note" name="note" style="border-color: #181f5a;color: black">
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
    </div>
    <?php Include ('../includes/footer.php') ?>
</div>
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


                            <div class="form-group col-md-6">
                                <label>Cheque No </label>
                                <input type="text"  class="form-control" placeholder="Cheque No" id="c_no" name="c_no" style="border-color: #181f5a;color: black">
                            </div>
                            <div class="form-group col-md-6">
                                <label>From Date </label>
                                <input type="date"  class="form-control" id="f_date" name="f_date" style="border-color: #181f5a;color: black">
                            </div>
                            <div class="form-group col-md-12">
                                <label>To Date </label>
                                <input type="date"  class="form-control" id="t_date" name="t_date" style="border-color: #181f5a;color: black">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Bank Name </label>
                                <input type="text"  class="form-control" placeholder="Bank Name" id="b_name" name="b_name" style="border-color: #181f5a;color: black">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Account No </label>
                                <input type="text"  class="form-control" placeholder="Account No" id="acc_no" name="acc_no" style="border-color: #181f5a;color: black">
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
</div></div>

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

    function addTitle() {
        $("#title").html("Add Cheque");
        $('#staff_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Cheque- "+data);
        $('#staff_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({

            type: "POST",
            url: "view_api.php",
            data: 'cheque_no='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {

                    $("#bank_name").val(res.bank_name);
                    $("#acc_no").val(res.acc_no);
                    $("#from_no").val(res.from_number);
                    $("#to_no").val(res.to_number);
                    $("#note").val(res.notes);
                    $("#old_pa_id").val(res.cheque_id);
                    $("#cheque_id").val(res.cheque_id);


                    var edit_model_title = "Edit Cheque - "+data;
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
                bank_name: {
                    required: true
                },
                acc_no: {
                    required: true
                },
                from_no: {
                    required: true
                },
                to_no: {
                    required: true
                },

            },
            // Specify validation error messages
            messages: {
                bank_name: "*This field is required",
                acc_no: "*This field is required",
                from_no: "*This field is required",
                to_no: "*This field is required",
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
                        data: 'cheque_no='+data,
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

    $( document ).ready(function() {
        $('#search').val('<?php echo $search;?>');
        $('#branch_nameS').val('<?php echo $branch_nameS;?>');

    });
    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&c_no=<?php echo $c_no?>&b_name=<?php echo $b_name?>&acc_no=<?php echo $acc_no?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&c_no=<?php echo $c_no?>&b_name=<?php echo $b_name?>&acc_no=<?php echo $acc_no?>";
    });
</script>

</body>
</html>
