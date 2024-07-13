<?php Include("../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
$search= $_GET['search'];


if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Company Profile</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://bhims.ca/piloting/img/favicon_New.png">
    <link href="https://erp.aecindia.net/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://erp.aecindia.net/vendor/chartist/css/chartist.min.css">
    <link href="https://erp.aecindia.net/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="https://erp.aecindia.net/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="https://erp.aecindia.net/css/style.css" rel="stylesheet">
    <link href="https://erp.aecindia.net/vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://erp.aecindia.net/vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="https://erp.aecindia.net/vendor/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="https://erp.aecindia.net/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://erp.aecindia.net/vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="https://erp.aecindia.net/vendor/pickadate/themes/default.date.css">
    <link href="https://erp.aecindia.net/vendor/summernote/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

</head>
<style>
    .custom-date-input {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 8px;
        font-size: 16px;
        outline: none;
        transition: border-color 0.3s ease;
        width: 200px; /* Adjust width as needed */
    }

    /* Style for the date input field when focused */
    .custom-date-input:focus {
        border-color: #007bff; /* Change border color on focus */
    }
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
    $header_name ="Company Profile";
    Include ('../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
<!--                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>-->
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Company Profile</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">

                            <div class="form-group mx-sm-3 mb-2">
                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                        </form>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" style="margin-left: 20px;" onclick="addTitle()">ADD</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Company Name</strong></th>
                                <th><strong>Phone</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>GSTIN</strong></th>
                                <th><strong>Image</strong></th>
                                <th><strong>View</strong></th>
                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
                            //                            $sql = "SELECT * FROM user ORDER BY id  LIMIT $start,10";

                            if($search == "") {
                                $sql = "SELECT * FROM company_profile ORDER BY id  LIMIT $start,10";
                            }
                            else {
                                $sql = "SELECT * FROM company_profile WHERE company_name LIKE '%$search%' ORDER BY id  LIMIT $start,10";
                            }

//                            if($search == "") {
//                                $sql = "SELECT * FROM company_profile ORDER BY id  LIMIT $start,10";
//                            }
//                            else {
//                                $sql = "SELECT * FROM company_profile WHERE company_name LIKE '%$search%' ORDER BY id  LIMIT $start,10";
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

//                            if($row['access_status'] == 1){
//                                $statColor = 'success';
//                                $statCont = 'Active';
//                            }
//                            else {
//                                $statColor = 'danger';
//                                $statCont = 'In Active';
//                            }
//                            $career_dates =   $row['career_date'];
//                            $career_date =   date('d-F-Y');
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>

                                <td> <?php echo $row['company_name']?> </td>
                                <td> <?php echo $row['phone']?> </td>
                                <td><?php echo $row['email']?></td>
                                <td><?php echo $row['gstin']?></td>

                                <td style="cursor: pointer">   <span class="badge badge-pill <?php echo $img_upload?> ml-2" data-toggle="modal" data-target="<?php echo $img_modal?>" onclick="imgModal('<?php echo $row['company_id']; ?>')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                                              <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                              <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                                            </svg>
                                        </span>
                                </td>

<!--                                <td> <span class="badge badge-pill badge---><?php //echo $statColor?><!--">--><?php //echo $statCont?><!--</span>-->
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="<?php echo $website?>/company_profile/show_file.php?company_id=<?php echo $row['company_id']?>"
                                           class="btn btn-primary shadow btn-xs sharp me-1"><i
                                                    class="fa fa-eye"></i></a>
                                    </div>
                    </div>
                    </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">

                                            <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['company_id'];?>')">Edit</a>
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin') {
                                                ?>
                                            <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $row['company_id'];?>')">Delete</a>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>&search=<?php echo $search ?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }
                                    if($search == "") {
                                        $sql = "SELECT COUNT(id) as count FROM company_profile";
                                    }
                                    else {
                                        $sql = "SELECT COUNT(id) as count FROM company_profile WHERE company_name LIKE '%$search%'";
                                    }

//                                    $sql = 'SELECT COUNT(id) as count FROM company_profile;';
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
                                                                                               href="?page_no=<?php echo $i ?>&search=<?php echo $search?>"><?php echo $i ?></a>
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
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $nextPage ?>&search=<?php echo $search ?>"><i class="fa-solid fa-greater-than"></i></a></li>
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
                                        <label>Company Name *</label>
                                        <input type="text" class="form-control" placeholder="Company Name" id="company_name" name="company_name" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="company_id" name="company_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Mobile *</label>
                                        <input type="number" class="form-control" placeholder="Phone" id="phone" name="phone" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email *</label>
                                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" onkeyup="myFunction()" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>GSTIN/UIN *</label>
                                        <input type="text" class="form-control" placeholder="GSTIN/UIN" id="gstin" name="gstin" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>State Name *</label>
                                        <input type="text" class="form-control" placeholder="State Name" id="state" name="state" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Bank Name 1 *</label>
                                        <input type="text" class="form-control" placeholder="Bank Name" id="bank_name" name="bank_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Bank Name 2 </label>
                                        <input type="text" class="form-control" placeholder="Bank Name" id="bank_name2" name="bank_name2" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Account Name *</label>
                                        <input type="text" class="form-control" placeholder="Account Name" id="acc_name" name="acc_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Account No *</label>
                                        <input type="text" class="form-control" placeholder="Account No" id="acc_no" name="acc_no" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>IFSC Code  *</label>
                                        <input type="text" class="form-control" placeholder="IFSC Code" id="ifsc" name="ifsc" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Branch Name:  *</label>
                                        <input type="text" class="form-control" placeholder="Branch Name" id="branch_name" name="branch_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="upload_image">Company Image(1 MB) </label>
                                        <input type="file" class="form-control" placeholder="Upload Image" id="upload_image" name="upload_image" style="border-color: #181f5a;color: black" accept=".jpg,.jpeg,.png">
                                    </div>
                                    <!--                                    <div class="form-row">-->
                                    <div class="form-group col-md-12">
                                        <label>Address *</label>
                                        <textarea class="form-control" placeholder="Address" id="address" name="address" style="border-color: #181f5a;color: black"></textarea>
                                        <!---->
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

    <?php Include ('../includes/footer.php') ?>


</div>

<script>
    function imgModal(src) {
        document.getElementById('modal_images').setAttribute("src",'company_logo/'+src+'.jpg');

    }

</script>


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
    function myFunction() {
        // let x = document.getElementById("customer_email");
        let y = document.getElementById("email");
        // x.value = x.value.toLowerCase();
        y.value = y.value.toLowerCase();
    }
    function addTitle() {
        $("#title").html("Add company");
        $('#career_form')[0].reset();
        $('#api').val("add_api.php")
        // $('#game_id').prop('readonly', false);
    }
    function editTitle(data) {

        $("#title").html("Edit company- "+data);
        $('#career_form')[0].reset();
        $('#api').val("edit_api.php");

        $.ajax({
            type: "POST",
            url: "view_api.php",
            data: 'company_id='+data,
            dataType: "json",
            success: function(res){
                if(res.status=='success')
                {

                    $("#company_name").val(res.company_name);
                    $("#phone").val(res.phone);
                    $("#email").val(res.email);
                    $("#gstin").val(res.gstin);
                    $("#state").val(res.state);
                    $("#bank_name").val(res.bank_name);
                    $("#bank_name2").val(res.bank_name2);
                    $("#acc_name").val(res.acc_name);
                    $("#acc_no").val(res.acc_no);
                    $("#ifsc").val(res.ifsc);
                    $("#address").val(res.address);
                    $("#branch_name").val(res.branch_name);
                    // $("#access_status").val(res.access_status);
                    // $(".summernote").code("your text");

                    $("#old_pa_id").val(res.company_id);
                    $("#company_id").val(res.company_id);

                    // if(Number(res.access_status) == 1){
                    //     document.getElementById("access_status").checked = true;
                    // }
                    // else {
                    //     document.getElementById("access_status").checked = false;
                    // }

                    var edit_model_title = "Edit company - "+data;
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


                company_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                state: {
                    required: true
                },
                phone: {
                    required: true,
                    maxlength: 10,
                    minlength: 10
                },
                gstin: {
                    required: true
                },
                address: {
                    required: true
                },
                bank_name: {
                    required: true
                },
                branch_name: {
                    required: true
                },
                ifsc: {
                    required: true
                },
                acc_no: {
                    required: true
                },
                acc_name: {
                    required: true
                },

                // upload_image: {
                //     required: true
                // },


            },
            // Specify validation error messages
            messages: {

               company_name: "*This field is required",
                email: "*This field is required",
                state: "*This field is required",
                phone: {
                    required:"*This field is required",
                    maxlength:"*Mobile Number Should Be 10 Character",
                    minlength:"*Mobile Number Should Be 10 Character"
                },
                gstin: "*This field is required",
                address: "*This field is required",
                upload_image: "*This field is required",
                bank_name: "*This field is required",
                branch_name: "*This field is required",
                ifsc: "*This field is required",
                acc_no: "*This field is required",
                acc_name: "*This field is required",

            }
            // Make sure the form is submitted to the destination defined
        });

    //add data

    $('#add_btn').click(function () {

        $("#career_form").valid();

        if($("#career_form").valid()==true) {

            var api = $('#api').val();
            var form = $("#career_form");
            // var access_status = $('#access_status').is(":checked");

            // console.log(access_status);

            // if(access_status == true)
            // {
            //     access_status =1;
            // }
            // else{
            //     access_status =0;
            // }
            var formData = new FormData(form[0]);
            // formData.append("active_status",access_status);

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
                        data: 'company_id='+data,
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

    });

</script>


</body>
</html>
