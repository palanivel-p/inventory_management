<?php Include("../../includes/connection.php");
require_once '../../includes/excel_generator/PHPExcel.php';
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
    <title>Product</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://bhims.ca/piloting/img/favicon_New.png">
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

    <!--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />-->
    <!--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
    <link rel="stylesheet" href="../../vendor/select2/css/select2.min.css">

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
    $header_name ="Product";
    Include ('../../includes/header.php') ?>

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
                    <h4 class="card-title">Product List</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <!--                        <form class="form-inline">-->
                        <!---->
                        <!--                            <div class="form-group mx-sm-3 mb-2">-->
                        <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                        <!--                            </div>-->
                        <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        <!--                        </form>-->
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="justify-content: end">FILTER</button>
                        <?php
                        if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin' || $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                            ?>
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" style="margin-left: 20px;" onclick="addTitle()">ADD</button>
                            <?php
                        }
                        ?>
                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>
                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>
            </span>Excel</button>
                        <button type="button" class="btn btn-rounded btn-success" data-toggle="modal" data-target="#upload_excel" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-upload color-success"></i>
                    </span>Upload File</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>

                                <th><strong>Product Name</strong></th>
                                <th><strong>Product Code</strong></th>
                                <th><strong>Primary Category</strong></th>
                                <th><strong>Sub Category</strong></th>
                                <th><strong>Brand</strong></th>
                                <th><strong>Stock</strong></th>
                                <th><strong>Product Unit</strong></th>
                                <th><strong>Product Image</strong></th>
                                <th><strong>Product Varient</strong></th>
                                <th><strong>View</strong></th>
                                <?php
                                if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                                    ?>
                                    <th><strong>Action</strong></th>
                                    <?php
                                }
                                ?>
                            </tr>
                            </thead>
                            <?php
                            if($brand == "" && $p_code == "" && $s_category== "" && $p_category == ""&& $p_name == "") {
                                $sql = "SELECT * FROM product ORDER BY id DESC LIMIT $start,10";
                            }
                            else {
                                $sql = "SELECT * FROM product WHERE id > 0 $brandSql$categorySql$pCodeSql$pNameSql$pcategorySql ORDER BY id  LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;

                            $category_id=$row['sub_category'];
                            $unit_id=$row['product_unit'];
                            $brand_id=$row['brand_type'];
                            $product_varient = $row['product_varient'];
                            $product_id = $row['product_id'];
                            $product_cost = $row['product_cost'];
                            $product_price = $row['product_price'];
                            $request_cost = $row['request_cost'];
                            $request_price = $row['request_price'];

                            $sqlRequest = "SELECT * FROM `product` WHERE `product_id`='$product_id'";
                            $resRequest = mysqli_query($conn, $sqlRequest);
                            $rowRequest = mysqli_fetch_assoc($resRequest);
                            $price =  $rowRequest['sub_category'];

                            $sqlSubCategory = "SELECT * FROM `category` WHERE `category_id`='$category_id'";
                            $resSubCategory = mysqli_query($conn, $sqlSubCategory);
                            $rowSubCategory = mysqli_fetch_assoc($resSubCategory);
                            $SubCategory =  $rowSubCategory['sub_category'];

                            $sqlUnit = "SELECT * FROM `unit` WHERE `unit_subname`='$unit_id'";
                            $resUnit = mysqli_query($conn, $sqlUnit);
                            $rowUnit = mysqli_fetch_assoc($resUnit);
                            $unit_name =  $rowUnit['unit_name'];

                            $sqlBrand = "SELECT * FROM `brand` WHERE `brand_id`='$brand_id'";
                            $resBrand = mysqli_query($conn, $sqlBrand);
                            $rowBrand = mysqli_fetch_assoc($resBrand);
                            $brand_name =  $rowBrand['brand_name'];
                            $img = $row['img'];
                            if($img == 1) {
                                $img_upload = "badge-success";
                                $img_modal = '#image_modal';
                            }
                            else {
                                $img_upload = "badge-danger";
                            }

                            //   $career_dates =   $row['career_date'];
                            //   $career_date =   date('d-F-Y');
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td>
                                    <?php
                                    $product_name = $row['product_name'];
                                    $product_variant = $row['product_varient'];

                                    if (empty($product_variant)) {
                                        echo $product_name;
                                    } else {
                                        echo $product_name . ' - ' . $product_variant;
                                    }
                                    ?>
                                </td>
                                <td> <?php echo $row['product_code']?> </td>
                                <td> <?php echo $row['primary_category']?> </td>
                                <td> <?php echo $SubCategory?> </td>
                                <td> <?php echo $brand_name?> </td>
                                <td> <?php echo $row['stock_qty']?> </td>
                                <td> <?php echo $unit_name?> </td>
                                <td style="cursor: pointer">   <span class="badge badge-pill <?php echo $img_upload?> ml-2" data-toggle="modal" data-target="<?php echo $img_modal?>" onclick="imgModal('<?php echo $row['product_id']; ?>')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                                              <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                              <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                                            </svg>
                                        </span>
                                </td>
                                <td style="cursor: pointer">
                                    <a href="../../product_vari/?product_id=<?php echo $row['product_id'];?>" class="btn btn-block btn-primary">Varient</a>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="<?php echo $website?>/inventory/product/show_file.php?product_id=<?php echo $row['product_id']?>"
                                           class="btn btn-primary shadow btn-xs sharp me-1"><i
                                                    class="fa fa-eye"></i></a>
                                    </div>
                    </div>
                    </td>
                    <?php
                    if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Stores'|| $_COOKIE['role'] == 'Sales') {
                        ?>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                    <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                </button>
                                <div class="dropdown-menu">
                                    <?php
                                    if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                                        ?>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['product_id'];?>')">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#term_list" style="cursor: pointer" onclick="term('<?php echo $row['product_id'];?>','<?php echo $row['product_cost'] ?>','<?php echo $row['product_price']?>')">Change price</a>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($_COOKIE['role'] == 'Super Admin') {
                                        ?>
                                        <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $row['product_id'];?>')">Delete</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                        <?php
                    }
                    ?>
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
                                    <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>"><i class="fa-solid fa-less-than"></i></a></li>
                                    <?php
                                }
                                if($brand == "" && $p_code == "" && $s_category== "" && $p_category == ""&& $p_name == "") {
                                    $sql = "SELECT COUNT(id) as count FROM product";
                                }
                                else {
                                    $sql = "SELECT COUNT(id) as count FROM product WHERE id > 0 $brandSql$categorySql$pCodeSql$pNameSql$pcategorySql";
                                }
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

    <div class="modal fade" id="term_list"  data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="term_title">Term And Condition</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="basic-form" style="color: black;">
                        <form id="term_form" autocomplete="off">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Product id</label>
                                    <input type="text" class="form-control" id="pr_id" name="pr_id" readonly style="border-color: #181f5a;color: black">
                                    <input type="hidden" class="form-control"  id="change_id" name="change_id">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Product Price</label>
                                    <input type="text" class="form-control" id="pro_price" name="pro_price" placeholder="Product Price" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Product Cost</label>
                                    <input type="text" class="form-control" id="pro_cost" name="pro_cost" placeholder="Product Cost" style="border-color: #181f5a;color: black">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                    <button type="button" class="btn btn-primary" id="term_btn">ADD</button>
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
                        <form id="expense_form" autocomplete="off">
                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Product Name *</label>
                                    <input type="text" class="form-control" placeholder="Product Name" id="product_name" name="product_name" style="border-color: #181f5a;color: black">
                                    <input type="hidden" class="form-control"  id="api" name="api">
                                    <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                    <input type="hidden" class="form-control"  id="product_id" name="product_id">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Product Code *</label>
                                    <input type="text" class="form-control" placeholder="Product Code" id="product_code" name="product_code" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>HSN / SAC Code *</label>
                                    <input type="text" class="form-control" placeholder="HSN / SAC Code" id="hsn_code" name="hsn_code" style="border-color: #181f5a;color: black">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Primary Category *</label>
                                    <select  class="form-control" id="primary_category" name="primary_category" style="border-color: #181f5a;color: black">
                                        <option value=''>Select Primary category</option>
                                        <option value='foundry'>Foundry</option>
                                        <option value='distributor'>Distributor</option>
                                        <option value='others'>Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Sub Category *</label>
                                    <select  class="form-control" id="sub_category" name="sub_category" style="border-color: #181f5a;color: black">
                                        <option value=''>select Sub Category</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Brand Type</label>
<!--                                    <select  class="form-control js-example-disabled-results" id="brand_type" name="brand_type" style="border-color: #181f5a;color: black">-->
                                    <select  class="form-control" id="brand_type" name="brand_type" style="border-color: #181f5a;color: black">
                                        <?php
                                        $sqlDevice = "SELECT * FROM `brand`";
                                        $resultDevice = mysqli_query($conn, $sqlDevice);
                                        if (mysqli_num_rows($resultDevice) > 0) {
                                            while ($rowDevice = mysqli_fetch_array($resultDevice)) {
                                                ?>
                                                <option
                                                        value='<?php echo $rowDevice['brand_id']; ?>'><?php echo strtoupper($rowDevice['brand_name']); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Product Cost *</label>
                                    <input type="number" class="form-control" placeholder="Product Cost" id="product_cost" name="product_cost" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Product Price *</label>
                                    <input type="number" class="form-control" placeholder="Product Price" id="product_price" name="product_price" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Product unit</label>
                                    <select  class="form-control" id="product_unit" name="product_unit" style="border-color: #181f5a;color: black">

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

                                <div class="form-group col-md-4">
                                    <label>Stock Alert *</label>
                                    <input type="number" class="form-control" placeholder="Stock Alert" id="stock_alert" name="stock_alert" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tax (%)</label>
                                    <select  class="form-control" id="order_tax" name="order_tax" style="border-color: #181f5a;color: black">
                                        <option value=18>18%</option>
                                        <option value=12>12%</option>
                                        <option value=5>5%</option>
                                        <option value=0>0%</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tax Type *</label>
                                    <select  class="form-control" id="tax_type" name="tax_type" style="border-color: #181f5a;color: black">
                                        <option value=''>Select Receipt Type</option>
                                        <option value='exclusive'>Exclusive</option>
                                        <option value='inclusive'>Inclusive</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="upload_image">Product Image (1 MB) </label>
                                    <input type="file" class="form-control" placeholder="Upload Image" id="upload_image" name="upload_image" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Description </label>
                                    <textarea class="form-control" placeholder="Description" id="description" name="description" rows="4" cols="50" style="border-color: #181f5a;color: black"></textarea>
                                </div>
                                <!--                                    <div class="form-group col-md-12">-->
                                <!--                                        <label>This Product Has Multi Varients</label>-->
                                <!--                                        <label class="switch">-->
                                <!--                                            <input type="checkbox" checked name="TT_sticky_header" id="TT_sticky_header_function" value="{TT_sticky_header}" onchange="stickyheaddsadaer(this)"/>-->
                                <!--                                         <input type="checkbox"  id="access_status"  name="access_status">-->
                                <!--                                            <span class="slider round"></span>-->
                                <!--                                        </label>-->
                                <!--                                    </div>-->
                                <!--                                    <div class="form-group col-md-12" id="product_varients">-->
                                <!--                                                                               <label>Order Tax *</label>-->
                                <!--                                        <input type="text" class="form-control" placeholder="Add product varient" id="paroduct_varient" name="paroduct_varient" style="border-color: #181f5a;color: black">-->
                                <!--                                    </div>-->
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
<div class="modal fade" id="image_modal"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: 1px solid transparent;">



                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <img src="" style="width:100%" id="modal_images">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="upload_excel"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: 1px solid transparent;">

                <h5 class="modal-title" id="title"></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form" style="color: black;">
                    <form id="itemProfileExcel" autocomplete="off">
                        <div class="form-row">
                            <div class="form-group col-md-12" style="display: flex;justify-content: space-evenly;">
                                <a href="UploadExcelFormats.csv" download="UploadExcelFormats.csv">

                                    <button type="button" class="btn btn-rounded btn-success"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>
                                        </span>Download</button>
                                </a>
                                <p style="margin-top: 9px;">Sample Excel File To Upload</p>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Upload Excel File (Extension Should Be .csv)</label>
                                <input type="file" class="form-control"  type="file" name="file" id="excel_file" style="border-color: #181f5a;color: black">
                            </div>

                        </div>

                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;">Close</button>
                <button type="button" class="btnn_excel_ajax btn btn-primary" id="btnn_excel_ajax">Upload</button>
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
<script>
    function imgModal(src) {
        document.getElementById('modal_images').setAttribute("src",'product_img/'+src+'.jpg');

    }

</script>

<script src="../../vendor/global/global.min.js"></script>
<script src="../../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../../vendor/chart.js/Chart.bundle.min.js"></script>
<script src="../../js/custom.min.js"></script>
<script src="../../js/dlabnav-init.js"></script>
<script src="../../vendor/owl-carousel/owl.carousel.js"></script>
<script src="../../vendor/peity/jquery.peity.min.js"></script>
<!--<script src="../vendor/apexchart/apexchart.js"></script>-->
<script src="../../js/dashboard/dashboard-1.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../../js/plugins-init/jquery.validate-init.js"></script>
<script src="../vendor/moment/moment.min.js"></script>
<script src="../../vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../vendor/summernote/js/summernote.min.js"></script>
<script src="../../js/plugins-init/summernote-init.js"></script>

<script src="../../vendor/select2/js/select2.full.min.js"></script>
<script src="../../js/plugins-init/select2-init.js"></script>
<script>


    function term(pro_id,cost,price) {
        $("#term_title").html("cost & Price change " + pro_id);
        $('#term_form')[0].reset();
        // $('#apii').val("repayment_api.php")
        $('#pr_id').val(pro_id);
        $('#pro_cost').val(cost);
        $('#pro_price').val(price);

    }
    //add Term And Condition
    $('#term_btn').click(function () {
        $("#term_form").valid();
        if($("#term_form").valid()==true) {

            //var loan_id = "<?php //echo $loan_id?>//";
            // var loan_id = 56
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "request_api.php",
                data: $('#term_form').serialize(),
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

                                document.getElementById("term_btn").disabled = false;
                                document.getElementById("term_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("term_btn").disabled = false;
                    document.getElementById("term_btn").innerHTML = 'Add';
                }

            });

        } else {
            document.getElementById("term_btn").disabled = false;
            document.getElementById("term_btn").innerHTML = 'Add';

        }

    });

    //to validate form
    $("#term_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                pr_id: {
                    required: true
                },
                pro_price: {
                    required: true
                },
                pro_cost: {
                    required: true
                },



            },
            // Specify validation error messages
            messages: {
                pr_id: "*This field is required",
                pro_price: "*This field is required",
                pro_cost: "*This field is required",

            }
            // Make sure the form is submitted to the destination defined
        });



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

    function primary_category_fun(primary_category, sub_category) {
        if (primary_category != '') {
            $.ajax({
                type: "POST",
                url: "category_api.php",
                data: 'primary_category=' + primary_category,
                dataType: "json",
                success: function(res) {
                    if (res.status == 'success') {
                        var category_sub = res.sub_category;
                        var category_sub_id = res.category_sub_id;

                        // Clear previous options
                        var subCategoryElement = document.getElementById('sub_category');
                        subCategoryElement.innerHTML = '';

                        // Add an empty option with the name "Select sub category"
                        var emptyOption = document.createElement('option');
                        emptyOption.value = '';
                        emptyOption.innerHTML = 'Select sub category';
                        emptyOption.setAttribute('selected', 'selected');
                        subCategoryElement.appendChild(emptyOption);

                        for (let i = 0; i < category_sub.length; i++) {
                            var opt = document.createElement('option');
                            opt.value = category_sub_id[i];
                            opt.innerHTML = category_sub[i];
                            subCategoryElement.appendChild(opt);
                        }

                        if (category_sub != undefined) {
                            $("#sub_category").val(sub_category);
                        }
                        $("#sub_category").trigger("change");
                    } else if (res.status == 'failure') {
                        Swal.fire("Invalid", res.msg, "warning");
                    } else if (res.status == 'failure') { // This condition is redundant, consider removing or updating
                        Swal.fire("Failure", res.msg, "error");
                    }
                },
                error: function() {
                    Swal.fire("Check your network connection");
                }
            });
        }
    }


    function addTitle() {
        $("#title").html("Add Product");
        $('#expense_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Product- "+data);
        $('#expense_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'product_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {

                    $("#product_name").val(res.product_name);
                    $("#paroduct_varient").val(res.product_varient);
                    $("#product_code").val(res.product_code);
                    $("#primary_category").val(res.primary_category);
                    $("#sub_category").val(res.sub_category);
                    $("#brand_type").val(res.brand_type);
                    $("#product_cost").val(res.product_cost);
                    $("#product_price").val(res.product_price);
                    $("#product_unit").val(res.product_unit);
                    $("#sales_unit").val(res.sales_unit);
                    $("#purchase_unit").val(res.purchase_unit);
                    $("#stock_alert").val(res.stock_alert);
                    $("#order_tax").val(res.order_tax);
                    $("#tax_type").val(res.tax_type);
                    $('#description').val(res.description);
                    $('#hsn_code').val(res.hsn_code);
                    $("#old_pa_id").val(res.product_id);
                    $("#product_id").val(res.product_id);

                    $("#sub_category option").remove();
                    primary_category_fun(res.primary_category,res.sub_category);

                    $("#sub_category").trigger("change");

                    var edit_model_title = "Edit Product - "+data;
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
                product_name: {
                    required: true
                },
                product_code: {
                    required: true
                },
                category_type: {
                    required: true
                },
                brand_type: {
                    required: true
                },
                product_cost: {
                    required: true
                },
                product_price: {
                    required: true
                },
                product_unit: {
                    required: true
                },
                sales_unit: {
                    required: true
                },
                purchase_unit: {
                    required: true
                },
                stock_alert: {
                    required: true
                },
                // order_tax: {
                //     required: true
                // },
                tax_type: {
                    required: true
                },
                // description: {
                //     required: true
                // },
                primary_category: {
                    required: true
                },
                hsn_code: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                product_name: "*This field is required",
                product_cost: "*This field is required",
                product_code: "*This field is required",
                product_price: "*This field is required",
                product_unit: "*This field is required",
                purchase_unit: "*This field is required",
                category_type: "*This field is required",
                // product_unit: "*This field is required",
                brand_type: "*This field is required",
                stock_alert: "*This field is required",
                order_tax: "*This field is required",
                tax_type: "*This field is required",
                // description: "*This field is required",
                primary_category: "*This field is required",
                hsn_code: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {

        $("#expense_form").valid();

        if($("#expense_form").valid()==true) {

            var api = $('#api').val();
            var form = $("#expense_form");
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


</body>
</html>
