<?php Include("../../../includes/connection.php");
require_once '../../../includes/excel_generator/PHPExcel.php';
error_reporting(0);
$page= $_GET['page_no'];
$p_name= $_GET['p_name'];
$p_code= $_GET['p_code'];
$s_category= $_GET['s_category'];
$p_category= $_GET['p_category'];
$brand= $_GET['brand'];
if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}
if($p_name != ""){
//    $pNameSql= " AND product_name = '".$p_name."'";
    $pNameSql = " AND product_name LIKE '%" . $p_name . "%'";

}
else{
    $pNameSql ="";
}

if($p_code != ""){
//    $pCodeSql= " AND product_code = '".$p_code."'";
    $pCodeSql = " AND product_code LIKE '%" . $p_code . "%'";
}
else{
    $pCodeSql ="";
}

if($s_category != ""){
//    $categorySql= " AND sub_category = '".$s_category."'";
    $categorySql = " AND sub_category LIKE '%" . $s_category . "%'";
}
else{
    $categorySql ="";
}
if($p_category != ""){
//    $pcategorySql= " AND primary_category = '".$p_category."'";
    $pcategorySql = " AND primary_category LIKE '%" . $p_category . "%'";
}
else{
    $pcategorySql ="";
}

if($brand != ""){
//    $brandSql= "AND brand_type = '".$brand."'";
    $brandSql = " AND brand_type LIKE '%" . $brand . "%'";
}
else {
    $brandSql = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Supplier</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://bhims.ca/piloting/img/favicon_New.png">
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

    <!--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />-->
    <!--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
    <link rel="stylesheet" href="../../../vendor/select2/css/select2.min.css">

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
    $header_name ="Planner";
    Include ('../../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <!--                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>-->
                <!--                <li class="breadcrumb-item active"><a href="javascript:void(0)">Product</a></li>-->
                <li class="breadcrumb-item active"></li>
            </ol>

        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Supplier</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <!--                        <form class="form-inline">-->
                        <!---->
                        <!--                            <div class="form-group mx-sm-3 mb-2">-->
                        <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                        <!--                            </div>-->
                        <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        <!--                        </form>-->
                        <!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="justify-content: end">FILTER</button>-->

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Plan Date</strong></th>
                                <th><strong>Supplier Name</strong></th>
                                <th><strong>PO NO</strong></th>
                                <th><strong>Amount</strong></th>
                                <th><strong>Due Amount</strong></th>
                                <th><strong>Due Date</strong></th>
                                <th><strong>Next Follow Up Date</strong></th>

                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
                            //    $sql = "SELECT * FROM expense ORDER BY id  LIMIT $start,10";

                            //                            if($brand == "" && $p_code == "" && $s_category== "" && $p_category == ""&& $p_name == "") {
                            $sql = "SELECT * FROM purchase ORDER BY id DESC LIMIT $start,10";
                            //                            }
                            //                            else {
                            //                                $sql = "SELECT * FROM product WHERE id > 0 $brandSql$categorySql$pCodeSql$pNameSql$pcategorySql ORDER BY id  LIMIT $start,10";
                            //                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $d_date = $row['due_date']; // Assuming 'due_date' is the correct key in $row array
                            $next_date = date('Y-m-d', strtotime('+7 days', strtotime($d_date)));
                            $nd = date('d-m-Y', strtotime($next_date));
                            $due_date = date('Y-m-d', strtotime($d_date));
                            $dd = date('d-m-Y', strtotime($due_date));

                            $invoice=$row['purchase_id'];

                            $p_date=$row['plan_date'];
                            $plan_date = date('d-m-Y', strtotime($p_date));
                            if($plan_date == '30-11--0001'){
                                $plan_d= "NA";
                            }else{
                                $plan_d= $plan_date;
                            }

                            $supplier_id = $row['supplier'];
                            $grand_total = $row['grand_total'];


                            $sqlRequest = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                            $resRequest = mysqli_query($conn, $sqlRequest);
                            $rowRequest = mysqli_fetch_assoc($resRequest);
                            $supplier_name = $rowRequest['supplier_name'];
                            $due_date1 = $row['due_date'];

                            $due_date = date('d-m-Y', strtotime($due_date1));

                            $follow_date = date('d-m-Y', strtotime($due_date . ' + 7 days'));


                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td> <?php echo $plan_d?> </td>
                                <td> <?php echo $supplier_name?> </td>
                                <td> <?php echo $invoice?> </td>
                                <td> <?php echo $grand_total?> </td>
                                <td> <?php echo $grand_total?> </td>
                                <td> <?php echo $dd?> </td>
                                <td> <?php echo $nd?> </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                                                ?>
                                                <a class="dropdown-item" href="<?php echo $website; ?>/planner/accounts_planner/supplier/followup.php?supplier=<?php echo $rowRequest['supplier_name'];?>&po_no=<?php echo $row['purchase_id'] ?>" style="cursor: pointer" >Follow Up</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $supplier_name;?>','<?php echo $invoice;?>','<?php echo $grand_total;?>','<?php echo $d_date;?>','<?php echo $next_date;?>')">Edit</a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>

                                <?php

                                }}
                                ?>
                            </tr>

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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }
//                                    if($brand == "" && $p_code == "" && $s_category== "" && $p_category == ""&& $p_name == "") {
                                        $sql = "SELECT COUNT(id) as count FROM purchase";
//                                    }
//                                    else {
//                                        $sql = "SELECT COUNT(id) as count FROM product WHERE id > 0 $brandSql$categorySql$pCodeSql$pNameSql$pcategorySql";
//                                    }
                                    //                                    $sql = 'SELECT COUNT(id) as count FROM product;';
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
                                                                                               href="?page_no=<?php echo $i ?>"><?php echo $i ?></a>
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
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>"><i class="fa-solid fa-greater-than"></i></a></li>
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
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Product</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="credit_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label>Plan Date *</label>
                                        <input type="date" class="form-control" id="plan_date" name="plan_date" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="apis" name="apis">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="plan_id" name="plan_id">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Supplier Name *</label>
                                        <input type="text" class="form-control" placeholder="Customer Name" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>PO No *</label>
                                        <input type="text" class="form-control" placeholder="Invoice No" id="invoice_no" name="invoice_no" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Grand Total *</label>
                                        <input type="number" class="form-control" placeholder="Grand Total" id="grand_total" name="grand_total" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Due Amount*</label>
                                        <input type="number" class="form-control" placeholder="Due Amount" id="due_amount" name="due_amount" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Due Date *</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Next FollowUp Date *</label>
                                        <input type="date" class="form-control" id="next_due" name="next_due" style="border-color: #181f5a;color: black">
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
                                    <label>Product Name </label>
                                    <input type="text"  class="form-control" placeholder="Product Name" id="p_name" name="p_name" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Product Code </label>
                                    <input type="text"  class="form-control" placeholder="Product Code" id="p_code" name="p_code" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Primary Category *</label>
                                    <select  class="form-control" id="p_category" name="p_category" style="border-color: #181f5a;color: black">
                                        <option value=''>All</option>
                                        <option value='foundry'>Foundry</option>
                                        <option value='distributor'>Distributor</option>
                                        <option value='others'>Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Sub Category</label>
                                    <select  class="form-control" id="s_category" name="s_category" style="border-color: #181f5a;color: black">
                                        <option value=''>All</option>
                                        <?php
                                        $sqlSubCategory_id = "SELECT DISTINCT sub_category FROM `product`";
                                        $resultSubCategory_id = mysqli_query($conn, $sqlSubCategory_id);
                                        if (mysqli_num_rows($resultSubCategory_id) > 0) {
                                            while ($rowSubCategory_id = mysqli_fetch_array($resultSubCategory_id)) {

                                                $SubCategoryId =  $rowSubCategory_id['sub_category'];
                                                if (!empty($SubCategoryId)) {
                                                    $sqlSubCategory = "SELECT * FROM `category` WHERE `category_id`='$SubCategoryId'";
                                                    $resSubCategory = mysqli_query($conn, $sqlSubCategory);
                                                    $rowSubCategory = mysqli_fetch_assoc($resSubCategory);
                                                    $SubCategory = $rowSubCategory['sub_category'];
                                                    ?>
                                                    <option
                                                            value='<?php echo $SubCategoryId; ?>'><?php echo strtoupper($SubCategory); ?></option>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Brand</label>
                                    <select  class="form-control" id="brand" name="brand" style="border-color: #181f5a;color: black">
                                        <option value=''>All</option>
                                        <?php
                                        $sqlBrand_id = "SELECT DISTINCT brand_type FROM `product`";
                                        $resultBrand_id = mysqli_query($conn, $sqlBrand_id);
                                        if (mysqli_num_rows($resultBrand_id) > 0) {
                                            while ($rowBrand_id = mysqli_fetch_array($resultBrand_id)) {

                                                $Brand_id =  $rowBrand_id['brand_type'];

                                                $sqlBrandName = "SELECT * FROM `brand` WHERE `brand_id`='$Brand_id'";
                                                $resBrandName = mysqli_query($conn, $sqlBrandName);
                                                $rowBrandName = mysqli_fetch_assoc($resBrandName);
                                                $BrandName =  $rowBrandName['brand_name'];
                                                ?>
                                                <option
                                                        value='<?php echo $Brand_id; ?>'><?php echo strtoupper($BrandName); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
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
<!--<script src="../vendor/apexchart/apexchart.js"></script>-->
<script src="../../../js/dashboard/dashboard-1.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../../../js/plugins-init/jquery.validate-init.js"></script>
<script src="../../../vendor/moment/moment.min.js"></script>
<script src="../../../vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../../vendor/summernote/js/summernote.min.js"></script>
<script src="../../../js/plugins-init/summernote-init.js"></script>

<script src="../../../vendor/select2/js/select2.full.min.js"></script>
<script src="../../../js/plugins-init/select2-init.js"></script>
<script>

    const product_varient = document.getElementById('product_varients');
    function stickyheaddsadaer(obj) {
        if($(obj).is(":checked")){
            product_varient.style.display = 'block';
            document.getElementById('product_varient').classList.remove('ignore');
        }
        else if ($(obj).is(":not(:checked)")) {
            product_varient.style.display = 'none';
            document.getElementById('product_varient').className +=" ignore";
        }
    }


    function addTitle() {
        $("#title").html("Add Product");
        $('#credit_form')[0].reset();
        $('#apis').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(c_name, invoice, grand, due, next_due) {
        $("#title").html("Edit Supplier Plan");
        $('#credit_form')[0].reset();
        $('#apis').val("edit_plan.php");
        $('#customer_name').val(c_name);
        $('#invoice_no').val(invoice);
        $('#grand_total').val(grand);
        $('#due_amount').val(grand);
        $('#due_date').val(due);
        $('#next_due').val(next_due);
        $('#career_list').modal('show');
    }
    function formatDate(date) {
        var formatted_date = new Date(date);
        var year = formatted_date.getFullYear();
        var month = ('0' + (formatted_date.getMonth() + 1)).slice(-2);
        var day = ('0' + formatted_date.getDate()).slice(-2);
        return year + '-' + day + '-' + month;
    }

    //to validate form
    $("#credit_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                plan_date: {
                    required: true
                },
                customer_name: {
                    required: true
                },
                invoice_no: {
                    required: true
                },
                grand_total: {
                    required: true
                },
                due_amount: {
                    required: true
                },
                due_date: {
                    required: true
                },
                next_due: {
                    required: true
                },

            },
            // Specify validation error messages
            messages: {
                plan_date: "*This field is required",
                customer_name: "*This field is required",
                invoice_no: "*This field is required",
                grand_total: "*This field is required",
                due_amount: "*This field is required",
                due_date: "*This field is required",
                next_due: "*This field is required",
                product_unit: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {

        $("#credit_form").valid();

        if($("#credit_form").valid()==true) {

            var api = $('#apis').val();
            var form = $("#credit_form");
            var formData = new FormData(form[0]);
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({
                type: "POST",
                url: api,
                data: formData,
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
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
                        data: 'product_id='+data,
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
        $("#sub_category").trigger("change");

        $("#primary_category").change(function(){
            var primary_category = this.value;
            // alert(this.value);
            $("#sub_category option").remove();
            if(primary_category != ''){
                primary_category_fun(primary_category);
            }
        });
    });



    //to validate form
    $("#itemProfileExcel").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {

                file: {
                    required: true
                },

            }
            // Make sure the form is submitted to the destination defined
        });

    $(document).on("click", ".btnn_excel_ajax", function () {

        $("#itemProfileExcel").valid();
        if($("#itemProfileExcel").valid()==true) {
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            var file_data = $('#excel_file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: 'excel_insert.php', // point to server-side PHP script
                dataType: 'json',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
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
                                window.window.location.reload();
                            });

                    }
                },
                error: function () {
                    Swal.fire("Check your network connection");
                    document.getElementById("btnn_excel_ajax").disabled = false;
                    document.getElementById("btnn_excel_ajax").innerHTML = 'Upload';
                }

            });
        }
        else {
            document.getElementById("btnn_excel_ajax").disabled = false;
            document.getElementById("btnn_excel_ajax").innerHTML = 'Upload';

        }


    });

    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&p_name=<?php echo $p_name?>&p_code=<?php echo $p_code?>&p_category=<?php echo $p_category?>&s_category=<?php echo $s_category?>&brand=<?php echo $brand?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&p_name=<?php echo $p_name?>&p_code=<?php echo $p_code?>&p_category=<?php echo $p_category?>&s_category=<?php echo $s_category?>&brand=<?php echo $brand?>";
    });
    //select search
    var $disabledResults = $(".js-example-disabled-results");
    $disabledResults.select2();
</script>
<script>


    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&p_name=<?php echo $p_name?>&p_code=<?php echo $p_code?>&p_category=<?php echo $p_category?>&s_category=<?php echo $s_category?>&brand=<?php echo $brand?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&p_name=<?php echo $p_name?>&p_code=<?php echo $p_code?>&p_category=<?php echo $p_category?>&s_category=<?php echo $s_category?>&brand=<?php echo $brand?>";
    });
    //select search
    var $disabledResults = $(".js-example-disabled-results");
    $disabledResults.select2();
</script>


</body>
</html>
