<?php Include("../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);
$page= $_GET['page_no'];
$search= $_GET['search'];
$branch_nameS= $_GET['branch_nameS']== ''?"all":$_GET['branch_nameS'];

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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>All Intent profile</title>

    <link rel="icon" type="image/png" sizes="16x16" href="../../images/favicon_New.png">
    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../vendor/chartist/css/chartist.min.css">

    <link href="../../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

    $header_name ="All Intent";
    Include ('../../includes/header.php') ?>



    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">All Intent</a></li>


            </ol>

        </div>


        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Intent List</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">

                            <div class="form-group mx-sm-3 mb-2">

                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                        </form>
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" style="margin-left: 20px;" onclick="addTitle()">ADD</button>-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>Intent Id</strong></th>
                                <th><strong>Supplier</strong></th>

                                <th><strong>ACTION</strong></th>

                            </tr>
                            </thead>
                            <?php
                            if($search == "") {
                                $sql = "SELECT * FROM purchase ORDER BY id DESC LIMIT $start,10";
                            }
                            else {
                                $sql = "SELECT * FROM purchase WHERE supplier_name LIKE '%$search%'ORDER BY id  LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $supplier_id=$row['supplier'];

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

                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $row['purchase_date']?></td>
                                <td> <?php echo $row['purchase_id']?> </td>
                                <td> <?php echo $Supplier?> </td>
<!--                                <td> --><?php //echo $row['grand_total']?><!-- </td>-->
<!--                                <td> --><?php //echo '0'?><!-- </td>-->
<!--                                <td> --><?php //echo $row['grand_total']?><!-- </td>-->
<!--                                <td> <span class="badge badge-pill badge---><?php //echo $statColor?><!--">--><?php //echo $statCont?><!--</span>-->


                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">

                                            <a class="dropdown-item" href="https://aec.codesen.in/quotation/edit.php?purchase_id=<?php echo $row['purchase_id']?>" style="cursor: pointer" onclick="editTitle('<?php echo $row['purchase_id'];?>')">Edit</a>


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
                                                                                href="?page_no=<?php echo 1 ?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i
                                                    class="la la-angle-double-left"  style="padding-top: 9px;"></i></a></li>
                                        <?php
                                    }

                                    if($prevPage==0)
                                    {
                                        ?>
                                        <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="la la-angle-left"></i></a></li>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="la la-angle-left"></i></a></li>
                                        <?php
                                    }
                                    if($search == "") {
                                        $sql = "SELECT COUNT(id) as count FROM purchase";
                                    }
                                    else {
                                        $sql = "SELECT COUNT(id) as count FROM purchase WHERE supplier_name LIKE '%$search%'";
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
                                            <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="la la-angle-right"></i></a></li>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="la la-angle-right"></i></a></li>
                                            <?php
                                        }
                                        if($nextPage<$pageFooter)
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $pageFooter ?>&search=<?php echo $search ?>&branch_nameS=<?php echo $branch_nameS ?>"><i class="la la-angle-double-right"></i></a></li>
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
                        <h5 class="modal-title" id="title">Intent  Details</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="staff_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-12">
                                        <label>Date *</label>
                                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="purchase_id" name="purchase_id">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Supplier *</label>
                                        <select data-search="true" class="form-control tail-select w-full" id="supplier" name="supplier" style="border-color: #181f5a;color: black">
                                            <?php
                                            $sqlSupplier = "SELECT * FROM `supplier`";
                                            $resultSupplier = mysqli_query($conn, $sqlSupplier);
                                            if (mysqli_num_rows($resultSupplier) > 0) {
                                                while ($rowSupplier = mysqli_fetch_array($resultSupplier)) {
                                                    ?>
                                                    <option
                                                        value='<?php echo $rowSupplier['unit_id']; ?>'><?php echo strtoupper($rowSupplier['base_unit']); ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Product Name *</label>
                                        <input type="text" class="form-control" placeholder="Product Name" id="product_name" name="product_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <!--                                    <div class="form-group col-md-12" style="font-size: 15px">-->
                                    <!--                                        <h5>Order items *</h5>-->
                                    <!--                                        <div class="table-responsive">-->
                                    <!--                                            <table class="table table-hover">-->
                                    <!--                                                <thead class="bg-gray-300">-->
                                    <!--                                                <tr><th scope="col">#</th>-->
                                    <!--                                                    <th scope="col">Product</th>-->
                                    <!--                                                    <th scope="col">Net Unit Cost</th>-->
                                    <!--                                                    <th scope="col">Stock</th>-->
                                    <!--                                                    <th scope="col">Qty</th>-->
                                    <!--                                                    <th scope="col">Discount</th>-->
                                    <!--                                                    <th scope="col">Tax</th>-->
                                    <!--                                                    <th scope="col">Subtotal</th>-->
                                    <!--                                                    <th scope="col" class="text-center"><i class="fa fa-trash"></i></th>-->
                                    <!--                                                </tr> </thead>-->
                                    <!--                                                <tbody>-->
                                    <!--                                                <tr><td>1</td>-->
                                    <!--                                                    <td><span>AEC-6</span> <br> <span class="badge badge-success">WHYTHEAT K  MAXWELL</span> <i class="i-Edit"></i></td> <td>₹ 1.000</td>-->
                                    <!--                                                    <td><span class="badge badge-outline-warning">0 Kg</span></td>-->
                                    <!--                                                    <td><div class="quantity"><div role="group" class="input-group">-->
                                    <!--                                    <div class="input-group-prepend"><span class="btn btn-primary btn-sm">-</span></div> <input min="0" class="form-control"> <div class="input-group-append"><span class="btn btn-primary btn-sm">+</span></div></div></div></td>-->
                                    <!--                                                    <td>₹ 0.00</td> <td>₹ 0.00</td> <td>₹ 1.00</td>-->
                                    <!--                                                    <td><a title="Delete" class="btn btn-icon btn-sm"><i class="i-Close-Window text-25 text-danger"></i></a></td></tr>-->
                                    <!--                                                </tbody>-->
                                    <!--                                            </table>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->
                                    <!--                                                <div class="offset-md-9 col-md-3 mt-4">-->
                                    <!--                                                <table class="table table-striped table-sm"><tbody><tr>-->
                                    <!--                                                <td class="bold">Order Tax</td> <td><span>₹ 0.00 (0.00 %)</span></td></tr> <tr>-->
                                    <!--                                                <td class="bold">Discount</td> <td>₹ 0.00</td></tr> <tr><td class="bold">Shipping</td>-->
                                    <!--                                                <td>₹ 0.00</td></tr> <tr><td><span class="font-weight-bold">Grand Total</span></td>-->
                                    <!--                                                <td><span class="font-weight-bold">₹ 1.00</span></td></tr></tbody></table>-->
                                    <!--                                                </div>-->
                                    <div class="form-group col-md-12">
                                        <label>Stock *</label>
                                        <input type="text" class="form-control" placeholder="Net Unit Cost" id="stock" name="stock" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Net Unit Cost *</label>
                                        <input type="text" class="form-control" placeholder="Net Unit Cost" id="unit_cost" name="unit_cost" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Quantity *</label>
                                        <input type="text" class="form-control" placeholder="Quantity" id="qty" name="qty" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Sub Total *</label>
                                        <input type="text" class="form-control" placeholder="Sub Total" id="sub_total" name="sub_total" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Discount *</label>
                                        <input type="text" class="form-control" placeholder="Discount" id="discount" name="discount" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Order Tax *</label>
                                        <input type="text" class="form-control" placeholder="Order Tax" id="order_tax" name="order_tax" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Shipping *</label>
                                        <input type="text" class="form-control" placeholder="Shipping" id="shipping" name="shipping" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Grand Total *</label>
                                        <input type="text" class="form-control" placeholder="Grand Total" id="grand_total" name="grand_total" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Payment Status *</label>
                                        <select data-search="true" class="form-control tail-select w-full" id="payment_status" name="payment_status" style="border-color: #181f5a;color: black">
                                            <option value=''>Select supplier</option>
                                            <option value='1'>Received</option>
                                            <option value='2'>Pending</option>
                                            <option value='3'>Odered</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Notes *</label>
                                        <textarea class="form-control" placeholder="Notes" id="notes" name="notes" rows="4" cols="50" style="border-color: #181f5a;color: black"></textarea>
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
    <?php Include ('../../includes/footer.php') ?>
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

    function addTitle() {
        $("#title").html("Add Purchase");
        $('#staff_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Purchase- "+data);
        $('#staff_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({

            type: "POST",
            url: "view_api.php",
            data: 'purchase_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {

                    $("#purchase_date").val(res.purchase_date);
                    $("#supplier").val(res.supplier);
                    $("#product_name").val(res.product_name);
                    $("#unit_cost").val(res.unit_cost);
                    $("#stock").val(res.stock);
                    $("#qty").val(res.qty);
                    $("#discount").val(res.discount);
                    $("#order_tax").val(res.order_tax);
                    $("#notes").val(res.notes);
                    $("#shipping").val(res.shipping);
                    $("#sub_total").val(res.sub_total);
                    $("#grand_total").val(res.grand_total);
                    $("#old_pa_id").val(res.purchase_id);
                    $("#purchase_id").val(res.purchase_id);
                    $("#payment_status").val(res.payment_status);


                    var edit_model_title = "Edit Purchase - "+data;
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
                date: {
                    required: true
                },
                // supplier: {
                //     required: true
                // },
                product_name: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                date: "*This field is required",
                supplier: "*This field is required",
                product_name: "*This field is required",
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

</script>

</body>
</html>
