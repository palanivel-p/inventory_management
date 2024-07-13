<?php Include("../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");
$callDate = date('Y-m-d');
error_reporting(0);
$page= $_GET['page_no'];
//$market_id= $_GET['market_id'];
//$e_category= $_GET['e_category'];
//$s_name= $_GET['s_name'];

$product_id= $_GET['customer_name'];
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
    $product_idSql= " AND customer_name LIKE '%".$product_id."%'";
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
$plCount =0;
$plCounts =1;

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
                    <h4 class="card-title">Market Target List</h4>
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
                        <?php
                        if($_COOKIE['role'] = 'Super Admin'){
                        ?>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>
                        <?php
                        }
                        ?>
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
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
                                <th><strong>Month</strong></th>
                                <th><strong>Marketing Person</strong></th>
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Target Visit</strong></th>
                                <th><strong>Achieved Visit</strong></th>
                                <th><strong>Target Commitment</strong></th>
                                <th><strong>Achieved Commitment</strong></th>
                                <th><strong>Product Details</strong></th>
                                <?php
                                if($_COOKIE['role'] == 'Super Admin') {
                                    ?>
                                    <th><strong>Action</strong></th>
                                    <?php
                                }
                                ?>
                            </tr>
                            </thead>
                            <?php
                            //                             $sql = "SELECT * FROM market ORDER BY id  LIMIT $start,10";
                            $currentDate = date('Y-m-d');
//                                                        if($product_id == "") {
                            //                                $sql = "SELECT * FROM market WHERE visit_date  BETWEEN '$from_date' AND '$to_date'$addedBy ORDER BY id  LIMIT $start,10";
                                                                $sql = "SELECT * FROM target WHERE id>0 $addedBy ORDER BY id DESC LIMIT $start,10";
//                                                        }
//                                                        else{
//                                                            $sql = "SELECT * FROM market WHERE visit_date  BETWEEN '$from_date' AND '$to_date' $product_idSql$addedBy ORDER BY id  LIMIT $start,10";
//                                                                   $sql = "SELECT * FROM target  ORDER BY next_follow  LIMIT $start,10";
//                                                        }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $commitment_value = $row['commitment_value'];
                            $no_visit = $row['no_visit'];
                            $month = $row['month'];
                            $marketing_person = $row['marketing_person'];
                            $customer_name = $row['customer_name'];

                            $month_names = [
                                '01' => 'January',
                                '02' => 'February',
                                '03' => 'March',
                                '04' => 'April',
                                '05' => 'May',
                                '06' => 'June',
                                '07' => 'July',
                                '08' => 'August',
                                '09' => 'September',
                                '10' => 'October',
                                '11' => 'November',
                                '12' => 'December'
                            ];
                            $month_number  = $row['month'];
                            $month_name = $month_names[$month_number];
                            $sqlVisit = "SELECT COUNT(*) AS visit_count FROM marketing WHERE MONTH(visit_date) = '$month_number'";
                            $resVisit = mysqli_query($conn, $sqlVisit);
                            $rowVisit = mysqli_fetch_assoc($resVisit);
                            $achieved_visit = ($rowVisit['visit_count']) ? $rowVisit['visit_count'] : "NA";

                            // Fetch sum of commitment values for the current month from database
                            $sqlCommitment = "SELECT SUM(commitment_value) AS sum_commitment FROM marketing WHERE MONTH(visit_date) = '$month_number'";
                            $resCommitment = mysqli_query($conn, $sqlCommitment);
                            $rowCommitment = mysqli_fetch_assoc($resCommitment);
                            $achieved_commitment = ($rowCommitment['sum_commitment']) ? $rowCommitment['sum_commitment'] : "NA";

//                            $sqlVisit = "SELECT MONTH(visit_date) AS visit_month, COUNT(*) AS visit_count FROM market GROUP BY visit_month";
//                            $resVisit = mysqli_query($conn, $sqlVisit);
//                            $rowVisit = mysqli_fetch_assoc($resVisit);
//                            $Visit_month =  $rowVisit['visit_month'];
//                            $visit_count =  $rowVisit['visit_count'];
//
//                            if ($month_number == $Visit_month || ($month_number < 10 && $month_number == intval($Visit_month))) {
//                                $achieved_visit = $visit_count;
//                            } else {
//                                $achieved_visit = "NA";
//                            }
                            ?>

                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $month_name;?></td>
                                <td><?php echo $marketing_person;?></td>
                                <td><?php echo $customer_name;?></td>
                                <td><?php echo $no_visit;?></td>
                                <td><?php echo $achieved_visit;?></td>
                                <td> <?php echo $commitment_value?> </td>
                                <td> <?php echo $achieved_commitment?> </td>

                                <td> <a href="product_detail.php?target_id=<?php echo $row['target_id'] ?>"><span class="badge badge-pill badge-success">View</span></a></td>
                                <?php
                                if($_COOKIE['role'] == 'Super Admin') {
                                    ?>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp"
                                                    data-toggle="dropdown">
                                                <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" style="cursor: pointer"
                                                   onclick="delete_model('<?php echo $row['target_id']; ?>')">Delete</a>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&product_id=<?php echo $product_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }

                                    //                                    $sql = 'SELECT COUNT(id) as count FROM market';
                                    //                                    if($product_id == "") {
                                    $sql = "SELECT COUNT(id) as count FROM target WHERE id>0 $addedBy";
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
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tit">Market Target</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="expense_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-6" id="m_date">
                                        <label>Month *</label>
                                        <select class="form-control" name="month" id="month" style="border-color: #181f5a;color: black">
                                            <option value="">Select Month</option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="y_date">
                                        <label>Year *</label>
                                        <select class="form-control" name="year" id="year" style="border-color: #181f5a;color: black">
                                            <option value="">Select Year</option>
                                            <?php
                                            // Get the current year
                                            $currentYear = date('Y');
                                            // Generate options for the next 5 years
                                            for ($year = $currentYear; $year <= $currentYear + 4; $year++) {
                                                echo "<option value=\"$year\">$year</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6" id="v_date">
                                        <label>No of visit *</label>
                                        <input type="number" class="form-control" id="no_visit" name="no_visit" placeholder="No Of Viist" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="target_id" name="target_id">
                                    </div>
                                    <div class="form-group col-md-6" >
                                        <label>Commitment Value *</label>
                                        <input type="number" class="form-control" id="commitment_value" name="commitment_value" placeholder="Commitment Value" style="border-color: #181f5a;color: black">
                                    </div>
<!--                                    <div class="form-group col-md-6" >-->
<!--                                        <label>Marketing Person *</label>-->
<!--                                        <input type="text" class="form-control" id="marketing_person" name="marketing_person" placeholder="Marketing Person" style="border-color: #181f5a;color: black">-->
<!--                                    </div>-->
                                    <div class="form-group col-md-6">
                                        <label>Marketing Person *</label>
                                        <select data-search="true" class="form-control" id="marketing_person" name="marketing_person" style="border-color: #181f5a;color: black">
                                            <option value=""> Select Marketing Person</option>
                                            <?php
                                            $sqlUser = "SELECT * FROM `user` WHERE role = 'Market'";
                                            $resultUser = mysqli_query($conn, $sqlUser);
                                            if (mysqli_num_rows($resultUser) > 0) {
                                                while ($rowUser = mysqli_fetch_array($resultUser)) {
                                                    ?>
                                                    <option
                                                            value='<?php echo $rowUser['user_id']; ?>'><?php echo strtoupper($rowUser['f_name']); ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Customer Name*</label>
                                        <select data-search="true" class="form-control" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black">
                                            <option value=""> Select Customer</option>
                                            <?php
                                            $sqlSupplier = "SELECT * FROM `market_profile`";
                                            $resultSupplier = mysqli_query($conn, $sqlSupplier);
                                            if (mysqli_num_rows($resultSupplier) > 0) {
                                                while ($rowSupplier = mysqli_fetch_array($resultSupplier)) {
                                                    ?>
                                                    <option
                                                            value='<?php echo $rowSupplier['customer_name']; ?>'><?php echo strtoupper($rowSupplier['customer_name']); ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row" style="margin-top: 20px;padding: 15px;">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="" id="personalLoan_div">
                                                <?php
                                                if($plCount > 0) {
                                                    $sqlPersonalLoan = "SELECT * FROM market_detail WHERE market_id =''";

                                                    $resultPersonalLoan = mysqli_query($conn, $sqlPersonalLoan);
                                                    if (mysqli_num_rows($resultPersonalLoan)>0) {
                                                        $plCount_id = 0;
                                                        while($rowPersonalLoan = mysqli_fetch_assoc($resultPersonalLoan)) {
                                                            $plCount_id++;

                                                            $product_description = $rowPersonalLoan['product_description'];
                                                            $quantity = $rowPersonalLoan['quantity'];
                                                            $carton_number = $rowPersonalLoan['carton_number'];
                                                            $total_carton = $rowPersonalLoan['total_carton'];
                                                            ?>

                                                            <div class="row personalLoanInputs" id="<?php echo 'personalLoan_divRow_'.$plCount_id?>">
                                                                <h2 class="personalLoan_divRow_heading" style="width:100%">Product Quantity Details <?php echo $plCount_id?></h2>

                                                                <div class="col-md-4">
                                                                    <div class="input-block mb-3">
                                                                        <label>QUANTITY</label>
                                                                        <input type="text" class="form-control plAdd" id="<?php echo 'Quantity_'.$plCount_id?>" name="<?php echo 'Quantity_'.$plCount_id?>" value="<?php echo $quantity?>" style="border-color: black;color: black">
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="input-block mb-3">
                                                                    <label>Product Name*</label>
                                                                    <select data-search="true" class="form-control js-example-disabled-results tail-select w-full" id="product_name_<?php echo $plCount_id ?>" name="product_name_<?php echo $plCount_id ?>" style="border-color: #181f5a;color: black" onchange="supplierfun(this.value)">
                                                                        <option value="">Select Product</option>
                                                                        <?php
                                                                        $sqlProducts = "SELECT * FROM `product`";
                                                                        $resultProducts = mysqli_query($conn, $sqlProducts);
                                                                        if (mysqli_num_rows($resultProducts) > 0) {
                                                                            while ($rowProduct = mysqli_fetch_array($resultProducts)) {
                                                                                ?>
                                                                                <option value='<?php echo $rowProduct['product_id']; ?>' <?php if ($rowProduct['product_id'] == $rowPersonalLoan['product_id']) echo "selected"; ?>><?php echo strtoupper($rowProduct['product_name']); ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
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

                                    <div class="form-group col-sm-6" style="margin-left: 12px">
                                        <button onclick="addPL()" type="button" class="btn btn-success w-30" style="width: 88px">Add</button>
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
    <!-- View Modal -->
    <div class="modal fade" id="view_modal"  data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tit_view">Product Target</h5>
                    <!-- Close button -->
                    <a href="<?php echo $website?>/planner/marketing_target/"><button class="btn btn-danger" type="button"><span>&times;</span></button></a>
<!--                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>-->
<!--                    <button type="button" class="close" data-dismiss="modal">-->
<!--                        <a href="https://erp.aecindia.net/planner/marketing_target" style="text-decoration: none; color: inherit;">-->
<!--                            Visit Report</a><span>&times;</span>-->
<!--                    </button>-->

                </div>
                <div class="modal-body">
                    <div class="basic-form" style="color: black;">
                        <form id="view_form" autocomplete="off">
                            <div class="form-row">
                                <?php
                                $target_id = $_GET['target_id'];
//                                $months = $_GET['months'];
//                                $month = '09'; // Or the value from your variable
//                                $year = date('Y');
                                if($plCounts > 0) {
                                    $sqlTargetD = "SELECT * FROM target_detail WHERE target_id='$target_id'";

                                    $resultTargetD = mysqli_query($conn, $sqlTargetD);
                                    if (mysqli_num_rows($resultTargetD)>0) {
                                        $plCount_id = 0;
                                        while($rowTargetD = mysqli_fetch_assoc($resultTargetD)) {
                                            $plCount_id++;

                                            $sqlTarget = "SELECT * FROM `target` WHERE `target_id`='$target_id'";
                                            $resTarget = mysqli_query($conn, $sqlTarget);
                                            $rowTarget = mysqli_fetch_assoc($resTarget);
                                            $year =  $rowTarget['year'];
                                            $months =  $rowTarget['month'];

                                            $product_id = $rowTargetD['product_id'];
                                            $qty = $rowTargetD['qty'];

                                            $sqlProduct = "SELECT * FROM `product` WHERE `product_id`='$product_id'";
                                            $resProduct = mysqli_query($conn, $sqlProduct);
                                            $rowProduct = mysqli_fetch_assoc($resProduct);
                                            $product_name =  $rowProduct['product_name'];

                                            // Fetch the sum of quantity from sale_details table for the current product and sale date
//                                          echo  $sqlAchievedQty = "SELECT SUM(qty) AS sum_qty FROM `sale_details` WHERE product_id = '$product_id' AND sale_date = '$months'";
                                              $sqlAchievedQty = "SELECT SUM(qty) AS sum_qty FROM `sale_details` WHERE product_id = '$product_id' AND MONTH(sale_date) = '$months' AND YEAR(sale_date) = '$year'";
                                            $resAchievedQty = mysqli_query($conn, $sqlAchievedQty);
                                            $rowAchievedQty = mysqli_fetch_assoc($resAchievedQty);
                                            $achieved_qty = ($rowAchievedQty['sum_qty']) ? $rowAchievedQty['sum_qty'] : 0;

                                            ?>

                                            <div class="row personalLoanInputs" id="<?php echo 'personalLoan_divRow_'.$plCount_id?>">
                                                <h2 class="personalLoan_divRow_heading" style="width:100%">Product Details <?php echo $plCount_id?></h2>

                                                <div class="col-md-6">
                                                    <div class="input-block mb-3">
                                                        <label>Product Name</label>
                                                        <input type="text" class="form-control plAdd" id="<?php echo 'product_name_'.$plCount_id?>" name="<?php echo 'product_name_'.$plCount_id?>" value="<?php echo $product_name?>" readonly style="border-color: black;color: black">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-block mb-3">
                                                        <label>Target Qty</label>
                                                        <input type="text" class="form-control plAdd" id="<?php echo 'Quantity_'.$plCount_id?>" name="<?php echo 'Quantity_'.$plCount_id?>" value="<?php echo $qty?>" readonly style="border-color: black;color: black">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-block mb-3">
                                                        <label>Achieved Qty</label>
                                                        <input type="text" class="form-control plAdd" id="<?php echo 'achieved_qty_'.$plCount_id?>" name="<?php echo 'achieved_qty_'.$plCount_id?>" value="<?php echo $achieved_qty?>" readonly style="border-color: black;color: black">
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?php echo $website?>/planner/marketing_target/"><button class="btn btn-danger" style="background-color: red; color: white;text-transform: uppercase" type="button"><span>Close</span></button></a>
<!--                    <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>-->
<!--                    <button type="button" class="btn btn-primary" id="view_btn">ADD</button>-->
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
                                    <label> Visit From Date </label>
                                    <input type="date"  class="form-control" id="f_date" name="f_date" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Visit To Date </label>
                                    <input type="date"  class="form-control" id="t_date" name="t_date" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Marketing Person </label>
                                    <input type="text"  class="form-control" placeholder="Product Name" id="p_name" name="p_name" style="border-color: #181f5a;color: black">
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

    //Status
    function status_fun(data , month_name) {
        var url = new URL(window.location.href);
        var params = new URLSearchParams(url.search);
        if (!params.has('target_id') || !params.has('month_name')) {
            window.location.href = "https://erp.aecindia.net/planner/marketing_target/?target_id=" + data + "&month_name=" + month_name;
        }
    }
    // function status_fun(data, month , month_name) {
    //     window.location.href = "https://erp.aecindia.net/planner/marketing_target/?purchase_sts=" + data + "&months=" + month + "&month_name=" + month_name;
    // }

    //status close

    var sts = '<?php echo  $_GET['target_id']?>';
    var month_name = '<?php echo  $_GET['month_name']?>';
    if(sts != ''){
        var edit_model_title = "Add Status - "+sts;
        $('#view_btn').html("Save");
        $('#tit_view').html("Target - " + month_name);
        $('#p_id').val(sts);
        $('#view_modal').modal('show');
    }

    $("#expense_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                marketing_person: {
                    required: true
                },
                commitment_value: {
                    required: true
                },
                no_visit: {
                    required: true
                },
                year: {
                    required: true
                },
                month: {
                    required: true
                },
                customer_name: {
                    required: true
                },
            },
            // Specify validation error messages
            messages: {
                marketing_person: "*Please Enter Mrket Person Name",
                commitment_value: "*Please Select The Date",
                no_visit: "*Please Select The Date",
                year: "*Please Select The Date",
                month: "*Please Select The Date",
                customer_name: "*Please Select The Date",
            }
            // Make sure the form is submitted to the destination defined
        });
    //add data
    $('#add_btn').click(function () {


        $("#expense_form").valid();

        if($("#expense_form").valid()==true) {

            let inputArr = [];

            let personalLoan = {};
            let personalLoanAr = [];

            const personalLoanInputsS = document.getElementsByClassName("personalLoanInputs");

            // personalLoan_divRow_
            for(let a=0;a<personalLoanInputsS.length;a++){
                console.log(personalLoanInputsS[a]);
                inputArr.push(personalLoanInputsS[a].id);
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
                    // if(c == 0){
                    // eleValueStr+=inputElements[c].id+'%';
                    // }else {
                    eleValueStr+=inputElements[c].value+'%';
                    // }

                }

                let keyNa = 'pl'+(b+1);
                personalLoan[keyNa] = eleValue;
                personalLoanAr.push(eleValueStr);

            }

            console.log(personalLoan);

            var form = $("#expense_form");
            var formData = new FormData(form[0]);
            var api= document.getElementById('api').value;

            // formData.append('personalLoan',JSON.stringify(personalLoan));
            formData.append('personalLoan',personalLoanAr);

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';


            Swal.fire({
                title: "Update",
                text: "Are you sure want to Update the Product Record?",
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
                            url: api,
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
                    }


                });




        } else {
            document.getElementById("add_btn").disabled = false;
            document.getElementById("add_btn").innerHTML = 'Add';

        }

    });

    var personalLoanCount = <?php echo $plCount?>;
    if(personalLoanCount == 0){
        personalLoanCount = 1;
    }
    var personalLoanCounts = <?php echo $plCount?> + 1;


    function addPL() {

            const headingClassCheck = document.getElementsByClassName("personalLoan_divRow_heading");
            if (headingClassCheck.length > 0) {
                personalLoanCount = personalLoanCount + 1;
            }

            var divRow = document.createElement("div");
            divRow.classList.add("row", "personalLoanInputs");
            divRow.setAttribute("id", 'personalLoan_divRow_' + personalLoanCounts);

            let heading2 = document.createElement("h2");
            heading2.innerHTML = "Product Quantity Details " + personalLoanCount;
            heading2.classList.add("personalLoan_divRow_heading", "col-12"); // Added col-12 class for full width

            divRow.append(heading2);

            // Create a line break after the heading
            let lineBreak = document.createElement("br");
            divRow.append(lineBreak);

            let labelArr = ["product_name_", "Quantity_"];
            let placeholders = ["Enter Product Name", "Enter Quantity"];

        for (let i = 0; i < labelArr.length; i++) {
            let divcol = document.createElement("div");
            divcol.classList.add("col-md-6");

            let divInput = document.createElement("div");
            divInput.classList.add("form-group"); // Add form-group class for proper alignment

            let textL = labelArr[i];
            let resultTxt = textL.replaceAll("_", " ");

            let formLabel = document.createElement("label");
            formLabel.innerHTML = resultTxt.toUpperCase();

            let formInput;

            if (labelArr[i] === "product_name_") {
                formInput = document.createElement("select");
                formInput.classList.add("form-control", "plAdd");
                formInput.setAttribute("id", labelArr[i] + personalLoanCount);
                formInput.setAttribute("name", labelArr[i] + personalLoanCount);
                formInput.style.color = "black";
                formInput.style.borderColor = "black";

                // Create default option
                let defaultOption = document.createElement("option");
                defaultOption.value = "";
                defaultOption.text = "Select Product";
                formInput.appendChild(defaultOption);

                // Fetch products using AJAX
                fetchProducts(formInput);
            } else {
                formInput = document.createElement("input");
                formInput.classList.add("form-control", "plAdd");
                formInput.setAttribute("id", labelArr[i] + personalLoanCount);
                formInput.setAttribute("type", labelArr[i] === "Quantity_" ? "number" : "text");
                formInput.setAttribute("name", labelArr[i] + personalLoanCount);
                formInput.setAttribute("placeholder", placeholders[i]);
                formInput.style.color = "black";
                formInput.style.borderColor = "black";
            }

            divInput.append(formLabel);
            divInput.append(formInput);
            divcol.append(divInput);
            divRow.append(divcol);
        }

        function fetchProducts(selectElement) {
            // Fetch products from the server using AJAX
            fetch('product_api.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Populate select element with products
                    data.forEach(product => {
                        let option = document.createElement("option");
                        option.value = product.product_id;
                        option.text = product.product_name.toUpperCase();
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching products:', error));
        }



        let divcol = document.createElement("div");
            divcol.classList.add("col-md-4");

            let divInput = document.createElement("div");
            divInput.classList.add("form-group"); // Add form-group class for proper alignment

            let btnRe = document.createElement("button");
            btnRe.innerHTML = "Remove";
            btnRe.classList.add("btn", "btn-danger", "w-30", "mr-2");
            btnRe.setAttribute("onclick", "removeEle('personalLoan_divRow_" + personalLoanCounts + "','pl')");
            btnRe.setAttribute("type", "button");
            btnRe.style.marginTop = "30px";
            divInput.append(btnRe);

            // Create and configure the "Add" button

            divcol.append(divInput);
            divRow.append(divcol)

            personalLoanCounts++;

            document.getElementById("personalLoan_div").append(divRow);

    }



    function removeEle(eleId,loanType) {
        document.getElementById(eleId).remove();

        if(loanType == "pl"){
            const headingClass = document.getElementsByClassName("personalLoan_divRow_heading");

            for(let i=0;i<headingClass.length;i++){
                headingClass[i].innerHTML = "Product Details "+(i+1);
                personalLoanCount = i+1;
            }
        }

    }
    function addTitle() {
        $("#api").val("add_api.php");
        $("#title").html('Add Inventory');
        $('#add_btn').html("Add");
        $('#career_list').modal('show');
        $('#expense_form')[0].reset(); // Reset the form


    }

    function editTitle(data) {

        $("#title").html("Edit Target- "+data);
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
</script>


</body>
</html>
