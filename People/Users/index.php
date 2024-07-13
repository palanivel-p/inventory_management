<?php Include("../../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
$u_name= $_GET['u_name'];
$u_code= $_GET['u_code'];
$mobile= $_GET['mobile'];
$email= $_GET['email'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];
$user_doc = $_GET['userid'];

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
//    $uNameSql= " AND username = '".$u_name."'";
    $uNameSql = " AND username LIKE '%" . $u_name . "%'";

}
else{
    $uNameSql ="";
}

if($u_code != ""){
//    $uCodeSql= " AND user_id = '".$u_code."'";
    $uCodeSql = " AND user_id LIKE '%" . $u_code . "%'";
}
else{
    $uCodeSql ="";
}

if($mobile != ""){
//    $mobileSql= " AND phone = '".$mobile."'";
    $mobileSql = " AND phone LIKE '%" . $mobile . "%'";
}
else{
    $mobileSql ="";
}

if($email != ""){
//    $emailSql= "AND email = '".$email."'";
    $emailSql = " AND email LIKE '%" . $email . "%'";
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

    <title>Users</title>
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
    $header_name ="User";
    Include ('../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">User</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User List</h4>
                    <div style="display: flex;justify-content: flex-end;">
                        <form class="form-inline">
                            <!--                            <div class="form-group mx-sm-3 mb-2">-->
                            <!--                                <input type="text" class="form-control" placeholder="Search By Name" name="search" id="search" style="border-radius:20px;color:black;" >-->
                            <!--                            </div>-->
                            <!--                            <button type="submit" class="btn btn-primary mb-2">Search</button>-->
                        </form>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#career_list" onclick="addTitle()">ADD</button>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#invoice_filter" style="margin-left: 20px;">FILTER</button>
                        <button type="button" class="pdf_download btn btn-primary mb-2" id="pdf" style="margin-left: 20px;">PDF</button>
                        <!--                        <button class="pdf_download btn btn-success" type="button" id="pdf">PDF</button>-->
                        <button type="button" class="excel_download btn btn-rounded btn-success" style="margin-left: 20px;"><span class="btn-icon-left text-success"><i class="fa fa-download color-success"></i>
            </span>Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead>
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <!--                                <th><strong>First Name</strong></th>-->
                                                                <th><strong>Staff ID</strong></th>
                                <th><strong>UserName</strong></th>
                                <th><strong>Role</strong></th>
                                <th><strong>Phone</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>Password</strong></th>
                                <th><strong>Image</strong></th>
                                <th><strong>Documents</strong></th>
                                <th><strong>Active Status</strong></th>
                                <th><strong>Field App</strong></th>
                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
                            //                            $sql = "SELECT * FROM user ORDER BY id  LIMIT $start,10";

                            if($email == "" && $mobile== "" && $u_name == "") {
                                $sql = "SELECT * FROM user  ORDER BY id  LIMIT $start,10";
                            }
                            else {
                                $sql = "SELECT * FROM user WHERE id > 0 $emailSql$mobileSql$uNameSql ORDER BY id  LIMIT $start,10";
                            }

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
                            $career_dates =   $row['career_date'];
                            $career_date =   date('d-F-Y');
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <!--                                <td>--><?php //echo $row['f_name']?><!--</td>-->
                                                                <td> <?php echo $row['staff_id']?> </td>
                                <td> <?php echo $row['username']?> </td>
                                <td> <?php echo $row['role']?> </td>
                                <td> <?php echo $row['phone']?> </td>
                                <td><?php echo $row['email']?></td>
                                <td><?php echo $row['password']?></td>
                                <td style="cursor: pointer">   <span class="badge badge-pill <?php echo $img_upload?> ml-2" data-toggle="modal" data-target="<?php echo $img_modal?>" onclick="imgModal('<?php echo $row['user_id']; ?>')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                                              <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                              <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                                            </svg>
                                        </span>
                                </td>

                                <td>  <a href="<?php echo $website ?>/People/Users/?userid=<?php echo $row['user_id']; ?>" class="btn btn-success" style="padding: 6px">Documents</a>
                                </td>


                                <td> <span class="badge badge-pill badge-<?php echo $statColor?>"><?php echo $statCont?></span></td>
                                <?php
                                if($row['field_app']==1){
                                    ?>
                                    <td><button type="button" class="btn btn-success pt-1 pb-1" id="<?php echo $row['user_id'];?>" onclick="resend('<?php echo $row['user_id']; ?>');">Send</button>
                                    </td>
                                <?php
                                }else{
                                    ?>
                                    <td></td>
                                <?php
                                }
                                ?>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['user_id'];?>')">Edit</a>
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin') {
                                                ?>
                                                <a class="dropdown-item" style="cursor: pointer"
                                                   onclick="delete_model('<?php echo $row['user_id']; ?>')">Delete</a>
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
                                        <li class="page-item page-indicator"><a class="page-link" href="?page_no=<?php echo $prevPage?>"><i class="fa-solid fa-less-than"></i></a></li>
                                        <?php
                                    }

                                    //                                    $sql = 'SELECT COUNT(id) as count FROM user;';
                                    if($email == "" && $mobile== "" && $u_name == "") {
                                        $sql = "SELECT COUNT(id) as count FROM user";
                                    }
                                    else {
                                        $sql = "SELECT COUNT(id) as count FROM user WHERE id > 0 $emailSql$mobileSql$uNameSql";
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





        <div class="modal fade" id="document_list" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='<?php echo $website ?>/People/Users'">
                            <span aria-hidden="true" >&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <?php
                            $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$user_doc'";
                            $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
                            if (mysqli_num_rows($resValidateCookie) > 0) {
                                $row = mysqli_fetch_assoc($resValidateCookie);
                                if ($row['doc1'] == 1) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label>Document 1</label>
                                        <button class="btn btn-primary btn-block"
                                                onclick="window.open('<?php echo $website ?>/People/Users/user_doc/<?php echo $user_doc ?>1.pdf', '_blank')">
                                            Document
                                        </button>
                                    </div>
                                    <?php
                                }
                                if ($row['doc2'] == 1) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label>Document 2</label>
                                        <button class="btn btn-primary btn-block"
                                                onclick="window.open('<?php echo $website ?>/People/Users/user_doc/<?php echo $user_doc ?>2.pdf', '_blank')">
                                            Document
                                        </button>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="row">
                            <?php
                            if ($row['doc3'] == 1) {
                                ?>
                                <div class="form-group col-md-6">
                                    <label>Document 3</label>
                                    <button class="btn btn-primary btn-block"
                                            onclick="window.open('<?php echo $website ?>/People/Users/user_doc/<?php echo $user_doc ?>3.pdf', '_blank')">
                                        Document
                                    </button>
                                </div>
                                <?php
                            }
                            if ($row['doc4'] == 1) {
                                ?>
                                <div class="form-group col-md-6">
                                    <label>Document 4</label>
                                    <button class="btn btn-primary btn-block"
                                            onclick="window.open('<?php echo $website ?>/People/Users/user_doc/<?php echo $user_doc ?>4.pdf', '_blank')">
                                        Document
                                    </button>
                                </div>
                                <?php
                            }
                            if ($row['doc5'] == 1) {
                                ?>
                                <div class="form-group col-md-6">
                                    <label>Document 5</label>
                                    <button class="btn btn-primary btn-block"
                                            onclick="window.open('<?php echo $website ?>/People/Users/user_doc/<?php echo $user_doc ?>5.pdf', '_blank')">
                                        Document
                                    </button>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo $website ?>/People/Users'">Close</button>
                        <!--                        <button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>




        <div class="modal fade" id="career_list"  data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                                        <label>Staff ID*</label>
                                        <input type="text" class="form-control" placeholder="Staff ID" id="staff_id" name="staff_id" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>First Name *</label>
                                        <input type="text" class="form-control" placeholder="First Name" id="first_name" name="first_name" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="user_id" name="user_id">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last Name *</label>
                                        <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>UserName *</label>
                                        <input type="text" class="form-control" placeholder="UserName" id="user_name" name="user_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone *</label>
                                        <input type="number" class="form-control" placeholder="Phone" id="Phone" name="Phone" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email *</label>
                                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" onkeyup="myFunction()" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Password *</label>
                                        <input type="text" class="form-control" placeholder="Password" id="password" name="password" style="border-color: #181f5a;color: black">
                                    </div>
                                    <!--                                    <div class="form-group col-md-6">-->
                                    <!--                                        <label>Role *</label>-->
                                    <!--                                        <input type="text" class="form-control" placeholder="Role" id="role" name="role" style="border-color: #181f5a;color: black">-->
                                    <!--                                    </div>-->
                                    <!--                                    <div class="form-row">-->
                                    <div class="form-group col-md-6">
                                        <label>Role *</label>
                                        <select data-search="true" class="form-control tail-select w-full" id="role" name="role" style="border-color: #181f5a;color: black">
                                            <option value=''>Select Role</option>
                                            <option value='Admin'>Admin</option>
                                            <option value='Accounts'>Accounts</option>
                                            <option value='Sales'>Sales</option>
                                            <option value='Stores'>Stores</option>
                                            <option value='Market'>Marketing</option>
                                            <option value='Service'>Service</option>
                                            <option value='Driver'>Driver</option>
                                            <option value='Others'>Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Eligible Leave Per Month *</label>
                                        <input type="number" class="form-control" placeholder="Sick + casual Leaves" id="leave" name="leave" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="upload_image">User Image(1 MB)</label>
                                        <input type="file" class="form-control" placeholder="Upload Image" id="upload_image" name="upload_image" style="border-color: #181f5a;color: black" accept=".jpg,.jpeg,.png">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="upload_Document">Document(1)</label>
                                        <input type="file" class="form-control" placeholder="Upload Document" id="upload_doc1" name="upload_doc1" style="border-color: #181f5a;color: black" accept=".pdf">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="upload_Document">Document(2)</label>
                                        <input type="file" class="form-control" placeholder="Upload Document" id="upload_doc2" name="upload_doc2" style="border-color: #181f5a;color: black" accept=".pdf">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="upload_Document">Document(3)</label>
                                        <input type="file" class="form-control" placeholder="Upload Document" id="upload_doc3" name="upload_doc3" style="border-color: #181f5a;color: black" accept=".pdf">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="upload_Document">Document(4)</label>
                                        <input type="file" class="form-control" placeholder="Upload Document" id="upload_doc4" name="upload_doc4" style="border-color: #181f5a;color: black" accept=".pdf">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="upload_Document">Document(5)</label>
                                        <input type="file" class="form-control" placeholder="Upload Document" id="upload_doc5" name="upload_doc5" style="border-color: #181f5a;color: black" accept=".pdf">
                                    </div>
                                    <div class="form-group col-md-3 mt-5">
                                        <label>Field APP</label>
                                        <label class="switch">
                                            <input type="checkbox" checked id="field_status"  name="field_status">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="form-group col-md-3 mt-5">
                                        <label>Active Status</label>
                                        <label class="switch">
                                            <input type="checkbox" checked id="access_status"  name="access_status">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <h4>Salary Payment</h4>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Gross salary *</label>
                                        <input type="number" class="form-control" placeholder="Per Month Salary" id="gross" name="gross" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Deductions *</label>
                                        <input type="text" class="form-control" placeholder="Deduction per Month" id="deduction" name="deduction" style="border-color: #181f5a;color: black">
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
                                    <label>User Name </label>
                                    <input type="text"  class="form-control" placeholder="User Name" id="u_name" name="u_name" style="border-color: #181f5a;color: black">
                                </div>
                                <!--                                <div class="form-group col-md-12">-->
                                <!--                                    <label>User Code </label>-->
                                <!--                                    <input type="text"  class="form-control" placeholder="User Code" id="u_code" name="s_code" style="border-color: #181f5a;color: black">-->
                                <!--                                </div>-->
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
    function myFunction() {
        // let x = document.getElementById("customer_email");
        let y = document.getElementById("email");
        // x.value = x.value.toLowerCase();
        y.value = y.value.toLowerCase();
    }
    function imgModal(src) {
        document.getElementById('modal_images').setAttribute("src",'user_img/'+src+'.jpg');

    }
    function addTitle() {
        $("#title").html("Add User");
        $('#career_form')[0].reset();
        $('#api').val("add.php")
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
                    $("#staff_id").val(res.staff_id);
                    $("#first_name").val(res.f_name);
                    $("#last_name").val(res.l_name);
                    $("#user_name").val(res.username);
                    $("#Phone").val(res.phone);
                    $("#email").val(res.email);
                    $("#password").val(res.password);
                    $("#role").val(res.role);
                    // $("#warehouse").val(res.warehouse);
                    $("#access_status").val(res.access_status);
                    $("#field_status").val(res.field_status);
                    // $(".summernote").code("your text");
                    $("#leave").val(res.leave);
                    $("#gross").val(res.gross);
                    $("#deduction").val(res.deduction);
                    $("#old_pa_id").val(res.user_id);
                    $("#user_id").val(res.user_id);

                    if(Number(res.access_status) == 1){
                        document.getElementById("access_status").checked = true;
                    }
                    else {
                        document.getElementById("access_status").checked = false;

                    }

                    if(Number(res.field_status) == 1){
                        document.getElementById("field_status").checked = true;
                    }
                    else {
                        document.getElementById("field_status").checked = false;

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

                staff_id: {
                    required: true
                },

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
                leave: {
                    required: true
                },
                gross: {
                    required: true
                },
                deduction: {
                    required: true
                },


            },
            // Specify validation error messages
            messages: {
                staff_id: "*This field is required",
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
                deduction: "*This field is required",
                gross: "*This field is required",
                leave: "*This field is required",
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
            var field_status = $('#field_status').is(":checked");

            console.log(access_status);

            if(access_status == true)
            {
                access_status =1;
            }
            else{
                access_status =0;
            }

            if(field_status == true)
            {
                field_status =1;
            }
            else{
                field_status =0;
            }
            var formData = new FormData(form[0]);
            formData.append("active_status",access_status);
            formData.append("field_active",field_status);

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

    $( document ).ready(function() {
        $('#search').val('<?php echo $search;?>');


        var user = '<?php echo $user_doc; ?>'; // Assuming $user_doc contains the user document information

        if (user !== '') {
            $('#document_list').modal('show');
        }

    });


    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&u_name=<?php echo $u_name?>&mobile=<?php echo $mobile?>&email=<?php echo $email?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&u_name=<?php echo $u_name?>&mobile=<?php echo $mobile?>&email=<?php echo $email?>";
    });




    function resend(id) {

        $("#" + id).html('<i class="fa-solid fa-spinner fa-spin"></i>');
        document.getElementById(id).disabled = true;



        $.ajax({

            type: "POST",
            url: "resend.php",
            data: 'id='+id,
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

                    document.getElementById(id).disabled = false;
                    $("#" + id).text('Resend');
                }

                else if(res.status=='failure')
                {
                    swal(  {
                        title: "Failure",
                        text: res.msg,
                        icon: "error",
                        button: "OK",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        closeOnClickOutside: false,
                    })
                        .then((value) => {
// window.window.location.reload();

                        });
                    document.getElementById(id).disabled = false;
                    $("#" + id).text('Resend');
                }
            },
            error: function(){
                swal("Check your network connection");

// window.window.location.reload();

            }

        });





    }


</script>


</body>
</html>
