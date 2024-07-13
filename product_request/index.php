<?php Include("../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
$u_name= $_GET['u_name'];
$u_code= $_GET['u_code'];
$mobile= $_GET['mobile'];
$email= $_GET['email'];
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

if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}

if($u_name != ""){
    $uNameSql= " AND username = '".$u_name."'";

}
else{
    $uNameSql ="";
}

if($u_code != ""){
    $uCodeSql= " AND user_id = '".$u_code."'";

}
else{
    $uCodeSql ="";
}

if($mobile != ""){
    $mobileSql= " AND phone = '".$mobile."'";

}
else{
    $mobileSql ="";
}

if($email != ""){
    $emailSql= "AND email = '".$email."'";

}
else {
    $emailSql = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Product Request</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://bhims.ca/piloting/img/favicon_New.png">
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
    <link href="../vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="../vendor/pickadate/themes/default.date.css">
    <link href="../vendor/summernote/summernote.css" rel="stylesheet">
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
    $header_name ="Product Request";
    Include ('../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Product Request</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Product Request List</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">
                            <!--                            <div class="form-group mx-sm-3 mb-2">-->
                            <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                            <!--                            </div>-->
                            <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        </form>
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>-->
<!--                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>-->
<!--                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>-->
<!--                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>-->
<!--            </span>Excel</button>-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
<!--                                <th><strong>Date</strong></th>-->
                                <th><strong>Product Name</strong></th>
                                <th><strong>Current Price</strong></th>
                                <th><strong>Current Cost</strong></th>
                                <th><strong>Request Price</strong></th>
                                <th><strong>Request Cost</strong></th>
                                <th><strong>Approve Status</strong></th>


                            </tr>
                            </thead>
                            <?php
                            $sql = "SELECT * FROM `product` WHERE edit_request = 1 AND request =0 ORDER BY id ASC LIMIT $start,10";
//                            if($email == "" && $u_code == "" && $mobile== "" && $u_name == "") {
//                                $sql = "SELECT * FROM Product Request  ORDER BY id  LIMIT $start,10";
//                            }
//                            else {
//                                $sql = "SELECT * FROM Product Request WHERE id > 0 $emailSql$mobileSql$uCodeSql$uNameSql ORDER BY id  LIMIT $start,10";
//                            }

                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            $img = $row['img'];
                            if($img == 1) {
                                $img_upload = "badge-success";
                                $img_modal = '#image_modal';
                            }
                            else {
                                $img_upload = "badge-danger";
                            }

                            if($row['access_status'] == 1){
                                $statColor = 'success';
                                $statCont = 'Active';
                            }
                            else {
                                $statColor = 'danger';
                                $statCont = 'In Active';
                            }
                            $purchase_id =   $row['purchase_id'];
                            $product_price =   $row['request_price'];
                            $product_cost =   $row['request_cost'];

                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
<!--                                <td>--><?php //echo $row['date']?><!--</td>-->
                                <td><?php echo $row['product_name']?></td>
                                <td><?php echo $row['product_cost']?></td>
                                <td><?php echo $row['product_price']?></td>
                                <td><?php echo $row['request_cost']?></td>
                                <td><?php echo $row['request_price']?></td>

                                <td> <button type="button" class="btn btn-succes" id="verify_btn" onclick= "approve('<?php echo $row['product_id'];?>','<?php echo $product_cost;?>','<?php echo $product_price;?>')" style="background-color: green; color: white;">Approve</button></td>

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

                                    $sql = 'SELECT COUNT(id) as count FROM product WHERE edit_request = 1 AND request =0';
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
                        <h5 class="modal-title" id="title">Career</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form" style="color: black;">
                            <form id="career_form" autocomplete="off">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>First Name *</label>
                                        <input type="text" class="form-control" placeholder="First Name" id="first_name" name="first_name" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="Product Request_id" name="Product Request_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last Name *</label>
                                        <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Product RequestName *</label>
                                        <input type="text" class="form-control" placeholder="UserName" id="user_name" name="user_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone *</label>
                                        <input type="number" class="form-control" placeholder="Phone" id="Phone" name="Phone" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email *</label>
                                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Password *</label>
                                        <input type="text" class="form-control" placeholder="Password" id="password" name="password" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Role *</label>
                                        <input type="text" class="form-control" placeholder="Role" id="role" name="role" style="border-color: #181f5a;color: black">
                                    </div>
                                    <!--                                    <div class="form-row">-->
                                    <!--                                    <div class="form-group col-md-6">-->
                                    <!--                                       <label>Role *</label>-->
                                    <!--                                       <select data-search="true" class="form-control tail-select w-full" id="role" name="role" style="border-color: #181f5a;color: black">-->
                                    <!--                                           <option value=''>Select Role</option>-->
                                    <!--                                           <option value=''>Select Role</option>-->
                                    <!--                                           --><?php
                                    //                                           $sqlDevice = "SELECT * FROM `role`";
                                    //                                           $resultDevice = mysqli_query($conn, $sqlDevice);
                                    //                                           if (mysqli_num_rows($resultDevice) > 0) {
                                    //                                               while ($rowDevice = mysqli_fetch_array($resultDevice)) {
                                    //                                                   ?>
                                    <!--                                                   <option-->
                                    <!--                                                       value='--><?php //echo $rowDevice['role_id']; ?><!--'>--><?php //echo strtoupper($rowDevice['role_name']); ?><!--</option>-->
                                    <!--                                                   --><?php
                                    //                                               }
                                    //                                           }
                                    //                                           ?>
                                    <!--                                          -->
                                    <!--                                       </select>-->
                                    <!--                                   </div>-->
                                    <div class="form-group col-md-6">
                                        <label for="upload_image">User Image(1 MB)</label>
                                        <input type="file" class="form-control" placeholder="Upload Image" id="upload_image" name="upload_image" style="border-color: #181f5a;color: black" accept=".jpg,.jpeg,.png">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Active Status</label>
                                        <label class="switch">
                                            <input type="checkbox" checked id="access_status"  name="access_status">
                                            <span class="slider round"></span>
                                        </label>
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
    </div>


    <?php Include ('../includes/footer.php') ?>
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
                                    <label>User Name </label>
                                    <input type="text"  class="form-control" placeholder="User Name" id="u_name" name="s_name" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>User Code </label>
                                    <input type="text"  class="form-control" placeholder="User Code" id="u_code" name="s_code" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Mobile </label>
                                    <input type="text"  class="form-control" placeholder="mobile" id="mobile" name="mobile" style="border-color: #181f5a;color: black">
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Email </label>
                                    <input type="text"  class="form-control" placeholder="email" id="email" name="email" style="border-color: #181f5a;color: black">
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


<script>
    function imgModal(src) {
        document.getElementById('modal_images').setAttribute("src",'user_img/'+src+'.jpg');

    }
    function addTitle() {
        $("#title").html("Add User");
        $('#career_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);

    }
    function editTitle(data) {

        $("#title").html("Edit User- "+data);
        $('#career_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'user_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {
                    $("#first_name").val(res.f_name);
                    $("#last_name").val(res.l_name);
                    $("#user_name").val(res.username);
                    $("#Phone").val(res.phone);
                    $("#email").val(res.email);
                    $("#password").val(res.password);
                    $("#role").val(res.role);
                    // $("#warehouse").val(res.warehouse);
                    $("#access_status").val(res.access_status);
                    // $(".summernote").code("your text");

                    $("#old_pa_id").val(res.user_id);
                    $("#user_id").val(res.user_id);

                    if(Number(res.access_status) == 1){
                        document.getElementById("access_status").checked = true;
                    }
                    else {
                        document.getElementById("access_status").checked = false;

                    }

                    var edit_model_title = "Edit User - "+data;
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
    $("#career_form").validate(
        {
            ignore: '.ignore',
            // Specify validation rules
            rules: {

                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                user_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                },
                Phone: {
                    required: true,
                    maxlength: 10,
                    minlength: 10
                },
                role: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                first_name: "*This field is required",
                last_name: "*This field is required",
                user_name: "*This field is required",
                email: "*This field is required",
                password: "*This field is required",
                Phone: {
                    required:"*This field is required",
                    maxlength:"*Mobile Number Should Be 10 Character",
                    minlength:"*Mobile Number Should Be 10 Character"
                },
                role: "*This field is required",
            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {

        $("#career_form").valid();

        if($("#career_form").valid()==true) {

            var api = $('#api').val();
            var form = $("#career_form");
            var access_status = $('#access_status').is(":checked");

            console.log(access_status);

            if(access_status == true)
            {
                access_status =1;
            }
            else{
                access_status =0;
            }
            var formData = new FormData(form[0]);
            formData.append("active_status",access_status);

            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({

                type: "POST",
                url: api,
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
                        data: 'user_id='+data,
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

    // Approval ajax
    function approve(datas,p_cost,p_price) {

        Swal.fire({
            title: "Approve",
            text: "Are you sure want to Approve the record",
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
                    // var intend_id = document.getElementById("purchase_id").value;
                    // var doner_id = document.getElementById("doner_id").value;
                    $.ajax({

                        type: "POST",
                        url: "approve_api.php",
                        // data: 'product_id='+datas,
                        data: {
                            product_id: datas,
                            p_cost: p_cost,
                            p_price: p_price
                        },
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
                                        // window.window.location.reload();
                                        window.location.href = "<?php echo $website; ?>/product_request/";

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

    });


    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&u_name=<?php echo $u_name?>&u_code=<?php echo $u_code?>&mobile=<?php echo $mobile?>&email=<?php echo $email?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&u_name=<?php echo $u_name?>&u_code=<?php echo $u_code?>&mobile=<?php echo $mobile?>&email=<?php echo $email?>";
    });
</script>


</body>
</html>
