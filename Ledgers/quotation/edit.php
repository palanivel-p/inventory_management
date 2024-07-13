
<?php Include("../../includes//connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);
$page= $_GET['page_no'];
$purchase_id= $_GET['purchase_id'];
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

$sqlpurchase = "SELECT * FROM purchase WHERE purchase_id ='$purchase_id'";
$respurchase = mysqli_query($conn, $sqlpurchase);
$rowpurchase = mysqli_fetch_assoc($respurchase);
$purchase_date =  $rowpurchase['purchase_date'];
$discount=$rowpurchase['discount'];
$shipping=$rowpurchase['shipping'];
$order_tax=$rowpurchase['order_tax'];
$payment_status=$rowpurchase['payment_status'];
$status=$rowpurchase['status'];
$grand_total=$rowpurchase['grand_total'];
$notes=$rowpurchase['notes'];
$material=$rowpurchase['material'];
$transport=$rowpurchase['transport'];
$amended_date=$rowpurchase['amended_date'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Intent</title>

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

    $header_name ="Edit Intent";
    Include ('../../includes//header.php') ?>



    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Intent</a></li>


            </ol>

        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!--                    <div class="table-responsive">-->
                    <div class="col-lg-12">

                    </div>
                    <div class="basic-form" style="color: black;">
                        <form id="staff_form" autocomplete="off">
                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Intent Date *</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date"  value = "<?php echo $purchase_date?>" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <input type="hidden" class="form-control"  id="api" name="api">
                                    <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                    <input type="hidden" class="form-control"  id="purchase_id" name="purchase_id">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Supplier </label>
                                    <select data-search="true" class="form-control tail-select w-full" id="supplier" name="supplier" style="border-color: #181f5a;color: black">
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


                                <div class="form-group col-md-4">
                                    <label> Search Product *</label>
                                    <input type="text" class="form-control"  id="productName" name="productName" placeholder="Search By Product"  style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <ul class="productListUl" id="productListUl" style="display: none">
                                    </ul>
                                </div>

                                <div class="form-group col-md-12" style="font-size: 15px">
                                    <h5>Order items *</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-gray-300">
                                            <tr><th scope="col">#</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Net Unit Cost</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Delete</th>
<!--                                                <th scope="col" class="text-center"><i class="fa fa-trash"></i></th>-->
                                            </tr>
                                            </thead>
                                            <?php
                                            $sql = "SELECT * FROM purchase_details WHERE purchase_id ='$purchase_id'";
                                            $result = mysqli_query($conn, $sql);
                                            if (mysqli_num_rows($result)>0) {
                                            $sNo = 0;
                                            while($row = mysqli_fetch_assoc($result)) {
                                            $sNo++;
                                            $product_id=$row['product_id'];
                                            $unit_cost=$row['unit_cost'];
                                            $stock=$row['stock'];
                                            $qty=$row['qty'];
                                            $discount=$row['discount'];
                                            $tax=$row['tax'];
                                            $sub_total=$row['sub_total'];


                                            ?>
                                            <tbody id="tb">
                                            <tr>
                                                <td><strong><?php echo $sNo;?></strong></td>
                                                <td><?php echo $product_id?></td>
                                                <td> <?php echo $unit_cost?> </td>
<!--                                                <td> --><?php //echo $stock?><!-- </td>-->
                                                <td>
                                                    <div class="quantity">
                                                        <div role="group" class="input-group">
                                                            <button class="btn btn-primary btn-sm decrease" type="button" onclick="cart('minus', '<?php echo $product_id?>', '<?php echo $unit_cost?>')">-</button>
                                                            <span id="productId1_qty" style="margin-left: 9px; margin-right: 9px;"><?php echo 5?></span>
                                                            <button class="btn btn-primary btn-sm increase" type="button" onclick="cart('plus', '<?php echo $product_id?>', '<?php echo $unit_cost?>')">+</button>
                                                        </div>
                                                    </div>
                                                </td>

<!--                                                <td> --><?php //echo $discount?><!-- </td>-->
<!--                                                <td> --><?php //echo $tax?><!-- </td>-->
<!--                                                <td> --><?php //echo $sub_total?><!-- </td>-->
                                                <td><i class="fa fa-trash trash-icon" style="cursor: pointer;"></i></td>
                                            </tr>
                                            <?php } }
                                            ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Added By</label>
                                    <input type="number" class="form-control" placeholder="Added By" id="discount" name="discount" value = "<?php echo $discount?>" style="border-color: #181f5a;color: black">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Notes </label>
                                    <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value = "<?php echo $notes?>" style="border-color: #181f5a;color: black">
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
    <?php Include ('../../includes//footer.php') ?>

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

    $("#payment_status").val('<?php echo $payment_status?>');
    $("#payment_status").trigger("change");
    $("#status").val('<?php echo $status?>');
    $("#status").trigger("change");

    // $('#product_search').click(function () {
    $("#product_name").change(function(){

        let product_id = $("#product_name").val();
        console.log(product_id);
        // if(primary_category != ''){
        $.ajax({
            type: "POST",
            url: "product_api.php",
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
                    // const stock = document.createElement("td");
                    // stock.innerHTML = '2';
                    // tr.append(stock);
                    // tb.append(tr);

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

                    // const discount = document.createElement("td");
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
                    // let sub_total = product_price * quantity;
                    // sub_total.classList.add('sub_total');
                    // total.innerHTML = sub_total;
                    // tr.append(total);
                    // tb.append(tr);

                    $('#product_name').val('');

                    let grand_total = $('.sub_total').val('');

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

    $(productSearch).keyup(function(){
        $('#productListUl').empty();

        productListUl.style.display = "none";
        let valueProduct = this.value;

        if(valueProduct.length == 3){
            $.ajax({
                type: "POST",
                url: "product2_api.php",
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
    function tableBuild(product_name,product_price,productId) {

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

        // const discount = document.createElement("td");
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
        // tb.append(tr);

        const trashBin = document.createElement("td");
        const trashBinIcon = document.createElement("i");
        trashBinIcon.classList.add("fa", "fa-trash", "trash-icon");
        trashBinIcon.style.cursor = "pointer";
        trashBinIcon.addEventListener("click", function () {
            tr.remove();
        });
        trashBin.appendChild(trashBinIcon);
        tr.append(trashBin);
        tb.append(tr);

        productSearch.value = "";
        productListUl.style.display = "none";

        // $('#grand_total').val(sub_total);

        $('#discount,#order_tax,#shipping').keyup(function() {
            var discount=parseInt(document.getElementById("discount").value);
            var order_tax=parseInt(document.getElementById("order_tax").value);
            var shipping=parseInt(document.getElementById("shipping").value);
            var g_total= (order_tax) +  (shipping) + (sub_total) ;
            // g_total = ((discount/100)* g_total);
            $('#grand_total').val(g_total);
        });

    }

    function cart(oper,productId,productprice) {
        const qtySpan = document.getElementById(`${productId}_qty`);
        const totAmout = document.getElementById(`${productId}_totAmout`);


        if(oper == "plus"){
            let p  = Number(qtySpan.innerHTML) + 1;

            qtySpan.innerHTML = p;

            totAmout.innerHTML = p * Number(productprice);

        }
        else if(oper == "minus"){
            if(Number(qtySpan.innerHTML)>1){
                let m = Number(qtySpan.innerHTML) - 1;
                qtySpan.innerHTML = m;

                totAmout.innerHTML = m*Number(productprice);

            }

        }


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

</script>

</body>
</html>

