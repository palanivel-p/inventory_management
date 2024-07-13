<?php Include("../../../../includes/connection.php");
//require_once '../../../../includes/excel_generator/PHPExcel.php';
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
                    <h4 class="card-title">Supplier FollowUp</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <!--                        <form class="form-inline">-->
                        <!---->
                        <!--                            <div class="form-group mx-sm-3 mb-2">-->
                        <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                        <!--                            </div>-->
                        <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        <!--                        </form>-->
                        <button type="button" class="btn btn-primary mb-2" onclick="goToTestPage()" style="justify-content: end">Back</button>
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="justify-content: end">Back</button>-->
                        <?php
                        if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin' || $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                            ?>
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" style="margin-left: 20px;" onclick="addTitle()">ADD</button>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>Supplire Name</strong></th>
                                <th><strong>Spoken With</strong></th>
                                <th><strong>Mobile</strong></th>
                                <th><strong>Discussed About</strong></th>
                                <th><strong>committed Value</strong></th>
                                <th><strong>Next Follow Date</strong></th>
                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
                            //    $sql = "SELECT * FROM expense ORDER BY id  LIMIT $start,10";

                            //                            if($brand == "" && $p_code == "" && $s_category== "" && $p_category == ""&& $p_name == "") {
                            $sql = "SELECT * FROM follow_up ORDER BY id DESC LIMIT $start,10";
                            //                            }
                            //                            else {
                            //                                $sql = "SELECT * FROM product WHERE id > 0 $brandSql$categorySql$pCodeSql$pNameSql$pcategorySql ORDER BY id  LIMIT $start,10";
                            //                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;

                            $f_date = $row['follow_date'];
                            $follow_date = date('d-m-Y', strtotime($f_date));
                            $customer_name = $row['name'];
                            $spoken = $row['spoken'];
                            $mobile = $row['mobile'];
                            $discussed_About = $row['discussed_About'];
                            $committed_value = $row['committed_value'];
                            $n_follow = $row['next_follow'];
                            $next_date = date('d-m-Y', strtotime($n_follow));

                            $sqlRequest = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                            $resRequest = mysqli_query($conn, $sqlRequest);
                            $rowRequest = mysqli_fetch_assoc($resRequest);
                            $price = $rowRequest['sub_category'];
                            $f_date = $row['follow_date'];

                            $follow_date = date('d-m-Y', strtotime($f_date));

                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo; ?></strong></td>
                                <td><?php echo $follow_date ?> </td>
                                <td> <?php echo $customer_name?> </td>
                                <td> <?php echo $spoken ?> </td>
                                <td> <?php echo $mobile ?> </td>
                                <td> <?php echo $discussed_About ?> </td>
                                <td> <?php echo $committed_value ?> </td>
                                <td> <?php echo $next_date ?> </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin'|| $_COOKIE['role'] == 'Admin'|| $_COOKIE['role'] == 'Sales'|| $_COOKIE['role'] == 'Stores') {
                                                ?>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['followup_id'];?>')">Edit</a>                                                <?php
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
                                        $sql = "SELECT COUNT(id) as count FROM follow_up";
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
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Follow Up Form</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="basic-form" style="color: black;">
                            <form id="follow_form" autocomplete="off">
                                <div class="form-row">

                                    <div class="form-group col-md-12">
                                        <label>Date & Time *</label>
                                        <input type="date" class="form-control" id="date_time" name="date_time" required="" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="followup_id" name="followup_id">
                                    </div>

<!--                                    <div class="form-group col-md-3">-->
<!--                                        <select class="form-control default-select" name="start_from_hr" id="hr_startTime">-->
<!--                                            <option value="01">1</option>-->
<!--                                            <option value="02">2</option>-->
<!--                                            <option value="03">3</option>-->
<!--                                            <option value="04">4</option>-->
<!--                                            <option value="05">5</option>-->
<!--                                            <option value="06">6</option>-->
<!--                                            <option value="07">7</option>-->
<!--                                            <option value="08">8</option>-->
<!--                                            <option value="09">9</option>-->
<!--                                            <option value="10">10</option>-->
<!--                                            <option value="11">11</option>-->
<!--                                            <option value="12">12</option>-->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-3">-->
<!--                                        <select class="form-control default-select" name="start_from_min" id="min_startTime">-->
<!--                                            <option value="00">00</option>-->
<!--                                            <option value="01">01</option>-->
<!--                                            <option value="02">02</option>-->
<!--                                            <option value="03">03</option>-->
<!--                                            <option value="04">04</option>-->
<!--                                            <option value="05">05</option>-->
<!--                                            <option value="06">06</option>-->
<!--                                            <option value="07">07</option>-->
<!--                                            <option value="08">08</option>-->
<!--                                            <option value="09">09</option>-->
<!--                                            <option value="10">10</option>-->
<!--                                            <option value="11">11</option>-->
<!--                                            <option value="12">12</option>-->
<!--                                            <option value="13">13</option>-->
<!--                                            <option value="14">14</option>-->
<!--                                            <option value="15">15</option>-->
<!--                                            <option value="16">16</option>-->
<!--                                            <option value="17">17</option>-->
<!--                                            <option value="18">18</option>-->
<!--                                            <option value="19">19</option>-->
<!--                                            <option value="20">20</option>-->
<!--                                            <option value="21">21</option>-->
<!--                                            <option value="22">22</option>-->
<!--                                            <option value="23">23</option>-->
<!--                                            <option value="24">24</option>-->
<!--                                            <option value="25">25</option>-->
<!--                                            <option value="26">26</option>-->
<!--                                            <option value="27">27</option>-->
<!--                                            <option value="28">28</option>-->
<!--                                            <option value="29">29</option>-->
<!--                                            <option value="30">30</option>-->
<!--                                            <option value="31">31</option>-->
<!--                                            <option value="32">32</option>-->
<!--                                            <option value="33">33</option>-->
<!--                                            <option value="34">34</option>-->
<!--                                            <option value="35">35</option>-->
<!--                                            <option value="36">36</option>-->
<!--                                            <option value="37">37</option>-->
<!--                                            <option value="38">38</option>-->
<!--                                            <option value="39">39</option>-->
<!--                                            <option value="40">40</option>-->
<!--                                            <option value="41">41</option>-->
<!--                                            <option value="42">42</option>-->
<!--                                            <option value="43">43</option>-->
<!--                                            <option value="44">44</option>-->
<!--                                            <option value="45">45</option>-->
<!--                                            <option value="46">46</option>-->
<!--                                            <option value="47">47</option>-->
<!--                                            <option value="48">48</option>-->
<!--                                            <option value="49">49</option>-->
<!--                                            <option value="50">50</option>-->
<!--                                            <option value="51">51</option>-->
<!--                                            <option value="52">52</option>-->
<!--                                            <option value="53">53</option>-->
<!--                                            <option value="54">54</option>-->
<!--                                            <option value="55">55</option>-->
<!--                                            <option value="56">56</option>-->
<!--                                            <option value="57">57</option>-->
<!--                                            <option value="58">58</option>-->
<!--                                            <option value="59">59</option>-->
<!---->
<!--                                        </select>-->
<!---->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-3">-->
<!--                                        <select class="form-control default-select" name="start_from_sec" id="sec_startTime">-->
<!--                                            <option value="00">00</option>-->
<!--                                            <option value="01">01</option>-->
<!--                                            <option value="02">02</option>-->
<!--                                            <option value="03">03</option>-->
<!--                                            <option value="04">04</option>-->
<!--                                            <option value="05">05</option>-->
<!--                                            <option value="06">06</option>-->
<!--                                            <option value="07">07</option>-->
<!--                                            <option value="08">08</option>-->
<!--                                            <option value="09">09</option>-->
<!--                                            <option value="10">10</option>-->
<!--                                            <option value="11">11</option>-->
<!--                                            <option value="12">12</option>-->
<!--                                            <option value="13">13</option>-->
<!--                                            <option value="14">14</option>-->
<!--                                            <option value="15">15</option>-->
<!--                                            <option value="16">16</option>-->
<!--                                            <option value="17">17</option>-->
<!--                                            <option value="18">18</option>-->
<!--                                            <option value="19">19</option>-->
<!--                                            <option value="20">20</option>-->
<!--                                            <option value="21">21</option>-->
<!--                                            <option value="22">22</option>-->
<!--                                            <option value="23">23</option>-->
<!--                                            <option value="24">24</option>-->
<!--                                            <option value="25">25</option>-->
<!--                                            <option value="26">26</option>-->
<!--                                            <option value="27">27</option>-->
<!--                                            <option value="28">28</option>-->
<!--                                            <option value="29">29</option>-->
<!--                                            <option value="30">30</option>-->
<!--                                            <option value="31">31</option>-->
<!--                                            <option value="32">32</option>-->
<!--                                            <option value="33">33</option>-->
<!--                                            <option value="34">34</option>-->
<!--                                            <option value="35">35</option>-->
<!--                                            <option value="36">36</option>-->
<!--                                            <option value="37">37</option>-->
<!--                                            <option value="38">38</option>-->
<!--                                            <option value="39">39</option>-->
<!--                                            <option value="40">40</option>-->
<!--                                            <option value="41">41</option>-->
<!--                                            <option value="42">42</option>-->
<!--                                            <option value="43">43</option>-->
<!--                                            <option value="44">44</option>-->
<!--                                            <option value="45">45</option>-->
<!--                                            <option value="46">46</option>-->
<!--                                            <option value="47">47</option>-->
<!--                                            <option value="48">48</option>-->
<!--                                            <option value="49">49</option>-->
<!--                                            <option value="50">50</option>-->
<!--                                            <option value="51">51</option>-->
<!--                                            <option value="52">52</option>-->
<!--                                            <option value="53">53</option>-->
<!--                                            <option value="54">54</option>-->
<!--                                            <option value="55">55</option>-->
<!--                                            <option value="56">56</option>-->
<!--                                            <option value="57">57</option>-->
<!--                                            <option value="58">58</option>-->
<!--                                            <option value="59">59</option>-->
<!---->
<!--                                        </select>-->
<!---->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-3">-->
<!--                                        <select class="form-control default-select" name="start_from_am" id="am_startTime">-->
<!--                                            <option value="am">AM</option>-->
<!--                                            <option value="pm">PM</option>-->
<!--                                        </select>-->
<!--                                    </div>-->
                                    <div class="form-group col-md-12">
                                        <label>Supplier Name *</label>
                                        <input type="text" class="form-control" placeholder="Supplier Name" id="sup_name" name="sup_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Spoken With *</label>
                                        <input type="text" class="form-control" placeholder="Spoken With" id="spoken" name="spoken" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Mobile *</label>
                                        <input type="number" class="form-control" placeholder="Number" id="mobile" name="mobile" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Discussed About *</label>
                                        <input type="text" class="form-control" placeholder="Discussed About" id="about" name="about" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Committed Value *</label>
                                        <input type="text" class="form-control" placeholder="Committed Value" id="value" name="value" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Next Followup Date *</label>
                                        <input type="date" class="form-control" placeholder="Next Followup Date" id="follow_date" name="follow_date" style="border-color: #181f5a;color: black">
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
<script>
    function imgModal(src) {
        document.getElementById('modal_images').setAttribute("src",'product_img/'+src+'.jpg');

    }

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

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
<script src="../vendor/moment/moment.min.js"></script>
<script src="../../../vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../../vendor/summernote/js/summernote.min.js"></script>
<script src="../../../js/plugins-init/summernote-init.js"></script>

<script src="../../../vendor/select2/js/select2.full.min.js"></script>
<script src="../../../js/plugins-init/select2-init.js"></script>


<script>

    function goToTestPage() {
        window.location.href = "index.php";
    }

    function addTitle() {
        $("#title").html("Add Follow Up");
        $('#follow_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }

    function editTitle(data) {

        $("#title").html("Edit Follow Up- "+data);
        $('#follow_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'followup_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {

                    $("#followup_id").val(res.followup_id);
                    $("#date_time").val(res.follow_date);
                    $("#hr_startTime").val(res.hours);
                    $("#hr_startTime").val(res.hours).trigger('change');
                    $("#min_startTime").val(res.minutes);
                    $("#min_startTime").val(res.minutes).trigger('change');
                    $("#sec_startTime").val(res.seconds);
                    $("#sec_startTime").val(res.seconds).trigger('change');
                    $("#am_startTime").val(res.am_pm);
                    $("#am_startTime").val(res.am_pm).trigger('change');
                    $("#sup_name").val(res.name);
                    $("#spoken").val(res.spoken);
                    $("#mobile").val(res.mobile);
                    $("#about").val(res.discussed_About);
                    $("#value").val(res.committed_value);
                    $("#follow_date").val(res.next_follow);
                    $("#old_pa_id").val(res.followup_id);





                    var edit_model_title = "Edit Followup - "+data;
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
    $("#follow_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {

                date_time: {
                    required: true
                },
                start_from_hr: {
                    required: true
                },
                start_from_min: {
                    required: true
                },
                start_from_sec: {
                    required: true
                },
                start_from_am: {
                    required: true
                },
                sup_name: {
                    required: true
                },
                spoken: {
                    required: true
                },
                mobile: {
                    required: true
                },
                about: {
                    required: true
                },
                value: {
                    required: true
                },
                follow_date: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {

                date_time: "*This field is required",
                start_from_hr: "*This field is required",
                start_from_min: "*This field is required",
                start_from_sec: "*This field is required",
                start_from_am: "*This field is required",
                sup_name: "*This field is required",
                spoken: "*This field is required",
                mobile: "*This field is required",
                about: "*This field is required",
                value: "*This field is required",
                follow_date: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {

        $("#follow_form").valid();

        if($("#follow_form").valid()==true) {

            var api = $('#api').val();
            var form = $("#follow_form");
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
                        data: 'followup_id='+data,
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
