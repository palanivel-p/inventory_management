<?php Include("../../includes/connection.php");
date_default_timezone_set("Asia/kolkata");
$callDate = date('Y-m-d');
error_reporting(0);
$page= $_GET['page_no'];
//$service_id= $_GET['service_id'];
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Service Planner</title>
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
    $header_name ="Service Planner";
    Include ('../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Service Planner</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Service Planner List</h4>
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
                        <a href="<?php echo $website; ?>/planner/service_planner/calender_view.php" class="btn btn-primary mb-2" style="color: white; text-decoration: none;">Calender View</a>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()" style="margin-left: 20px;">ADD</button>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>
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
                                <th><strong>Plan Date</strong></th>
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Meet Person</strong></th>
                                <th><strong>Mobile</strong></th>
                                <th><strong>Communication Through</strong></th>
                                <th><strong>Service Type</strong></th>
                                <th><strong>Added By</strong></th>
                                <th><strong>Notes</strong></th>

                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
                            //                             $sql = "SELECT * FROM Service ORDER BY id  LIMIT $start,10";
                            $currentDate = date('Y-m-d');
                                                        if($product_id == "") {
                            //                                $sql = "SELECT * FROM Service WHERE visit_date  BETWEEN '$from_date' AND '$to_date'$addedBy ORDER BY id  LIMIT $start,10";
                                                            $sql = "SELECT * FROM service WHERE next_follow > '$currentDate' ORDER BY id DESC LIMIT $start,10";
                                                        }
                                                        else{
//                                                            $sql = "SELECT * FROM service WHERE visit_date  BETWEEN '$from_date' AND '$to_date' $product_idSql$addedBy ORDER BY id  LIMIT $start,10";
                                                            $sql = "SELECT * FROM service WHERE next_follow  BETWEEN '$from_date' AND '$to_date'$product_idSql ORDER BY id  LIMIT $start,10";
                                                        }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $addedBy = $row['added_by'];
                            $sqlUser = "SELECT * FROM `user` WHERE `user_id`='$addedBy'";
                            $resUser = mysqli_query($conn, $sqlUser);
                            $rowUser = mysqli_fetch_assoc($resUser);
                            $User_name =  $rowUser['f_name'];
                            if($User_name == ''){
                                $user='Super Admin';
                            }
                            else{
                                $user = $User_name;
                            }
                            $v_date = $row['next_follow'];
                            $visite_date = date('d-m-Y', strtotime($v_date));

                            $customer_name=$row['customer_name'];

                            if($row['payment_status'] == 'paid'){
                                $statColor = 'success';
                                $statCont = 'Paid';
                            }
                            else {
                                $statColor = 'danger';
                                $statCont = 'UnPaid';
                            }
                            if($row['mobile'] == ''){
                                $mobile='NA';
                            }
                            else{
                                $mobile = $row['mobile'];
                            }
                            if($row['assigned_to'] == ''){
                                $assigned_to='NA';
                            }
                            else{
                                $assigned_to = $row['assigned_to'];
                            }
                            if($row['notes'] == ''){
                                $notes='NA';
                            }
                            else{
                                $notes = $row['notes'];
                            }
                            if($row['communication'] == ''){
                                $communication='NA';
                            }
                            else{
                                $communication = $row['communication'];
                            }
                            if($row['service_type'] == ''){
                                $service_type='NA';
                            }
                            else{
                                $service_type = $row['service_type'];
                            }
                            ?>

                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $visite_date?></td>
                                <td> <?php echo $customer_name?> </td>
                                <td> <?php echo $row['meet_whom']?> </td>
                                <td> <?php echo $mobile?> </td>
                                <td> <?php echo $communication?> </td>
                                <td> <?php echo $service_type?> </td>
                                <td> <?php echo $user?> </td>
                                <td> <?php echo $notes?> </td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['service_id'];?>')">Edit</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#visit_list" style="cursor: pointer" onclick="visit('<?php echo $row['customer_name']?>')">Visit Report</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#call_list" style="cursor: pointer" onclick="repayment('<?php echo $row['customer_name']?>','<?php echo $customer_name?>','<?php echo $v_date?>')">Call Tracking</a>
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin' || $_COOKIE['role'] == 'service') {
                                                ?>
                                                <?php
                                            }
                                            ?>
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

                                    //                                    $sql = 'SELECT COUNT(id) as count FROM service';
                                    //                                    if($product_id == "") {
                                    $sql = "SELECT COUNT(id) as count FROM service WHERE next_follow > '$currentDate'";
                                    //                                    }
                                    //                                    {
                                    //                                        $sql = "SELECT COUNT(id) as count FROM service WHERE visit_date  BETWEEN '$from_date' AND '$to_date' $product_idSql$addedBy ";
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
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Expense</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="expense_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-6" id="v_date">
                                        <label>Plan Date *</label>
                                        <input type="date" class="form-control" id="visit_date" name="visit_date" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="service_id" name="service_id">
                                    </div>
<!--                                    <div class="form-group col-md-6" id="c_name">-->
<!--                                        <label>Customer Name *</label>-->
<!--                                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name" style="border-color: #181f5a;color: black">-->
<!--                                    </div>-->
                                    <div class="form-group col-md-6" id="c_name">
                                        <label>Customer Name*</label>
                                        <select data-search="true" class="form-control" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black">
                                            <option value=""> Select Customer</option>
                                            <?php
                                            $sqlSupplier = "SELECT * FROM `service_profile` WHERE assigned_to = '$added_by'";
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
                                    <div class="form-group col-md-6" id="m_meet">
                                        <label>Meet Person *</label>
                                        <input type="text" class="form-control" id="meet" name="meet" placeholder="Meet Person" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="s_product">
                                        <label>Mobile *</label>
                                        <input type="number" class="form-control" placeholder="Mobile" id="mobile" name="mobile" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="repayment_modes">
                                        <label>Service Type *</label>
                                        <select  class="form-control" id="service_type" name="service_type" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <option value=''>Select Service</option>
                                            <option value='Furnace Lining'>Furnace Lining</option>
                                            <option value='Laddle Wet Lining'>Laddle Wet Lining</option>
                                            <option value='Laddle Dry Lining'>Laddle Dry Lining</option>
                                            <option value='Erosion Analysis'>Erosion Analysis</option>
                                            <option value='Furnace Patch'>Furnace Patch</option>
                                            <option value='Laddle Chipping'>Laddle Chipping</option>
                                            <option value='Laddle Patch'>Laddle Patch</option>
                                            <option value='Steel Shots Analysis'>Steel Shots Analysis</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="s_next">
                                        <label>Communication Through</label>
                                        <select data-search="true" class="form-control js-example-disabled-results tail-select w-full" id="communication" name="communication" style="border-color: #181f5a;color: black">
                                            <option value="call"> Call</option>
                                            <option value="visit"> Visit</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12" id="s_duscuss">
                                        <label>Notes* </label>
                                        <textarea class="form-control" placeholder="Notes" id="notes" name="notes" style="border-color: #181f5a;color: black"></textarea>
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
        <div class="modal fade" id="visit_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visit_title">Visit Report</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="visit_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-6" id="v_date">
                                        <label>Visit Date *</label>
                                        <input type="date" class="form-control" id="v_date" name="v_date" style="border-color: #181f5a;color: black">
                                        <!--                                        <input type="hidden" class="form-control"  id="api" name="api">-->
                                        <!--                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">-->
                                        <!--                                        <input type="hidden" class="form-control"  id="market_id" name="market_id">-->
                                    </div>

                                    <div class="form-group col-md-6" id="c_name">
                                        <label>Customer Name*</label>
                                        <select data-search="true" class="form-control" id="cus_name" name="cus_name" style="border-color: #181f5a;color: black">
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
                                    <div class="form-group col-md-6" id="mob">
                                        <label>Mobile *</label>
                                        <input type="number" class="form-control" placeholder="Mobile" id="mobile" name="mobile" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="m_meet">
                                        <label>Meet whom *</label>
                                        <input type="text" class="form-control" id="meet_person" name="meet_person" placeholder="Meet Person" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="com">
                                        <label>Communication Through </label>
                                        <select  class="form-control" id="communication" name="communication" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <option value='visit'>Visit</option>
                                            <option value='call'>Call</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" id="s_sample">
                                        <label>Sample Given *</label>
                                        <select  class="form-control" id="sample" name="sample" style="border-color: #181f5a;color: black;text-transform: uppercase;">
                                            <!--                                            <option value=''>Select Pay Mode</option>-->
                                            <option value='Yes'>Yes</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6" id="s_product">
                                        <label>Product Name </label>
                                        <input type="text" class="form-control" placeholder="Product Name" id="material_name" name="material_name" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="s_qty">
                                        <label>qty </label>
                                        <input type="number" class="form-control" placeholder="Quantity" id="qty" name="qty" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="s_progress">
                                        <label>Progress </label>
                                        <input type="text" class="form-control" placeholder="Progress" id="progress" name="progress" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="c_value">
                                        <label>Commitment Value </label>
                                        <input type="number" class="form-control" placeholder="Commitment Value" id="commitment_value" name="commitment_value" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="c_qty">
                                        <label>Commitment Qty * </label>
                                        <input type="text" class="form-control" placeholder="Commitment Qty" id="commitment_qty" name="commitment_qty" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="s_next">
                                        <label>Next Follow date *</label>
                                        <input type="date" class="form-control" id="n_date" name="n_date" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12" id="s_duscuss">
                                        <label>Notes </label>
                                        <textarea class="form-control" placeholder="Notes" id="discuss_about" name="discuss_about" style="border-color: #181f5a;color: black"></textarea>
                                    </div>

                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;">Close</button>
                        <button type="button" class="btn btn-primary" id="visit_btn">ADD</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="call_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titles">Call Tracking</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="call_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-12" id="payment_date">
                                        <label>Call Date *</label>
                                        <input type="date" class="form-control" id="call_date" name="call_date" min="<?php echo $currentDate?>" max="<?php echo $currentDate?>" value="<?php echo $currentDate?>" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="next" name="next">
                                        <input type="hidden" class="form-control"  id="apiis" name="apiis">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="call_id" name="call_id">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Customer Name *</label>
                                        <input type="text" class="form-control" id="customer" name="customer" readonly style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="customer_id" name="customer_id">
                                    </div>
                                    <div class="form-group col-md-12" id="Notess">
                                        <label>Notes *</label>
                                        <textarea class="form-control" placeholder="Note" id="note" name="note" readonly style="border-color: #181f5a; color: black"></textarea>

                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Next FollowUp Date*</label>
                                        <input type="date" class="form-control" id="next_date" name="next_date" style="border-color: #181f5a;color: black">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal" style="background-color: red; color: white;text-transform: uppercase">Close</button>
                        <button type="button" class="btn btn-primary" id="call_btn">ADD</button>
                    </div>
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
                                    <label>Customer Name </label>
                                    <input type="text"  class="form-control" placeholder="Customer Name" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black">
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
    //visit report
    document.addEventListener('DOMContentLoaded', function () {
        const sample = document.getElementById('sample');
        const v_date = document.getElementById('v_date');
        const c_name = document.getElementById('c_name');
        const m_meet = document.getElementById('m_meet');
        const s_sample = document.getElementById('s_sample');
        const s_product = document.getElementById('s_product');
        const s_qty = document.getElementById('s_qty');
        const s_progress = document.getElementById('s_progress');
        const c_qty = document.getElementById('c_qty');
        const c_value = document.getElementById('c_value');
        const s_next = document.getElementById('s_next');
        const s_duscuss = document.getElementById('s_duscuss');


        // Add an event listener to the dropdown
        sample.addEventListener('change', function () {
            a(sample.value);
        });

        function a(values) {
            if (values === 'Yes') {
                // Hide the input field when 'Hide Input Field' is selected
                sample.style.display = 'block';
                v_date.style.display = 'block';
                c_name.style.display = 'block';
                m_meet.style.display = 'block';
                s_product.style.display = 'block';
                s_qty.style.display = 'block';
                s_progress.style.display = 'block';
                // s_last.style.display = 'block';
                c_qty.style.display = 'block';
                c_value.style.display = 'block';
                s_next.style.display = 'block';
                s_duscuss.style.display = 'block';


            }
            else {
                // Show the input field for other selections
                sample.style.display = 'block';
                v_date.style.display = 'block';
                c_name.style.display = 'block';
                m_meet.style.display = 'block';
                s_product.style.display = 'none';
                s_qty.style.display = 'none';
                s_progress.style.display = 'none';
                c_qty.style.display = 'block';
                c_value.style.display = 'block';
                s_next.style.display = 'block';
                s_duscuss.style.display = 'block';
            }
        }
    });

    //to validate form
    $("#visit_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                v_date: {
                    required: true
                },
                cus_name: {
                    required: true
                },
                meet_person: {
                    required: true
                },
                sample: {
                    required: true
                },
                commitment_qty: {
                    required: true
                },
                n_date: {
                    required: true
                },
                mobile: {
                    required: true
                },

            },
            // Specify validation error messages
            messages: {
                v_date: "*This field is required",
                cus_name: "*This field is required",
                meet_person: "*This field is required",
                sample: "*This field is required",
                discuss_about: "*This field is required",
                commitment_qty: "*This field is required",
                n_date: "*This field is required",
                mobile: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    function visit(customer_name) {
        $("#visit_titles").html("Visit Report");
        // Reset the form
        $('#visit_form')[0].reset();
        // Set the API value
        $('#visit_api').val("visit_api.php");
        // Set the customer dropdown value and trigger change event
        $('#cus_name').val(customer_name);
        // Optionally, you can also set the text of the dropdown to the customer name
        $('#cus_name option:selected').text(customer_name);
        $('#cus_name').trigger('change');
    }
    //add data
    $('#visit_btn').click(function () {
        $("#visit_form").valid();
        if($("#visit_form").valid()==true) {
            // var visit_api = $('#visit_api').val();
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({
                type: "POST",
                url: "visit_api.php",
                data: $('#visit_form').serialize(),
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

                                document.getElementById("visit_btn").disabled = false;
                                document.getElementById("visit_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("visit_btn").disabled = false;
                    document.getElementById("visit_btn").innerHTML = 'Add';
                }

            });

        } else {
            document.getElementById("visit_btn").disabled = false;
            document.getElementById("visit_btn").innerHTML = 'Add';

        }

    });

    // Get the current date
    var currentDate = new Date();

    // Add one day to the current date
    currentDate.setDate(currentDate.getDate() + 1);

    // Get the year, month, and day of the next date
    var nextDateYear = currentDate.getFullYear();
    var nextDateMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    var nextDateDay = ('0' + currentDate.getDate()).slice(-2);

    // Format the next date in YYYY-MM-DD format
    var nextDate = nextDateYear + '-' + nextDateMonth + '-' + nextDateDay;

    // Set the minimum attribute of the visit_date input to the next date
    $('#plan_date').attr('min', nextDate);
    // Get the current date
    var currentDate = new Date();

    // Add one day to the current date
    currentDate.setDate(currentDate.getDate() + 1);

    // Get the year, month, and day of the next date
    var nextDateYear = currentDate.getFullYear();
    var nextDateMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    var nextDateDay = ('0' + currentDate.getDate()).slice(-2);

    // Format the next date in YYYY-MM-DD format
    var nextDate = nextDateYear + '-' + nextDateMonth + '-' + nextDateDay;

    // Set the minimum attribute of the visit_date input to the next date
    $('#visit_date').attr('min', nextDate);

    function addTitle() {
        $("#title").html("Add service");
        $('#expense_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit service- "+data);
        $('#expense_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'service_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {
                    $("#visit_date").val(res.next_follow);
                    $("#customer_name").val(res.customer_name);
                    $("#meet").val(res.meet_whom);
                    $('#mobile').val(res.mobile);
                    $('#assigned').val(res.assigned_to);
                    $('#communication').val(res.communication);
                    $('#notes').val(res.notes);
                    $("#old_pa_id").val(res.service_id);
                    $("#service_id").val(res.service_id);


                    var edit_model_title = "Edit service - "+data;
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
                visit_date: {
                    required: true
                },
                customer_name: {
                    required: true
                },
                meet: {
                    required: true
                },
                mobile: {
                    required: true
                },
                assigned: {
                    required: true
                },
                communication: {
                    required: true
                },
                notes: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                visit_date: "*This field is required",
                customer_name: "*This field is required",
                meet: "*This field is required",
                mobile: "*This field is required",
                assigned: "*This field is required",
                communication: "*This field is required",
                notes: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {

        $("#expense_form").valid();

        if($("#expense_form").valid()==true) {

            var api = $('#api').val();

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: api,
                data: $('#expense_form').serialize(),
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

    function repayment(customer_id, customer_name,next) {
        // Uncomment the following line for debugging
        // alert(customer_id);

        // Set the modal title
        $("#titles").html("Call Details");

        // Reset the call form
        $('#call_form')[0].reset();

        // Set the API value
        $('#apiis').val("repayment_api.php");

        // Set the customer dropdown value and trigger change event
        $('#customer').val(customer_name);
        $('#customer_id').val(customer_id);
        $('#next_date').val(next);
        // Optionally, you can also set the text of the dropdown to the customer name
        // $('#customer').find('option:selected').text(customer_name);
        // $('#customer').trigger('change');
    }

    //add data
    $('#call_btn').click(function () {
        $("#call_form").valid();
        if($("#call_form").valid()==true) {
            var api = $('#apiis').val();
            //var loan_id = "<?php //echo $loan_id?>//";
            // var loan_id = 56
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: "call_api.php",
                data: $('#call_form').serialize(),
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

                                document.getElementById("call_btn").disabled = false;
                                document.getElementById("call_btn").innerHTML = 'Add';
                            });

                    }
                },
                error: function () {

                    Swal.fire('Check Your Network!');
                    document.getElementById("call_btn").disabled = false;
                    document.getElementById("call_btn").innerHTML = 'Add';
                }

            });

        } else {
            document.getElementById("call_btn").disabled = false;
            document.getElementById("call_btn").innerHTML = 'Add';

        }

    });


    //to validate form
    $("#call_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {
                notes: {
                    required: true
                },

            },
            // Specify validation error messages
            messages: {
                notes: "*This field is required",
                pay_made: "*This field is required",
                repayment_mode: "*This field is required",
                ref_no: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
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
                        data: 'service_id='+data,
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
        $('#product_id').val('<?php echo $product_id;?>');
        $('#t_date').val('<?php echo $t_date;?>');
        $('#f_date').val('<?php echo $f_date;?>');

    });

    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&service_id=<?php echo $service_id?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&service_id=<?php echo $service_id?>";
    });
</script>


</body>
</html>
