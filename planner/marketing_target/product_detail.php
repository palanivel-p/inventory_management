<?php Include("../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");
$callDate = date('Y-m-d');
error_reporting(0);
$page= $_GET['page_no'];
$target_id = $_GET['target_id'];
//$target = $_GET['target'];
$product_id= $_GET['product_id'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-d');
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


if($product_id != ""){
    $product_idSql= " AND product_id LIKE '%".$product_id."%'";
}
else{
    $product_idSql ="";
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
    <title>Market Target</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://erp.aecindia.net/includes/AEC.png">
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
    <link rel="stylesheet" href="../../vendor/select2/css/select2.min.css">
    <script src="../../vendor/select2/js/select2.full.min.js"></script>
    <script src="../../js/plugins-init/select2-init.js"></script>
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
    $header_name ="Market Target";
    Include ('../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Market Target</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">
                        <div class="form-group mx-sm-3 mb-2">
                                <label>Product</label>
                                <select data-search="true" class="form-control js-example-disabled-results" id="product_id" name="product_id" style="border-radius:20px;color:black;border:1px solid black;">
                                   <option value="">Select Product</option>
                                    <?php
                                    $sqlDevice = "SELECT * FROM `product`";
                                    $resultDevice = mysqli_query($conn, $sqlDevice);
                                    if (mysqli_num_rows($resultDevice) > 0) {
                                        while ($rowDevice = mysqli_fetch_array($resultDevice)) {
                                            ?>
                                            <option
                                                    value='<?php echo $rowDevice['product_id']; ?>'><?php echo strtoupper($rowDevice['product_name']); ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <input type="hidden" class="form-control" placeholder="Search By Name" name="target_id" id="target_id" value="<?php echo $target_id?>" style="border-radius:20px;color:black;" >
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                        </form>
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>-->
<!--                                                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
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
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Product Name</strong></th>
                                <th><strong>Target Qty</strong></th>
                                <th><strong>Achieved Qty</strong></th>
                            </tr>
                            </thead>
                            <?php
                            $sNo = $start;

                            if($product_id == "") {
                            $sql = "SELECT * FROM target_detail WHERE target_id='$target_id' ORDER BY id DESC LIMIT $start,10";
                                }
                                else{
                                    $sql = "SELECT * FROM target_detail WHERE target_id='$target_id' $product_idSql ORDER BY id  LIMIT $start,10";
//                                           $sql = "SELECT * FROM target  ORDER BY next_follow  LIMIT $start,10";
                                }

//                            $sqlTargetD = "SELECT * FROM target_detail WHERE target_id='$target_id'";
                            $resultTargetD = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($resultTargetD)>0) {
                            while($rowTargetD = mysqli_fetch_assoc($resultTargetD)) {
                            $sNo++;
                            $sqlTarget = "SELECT * FROM `target` WHERE `target_id`='$target_id'";
                            $resTarget = mysqli_query($conn, $sqlTarget);
                            $rowTarget = mysqli_fetch_assoc($resTarget);
                            $year =  $rowTarget['year'];
                            $months =  $rowTarget['month'];
                            $customer_name =  $rowTarget['customer_name'];

                            $product_id = $rowTargetD['product_id'];
                            $qty = $rowTargetD['qty'];

                            $sqlProduct = "SELECT * FROM `product` WHERE `product_id`='$product_id'";
                            $resProduct = mysqli_query($conn, $sqlProduct);
                            $rowProduct = mysqli_fetch_assoc($resProduct);
                            $product_name =  $rowProduct['product_name'];

                            $sqlAchievedQty = "SELECT SUM(qty) AS sum_qty FROM `sale_details` WHERE product_id = '$product_id' AND MONTH(sale_date) = '$months' AND YEAR(sale_date) = '$year'";
                            $resAchievedQty = mysqli_query($conn, $sqlAchievedQty);
                            $rowAchievedQty = mysqli_fetch_assoc($resAchievedQty);
                            $achieved_qty = ($rowAchievedQty['sum_qty']) ? $rowAchievedQty['sum_qty'] : 0;
                            ?>

                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $customer_name;?></td>
                                <td><?php echo $product_name;?></td>
                                <td><?php echo $qty;?></td>
                                <td><?php echo $achieved_qty;?></td>

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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&product_id=<?php echo $product_id ?>&target_id=<?php echo $target_id?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }

                                 if($product_id == "") {
                                     $sql = "SELECT COUNT(id) as count FROM target_detail WHERE target_id='$target_id'";
                                            }
                                else{
                                    $sql = "SELECT COUNT(id) as count FROM target_detail WHERE target_id='$target_id' $product_idSql";
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
                                                                                               href="?page_no=<?php echo $i ?>&product_id=<?php echo $product_id ?>&target_id=<?php echo $target_id?>"><?php echo $i ?></a>
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
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&product_id=<?php echo $product_id ?>&target_id=<?php echo $target_id?>"><i class="fa-solid fa-greater-than"></i></a></li>
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
                        data: 'target_id='+data,
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
    //select search
    var $disabledResults = $(".js-example-disabled-results");
    $disabledResults.select2();

</script>


</body>
</html>
