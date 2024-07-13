
<?php Include("../includes/connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);

$intend_id= $_GET['intend_id'];

$purchase_id= $_GET['purchase_id'];
$sqlPurchase = "SELECT * FROM purchase WHERE purchase_id ='$purchase_id'";
$resPurchase = mysqli_query($conn, $sqlPurchase);
$rowPurchase = mysqli_fetch_assoc($resPurchase);
$purchase_date =  $rowPurchase['purchase_date'];
$due_date =  $rowPurchase['due_date'];
$supplier =  $rowPurchase['supplier'];
$currency_id =  $rowPurchase['currency_id'];
$payment_terms =  $rowPurchase['payment_terms'];
$discountss =  $rowPurchase['discount'];
$discounts=  str_replace("%","",$discountss);
$payment_status =  $rowPurchase['status'];
$status =  $rowPurchase['payment_status'];
$grand_total =  $rowPurchase['grand_total'];
$material =  $rowPurchase['material'];
$transport =  $rowPurchase['transport'];
$notes=$rowPurchase['notes'];
$total_tax=$rowPurchase['total_tax'];
$tds=$rowPurchase['tds'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> Purchase profile</title>

    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon_New.png">
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

<!--    <script src="../vendor/select2/js/select2.full.min.js"></script>-->
<!--    <script src="../js/plugins-init/select2-init.js"></script>-->
    <link rel="stylesheet" href="../vendor/select2/css/select2.min.css">

    <link href="../vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">

    <link rel="stylesheet" href="../vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="../vendor/pickadate/themes/default.date.css">
    <link href="../vendor/summernote/summernote.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">


</head>
<style>
    /*table {*/
    /*    font-size: 12px;*/
    /*}*/
    .btn.btn-sm {
        /* Adjust the font size */
        font-size: 12px;
        /* Adjust padding if needed */
        padding: 5px 10px;
    }
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

    $header_name ="Purchase";
    Include ('../includes/header.php')

    ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Purchase</a></li>
            </ol>

        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12">

                    </div>
                    <div class="basic-form" style="color: black;">
                        <form id="staff_form" autocomplete="off">
                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label> Purchase Date *</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <input type="hidden" class="form-control"  id="api" name="api">
                                    <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                    <input type="hidden" class="form-control"  id="purchase_id" name="purchase_id" >
                                    <input type="hidden" class="form-control"  id="intend_id" name="intend_id" value="<?php echo $intend_id?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="supplier">Supplier *</label>
                                    <select data-search="true" class="form-control js-example-disabled-results tail-select w-full" id="supplier" name="supplier" style="border-color: #181f5a; color: black" onclick="supplierfun(this.value)">
                                        <option value="">Select Supplier</option>
                                        <?php
                                        $sqlSupplier = "SELECT * FROM `supplier`";
                                        $resultSupplier = mysqli_query($conn, $sqlSupplier);

                                        if (mysqli_num_rows($resultSupplier) > 0) {
                                            while ($rowSupplier = mysqli_fetch_array($resultSupplier)) {

                                                ?>
                                                <option value='<?php echo $rowSupplier['supplier_id'].'_'.$rowSupplier['supply_place']; ?>'>
                                                    <?php echo strtoupper($rowSupplier['supplier_name']); ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="currency">Currency</label>
                                    <select data-search="true" class="form-control tail-select w-full" id="currency" name="currency" style="border-color: #181f5a; color: black">
                                        <?php
                                        $sqlCurrency = "SELECT * FROM `currency`";
                                        $resultCurrency = mysqli_query($conn, $sqlCurrency);
                                        if (mysqli_num_rows($resultCurrency) > 0) {
                                            while ($rowCurrency = mysqli_fetch_array($resultCurrency)) {
                                                ?>
                                                <option value='<?php echo $rowCurrency['currency_id']; ?>'>
                                                    <?php echo strtoupper($rowCurrency['currency_name']); ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="form-group col-md-4">
                                    <label> Search Product *</label>
                                    <input type="text" class="form-control"  id="productName" name="productName" placeholder="Search Product" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <ul class="productListUl" id="productListUl" style="display: none">
                                    </ul>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Payment terms *</label>
                                    <select data-search="true" class="form-control tail-select w-full" id="payment_terms" name="payment_terms"  style="border-color: #181f5a;color: black">
                                        <option value=''>Select Status</option>
                                        <option value='0' >Immediate</option>
                                        <option value='15'>15 Days</option>
                                        <option value='30'>30 Days</option>
                                        <option value='45'>45 Days</option>
                                        <option value='60'>60 Days</option>
                                        <option value='75'>75 Days</option>
                                        <option value='90'>90 Days</option>
                                        <option value='105'>105 Days</option>
                                        <option value='120'>120 Days</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Due Date *</label>
                                    <input type="date" class="form-control" id="d_date" name="d_date" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                </div>

                                <div class="form-group col-md-12" style="font-size: 15px">
                                    <h5>Order items *</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-gray-300">
                                            <tr><th scope="col">#</th>
                                                <th scope="col">Product Id</th>
                                                <th scope="col">Product Code</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Product Cost</th>
                                                <th scope="col">Stock</th>
                                                <th scope="col">Unit</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Discount(%)</th>
                                                <th scope="col">Discount Value</th>
                                                <th scope="col">Tax(%)</th>
                                                <th scope="col">Tax Value</th>
                                                <th scope="col">Subtotal</th>
                                                <th scope="col">Edit</th>
                                                <th scope="col">Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tb">
                                            <?php
                                            if($intend_id != ''){

                                            $sql = "SELECT * FROM intend_details WHERE intend_id ='$intend_id'";
                                            $result = mysqli_query($conn, $sql);
                                            $product_ids = "";
                                            $product_obj = "";

                                            if (mysqli_num_rows($result)>0) {
                                                $sNo = 0;
                                                while($row = mysqli_fetch_assoc($result)) {

                                                    $sNo++;
                                                    $intend_id=$row['intend_id'];
                                                    $product_id=$row['product_id'];
                                                    $product_name=$row['product_name'];

                                                    $sqlProduct = "SELECT * FROM product WHERE product_id ='$product_id'";
                                                    $resProduct = mysqli_query($conn, $sqlProduct);
                                                    $rowProduct = mysqli_fetch_assoc($resProduct);
                                                    $stock_qty =  $rowProduct['stock_qty'];
                                                    $unit_cost =  $rowProduct['product_cost'];
                                                    $product_unit =  $rowProduct['product_unit'];
                                                    $product_code =  $rowProduct['product_code'];

                                                    $stock=$row['Stock_count'];
                                                    $qty=$row['qty'];
                                                    $unit =  $row['unit'];
//                                                    $unit_cost =  $row['unit_cost'];
                                                    $discount1 =  $row['discount'];
                                                    $dis_symbl =  $row['dis_symbl'];
//                                                    $discount_s=  str_replace("%","",$discount1);
                                                    $discount_value =  0;
                                                    $discount_Type =  1;
                                                    $tax =  $row['tax'];
                                                    $tax_value =  $row['tax_value'];
                                                    $sub_total =  $qty * $unit_cost;

                                                    $product_ids .= $row['product_id'].",";
                                                    $taxValues = (18/100) * intval($unit_cost);

                                                    $product_objs .= "$product_id,$unit_cost,$qty,$discount1,$discount_Type,$tax_value,$unit"."#";

                                                    ?>
                                                    <tr>
                                                        <td><?php echo $sNo;?></td>
                                                        <td><?php echo $product_id?></td>
                                                        <td><?php echo $product_code?></td>
                                                        <td><?php echo $product_name?></td>
                                                        <td id="<?php echo $product_id.'_price'?>"><?php echo $unit_cost?></td>
                                                        <td>
                                                            <span id="<?php echo $product_id . '_stkidv' ?>"><?php echo $stock_qty ?></span>
                                                            <span id="<?php echo $product_id . '_stkidu' ?>">-<?php echo $product_unit ?></span>
                                                        </td>
                                                        <td id="<?php echo $product_id.'_bunit'?>"><?php echo $unit?></td>
                                                        <td id="<?php echo $product_id.'_quantity'?>"><?php echo $qty?></td>
                                                        <td>
                                                            <span id="<?php echo $product_id.'_dis' ?>"><?php echo $discount1?></span>
                                                            <span id="<?php echo $product_id.'_psym'?>"><?php echo $dis_symbl?></span>
                                                            <!--    <span>/</span>-->
                                                            <!--     <span id="--><?php //echo $product_id.'_disPercentage' ?><!--">0</span>-->
                                                        </td>
                                                        <td><span id="<?php echo $product_id.'_disPercentage' ?>"><?php echo round($discount_value)?></span></td>
                                                        <td id="<?php echo $product_id.'_tax' ?>"><?php echo $tax?></td>
                                                        <td id="<?php echo $product_id.'_descrip'?>"  style="display: none"></td>
                                                        <td><span id="<?php echo $product_id.'_taxValue' ?>"><?php echo round($tax_value)?></span></td>
                                                        <td id="<?php echo $product_id.'_totAmout' ?>"><?php echo round($sub_total)?></td>
                                                        <!--                                                        <td style="display: none" id="--><?php //echo $product_id.'_descrip'?><!--"></td>-->
                                                        <td>
                                                            <i data-toggle="modal" data-target="#purchase_list" onclick="editTitle('<?php echo $product_id; ?>')" class="fa fa-edit edit-icon" style="cursor: pointer;"></i>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-trash trash-icon" style="cursor: pointer;" onclick="removeProduct(this, '<?php echo $product_id; ?>')"></i>
                                                        </td>
                                                    </tr>
                                                <?php } }
                                                $product_objss = rtrim($product_objs, "#");
                                                $product_idss = rtrim($product_ids, ",");
                                            }
                                            ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Discount </label>
                                    <input type="number" class="form-control" placeholder="Discount" id="discount" name="discount" value="<?php echo $discountss?>" onkeyup="totalDiscount(this.value)" style="border-color: #181f5a;color: black">
                                    <!--                                    onkeyup="this.value = fnc(this.value, 0, 100)"-->
                                </div>
                                <div class="form-group col-md-4">
                                    <label>TDS </label>
                                    <input type="number" class="form-control" placeholder="TDS" id="tds" name="tds" value="<?php echo $tds?>" onkeyup="totalDiscount(this.value)" style="border-color: #181f5a;color: black">
                                    <!--                                    onkeyup="this.value = fnc(this.value, 0, 100)"-->
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Total Tax </label>
                                    <input type="number" class="form-control" placeholder="Tax" id="tax" name="tax" readonly style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Grand Total </label>
                                    <input type="number" class="form-control" placeholder="Grand Total" id="grand_total" name="grand_total" readonly style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-4">
                                    <label> Status *</label>
                                    <select data-search="true" class="form-control tail-select w-full" id="payment_status" name="payment_status" style="border-color: #181f5a;color: black">
                                        <option value='4' <?php echo ($status == '4') ? 'selected' : ''; ?>>Ordered</option>
                                        <option value='1' <?php echo ($status == '1') ? 'selected' : ''; ?>>Received</option>
                                        <option value='2' <?php echo ($status == '2') ? 'selected' : ''; ?>>Partially Pending</option>
                                        <option value='3' <?php echo ($status == '3') ? 'selected' : ''; ?>>Pending</option>


                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Payment Status *</label>
                                    <select data-search="true" class="form-control tail-select w-full" id="status" name="status" style="border-color: #181f5a; color: black">
                                        <option value='3' <?php echo ($payment_status == '3') ? 'selected' : ''; ?>>Unpaid</option>
                                        <option value='1' <?php echo ($payment_status == '1') ? 'selected' : ''; ?>>Paid</option>
                                        <option value='2' <?php echo ($payment_status == '2') ? 'selected' : ''; ?>>Partially Paid</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Material Received Date </label>
                                    <input type="date" class="form-control"  id="material_date" name="material_date" style="border-color: #181f5a;color: black">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Notes </label>
                                    <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="<?php echo $notes?>" style="border-color: #181f5a;color: black">
                                    <!--   <textarea class="form-control" placeholder="Notes" id="notes" name="notes" rows="4" cols="50" style="border-color: #181f5a;color: black"></textarea>-->
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" onclick="navigateToPage()"style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="add_btn">ADD</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="purchase_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titles">Unit</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="purchase_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-12">
                                        <!--                                        <label>product Id </label>-->
                                        <input type="hidden" class="form-control" placeholder="Product Id" id="p_id" name="p_id" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="edit_id" name="edit_id">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label> Quantity * </label>
                                        <input type="number" class="form-control" id="qtys" name="qtys" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Product Unit *</label>
                                        <select data-search="true" class="form-control tail-select w-full" id="unit" name="unit" style="border-color: #181f5a;color: black">
                                            <option value=""> Select Unit</option>
                                            <?php
                                            $sqlUnit = "SELECT * FROM `unit`";
                                            $resultUnit = mysqli_query($conn, $sqlUnit);
                                            if (mysqli_num_rows($resultUnit) > 0) {
                                                while ($rowUnit = mysqli_fetch_array($resultUnit)) {
                                                    ?>
                                                    <option
                                                        value='<?php echo $rowUnit['unit_subname']; ?>'><?php echo strtoupper($rowUnit['unit_name']); ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Discount Type </label>
                                        <select data-search="true" class="form-control tail-select w-full" id="type" name="type" style="border-color: #181f5a;color: black">
                                            <option value='1'>Percentage</option>
                                            <option value='2'>Fixed</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Discount *</label>
                                        <input type="number" class="form-control" placeholder="Discount" id="dis" name="dis" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Tax (%)</label>
                                        <select  class="form-control" id="tax_type" name="tax_type" style="border-color: #181f5a;color: black">
                                            <option value=18>18%</option>
                                            <option value=12>12%</option>
                                            <option value=5>5%</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Description </label>
                                        <input type="text" class="form-control" placeholder="Description" id="desc" name="desc" style="border-color: #181f5a;color: black">
                                    </div>

                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;">Close</button>
                        <button type="button" class="btn btn-primary" id="addbtn">ADD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Include ('../includes/footer.php') ?>

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

<script src="../vendor/select2/js/select2.full.min.js"></script>
<script src="../js/plugins-init/select2-init.js"></script>

<script>
    function navigateToPage() {
        window.location.href = "<?php echo $website; ?>/purchase/";
    }
    //due date
    $(document).ready(function() {
        $("#payment_terms").change(function() {
            const inputDateValue = document.getElementById("purchase_date").value;
            const payment_days = parseInt(document.getElementById("payment_terms").value);
            if (!isNaN(payment_days)) {
                const d = new Date(inputDateValue);
                d.setDate(d.getDate() + payment_days);
                const formattedDate = d.toISOString().split('T')[0];
                $('#d_date').val(formattedDate);
            }
        });
    });
</script>
<script>

    const productSearch = document.getElementById("productName");
    const productListUl =  document.getElementById("productListUl");
    const grand_total =  document.getElementById("grand_total");
    const discount =  document.getElementById("discount");
    const shipping =  document.getElementById("shipping");
    const order_tax =  document.getElementById("order_tax");


    var pro_obj = '<?php echo $product_objss?>';
    console.log(pro_obj);
    var pro_objs =  pro_obj .split("#");

    const productDetailArr = [];

    for(let q=0;q<pro_objs.length;q++){

        var pro_objes =  pro_objs[q].split(",");
        let product_obje = {
            product_id : pro_objes[0],
            price: Number(pro_objes[1]),
            qty: Number(pro_objes[2]),
            dis: Number(pro_objes[3]),
            disType: Number(pro_objes[4]),
            tax: Number(pro_objes[5]),
            unit: Number(pro_objes[6])
        };

        productDetailArr.push(product_obje);
    }

    console.log(productDetailArr);
    grandTotalCal(productDetailArr);


    //overall discount-grandtotal
    function totalDiscount(a){
        var totaldiscount = grandTotalCal(productDetailArr);

        var g_total =  document.getElementById("grand_total").value;
        var discount_value =  document.getElementById("discount").value;
        var tds_value =  document.getElementById("tds").value;
        if(discount_value == ''){
            discount_value =0;
        }
        else{
            discount_value =discount_value;
        }
        if(tds_value == ''){
            tds_value =0;
        }
        else{
            tds_value =tds_value;
        }
        console.log(discount_value);
        console.log(tds_value);
        var tds_dis = Number(discount_value) + Number(tds_value);
        if(tds_dis > g_total){
            alert("tds / discount value higher than grand total");
        }
        else{
            grand_total.value = Number(g_total) - Number(tds_dis);
        }
        console.log(tds_dis);
        console.log(grand_total.value);
        g_total = g_total-discount_value;
        // grand_total.value = totaldiscount - ((a/100) * totaldiscount);
        // grand_total.value = totaldiscount - a;
    }
    //Edit Product tax and discount
    function editTitle(data) {
        $('#purchase_form')[0].reset();
        var edit_model_title = "Edit Sale - "+data;

        let QUANTITY = document.getElementById(`${data}_quantity`).innerHTML;
        document.getElementById('qtys').value = Number(QUANTITY);

        let UNITS = document.getElementById(`${data}_bunit`).innerHTML;
        document.getElementById('unit').value = UNITS;
        $('#unit').trigger("change");
        console.log(UNITS);

        let DISCOUNTS = document.getElementById(`${data}_dis`).innerHTML;
        document.getElementById('dis').value = Number(DISCOUNTS);

        let DESCIPTIONS = document.getElementById(`${data}_descrip`).value;
        document.getElementById('desc').value = DESCIPTIONS;
        console.log(DESCIPTIONS);

        let dissym = document.getElementById(`${data}_psym`).innerHTML;
        // document.getElementById('type').value = dissym;
        // $('#type').trigger("change");
        if(dissym == '%'){
            document.getElementById('type').value = 1;
            $('#type').trigger("change");
        }
        else{
            document.getElementById('type').value = 2;
            $('#type').trigger("change");
        }
        console.log(dissym);

        $('#titles').html(edit_model_title);
        $('#addbtn').html("Save");
        $('#p_id').val(data);
        $('#purchase_list').modal('show');

    }
    //to edit validate form
    $("#purchase_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                qtys: {
                    required: true
                },
                unit: {
                    required: true
                },
                dis: {
                    required: true
                },
            },
            messages: {
                qtys: "*This field is required",
                unit: "*This field is required",
                dis: "*This field is required",
            }
        });
    $('#addbtn').click(function () {
        if ($("#purchase_form").valid()) {
            const p_id = $('#p_id').val();
            const qtys = $('#qtys').val();
            const unit = $('#unit').val();
            const dis = Number($('#dis').val());
            const type = Number($('#type').val());
            const tax_type = Number($('#tax_type').val());
            const desc = $('#desc').val();
            const customer =  document.getElementById("supplier").value;
            let customerArray = customer.split("_"); let txs = tax_type/2;
            if(customerArray[1] == 'Tamil Nadu') {
                $(`#${p_id}_tax`).text(`CGST - ${txs}%  SGST - ${txs}%`);
            }else{
                // document.getElementById(gArray[i] + "_tax").innerHTML = `IGST - ${tax_type}%`;
                $(`#${p_id}_tax`).text(`IGST - ${tax_type}%`);

            }

            $(`#${p_id}_descrip`).text(desc);
            $(`#${p_id}_description`).val(desc);
            $(`#${p_id}_quantity`).text(qtys);
            // $(`#${p_id}_tax`).text(tax_type + '%');
            $(`#${p_id}_bunit`).text(unit);
            $('#purchase_form')[0].reset();


            // Update discount and tax elements
            // $(`#${p_id}_dis`).text(dis);

            if (type == 1) {
                $(`#${p_id}_psym`).text('%'); // Set text to '%'
            } else if (type == 2) {
                $(`#${p_id}_psym`).text('₹'); // Set text to '₹'
            }


            // Get current total amount
            const subTotElement = document.getElementById(`${p_id}_totAmout`);
            const disPercentage = document.getElementById(`${p_id}_disPercentage`);
            const disPrice = document.getElementById(`${p_id}_price`);
            const disValue = document.getElementById(`${p_id}_dis`);

            let obj = productDetailArr.find(o => o.product_id === p_id);

            let disP;
            let unit_price;
            let dis_p;
            let dis_price;
            let s_price;
            let d_price;
            let p_price;
            console.log(unit);
            if (unit === "MT") {

                if (type == 1) {
                    unit_price = (obj.price  * 1000);
                    dis_p = (unit_price * (dis / 100));
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });

                    } else {
                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;
                    }
                } else if (type == 2) {
                    unit_price = (obj.price  * 1000);
                    dis_p = dis;
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });
                    } else {

                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;
                    }
                }
                obj.tax = ((tax_type/100) * s_price);

            }
            else if(unit === "bgs"){
                if (type == 1) {
                    unit_price = (obj.price  * 25);
                    dis_p = (unit_price * (dis / 100));
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });

                    } else {
                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;

                    }
                } else if (type == 2) {
                    unit_price = (obj.price  * 25);
                    dis_p = dis;
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });
                    } else {

                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;
                    }
                }
                obj.tax = ((tax_type/100) * s_price);
            }
            else if(unit === "mm"){
                if (type == 1) {
                    unit_price = (obj.price  * 12);
                    dis_p = (unit_price * (dis / 100));
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });

                    } else {
                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;

                    }
                } else if (type == 2) {
                    unit_price = (obj.price  * 12);
                    dis_p = dis;
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });
                    } else {

                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;
                    }
                }
                obj.tax = ((tax_type/100) * s_price);
            }
            else if(unit === "KG"){

                if (type == 1) {
                    unit_price = (obj.price);
                    dis_p = (unit_price * (dis / 100));
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });

                    } else {
                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;

                    }
                } else if (type == 2) {
                    unit_price = (obj.price);
                    dis_p = dis;
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });
                    } else {

                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;
                    }
                }
                obj.tax = ((tax_type/100) * s_price);

                console.log("st : ");
                console.log("subtotal : ");
                console.log("dis value : ");
                console.log("sub-dis : ");
                console.log("tax : "+obj.tax);

            }
            else {

                if (type == 1) {
                    unit_price = (obj.price);
                    dis_p = (unit_price * (dis / 100));
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });

                    } else {
                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;

                    }
                } else if (type == 2) {
                    unit_price = (obj.price);
                    dis_p = dis;
                    if (dis_p > unit_price) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Discount value is Greater than cost value',
                        });
                    } else {

                        //Discount Value
                        d_price = dis_p;
                        //price
                        dis_price = unit_price - dis_p;
                        //subTotal
                        s_price = dis_price * qtys;
                        // Discount Percentage
                        disP = dis;
                        p_price = dis_p;
                    }
                }
                obj.tax = ((tax_type/100) * s_price);
            }


            disPercentage.innerHTML = d_price.toFixed(2);
            subTotElement.innerHTML = s_price.toFixed(2);
            disPrice.innerHTML = unit_price;
            disValue.innerHTML = disP;
            $(`#${p_id}_taxValue`).text(obj.tax.toFixed(2));

            obj.dis = dis;
            obj.disType = type;
            obj.qty = qtys;
            obj.unit = unit;
            obj.taxes = tax_type;

            grandTotalCal(productDetailArr);
            $('#purchase_list').modal('hide');
        }
    });

    //to validate form
    $("#staff_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                purchase_date: {
                    required: true
                },
                supplier: {
                    required: true
                },
                product_name: {
                    required: true
                },
                payment_terms: {
                    required: true
                },



            },
            // Specify validation error messages
            messages: {
                purchase_date: "*This field is required",
                supplier: "*This field is required",
                product_name: "*This field is required",
                payment_status: "*This field is required",
                status: "*This field is required",
                payment_terms: "*This field is required",
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

            const tableRows = document.getElementById("tb").getElementsByTagName("tr");
            const rowDataArray = [];

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const productId = row.cells[1].textContent;
                // const tax = row.cells[9].querySelector('#' + productId + '_tax');
                // const productDesc = row.cells[9].querySelector('#' + productId + '_descrip');
                const discountSpan = row.cells[8].querySelector('#' + productId + '_dis');
                const persymblSpan = row.cells[8].querySelector('#' + productId + '_psym');
                const stockuSpan = row.cells[5].querySelector('#' + productId + '_stkidu');
                const stockvSpan = row.cells[5].querySelector('#' + productId + '_stkidv');
                const stockValue = stockvSpan ? stockvSpan.textContent : '';
                const rowData = {
                    sNo: row.cells[0].textContent,
                    // productId: row.cells[1].textContent,
                    productId: productId,
                    productCode: row.cells[2].textContent,
                    productName: row.cells[3].textContent,
                    netUnitCost: row.cells[4].textContent,
                    // stock: row.cells[4].textContent,
                    stockUnit: stockuSpan.textContent,
                    stockValue: stockValue.textContent,
                    unit: row.cells[6].textContent,
                    quantity: row.cells[7].textContent,
                    // discount: row.cells[7].textContent,
                    persymbl: persymblSpan.textContent,
                    discount: discountSpan.textContent,
                    discount_value: row.cells[9].textContent,
                    tax: row.cells[10].textContent,
                    productDesc: row.cells[11].textContent,
                    tax_value: row.cells[12].textContent,
                    subtotal: row.cells[13].textContent,
                    // productDesc: productDesc.textContent,
                    // Add other fields if needed
                };

                console.log(rowDataArray.push(rowData));
            }

            $.ajax({

                type: "POST",
                url: "<?php echo $website; ?>/purchase/add_api.php",
                data: $('#staff_form').serialize() + "&tableData=" + JSON.stringify(rowDataArray),

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
                                // window.window.location.reload();
                                window.location.href = "<?php echo $website; ?>/purchase/all_purchase.php";
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


    $( document ).ready(function() {
        $('#search').val('<?php echo $search;?>');
        $('#branch_nameS').val('<?php echo $branch_nameS;?>');

        $("#product_name").change(function(){
            $('#product_name').val(''); // Clears the value of the input field
        });

    });
    var $disabledResults = $(".js-example-disabled-results");
    $disabledResults.select2();



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
                        var product_stock = res.product_stock;
                        var product_tax = res.product_tax;
                        var product_unit = res.product_unit;
                        var product_code = res.product_code;

                        for(let i=0;i<product_id.length;i++){
                            const listUL = document.createElement("li");
                            listUL.innerHTML = product_name[i];

                            listUL.setAttribute("onclick",`tableBuild("${product_id[i]}","${product_price[i]}","${product_name[i]}","${product_stock[i]}","${product_tax[i]}","${product_unit[i]}","${product_code[i]}")`);
                            listUL.style.cursor = "pointer";
                            productListUl.append(listUL);
                        }

                        productListUl.style.display = "block";

                        $('#product_name').val('');


                    } else if (res.status == 'failure') {

                        Swal.fire(
                            {
                                title: "Failure",
                                text: "No Products",
                                icon: "warning",
                                button: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                closeOnClickOutside: false,
                            }
                        )
                            .then((value) => {

                            });

                    }
                },
                error: function () {
                    Swal.fire("Check your network connection");
                    // window.window.location.reload();
                }
            });

            // console.log(valueProduct);
        }

    });

    var pro  = '<?php echo $product_idss?>';
    var pro_id =  pro .split(",");

    var gArray = [...pro_id];
    // gArray.push(pro_id);
    console.log(gArray);
    console.log(pro_id);
    var rowCounter = <?php echo $sNo?>;


    function tableBuild(product_id,product_price,product_name,product_stock,product_tax,product_unit,product_code) {
        var supply  = document.getElementById('supplier').value;

        const myArray = supply.split("_");
        console.log(myArray);
        if(!gArray.includes(product_id)) {

            const tb = document.getElementById("tb");
            const tr = document.createElement("tr");
            rowCounter++;
            let productDetails = [];
            productDetails.push(product_id);
            productDetails.push(product_code);
            productDetails.push(product_name);
            productDetails.push(product_price);
            productDetails.push(product_stock);
            productDetails.push(product_unit);



            const sNo = document.createElement("td");
            sNo.innerHTML = rowCounter;
            tr.append(sNo);
            tb.append(tr);

            for (let i = 0; i < 6; i++) {
                const td = document.createElement("td");
                if (i === 4) {
                    const stockSpan = document.createElement("span");
                    stockSpan.setAttribute("id", `${product_id}_stkidv`);
                    stockSpan.innerHTML = productDetails[i];
                    td.append(stockSpan);

                    const idSpan = document.createElement("span");
                    idSpan.setAttribute("id", `${product_id}_stkidu`);
                    idSpan.innerHTML = "-" + product_unit;
                    td.append(idSpan);
                }
                else {
                    td.innerHTML = productDetails[i];
                }
                if (i === 3) {
                    td.setAttribute("id", `${product_id}_price`);
                }
                if (i === 5) {
                    td.setAttribute("id", `${product_id}_bunit`);
                }

                tr.append(td);
            }

            tb.append(tr);

            const quantity = document.createElement("td");
            quantity.setAttribute("id", `${product_id}_quantity`);
            quantity.innerHTML = 1;
            tr.appendChild(quantity);
            tb.append(tr);


            const discount = document.createElement("td");

            const discountValue = document.createElement('span');
            discountValue.innerHTML = '0';
            discountValue.setAttribute('id', `${product_id}_dis`);
            discount.appendChild(discountValue);

            const percentageSpan = document.createElement('span');
            percentageSpan.innerHTML = '%'; // Set text content to '%'
            percentageSpan.setAttribute('id', `${product_id}_psym`);
            discount.appendChild(percentageSpan);

            tr.append(discount);
            tb.append(tr);


            const discountPer = document.createElement("td");
            const discountPercentage = document.createElement('span');
            discountPercentage.innerHTML = 0; // Assuming percentage, adjust as needed
            discountPercentage.setAttribute('id', `${product_id}_disPercentage`);
            discountPer.appendChild(discountPercentage);
            tr.append(discountPer);
            tb.append(tr);

            const tax = document.createElement("td");
            const inputtax = document.createElement('span');

            if(myArray[1] == "Tamil Nadu"){
                // inputtax.innerHTML = `CGST - 9% <br> SGST - 9%`;
                let p_tax = product_tax/2;
                inputtax.innerHTML = `CGST - ${p_tax}% <br> SGST - ${p_tax}%`;
            }else{
                // inputtax.innerHTML = `IGST - 18%`;
                inputtax.innerHTML = `IGST - ${product_tax}%`;
            }
            inputtax.setAttribute('id', `${product_id}_tax`);
            tax.appendChild(inputtax);
            // const description = document.createElement('span');
            // description.innerHTML = ''; // Set text content to '%'
            // description.setAttribute('id', `${product_id}_descrip`);
            // description.setAttribute('style', `display:none`);
            // tax.appendChild(description);
            tr.append(tax);
            tb.append(tr);

            const description = document.createElement("td");
            const desinput = document.createElement("input");
            const descriptionInput = document.createElement("input");
            desinput.innerHTML = '';
            desinput.setAttribute('id', `${product_id}_descrip`);
            descriptionInput.setAttribute('id', `${product_id}_description`);
            descriptionInput.setAttribute('type', `text`);
            description.appendChild(desinput);
            description.appendChild(descriptionInput);
            description.setAttribute('style', 'display: none');
            tr.append(description);
            tb.append(tr);

            let taxValues;
            if(product_unit == "MT"){
                taxValues = ((product_tax/100) * Number(product_price)) * 1000;

            }
            else if(product_unit == "bgs"){
                taxValues = ((product_tax/100) * Number(product_price)) * 25;
            }
            else if(product_unit == "mm"){
                taxValues = ((product_tax/100) * Number(product_price)) * 12;
            }
            else{
                taxValues = (product_tax/100) * Number(product_price);

            }


            const taxvalue = document.createElement("td");
            const inputtaxval = document.createElement('span');
            // inputtaxval.innerHTML = Math.round(taxValues);
            inputtaxval.innerHTML =parseFloat(taxValues).toFixed(2);
            inputtaxval.setAttribute('id', `${product_id}_taxValue`);
            taxvalue.appendChild(inputtaxval);
            tr.append(taxvalue);
            tb.append(tr);

            let subTotal;
            if(product_unit == "MT"){
                subTotal = Number(product_price) * 1000;

            }
            else if(product_unit == "bgs"){
                subTotal = Number(product_price) * 25;
            }
            else if(product_unit == "mm"){
                subTotal = Number(product_price) * 12;
            }
            else{
                subTotal = Number(product_price);
            }

            const total = document.createElement("td");
            let quantitys = $('.quantity-input').val('');
            let sub_total = Number(product_price) * 1;
            total.setAttribute('id', `${product_id}_totAmout`);
            total.innerHTML = parseFloat(subTotal).toFixed(2);
            // total.innerHTML = Math.round(subTotal);
            $('#grand_total').val(sub_total);
            tr.append(total);



            const editCell = document.createElement("td");
            const editIcon = document.createElement("i");
            editIcon.setAttribute('data-toggle', `modal`);
            editIcon.setAttribute('data-target', `#purchase_list`);
            editIcon.setAttribute('onclick', `editTitle("${product_id}")`);
            editIcon.classList.add("fa", "fa-edit", "edit-icon");
            editIcon.style.cursor = "pointer";

            editCell.appendChild(editIcon);
            tr.appendChild(editCell);
            tb.appendChild(tr);


            const trashBin = document.createElement("td");
            const trashBinIcon = document.createElement("i");
            trashBinIcon.classList.add("fa", "fa-trash", "trash-icon");
            trashBinIcon.style.cursor = "pointer";
            trashBinIcon.addEventListener("click", function () {
                tr.remove();
            });
            trashBinIcon.setAttribute('onclick', `removeProduct("${product_id}")`);

            trashBin.appendChild(trashBinIcon);
            tr.append(trashBin);

            tb.append(tr);

            productSearch.value = "";
            productListUl.style.display = "none";

            gArray.push(product_id);

            productDetailArr.push({
                product_id : product_id,
                price: product_price,
                qty: 1,
                dis: 0,
                disType: 1,
                tax: taxValues,
                unit: product_unit,
                taxes: product_tax
            });

            grandTotalCal(productDetailArr);

        }
        else {
            Swal.fire({
                title: "Error",
                text: "This product is already added",
                icon: "error",
                button: "OK",
                allowOutsideClick: false,
                allowEscapeKey: false,
                closeOnClickOutside: false,
            })
                .then((value) => {
                    // If needed, you can perform additional actions after the user clicks OK
                });
        }
    }

    function removeProduct(iconElement,product_id) {

        let obj = productDetailArr.findIndex(o => o.product_id === product_id);
        productDetailArr.splice(obj,1);
        gArray.splice(obj,1);
        grandTotalCal(productDetailArr);
        const tr = iconElement.closest('tr');
        if (tr) {
            tr.remove();
        } else {
            console.error("Unable to find the table row.");
        }
    }

    function grandTotalCal(productAr){
        let grossPrice = 0;
        let tax_order = 0;
        for(let x=0;x<productAr.length;x++){

            // let subTot =  productAr[x].price * productAr[x].qty;
            let subTot;
            let subtotal_values;
            if (productAr[x].unit === "MT") {
                subTot = productAr[x].price * (productAr[x].qty * 1000);

                if (productAr[x].disType  == 1) {
                    subtotal_values = (productAr[x].price - ((productAr[x].dis / 100)) * productAr[x].price) * (productAr[x].qty * 1000);

                } else if (productAr[x].disType  == 2) {
                    subtotal_values = (productAr[x].price - productAr[x].dis) * (productAr[x].qty * 1000);

                }
                // subtotal_final = subtotal_values * (productAr[x].qty * 1000);
                // disval_final =  productAr[x].price - subtotal_values;
                //changes
                // productAr[x].tax = ((tax_type/100) * Number(productAr[x].price)) * (1000*productAr[x].qty);

            }
                // if (productAr[x].unit === "MT") {
                //     subTot = productAr[x].price * (productAr[x].qty * 1000);
                //
                //
                //
            // }
            else if (productAr[x].unit === "bgs") {
                subTot = productAr[x].price * (productAr[x].qty * 25);
                if (productAr[x].disType  == 1) {
                    subtotal_values = (productAr[x].price - ((productAr[x].dis / 100)) * productAr[x].price) *  (productAr[x].qty * 25);

                } else if (productAr[x].disType  == 2) {
                    subtotal_values = (productAr[x].price - productAr[x].dis) *  (productAr[x].qty * 25);

                }

            }
            else if (productAr[x].unit === "mm") {
                subTot = productAr[x].price * (productAr[x].qty * 12);
                if (productAr[x].disType  == 1) {
                    subtotal_values = (productAr[x].price - ((productAr[x].dis / 100)) * productAr[x].price) *  (productAr[x].qty * 12);

                } else if (productAr[x].disType  == 2) {
                    subtotal_values = (productAr[x].price - productAr[x].dis) *  (productAr[x].qty * 12);

                }
            }
            else {
                subTot = productAr[x].price * productAr[x].qty;

                if (productAr[x].disType  == 1) {
                    subtotal_values = (productAr[x].price - ((productAr[x].dis / 100)) * productAr[x].price) *  (productAr[x].qty);

                } else if (productAr[x].disType  == 2) {
                    subtotal_values = (productAr[x].price - productAr[x].dis) *  (productAr[x].qty);

                }
            }
            // tax_order+= productAr[x].qty * productAr[x].tax;
            //change
            tax_order+=  productAr[x].tax;
            var discountAmount;
            var discountAmounts;
            if(productAr[x].disType == 1){
                discountAmount = subTot - ((productAr[x].dis/100) * subTot);
                console.log("discount");
                //discountAmounts = subTot - discountAmount;
                // discountAmount = (subTot * productAr[x].dis) / 100;
            }else if(productAr[x].disType == 2){
                discountAmount = subTot - productAr[x].dis;
                console.log("Fixed");
                //  discountAmount = discountAmounts;
            }

            // grossPrice += Math.round(subTot);
            grossPrice += subtotal_values;


        }
        console.log(grossPrice);
        // grand_total.value = grossPrice + tax_order;
        // document.getElementById("tax").value = tax_order;
        //changes
        var grand_total = grossPrice + tax_order;
        var tax = tax_order;

        // Round grand_total and tax to 2 decimal places
        grand_total = grand_total.toFixed(2);
        tax = tax.toFixed(2);

        // Assign the rounded values back to their respective elements
        document.getElementById("grand_total").value = grand_total;
        document.getElementById("tax").value = tax;
        return grossPrice + tax_order;


        // grand_total.value = grossPrice + (order_tax.value == ''?0:parseFloat(order_tax.value)) +  (shipping.value == ''?0:parseFloat(shipping.value));
    }

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

    function supplierfun(a){
        let supplyArray = a.split("_");
        console.log(supplyArray[1]);
        if(gArray.length > 0){
            for(let i =0; i<gArray.length; i++){
                let obj = productDetailArr.find(o => o.product_id === gArray[i]);
                let tx = obj.taxes/2;
                if(supplyArray[1] == 'Tamil Nadu') {
                    // document.getElementById(gArray[i]+"_tax").innerHTML= `CGST -` +tx +`%` <br> `SGST - ` + tx `%`;
                    document.getElementById(gArray[i] + "_tax").innerHTML = `CGST - ${tx}% <br> SGST - ${tx}%`;

                }else{
                    document.getElementById(gArray[i]+"_tax").innerHTML= `IGST-`+ obj.taxes +`%`;
                }
            }
        }
    }
    //select search
    var $disabledResults = $(".js-example-disabled-results");
    $disabledResults.select2();
</script>

</body>
</html>

