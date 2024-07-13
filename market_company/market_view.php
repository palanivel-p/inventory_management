
<?php Include("../includes/connection.php");
date_default_timezone_set("Asia/kolkata");

error_reporting(0);
$page= $_GET['page_no'];
$pur_id= $_GET['pur_id'];
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
if($pur_id != ""){
    $pur_idSql= " AND purchase_id = '".$pur_id."'";

}
else{
    $pur_idSql ="";
}

if($page=='') {
    $page=1;
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
    $addedBy = " AND added_by='$added_by'";
}
$hlCount =1;
$plCount =1;
$clCount =1;
$glCount =1;
$market_type= $_GET['market_type'];
$market_id = $_GET['market_id'];


if($market_id != '' && $market_type == 'company_profile') {
    $sql = "SELECT * FROM market_profile WHERE market_profile_id='$market_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $material_type = $row['material_type'];
        $customer = $row['customer_name'];
        $location = $row['location'];
        $shift = $row['shift'];
        $monthly_production = $row['monthly_production'];

    }
}

$sqlcl = "SELECT COUNT(id) AS clCount FROM market_contact WHERE market_profile_id='$market_id'";
$resultcl = mysqli_query($conn, $sqlcl);
$rowcl = mysqli_fetch_assoc($resultcl);
$clCount = $rowcl['clCount'];

$sqlgl = "SELECT COUNT(id) AS glCount FROM market_requirement WHERE market_profile_id='$market_id'";
$resultgl = mysqli_query($conn, $sqlgl);
$rowgl = mysqli_fetch_assoc($resultgl);
$glCount = $rowgl['glCount'];

$sqlPl = "SELECT COUNT(id) AS plCount FROM market_details WHERE market_profile_id='$market_id'";
$resultPl = mysqli_query($conn, $sqlPl);
$rowPl = mysqli_fetch_assoc($resultPl);
$plCount = $rowPl['plCount'];

$sqlhl = "SELECT COUNT(id) AS hlCount FROM market_laddle WHERE market_profile_id='$market_id'";
$resulthl = mysqli_query($conn, $sqlhl);
$rowhl = mysqli_fetch_assoc($resulthl);
$hlCount = $rowhl['hlCount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create Company Profile</title>

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

    $header_name ="View Company Profile";
    Include ('../includes/header.php')

    ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <!--            <ol class="breadcrumb">-->
            <!--                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>-->
            <!--                <li class="breadcrumb-item active"><a href="javascript:void(0)">Furnace</a></li>-->
            <!--            </ol>-->
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12">

                    </div>
                    <div class="basic-form" style="color: black;">
                        <form id="q1_form">

                            <input type="hidden" class="form-control"  id="market_profile_id" name="market_profile_id" value="<?php echo $market_id?>">
                            <input type="hidden" class="form-control"  id="market_profile_type" name="market_profile_type" value="<?php echo $market_type?>">
                            <?php
                            if($market_id != '' && $market_type == 'company_profile') {
                            ?>
                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label> Customer Name </label>
                                    <input type="text" class="form-control" placeholder="Customer Name" id="customer_name" name="customer_name" value="<?php echo $customer?>" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    <input type="hidden" class="form-control"  id="api" name="api">
                                    <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                    <input type="hidden" class="form-control"  id="company_id" name="company_id">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Material Type</label>
                                    <select name="material_type" id="material_type" class="form-control" style="border-color: #181f5a;color: black">
                                        <option value="Cast Iron" <?php echo ($material_type == "Cast Iron") ? 'selected' : ''; ?>>Cast Iron</option>
                                        <option value="Steel" <?php echo ($material_type == "Steel") ? 'selected' : ''; ?>>Steel</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label> Location </label>
                                    <input type="text" class="form-control"  id="location" name="location" placeholder="Location" value="<?php echo $location?>" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Monthly Production  </label>
                                    <input type="text" class="form-control" placeholder="Monthly Production " id="monthly_production" name="monthly_production" value="<?php echo $monthly_production?>" style="border-color: #181f5a;color: black">
                                    <!--   <textarea class="form-control" placeholder="Notes" id="notes" name="notes" rows="4" cols="50" style="border-color: #181f5a;color: black"></textarea>-->
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Shift Operation </label>
                                    <input type="number" class="form-control" placeholder="Shift Operation" id="shift" name="shift"  value="<?php echo $shift?>" style="border-color: #181f5a;color: black">
                                </div>

                            </div>
                            <?php }?>
                            <?php
                            if($market_id != '' && $market_type == 'contact_details') {
                            ?>
                            <!--  contact details-->
                            <section class="review-section information">
                                <div class="review-header text-center">
                                    <h3 class="review-title">Contact Details</h3>
                                    <!--                            <p class="text-muted">Lorem ipsum dollar</p>-->
                                    <button onclick="addCL()" type="button" class="btn btn-success w-30">Add</button>
                                </div>
                                <div class="row" style="margin-top: 20px;padding: 15px;">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="" id="cardLoan_div">
                                            <!-- Div are Insert Here-->
                                            <?php
                                            if($clCount > 0) {
                                                $sqlCardLoan = "SELECT * FROM market_contact WHERE market_profile_id='$market_id'";

                                                $resultCardLoan = mysqli_query($conn, $sqlCardLoan);
                                                if (mysqli_num_rows($resultCardLoan)>0) {
                                                    $clCount_id = 0;
                                                    while($rowCardLoan = mysqli_fetch_assoc($resultCardLoan)) {
                                                        $clCount_id++;

                                                        $name = $rowCardLoan['name'];
                                                        $position = $rowCardLoan['position'];
                                                        $mobile = $rowCardLoan['mobile'];
                                                        $email = $rowCardLoan['email'];

                                                        ?>

                                                        <div class="row cardLoanInputs" id="<?php echo 'cardLoan_divRow_'.$clCount_id?>">
                                                            <h2 class="cardLoan_divRow_heading" style="width:100%">Contact Details <?php echo $clCount_id?></h2>
                                                            <br>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>NAME</label>
                                                                    <input type="text" class="form-control clAdd" id="<?php echo 'cl_name_'.$clCount_id?>" name="<?php echo 'cl_name_'.$clCount_id?>" value="<?php echo $name?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>POSITION</label>
                                                                    <input type="text" class="form-control clAdd" id="<?php echo 'cl_position_'.$clCount_id?>" name="<?php echo 'cl_position_'.$clCount_id?>" value="<?php echo $position?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>MOBILE</label>
                                                                    <input type="number" class="form-control clAdd" id="<?php echo 'cl_mobile_'.$clCount_id?>" name="<?php echo 'cl_mobile_'.$clCount_id?>" value="<?php echo $mobile?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>EMAIL</label>
                                                                    <input type="email" class="form-control clAdd" id="<?php echo 'cl_email_'.$clCount_id?>" name="<?php echo 'cl_email_'.$clCount_id?>" value="<?php echo $email?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <!-- Static Remove Button -->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <button class="btn btn-danger w-30" onclick="removeEle('cardLoan_divRow_<?php echo $clCount_id ?>','cl')" type="button" style="margin-top: 30px;">Remove</button>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>

                                        </div>


                                    </div>
                                </div>
                                <!--        </div>-->
                            </section>

                            <?php }?>

                            <?php
                            if($market_id != '' && $market_type == 'requirement_details') {
                            ?>
                            <!--  Material Required-->
                            <section class="review-section information">
                                <div class="review-header text-center">
                                    <h3 class="review-title">Material Required</h3>
                                    <!--                            <p class="text-muted">Lorem ipsum dollar</p>-->
                                    <button onclick="addGL()" type="button" class="btn btn-success w-30">Add</button>
                                </div>
                                <div class="row" style="margin-top: 20px;padding: 15px;">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="" id="goldLoan_div">
                                            <!--                                           Div are Insert Here-->
                                            <?php
                                            if($glCount > 0) {
                                                $sqlGoldLoan = "SELECT * FROM market_requirement WHERE market_profile_id='$market_id'";

                                                $resultGoldLoan = mysqli_query($conn, $sqlGoldLoan);
                                                if (mysqli_num_rows($resultGoldLoan)>0) {
                                                    $glCount_id = 0;
                                                    while($rowGoldLoan = mysqli_fetch_assoc($resultGoldLoan)) {
                                                        $glCount_id++;

                                                        $product_name = $rowGoldLoan['product_name'];
                                                        $category = $rowGoldLoan['category'];
                                                        $supplier = $rowGoldLoan['supplier'];
                                                        $qty = $rowGoldLoan['qty'];

                                                        ?>

                                                        <div class="row goldLoanInputs" id="<?php echo 'goldLoan_divRow_'.$glCount_id?>">
                                                            <h2 class="goldLoan_divRow_heading" style="width:100%">Material Required <?php echo $glCount_id?></h2>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>PRODUCT NAME</label>
                                                                    <input type="text" class="form-control glAdd" id="<?php echo 'gl_product_name_'.$glCount_id?>" name="<?php echo 'gl_product_name_'.$glCount_id?>" value="<?php echo $product_name?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>CATEGORY</label>
                                                                    <input type="text" class="form-control glAdd" id="<?php echo 'gl_category_'.$glCount_id?>" name="<?php echo 'gl_category_'.$glCount_id?>" value="<?php echo $category?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>SUPPLIER</label>
                                                                    <input type="text" class="form-control glAdd" id="<?php echo 'gl_supplier_'.$glCount_id?>" name="<?php echo 'gl_supplier_'.$glCount_id?>" value="<?php echo $supplier?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>QTY</label>
                                                                    <input type="number" class="form-control glAdd" id="<?php echo 'gl_qty_'.$glCount_id?>" name="<?php echo 'gl_qty_'.$glCount_id?>" value="<?php echo $qty?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <!-- Static Remove Button -->
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <button class="btn btn-danger w-30" onclick="removeEle('goldLoan_divRow_<?php echo $clCount_id ?>','gl')" type="button" style="margin-top: 30px;">Remove</button>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>


                                    </div>
                                </div>
                                <!--    </div>-->
                            </section>
                            <?php }?>

                            <?php
                            if($market_id != '' && $market_type == 'furnace_details') {
                            ?>
                            <section class="review-section information">
                                <div class="review-header text-center">
                                    <h3 class="review-title">Furnace Details</h3>
                                    <button onclick="addPL()" type="button" class="btn btn-success w-30">Add</button>
                                </div>
                                <div class="row" style="margin-top: 20px;padding: 15px;">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="" id="personalLoan_div">

                                            <!--                                           Div are Insert Here-->
                                            <?php
                                            if($plCount > 0) {
                                                $sqlPersonalLoan = "SELECT * FROM market_details WHERE market_profile_id='$market_id'";

                                                $resultPersonalLoan = mysqli_query($conn, $sqlPersonalLoan);
                                                if (mysqli_num_rows($resultPersonalLoan)>0) {
                                                    $plCount_id = 0;
                                                    while($rowPersonalLoan = mysqli_fetch_assoc($resultPersonalLoan)) {
                                                        $plCount_id++;

                                                        $capacity = $rowPersonalLoan['capacity'];
                                                        $no_laddle = $rowPersonalLoan['no_laddle'];
                                                        $compititer = $rowPersonalLoan['compititer'];
                                                        $tapping_temperature = $rowPersonalLoan['tapping_temperature'];
                                                        $sg = $rowPersonalLoan['sg'];
                                                        $power = $rowPersonalLoan['power'];
                                                        $grey = $rowPersonalLoan['grey'];
                                                        $fork_height = $rowPersonalLoan['fork_height'];
                                                        $furnace_dia = $rowPersonalLoan['furnace_dia'];
                                                        $former_dia = $rowPersonalLoan['former_dia'];
                                                        $coilcoat_dia = $rowPersonalLoan['coilcoat_dia'];
                                                        $wall_thickness = $rowPersonalLoan['wall_thickness'];
                                                        $bottom_height = $rowPersonalLoan['bottom_height'];
                                                        $furnace_height = $rowPersonalLoan['furnace_height'];
                                                        $former_height = $rowPersonalLoan['former_height'];
                                                        $gld_height = $rowPersonalLoan['gld_height'];
                                                        $linning_material = $rowPersonalLoan['linning_material'];
                                                        $linning_lite = $rowPersonalLoan['linning_lite'];
                                                        $patching = $rowPersonalLoan['patching'];
                                                        $base_metal = $rowPersonalLoan['base_metal'];
                                                        $tapping = $rowPersonalLoan['tapping'];


                                                        ?>

                                                        <div class="row personalLoanInputs" id="<?php echo 'personalLoan_divRow_'.$plCount_id?>">
                                                            <h2 class="personalLoan_divRow_heading" style="width:100%">Furnace Details <?php echo $plCount_id?></h2>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>FURNACE CAPACITY</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'furnace_capacity_'.$plCount_id?>" name="<?php echo 'furnace_capacity_'.$plCount_id?>" value="<?php echo $capacity?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>FURNACE QTY</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'furnace_qty_'.$plCount_id?>" name="<?php echo 'furnace_qty_'.$plCount_id?>" value="<?php echo $no_laddle?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>COMPETITOR</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'competitor_'.$plCount_id?>" name="<?php echo 'competitor_'.$plCount_id?>" value="<?php echo $compititer?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>TAPPING TEMPERATURE</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'tapping_temperature_'.$plCount_id?>" name="<?php echo 'tapping_temperature_'.$plCount_id?>" value="<?php echo $tapping_temperature?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>POWER</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'power_'.$plCount_id?>" name="<?php echo 'power_'.$plCount_id?>" value="<?php echo $power?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>SG</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'sg_'.$plCount_id?>" name="<?php echo 'sg_'.$plCount_id?>" value="<?php echo $sg?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>GREY</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'grey_'.$plCount_id?>" name="<?php echo 'grey_'.$plCount_id?>" value="<?php echo $grey?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>FORK HEIGHT</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'fork_height_'.$plCount_id?>" name="<?php echo 'fork_height_'.$plCount_id?>" value="<?php echo $fork_height ?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LINNING MATERIAL</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'linning_material_'.$plCount_id?>" name="<?php echo 'linning_material_'.$plCount_id?>" value="<?php echo $linning_material ?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LINNING LITE</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'linning_lite_'.$plCount_id?>" name="<?php echo 'linning_lite_'.$plCount_id?>" value="<?php echo $linning_lite?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>PATCHING</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'patching_'.$plCount_id?>" name="<?php echo 'patching_'.$plCount_id?>" value="<?php echo $patching?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>BASE METAL</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'base_metal_'.$plCount_id?>" name="<?php echo 'base_metal_'.$plCount_id?>" value="<?php echo $base_metal?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>TAPPING</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'tapping_'.$plCount_id?>" name="<?php echo 'tapping_'.$plCount_id?>" value="<?php echo $tapping?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>FURNACE DIA</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'furnace_dia_'.$plCount_id?>" name="<?php echo 'furnace_dia_'.$plCount_id?>" value="<?php echo $furnace_dia?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>FORMER DIA</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'former_dia_'.$plCount_id?>" name="<?php echo 'former_dia_'.$plCount_id?>" value="<?php echo $former_dia ?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>COILCOAT DIA</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'coilcoat_dia_'.$plCount_id?>" name="<?php echo 'coilcoat_dia_'.$plCount_id?>" value="<?php echo $coilcoat_dia ?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>WALL THICKNESS</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'wall_thickness_'.$plCount_id?>" name="<?php echo 'wall_thickness_'.$plCount_id?>" value="<?php echo $wall_thickness?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>BOTTOM HEIGHT</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'bottom_height_'.$plCount_id?>" name="<?php echo 'bottom_height_'.$plCount_id?>" value="<?php echo $bottom_height?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>FURNACE HEIGHT</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'furnace_height_'.$plCount_id?>" name="<?php echo 'furnace_height_'.$plCount_id?>" value="<?php echo $furnace_height?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>FORMER HEIGHT</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'former_height_'.$plCount_id?>" name="<?php echo 'former_height_'.$plCount_id?>" value="<?php echo $former_height ?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>GLD HEIGHT</label>
                                                                    <input type="text" class="form-control plAdd" id="<?php echo 'gld_height_'.$plCount_id?>" name="<?php echo 'gld_height_'.$plCount_id?>" value="<?php echo $gld_height ?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <button type="button" class="btn btn-danger w-30" onclick="removeEle('<?php echo 'personalLoan_divRow_'.$plCount_id?>','pl')">Remove</button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <?php }?>

                            <?php
                            if($market_id != '' && $market_type == 'laddle_details') {
                            ?>
                            <section class="review-section information">
                                <div class="review-header text-center">
                                    <h3 class="review-title">Laddle Details</h3>
                                    <button onclick="addHL()" type="button" class="btn btn-success w-30">Add</button>
                                </div>
                                <div class="row" style="margin-top: 20px;padding: 15px;">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="" id="homeLoan_div">
                                            <!--                                           Div are Insert Here-->
                                            <?php
                                            if($hlCount > 0) {
                                                $sqlHomeLoan = "SELECT * FROM market_laddle WHERE market_profile_id='$market_id'";

                                                $resultHomeLoan = mysqli_query($conn, $sqlHomeLoan);
                                                if (mysqli_num_rows($resultHomeLoan)>0) {
                                                    $hlCount_id = 0;
                                                    while($rowHomeLoan = mysqli_fetch_assoc($resultHomeLoan)) {
                                                        $hlCount_id++;

                                                        $pre_heating = $rowHomeLoan['pre_heating'];
                                                        $laddle_type = $rowHomeLoan['laddle_type'];
                                                        $laddle_shape = $rowHomeLoan['laddle_shape'];
                                                        $capacity = $rowHomeLoan['capacity'];
                                                        $qty = $rowHomeLoan['qty'];
                                                        $current_linning = $rowHomeLoan['current_linning'];
                                                        $patching_material = $rowHomeLoan['patching_material'];
                                                        $coating_material = $rowHomeLoan['coating_material'];
                                                        $linninglite = $rowHomeLoan['linninglite'];
                                                        $competitor = $rowHomeLoan['competitor'];
                                                        $sg = $rowHomeLoan['sg'];
                                                        $grey = $rowHomeLoan['grey'];
                                                        $laddle_dia = $rowHomeLoan['laddle_dia'];
                                                        $laddle_former_dia = $rowHomeLoan['laddle_former_dia'];
                                                        $laddle_thickness = $rowHomeLoan['laddle_thickness'];
                                                        $laddle_height = $rowHomeLoan['laddle_height'];
                                                        $laddle_bottom_height = $rowHomeLoan['laddle_bottom_height'];
                                                        $laddle_former_height = $rowHomeLoan['laddle_former_height'];


                                                        ?>

                                                        <div class="row homeLoanInputs" id="<?php echo 'homeLoan_divRow_'.$hlCount_id?>">
                                                            <h2 class="homeLoan_divRow_heading" style="width: 100%">Laddle Details <?php echo $hlCount_id?></h2>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>PRE HEATING</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'pre_heating_'.$hlCount_id?>" name="<?php echo 'pre_heating_'.$hlCount_id?>" value="<?php echo $pre_heating?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE TYPE</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_type_'.$hlCount_id?>" name="<?php echo 'laddle_type_'.$hlCount_id?>" value="<?php echo $laddle_type?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE SHAPE</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_shape_'.$hlCount_id?>" name="<?php echo 'laddle_shape_'.$hlCount_id?>" value="<?php echo $laddle_shape?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>CAPACITY</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'capacity_'.$hlCount_id?>" name="<?php echo 'capacity_'.$hlCount_id?>" value="<?php echo $capacity?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>QTY</label>
                                                                    <input type="number" class="form-control hlAdd" id="<?php echo 'qty_'.$hlCount_id?>" name="<?php echo 'qty_'.$hlCount_id?>" value="<?php echo $qty?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>


                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>CURRENT LINNING</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'current_linning_'.$hlCount_id?>" name="<?php echo 'current_linning_'.$hlCount_id?>" value="<?php echo $current_linning?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>PATCHING MATERIAL</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'patching_material_'.$hlCount_id?>" name="<?php echo 'patching_material_'.$hlCount_id?>" value="<?php echo $patching_material?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>COATING MATERIAL</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'coating_material_'.$plCount_id?>" name="<?php echo 'coating_material_'.$plCount_id?>" value="<?php echo $coating_material?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LINNINGLITE</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'linninglite_'.$plCount_id?>" name="<?php echo 'linninglite_'.$plCount_id?>" value="<?php echo $linninglite?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>COMPETITOR</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'competitor_'.$hlCount_id?>" name="<?php echo 'competitor_'.$hlCount_id?>" value="<?php echo $competitor?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>SG</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'sg_'.$hlCount_id?>" name="<?php echo 'sg_'.$hlCount_id?>" value="<?php echo $sg?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>GREY</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'grey_'.$hlCount_id?>" name="<?php echo 'grey_'.$hlCount_id?>" value="<?php echo $grey?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>


                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE DIA</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_dia_'.$hlCount_id?>" name="<?php echo 'laddle_dia_'.$hlCount_id?>" value="<?php echo $laddle_dia?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE FORMER DIA</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_former_dia_'.$hlCount_id?>" name="<?php echo 'laddle_former_dia_'.$hlCount_id?>" value="<?php echo $laddle_former_dia?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE THICKNESS</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_thickness_'.$plCount_id?>" name="<?php echo 'laddle_thickness_'.$plCount_id?>" value="<?php echo $laddle_thickness?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE HEIGHT</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_height_'.$plCount_id?>" name="<?php echo 'laddle_height_'.$plCount_id?>" value="<?php echo $laddle_height?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE BOTTOM HEIGHT</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_bottom_height_'.$plCount_id?>" name="<?php echo 'laddle_bottom_height_'.$plCount_id?>" value="<?php echo $laddle_bottom_height?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <label>LADDLE FORMER HEIGHT</label>
                                                                    <input type="text" class="form-control hlAdd" id="<?php echo 'laddle_former_height_'.$plCount_id?>" name="<?php echo 'laddle_former_height_'.$plCount_id?>" value="<?php echo $laddle_former_height?>" style="border-color: black;color: black">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-block mb-3">
                                                                    <button type="button" class="btn btn-danger w-30" onclick="removeEle('<?php echo 'homeLoan_divRow_'.$hlCount_id?>','hl')">Remove</button>
                                                                </div>

                                                            </div>

                                                        </div>


                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                            </section>
                            <?php }?>

                            <div style="display: inline-block;">
                            </div>
                            <div style="margin-bottom: 25px;float: right;">
                                <button class="btn btn-primary" id="form_btn" type="button">Save Details</button>
                            </div>
                        </form>
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

<!--<script src="../assets/js/jquery-3.7.0.min.js"></script>-->
<!---->
<!--<script src="../assets/js/bootstrap.bundle.min.js"></script>-->
<!---->
<!--<script src="../assets/js/jquery.slimscroll.min.js"></script>-->
<!---->
<!--<script src="../assets/js/select2.min.js"></script>-->
<!---->
<!--<script src="../assets/js/jquery.dataTables.min.js"></script>-->
<!--<script src="../assets/js/dataTables.bootstrap4.min.js"></script>-->
<!---->
<!--<script src="../assets/js/moment.min.js"></script>-->
<!--<script src="../assets/js/bootstrap-datetimepicker.min.js"></script>-->
<!---->
<!--<script src="../assets/js/layout.js"></script>-->
<!--<script src="../assets/js/theme-settings.js"></script>-->
<!--<script src="../assets/js/greedynav.js"></script>-->
<!---->
<!--<script src="../assets/js/app.js"></script>-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

    //to validate form
    $("#q1_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                customerName: {
                    required: true
                },
            },
            // Specify validation error messages
            messages: {
                customerName: "*This field is required",

            }
            // Make sure the form is submitted to the destination defined
        });
    //add data
    $('#form_btn').click(function () {


        $("#q1_form").valid();

        if($("#q1_form").valid()==true) {

            let inputArr = [];
            let inputArrhl = [];
            let inputArrcl = [];
            let inputArrgl = [];

            let personalLoan = {};
            let personalLoanAr = [];

            let homeLoan = {};
            let homeLoanAr = [];

            let cardLoan = {};
            let cardLoanAr = [];

            let goldLoan = {};
            let goldLoanAr = [];

            const personalLoanInputsS = document.getElementsByClassName("personalLoanInputs");
            const homeLoanInputsS = document.getElementsByClassName("homeLoanInputs");
            const cardLoanInputsS = document.getElementsByClassName("cardLoanInputs");
            const goldLoanInputsS = document.getElementsByClassName("goldLoanInputs");

            // personalLoan_divRow_
            for(let a=0;a<personalLoanInputsS.length;a++){
                console.log(personalLoanInputsS[a]);
                inputArr.push(personalLoanInputsS[a].id);
            }

            for(let hla=0;hla<homeLoanInputsS.length;hla++){
                console.log(homeLoanInputsS[hla]);
                inputArrhl.push(homeLoanInputsS[hla].id);
            }
            for(let cla=0;cla<cardLoanInputsS.length;cla++){
                console.log(cardLoanInputsS[cla]);
                inputArrcl.push(cardLoanInputsS[cla].id);
            }


            for(let gla=0;gla<goldLoanInputsS.length;gla++){
                console.log(goldLoanInputsS[gla]);
                inputArrgl.push(goldLoanInputsS[gla].id);
            }
            console.log(inputArr);


            for(let b=0;b<inputArr.length;b++){
                // console.log(b);
                let divElem = document.getElementById(inputArr[b]);
                let inputElements = divElem.querySelectorAll(".plAdd");
                let eleValue = [];
                let eleValueStr = "";

                for(let c=0;c<inputElements.length;c++){
                    // console.log(c);
                    eleValue.push(inputElements[c].value);
                    // if(c == 7 || c == 8){
                    // eleValueStr+=inputElements[c].id+'%';
                    // }else {
                    eleValueStr+=inputElements[c].value+'%';
                    // }

                }

                let keyNa = 'pl'+(b+1);
                personalLoan[keyNa] = eleValue;
                personalLoanAr.push(eleValueStr);

            }

            for(let hlb=0;hlb<inputArrhl.length;hlb++){
                // console.log(b);
                let divElem = document.getElementById(inputArrhl[hlb]);
                let inputElements = divElem.querySelectorAll(".hlAdd");
                let eleValue = [];
                let eleValueStr = "";

                for(let hlc=0;hlc<inputElements.length;hlc++){
                    // console.log(c);
                    eleValue.push(inputElements[hlc].value);
                    // eleValueStr+=inputElements[hlc].value+'%';

                    // if(hlc == 7 || hlc == 8){
                    // eleValueStr+=inputElements[hlc].id+'%';
                    // }else {
                    eleValueStr+=inputElements[hlc].value+'%';
                    // }

                }


                let keyNa = 'hl'+(hlb+1);
                homeLoan[keyNa] = eleValue;
                homeLoanAr.push(eleValueStr);

            }

            for(let clb=0;clb<inputArrcl.length;clb++){
                // console.log(b);
                let divElem = document.getElementById(inputArrcl[clb]);
                let inputElements = divElem.querySelectorAll(".clAdd");
                let eleValue = [];
                let eleValueStr = "";

                for(let clc=0;clc<inputElements.length;clc++){
                    // console.log(c);
                    eleValue.push(inputElements[clc].value);
                    // eleValueStr+=inputElements[clc].value+'%';

                    // if(clc == 7 || clc == 8){
                    //     eleValueStr+=inputElements[clc].id+'%';
                    // }else {
                        eleValueStr+=inputElements[clc].value+'%';
                    // }

                }

                // console.log(inputElements.length);
                // console.log(eleValue);
                // console.log(inputElements[1].value);
                // console.log(inputElements[2].value);
                // console.log(inputElements[3].value);
                // for(let key in inputElements){
                //     eleValue.push(inputElements[key].value);
                //   //  console.log(inputElements[key].value);
                //
                // }
                let keyNa = 'cl'+(clb+1);
                cardLoan[keyNa] = eleValue;
                cardLoanAr.push(eleValueStr);

            }

            for(let glb=0;glb<inputArrgl.length;glb++){
                // console.log(b);
                let divElem = document.getElementById(inputArrgl[glb]);
                let inputElements = divElem.querySelectorAll(".glAdd");
                let eleValue = [];
                let eleValueStr = "";

                for(let glc=0;glc<inputElements.length;glc++){
                    // console.log(c);
                    eleValue.push(inputElements[glc].value);
                    eleValueStr+=inputElements[glc].value+'%';

                    // if(glc == 7 || glc == 8){
                    //     eleValueStr+=inputElements[glc].id+'%';
                    // }else {
                        eleValueStr+=inputElements[glc].value+'%';
                    // }

                }

                // console.log(inputElements.length);
                // console.log(eleValue);
                // console.log(inputElements[1].value);
                // console.log(inputElements[2].value);
                // console.log(inputElements[3].value);
                // for(let key in inputElements){
                //     eleValue.push(inputElements[key].value);
                //   //  console.log(inputElements[key].value);
                //
                // }
                let keyNa = 'gl'+(glb+1);
                goldLoan[keyNa] = eleValue;
                goldLoanAr.push(eleValueStr);

            }


            console.log(personalLoan);

            var form = $("#q1_form");
            var formData = new FormData(form[0]);

            // formData.append('personalLoan',JSON.stringify(personalLoan));
            formData.append('personalLoan',personalLoanAr);
            formData.append('homeLoan',homeLoanAr);
            formData.append('cardLoan',cardLoanAr);
            formData.append('goldLoan',goldLoanAr);
            //formData.append('personalLoan',personalLoan);

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';


            Swal.fire({
                title: "Update",
                text: "Are you sure want to Update the record?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                closeOnClickOutside: false,
                showCancelButton: true,

            })
                .then((value) => {

                    if (value.isConfirmed) {


                        $.ajax({

                            type: "POST",
                            url: 'edit_api.php',
                            async: false,
                            data: formData,
                            dataType: "json",
                            contentType: false,
                            cache: false,
                            processData: false,
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

                                            document.getElementById("form_btn").disabled = false;
                                            document.getElementById("form_btn").innerHTML = 'Add';
                                        });

                                }
                            },
                            error: function () {

                                Swal.fire('Check Your Network!');
                                document.getElementById("form_btn").disabled = false;
                                document.getElementById("form_btn").innerHTML = 'Add';
                            }

                        });
                    }


                });




        } else {
            document.getElementById("form_btn").disabled = false;
            document.getElementById("form_btn").innerHTML = 'Add';

        }

    });


    var loadFile = function(event) {
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    };


    // register jQuery extension
    jQuery.extend(jQuery.expr[':'], {
        focusable: function (el, index, selector) {
            return $(el).is('a, button, :input, [tabindex]');
        }
    });

    $(document).on('keypress', 'input,select', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            // Get all focusable elements on the page
            var $canfocus = $(':focusable');
            var index = $canfocus.index(document.activeElement) + 1;
            if (index >= $canfocus.length) index = 0;
            $canfocus.eq(index).focus();
        }
    });


    //var personalLoanCount = <?php //echo $plCount?>// + 1;
    var cardLoanCount = <?php echo $clCount?>;
    if(cardLoanCount == 0){
        cardLoanCount = 1;
    }
    var cardLoanCounts = <?php echo $clCount?> + 1;


    var goldLoanCount = <?php echo $glCount?>;
    if(goldLoanCount == 0){
        goldLoanCount = 1;
    }
    var goldLoanCounts = <?php echo $glCount?> + 1;

    var personalLoanCount = <?php echo $plCount?>;
    if(personalLoanCount == 0){
        personalLoanCount = 1;
    }
    var personalLoanCounts = <?php echo $plCount?> + 1;


    var homeLoanCount = <?php echo $hlCount?>;
    if(homeLoanCount == 0){
        homeLoanCount = 1;
    }
    var homeLoanCounts = <?php echo $hlCount?> + 1;

    function addCL() {
        const headingClassCheck = document.getElementsByClassName("cardLoan_divRow_heading");
        if (headingClassCheck.length > 0) {
            cardLoanCount = cardLoanCount + 1;
        }

        var divRow = document.createElement("div");
        divRow.classList.add("row", "cardLoanInputs");
        divRow.setAttribute("id", 'cardLoan_divRow_' + cardLoanCounts);

        let heading2 = document.createElement("h2");
        heading2.innerHTML = "Contact Details " + cardLoanCount;
        heading2.classList.add("cardLoan_divRow_heading", "col-12"); // Added col-12 class for full width

        divRow.append(heading2);

        // Create a line break after the heading
        let lineBreak = document.createElement("br");
        divRow.append(lineBreak);

        let labelArr = ["cl_name_", "cl_position_", "cl_mobile_", "cl_email_"];
        let placeholders = ["Enter Name", "Enter Position", "Enter Mobile", "Enter Email"];

        for (let i = 0; i < labelArr.length; i++) {
            let divcol = document.createElement("div");
            divcol.classList.add("col-md-4");

            let divInput = document.createElement("div");
            divInput.classList.add("form-group"); // Add form-group class for proper alignment

            let textL = labelArr[i];
            let resultTxts = textL.replaceAll("cl", "");
            let resultTxt = resultTxts.replaceAll("_", " ");

            let formLabel = document.createElement("label");
            formLabel.innerHTML = resultTxt.toUpperCase();

            let inputType = "";
            if (labelArr[i] == 'cl_mobile_') {
                inputType = "number";
            } else {
                inputType = "text";
            }

            let formInput = document.createElement("input");
            formInput.classList.add("form-control", "clAdd");
            formInput.setAttribute("id", labelArr[i] + cardLoanCount);
            formInput.setAttribute("type", inputType);
            formInput.setAttribute("name", labelArr[i] + cardLoanCount);
            formInput.setAttribute("placeholder", placeholders[i]);
            formInput.style.color = "black";
            // Add border color styling to the input fields
            formInput.style.borderColor = "black";

            if (labelArr[i] == 'cl_bank_statement_' || labelArr[i] == 'cl_repayment_schedule_') {
                formInput.setAttribute("accept", "application/pdf");
            }

            divInput.append(formLabel);
            divInput.append(formInput);
            divcol.append(divInput);
            divRow.append(divcol);
        }

        let divcol = document.createElement("div");
        divcol.classList.add("col-md-4");

        let divInput = document.createElement("div");
        divInput.classList.add("form-group"); // Add form-group class for proper alignment

        let btnRe = document.createElement("button");
        btnRe.innerHTML = "Remove";
        btnRe.classList.add("btn", "btn-danger", "w-30");
        btnRe.setAttribute("onclick", "removeEle('cardLoan_divRow_" + cardLoanCounts + "','cl')");
        btnRe.setAttribute("type", "button");
        btnRe.style.marginTop = "30px";
        divInput.append(btnRe);
        divcol.append(divInput);
        divRow.append(divcol);

        cardLoanCounts++;

        document.getElementById("cardLoan_div").append(divRow);
    }

    function addGL() {
        const headingClassCheck = document.getElementsByClassName("goldLoan_divRow_heading");
        if (headingClassCheck.length > 0) {
            goldLoanCount = goldLoanCount + 1;
        }

        var divRow = document.createElement("div");
        divRow.classList.add("row", "goldLoanInputs");
        divRow.setAttribute("id", 'goldLoan_divRow_' + goldLoanCounts);

        let heading2 = document.createElement("h2");
        heading2.innerHTML = "Material Required " + goldLoanCount;
        heading2.classList.add("goldLoan_divRow_heading", "col-12"); // Added col-12 class for full width

        divRow.append(heading2);

        // Create a line break after the heading
        let lineBreak = document.createElement("br");
        divRow.append(lineBreak);

        let labelArr = ["gl_product_name_", "gl_category_", "gl_supplier_", "gl_qty_"];
        let placeholders = ["Enter Product name", "Enter category", "Enter supplier", "Enter Quantity"];

        for (let i = 0; i < labelArr.length; i++) {
            let divcol = document.createElement("div");
            divcol.classList.add("col-md-4");

            let divInput = document.createElement("div");
            divInput.classList.add("form-group"); // Add form-group class for proper alignment

            let textL = labelArr[i];
            let resultTxts = textL.replaceAll("gl", "");
            let resultTxt = resultTxts.replaceAll("_", " ");

            let formLabel = document.createElement("label");
            formLabel.innerHTML = resultTxt.toUpperCase();

            let inputType = "";
            if (labelArr[i] == 'gl_qty_') {
                inputType = "number";
            } else if (labelArr[i] == 'gl_bank_statement_' || labelArr[i] == 'gl_repayment_schedule_') {
                inputType = "file";
            } else {
                inputType = "text";
            }

            let formInput = document.createElement("input");
            formInput.classList.add("form-control", "glAdd");
            formInput.setAttribute("id", labelArr[i] + goldLoanCount);
            formInput.setAttribute("type", inputType);
            formInput.setAttribute("name", labelArr[i] + goldLoanCount);
            formInput.setAttribute("placeholder", placeholders[i]);
            formInput.style.color = "black";
            // Add border color styling to the input fields
            formInput.style.borderColor = "black";

            if (labelArr[i] == 'gl_bank_statement_' || labelArr[i] == 'gl_repayment_schedule_') {
                formInput.setAttribute("accept", "application/pdf");
            }

            divInput.append(formLabel);
            divInput.append(formInput);
            divcol.append(divInput);
            divRow.append(divcol);
        }

        let divcol = document.createElement("div");
        divcol.classList.add("col-md-4");

        let divInput = document.createElement("div");
        divInput.classList.add("form-group"); // Add form-group class for proper alignment

        let btnRe = document.createElement("button");
        btnRe.innerHTML = "Remove";
        btnRe.classList.add("btn", "btn-danger", "w-30");
        btnRe.setAttribute("onclick", "removeEle('goldLoan_divRow_" + goldLoanCounts + "','gl')");
        btnRe.setAttribute("type", "button");
        btnRe.style.marginTop = "30px";
        divInput.append(btnRe);
        divcol.append(divInput);
        divRow.append(divcol);

        goldLoanCounts++;

        document.getElementById("goldLoan_div").append(divRow);
    }



    function addPL() {
        const headingClassCheck = document.getElementsByClassName("personalLoan_divRow_heading");
        if (headingClassCheck.length > 0) {
            personalLoanCount = personalLoanCount + 1;
        }

        var divRow = document.createElement("div");
        divRow.classList.add("row", "personalLoanInputs");
        divRow.setAttribute("id", 'personalLoan_divRow_' + personalLoanCounts);

        let heading2 = document.createElement("h2");
        heading2.innerHTML = "Furnace Details " + personalLoanCount;
        heading2.classList.add("personalLoan_divRow_heading", "col-12"); // Added col-12 class for full width

        divRow.append(heading2);

        // Create a line break after the heading
        let lineBreak = document.createElement("br");
        divRow.append(lineBreak);

        let labelArr = ["furnace_capacity_", "furnace_qty_", "competitor_","tapping_temperature_","power_","sg_","grey_","fork_height_","linning_material_","linning_lite_","patching_","base_metal_","tapping_","furnace_dia_","former_dia_","coilcoat_dia_","wall_thickness_","bottom_height_","furnace_height_","former_height_","gld_height_"];
        let placeholders = ["Enter furnace capacity", "Enter number of furnaces", "Enter competitor","Tapping Temperature","Power","SG","Grey","Fork Height" ,"Linning Material","Linning Lite","No Of patching","Base Metal","No Of Tapping","Furnace Dia D1","Former Dia D3","After Coilcoat Furnace Dia","Side Wall Thickness(S1,S2)","Bottom Height(BH1)","Furnace Height(H1)","Former Height(H2)","Gld Height"];

        for (let i = 0; i < labelArr.length; i++) {
            let divcol = document.createElement("div");
            divcol.classList.add("col-md-4");

            let divInput = document.createElement("div");
            divInput.classList.add("form-group"); // Add form-group class for proper alignment

            let textL = labelArr[i];
            let resultTxt = textL.replaceAll("_", " ");

            let formLabel = document.createElement("label");
            formLabel.innerHTML = resultTxt.toUpperCase();

            let formInput = document.createElement("input");
            formInput.classList.add("form-control", "plAdd");
            formInput.setAttribute("id", labelArr[i] + personalLoanCount);
            formInput.setAttribute("type", labelArr[i] === "no_of_furnace_" ? "number" : "text");
            formInput.setAttribute("name", labelArr[i] + personalLoanCount);
            formInput.setAttribute("placeholder", placeholders[i]);
            formInput.style.color = "black";
            // Add border color styling to the input fields
            formInput.style.borderColor = "black";

            divInput.append(formLabel);
            divInput.append(formInput);
            divcol.append(divInput);
            divRow.append(divcol);
        }

        let divcol = document.createElement("div");
        divcol.classList.add("col-md-4");

        let divInput = document.createElement("div");
        divInput.classList.add("form-group"); // Add form-group class for proper alignment

        let btnRe = document.createElement("button");
        btnRe.innerHTML = "Remove";
        btnRe.classList.add("btn", "btn-danger", "w-30");
        btnRe.setAttribute("onclick", "removeEle('personalLoan_divRow_" + personalLoanCounts + "','pl')");
        btnRe.setAttribute("type", "button");
        btnRe.style.marginTop = "30px";
        divInput.append(btnRe);
        divcol.append(divInput);
        divRow.append(divcol);

        personalLoanCounts++;

        document.getElementById("personalLoan_div").append(divRow);
    }

    function addHL(){

        const headingClassCheck = document.getElementsByClassName("homeLoan_divRow_heading");
        if(headingClassCheck.length > 0){
            homeLoanCount = homeLoanCount+1;
            // personalLoanCount = personalLoanCount+headingClassCheck.length;
        }

        var divRow = document.createElement("div");
        divRow.classList.add("row","homeLoanInputs");
        divRow.setAttribute("id",'homeLoan_divRow_'+homeLoanCounts);

        let heading2 = document.createElement("h2");
        heading2.innerHTML = "Laddle Details "+ homeLoanCount;
        heading2.classList.add("homeLoan_divRow_heading", "col-12"); // Added col-12 class for full width
        divRow.append(heading2);

        // let labelArr = ["laddle_capacity_","no_of_laddles_","laddle_dia_top_","laddle_dia_bottom_","laddle_dia_height_","former_dia/height_top_","former_dia/height_bottom_","side_wall_thickness_","bottom_height_","competitor_","sg","grey"];
        let labelArr = ["pre_heating_","laddle_type_","laddle_shape_","capacity_","qty_","current_linning_","patching_material_","coating_material_","linninglite_","competitor_","sg_","grey_","laddle_dia_","laddle_former_dia_","laddle_thickness_","laddle_height_","laddle_bottom_height_","laddle_former_height_"];
        let placeholders = ["Pre Heating", "Laddle Type", "Laddle Shape", "Capacity", "Quantity", "Current Linning Material", "patching Material", "Coating Material", "Laddle Linning Lite", "Enter competitor", "Enter sg", "Enter grey", "Laddle Dia L D1", "Former Dia D3", "Side Wall Thickness(S1,S2)", "Laddle Height(L H1)", "Bottom Height(BH1)", "Former Height(H2)"];
        for (let i = 0; i < labelArr.length; i++) {
            let divcol = document.createElement("div");
            divcol.classList.add("col-md-4");

            let divInput = document.createElement("div");
            divInput.classList.add("input-block", "mb-3");

            let textL = labelArr[i];
            let resultTxts = textL.replaceAll("hl", "");
            let resultTxt = resultTxts.replaceAll("_", " ");

            let formLabel = document.createElement("label");
            formLabel.innerHTML = resultTxt.toUpperCase();

            let formInput;

            if (labelArr[i] === "pre_heat_" || labelArr[i] === "laddle_type_" || labelArr[i] === "laddle_shape_") {
                formInput = document.createElement("select");
                formInput.classList.add("form-control", "hlAdd");
                formInput.setAttribute("id", labelArr[i] + homeLoanCounts);
                formInput.setAttribute("name", labelArr[i] + homeLoanCounts);

                let option1 = document.createElement("option");
                option1.value = "yes";
                option1.text = "Yes";

                let option2 = document.createElement("option");
                option2.value = "no";
                option2.text = "No";

                let option3 = document.createElement("option");
                option3.value = "treatment";
                option3.text = "Treatment";

                let option4 = document.createElement("option");
                option4.value = "pouring";
                option4.text = "Pouring";

                let option5 = document.createElement("option");
                option5.value = "tea spout";
                option5.text = "Tea Spout";

                let option6 = document.createElement("option");
                option6.value = "immerged";
                option6.text = "Immerged";

                let option7 = document.createElement("option");
                option7.value = "bottom pouring";
                option7.text = "Bottom Pouring";

                if (labelArr[i] === "pre_heat_") {
                    formInput.add(option1);
                    formInput.add(option2);
                } else if (labelArr[i] === "laddle_type_") {
                    formInput.add(option3);
                    formInput.add(option4);
                } else if (labelArr[i] === "laddle_shape_") {
                    formInput.add(option5);
                    formInput.add(option6);
                    formInput.add(option7);
                }
                formInput.style.color = "black";
                formInput.style.borderColor = "black";
            } else {
                formInput = document.createElement("input");
                formInput.classList.add("form-control", "hlAdd");
                formInput.setAttribute("type", labelArr[i] === "qty_" ? "number" : "text");
                formInput.setAttribute("id", labelArr[i] + homeLoanCounts);
                formInput.setAttribute("name", labelArr[i] + homeLoanCounts);
                formInput.setAttribute("placeholder", placeholders[i]);
                formInput.style.color = "black";
                formInput.style.borderColor = "black";
            }

            divInput.append(formLabel);
            divInput.append(formInput);
            divcol.append(divInput);
            divRow.append(divcol);
        }
        let divcol = document.createElement("div");
        divcol.classList.add("col-md-4");

        let divInput = document.createElement("div");
        divInput.classList.add("input-block", "mb-3");

        let btnRe = document.createElement("button");
        btnRe.innerHTML = "Remove";
        btnRe.classList.add("btn","btn-danger","w-30");
        btnRe.setAttribute("onclick","removeEle('homeLoan_divRow_"+homeLoanCounts+"','hl')");
        btnRe.setAttribute("type","button");

        divcol.append(btnRe);
        divRow.append(divcol);

        homeLoanCounts++;
        // personalLoanCount++;
        document.getElementById("homeLoan_div").append(divRow);

    }


    function removeEle(eleId,loanType) {
        document.getElementById(eleId).remove();

        if(loanType == "pl"){
            const headingClass = document.getElementsByClassName("personalLoan_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Furnace Details "+(i+1);
                personalLoanCount = i+1;
            }
        }

        if(loanType == "hl"){
            const headingClass = document.getElementsByClassName("homeLoan_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Laddle Details "+(i+1);
                homeLoanCount = i+1;
            }
        }

        if(loanType == "cl"){
            const headingClass = document.getElementsByClassName("cardLoan_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Contact Details "+(i+1);
                cardLoanCount = i+1;
            }
        }


        if(loanType == "appl"){
            const headingClass = document.getElementsByClassName("appLoan_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Material Details "+(i+1);
                appLoanCount = i+1;
            }
        }


        if(loanType == "gl"){
            const headingClass = document.getElementsByClassName("goldLoan_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Material Required "+(i+1);
                goldLoanCount = i+1;
            }
        }


        if(loanType == "al"){
            const headingClass = document.getElementsByClassName("autoLoan_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Auto Loan "+(i+1);
                autoLoanCount = i+1;
            }
        }

        if(loanType == "ins"){
            const headingClass = document.getElementsByClassName("insurance_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Insurance "+(i+1);
                insuranceCount = i+1;
            }
        }





    }

    var acc = document.getElementsByClassName("accordion");
    var d;

    for (d = 0; d < acc.length; d++) {
        acc[d].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }
</script>

</body>
</html>

