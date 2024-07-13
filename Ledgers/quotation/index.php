
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
    <title>Intend profile</title>

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


    <link rel="stylesheet" href="../../vendor/select2/css/select2.min.css">

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
    body{
        font-size: 15px;
    }

    .productListUl {

        background: aliceblue;
        text-align: center;
        height: auto;
        max-height: 131px;
        overflow-y: scroll;

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

    $header_name ="Intend";
    Include ('../../includes/header.php') ?>



    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Intend</a></li>


            </ol>

        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <!--<h2>Loan list</h2>-->
                    <div style="display: flex;justify-content: flex-end;">

<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>-->
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
<!--                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>-->
<!--                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>-->
<!--            </span>Excel</button>-->
                    </div>
                </div>
                <div class="card-body">
                    <!--                    <div class="table-responsive">-->
                    <div class="col-lg-12">

                    </div>
                    <div class="basic-form" style="color: black;">
                        <form id="staff_form" autocomplete="off">
                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label> Intend Date *</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <input type="hidden" class="form-control"  id="api" name="api">
                                    <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                    <input type="hidden" class="form-control"  id="purchase_id" name="purchase_id">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Supplier </label>
                                    <select data-search="true" class="form-control tail-select w-full" id="supplier" name="supplier" style="border-color: #181f5a;color: black">
                                        <option value=""> Select Supplier</option>
                                        <?php
                                        $sqlSupplier = "SELECT * FROM `supplier`";
                                        $resultSupplier = mysqli_query($conn, $sqlSupplier);
                                        if (mysqli_num_rows($resultSupplier) > 0) {
                                            while ($rowSupplier = mysqli_fetch_array($resultSupplier)) {
                                                ?>
                                                <option
                                                        value='<?php echo $rowSupplier['supplier_id']; ?>'><?php echo strtoupper($rowSupplier['supplier_name']); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>

                                <!--                                <div class="form-group col-md-4">-->
                                <!--                                    <label>Amended date *</label>-->
                                <!--                                    <input type="date" class="form-control" id="amended_date" name="amended_date" style="border-color: #181f5a;color: black;text-transform: uppercase">-->
                                <!--                                </div>-->

                                <div class="form-group col-md-4">
                                    <label> Search Product *</label>
                                    <input type="text" class="form-control"  id="productName" name="productName" placeholder="Search Product" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <ul class="productListUl" id="productListUl" style="display: none">
                                    </ul>
                                </div>


                                <!--                                <div class="form-group col-md-6">-->
                                <!--                                    <label> Search Product *</label>-->
                                <!--                                    <input type="text" class="form-control"  id="productName" name="productName">-->
                                <!--                                    <ul class="productListUl" id="productListUl" style="display: none">-->
                                <!--                                    </ul>-->
                                <!---->
                                <!--                                </div>-->
                                <div class="form-group col-md-12" style="font-size: 15px">
                                    <h5>Order items *</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-gray-300">
                                            <tr><th scope="col">#</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Net Unit Cost</th>
<!--                                                <th scope="col">Stock</th>-->
                                                <th scope="col">Qty</th>
<!--                                                <th scope="col">Subtotal</th>-->
                                                <th scope="col">Delete</th>
                                                <!--                                                <th scope="col" class="text-center"><i class="fa fa-trash"></i></th>-->
                                            </tr>
                                            </thead>
                                            <tbody id="tb">

                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Added By</label>
                                    <input type="number" class="form-control" placeholder="Added By" id="discount" name="discount" onkeyup="this.value = fnc(this.value, 0, 100)" style="border-color: #181f5a;color: black">
                                </div>


                                <div class="form-group col-md-6">
                                    <label>Notes *</label>
                                    <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" style="border-color: #181f5a;color: black">
                                    <!--   <textarea class="form-control" placeholder="Notes" id="notes" name="notes" rows="4" cols="50" style="border-color: #181f5a;color: black"></textarea>-->
                                </div>

                            </div>
                        </form>
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

<script src="../../vendor/select2/js/select2.full.min.js"></script>
<script src="../../js/plugins-init/select2-init.js"></script>


<script>


    // $('#product_search').click(function () {
    $("#product_name").change(function(){

        let product_id = $("#product_name").val();
        console.log(product_id);
        // if(primary_category != ''){
        $.ajax({
            type: "POST",
            url: "product2_api.php",
            data: 'product_id=' + product_id,
            dataType: "json",
            success: function (res) {
                if (res.status == 'success') {

                    var product_id = res.product_id;
                    var product_name = res.product_name;
                    var product_price = res.product_price;
                    console.log(product_id);
                    console.log(product_name);
                    console.log(product_price);
                    // productDetails.append(product_name);
                    // productDetails.append(product_price);
                    // function tab(name,email){
                    const tb = document.getElementById("tb");
                    const tr = document.createElement("tr");

                    let productDetails = [];
                    productDetails.push(product_name);
                    productDetails.push(product_price);
                    const sNo = document.createElement("td");
                    sNo.innerHTML = '1';
                    tr.append(sNo);
                    tb.append(tr);
                    // let b = [name,email];
                    for(let i=0;i<2;i++){
                        const td = document.createElement("td");
                        td.innerHTML = productDetails[i];
                        tr.append(td);
                    }

                    tb.append(tr);
                    const stock = document.createElement("td");
                    stock.innerHTML = '2';
                    tr.append(stock);
                    tb.append(tr);

                    // const qty = document.createElement("td");
                    //                    // qty.innerHTML = '3';
                    //                    // tr.append(qty);
                    //                    // tb.append(tr);

                    const td = document.createElement('td');
                    const divQuantity = document.createElement('div');
                    divQuantity.classList.add('quantity');

                    const divInputGroup = document.createElement('div');
                    divInputGroup.setAttribute('role', 'group');
                    divInputGroup.classList.add('input-group');

                    const decreaseBtn = document.createElement('button');
                    decreaseBtn.classList.add('btn', 'btn-primary', 'btn-sm', 'decrease');
                    decreaseBtn.textContent = '-';
                    divInputGroup.appendChild(decreaseBtn);

                    const inputQuantity = document.createElement('input');
                    inputQuantity.innerHTML= 1;
                    inputQuantity.setAttribute('type', 'number');
                    inputQuantity.setAttribute('min', '0');
                    inputQuantity.classList.add('form-control', 'quantity-input');
                    divInputGroup.appendChild(inputQuantity);

                    const increaseBtn = document.createElement('button');
                    increaseBtn.classList.add('btn', 'btn-primary', 'btn-sm', 'increase');
                    increaseBtn.textContent = '+';
                    divInputGroup.appendChild(increaseBtn);

                    divQuantity.appendChild(divInputGroup);
                    td.appendChild(divQuantity);
                    tr.append(td);
                    tb.append(tr);

                    const discount = document.createElement("td");
                    discount.innerHTML = '4';
                    tr.append(discount);
                    tb.append(tr);

                    const tax = document.createElement("td");
                    tax.innerHTML = '5';
                    tr.append(tax);
                    tb.append(tr);

                    const total = document.createElement("td");
                    let quantity = $('.quantity-input').val('');
                    let sub_total = product_price * quantity;
                    sub_total.classList.add('sub_total');
                    total.innerHTML = sub_total;
                    tr.append(total);
                    tb.append(tr);

                    $('#product_name').val('');

                    // let grand_total = $('.sub_total').val('');

                    // }

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
            // error: function () {
            // },
            error: function () {
                Swal.fire("Check your network connection");
                // window.window.location.reload();
            }
        });
        // }

    });



    function addTitle() {
        $("#title").html("Add Intend");
        $('#staff_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Intend- "+data);
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


                    var edit_model_title = "Edit Intend - "+data;
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
                amended_date: {
                    required: true
                },
                material: {
                    required: true
                },
                transport: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                date: "*This field is required",
                // supplier: "*This field is required",
                product_name: "*This field is required",
                amended_date: "*This field is required",
                material: "*This field is required",
                transport: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {
        $("#staff_form").valid();
        if($("#staff_form").valid()==true) {
            // var api = $('#api').val();

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            $.ajax({

                type: "POST",
                url: "add_api.php",
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
                error: function (error) {
                    console.error("Error sending data:", error);
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

        $("#product_name").change(function(){
            $('#product_name').val(''); // Clears the value of the input field
        });

    });
    var $disabledResults = $(".js-example-disabled-results");
    $disabledResults.select2();



    const productSearch = document.getElementById("productName");
    const productListUl =  document.getElementById("productListUl");
    const grand_total =  document.getElementById("grand_total");
    const discount =  document.getElementById("discount");
    const shipping =  document.getElementById("shipping");
    const order_tax =  document.getElementById("order_tax");

    $(productSearch).keyup(function(){
        $('#productListUl').empty();

        productListUl.style.display = "none";
        let valueProduct = this.value;

        if(valueProduct.length >= 1){
            $.ajax({
                type: "POST",
                url: "product_api.php",
                data: 'product_id=' + valueProduct,
                dataType: "json",
                success: function (res) {
                    if (res.status == 'success') {
                        var product_id = res.product_id;
                        var product_name = res.product_name;
                        var product_price = res.product_price;


                        for(let i=0;i<product_id.length;i++){
                            const listUL = document.createElement("li");
                            listUL.innerHTML = product_name[i];
                            listUL.setAttribute("onclick",`tableBuild("${product_name[i]}","${product_price[i]}","${product_id[i]}")`);
                            listUL.style.cursor = "pointer";
                            productListUl.append(listUL);
                        }

                        productListUl.style.display = "block";


                        // tableBuild(product_name,product_price);

                        // productDetails.append(product_name);
                        // productDetails.append(product_price);
                        // function tab(name,email){


                        $('#product_name').val('');

                        // }

                    } else if (res.status == 'failure') {

                        // Swal.fire(
                        //     {
                        //         title: "Failure",
                        //         text: res.msg,
                        //         icon: "warning",
                        //         button: "OK",
                        //         allowOutsideClick: false,
                        //         allowEscapeKey: false,
                        //         closeOnClickOutside: false,
                        //     }
                        // )
                        //     .then((value) => {
                        //
                        //         document.getElementById("add_btn").disabled = false;
                        //         document.getElementById("add_btn").innerHTML = 'Add';
                        //     });

                    }
                },
                // error: function () {
                // },
                error: function () {
                    Swal.fire("Check your network connection");
                    // window.window.location.reload();
                }
            });

            console.log(valueProduct);
        }



    });

    var gArray = [];
    var rowCounter = 0;

    const productDetailArr = [];

    function tableBuild(product_name,product_price,productId) {

        if(!gArray.includes(productId)) {

            const tb = document.getElementById("tb");
            const tr = document.createElement("tr");
            rowCounter++;
            let productDetails = [];
            productDetails.push(product_name);
            productDetails.push(product_price);
            const sNo = document.createElement("td");
            sNo.innerHTML = rowCounter;
            tr.append(sNo);
            tb.append(tr);

            for (let i = 0; i < 2; i++) {
                const td = document.createElement("td");
                td.innerHTML = productDetails[i];
                tr.append(td);
            }

            tb.append(tr);

            // const stock = document.createElement("td");
            // stock.innerHTML = '2';
            // tr.append(stock);
            // tb.append(tr);


            const td = document.createElement('td');
            const divQuantity = document.createElement('div');
            divQuantity.classList.add('quantity');

            const divInputGroup = document.createElement('div');
            divInputGroup.setAttribute('role', 'group');
            divInputGroup.classList.add('input-group');

            const decreaseBtn = document.createElement('button');
            decreaseBtn.classList.add('btn', 'btn-primary', 'btn-sm', 'decrease');
            decreaseBtn.textContent = '-';
            decreaseBtn.setAttribute('onclick', `cart("minus","${productId}","${product_price}")`);
            decreaseBtn.setAttribute('type', `button`);

            divInputGroup.appendChild(decreaseBtn);

            const inputQuantity = document.createElement('span');
            inputQuantity.innerHTML = 1;
            inputQuantity.setAttribute('id', `${productId}_qty`);
            inputQuantity.style.marginLeft = "9px";
            inputQuantity.style.marginRight = "9px";
            // inputQuantity.classList.add('form-control', 'quantity-input');
            divInputGroup.appendChild(inputQuantity);

            const increaseBtn = document.createElement('button');
            increaseBtn.classList.add('btn', 'btn-primary', 'btn-sm', 'increase');
            increaseBtn.textContent = '+';
            increaseBtn.setAttribute('onclick', `cart("plus","${productId}","${product_price}")`);
            increaseBtn.setAttribute('type', `button`);

            divInputGroup.appendChild(increaseBtn);

            divQuantity.appendChild(divInputGroup);
            td.appendChild(divQuantity);
            tr.append(td);
            tb.append(tr);

            const discount = document.createElement("td");
            // discount.innerHTML = '4';
            // tr.append(discount);
            // tb.append(tr);
            //
            // const tax = document.createElement("td");
            // tax.innerHTML = '5';
            // tr.append(tax);
            // tb.append(tr);
            //
            // const total = document.createElement("td");
            // let quantity = $('.quantity-input').val('');
            // let sub_total = Number(product_price) * 1;
            // total.setAttribute('id', `${productId}_totAmout`);
            // total.innerHTML = sub_total;
            // $('#grand_total').val(sub_total);
            // tr.append(total);


            const trashBin = document.createElement("td");
            const trashBinIcon = document.createElement("i");
            trashBinIcon.classList.add("fa", "fa-trash", "trash-icon");
            trashBinIcon.style.cursor = "pointer";
            trashBinIcon.addEventListener("click", function () {
                tr.remove();
            });
            trashBinIcon.setAttribute('onclick', `removeProduct("${productId}")`);

            trashBin.appendChild(trashBinIcon);
            tr.append(trashBin);

            tb.append(tr);
            // }


            productSearch.value = "";
            productListUl.style.display = "none";

            // $('#grand_total').val(sub_total);

            // sub_total = sub_total + sub_total;
            // $('#grand_total').val(sub_total);

            // $('#discount,#order_tax,#shipping').keyup(function () {
            //     var discount = parseInt(document.getElementById("discount").value);
            //     var order_tax = parseInt(document.getElementById("order_tax").value);
            //     var shipping = parseInt(document.getElementById("shipping").value);
            //     var g_total = (order_tax) + (shipping) + (sub_total);
            //     // g_total = ((discount/100)* g_total);
            //     $('#grand_total').val(g_total);
            // });
            gArray.push(productId);

            productDetailArr.push({
                product_id : productId,
                price: product_price,
                qty: 1
            });

            grandTotalCal(productDetailArr);

        }
        else{
            alert("This product is already added");
        }
    }

    function cart(oper,productId,productprice) {
        const qtySpan = document.getElementById(`${productId}_qty`);
        const totAmout = document.getElementById(`${productId}_totAmout`);

        let obj = productDetailArr.find(o => o.product_id === productId);



        if(oper == "plus"){
            let p  = Number(qtySpan.innerHTML) + 1;

            qtySpan.innerHTML = p;
            obj.qty = p;

            totAmout.innerHTML = p * Number(productprice);

        }
        else if(oper == "minus"){
            if(Number(qtySpan.innerHTML)>1){
                let m = Number(qtySpan.innerHTML) - 1;
                qtySpan.innerHTML = m;
                obj.qty =m;

                totAmout.innerHTML = m*Number(productprice);

            }

        }
        grandTotalCal(productDetailArr);

    }


    function removeProduct(productId) {


        let obj = productDetailArr.findIndex(o => o.product_id === productId);
        productDetailArr.splice(obj,1);
        grandTotalCal(productDetailArr);

    }

    function grandTotalCal(productAr){
        let grossPrice = 0;
        for(let x=0;x<productAr.length;x++){

            grossPrice += productAr[x].price * productAr[x].qty;
        }



        grand_total.value = grossPrice + (order_tax.value == ''?0:order_tax.value) +  (shipping.value == ''?0:shipping.value);



    }




    $('#add_btn').click(function () {
        const tableRows = document.getElementById("tb").getElementsByTagName("tr");
        const rowDataArray = [];

        for (let i = 0; i < tableRows.length; i++) {
            const row = tableRows[i];
            const rowData = {
                sNo: row.cells[0].textContent,
                productName: row.cells[1].textContent,
                netUnitCost: row.cells[2].textContent,
                // stock: row.cells[3].textContent,
                // quantity: row.cells[4].querySelector('.quantity-input').textContent,
                // discount: row.cells[5].textContent,
                // tax: row.cells[6].textContent,
                // subtotal: row.cells[7].textContent,
                // Add other fields if needed
            };

            console.log(rowDataArray.push(rowData));
        }

        setTimeout(function() {
            $.ajax({
                type: "POST",
                url: "purchase_api.php",
                data: {
                    tableData: JSON.stringify(rowDataArray),
                    // Add other parameters if needed
                },
                success: function(response) {
                    console.log("Data sent successfully:", response);

                },
                error: function(error) {
                    console.error("Error sending data:", error);

                }
            });
        }, 5000);


    });


    function fnc(value, min, max)
    {
        if(parseInt(value) < 0 || isNaN(value))
            return 0;
        else if(parseInt(value) > 100)
            return "Number is greater than 100";
        else return value;
    }


    function x() {

    }
</script>

</body>
</html>

