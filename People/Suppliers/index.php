<?php Include("../../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
$s_name= $_GET['s_name'];
$s_code= $_GET['s_code'];
$mobile= $_GET['mobile'];
$email= $_GET['email'];
if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}

if($s_name != ""){
//    $sNameSql= " AND supplier_name = '".$s_name."'";
    $sNameSql = " AND supplier_name LIKE '%" . $s_name . "%'";

}
else{
    $sNameSql ="";
}

if($s_code != ""){
//    $sCodeSql= " AND supplier_code = '".$s_code."'";
    $sCodeSql = " AND supplier_code LIKE '%" . $s_code . "%'";

}
else{
    $sCodeSql ="";
}

if($mobile != ""){
//    $mobileSql= " AND supplier_phone = '".$mobile."'";
    $mobileSql = " AND supplier_phone LIKE '%" . $mobile . "%'";
}
else{
    $mobileSql ="";
}

if($email != ""){
//    $emailSql= "AND supplier_email = '".$email."'";
    $emailSql = " AND supplier_email LIKE '%" . $email . "%'";
}
else {
    $emailSql = "";
}
$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin'){
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
    <title>Suppliers</title>
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
$header_name ="Supplier";
    Include ('../../includes/header.php') ?>

    <div class="content-body">
        <div class="page-titles" style="margin-left: 21px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Supplier</a></li>
            </ol>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Supplier List</h4>
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
                                <th><strong>Supplier Name</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>Phone</strong></th>

                                <th><strong>GSTIN</strong></th>
                                <th><strong>Address</strong></th>                  
                                <th><strong>View</strong></th>
                                <th><strong>Action</strong></th>

                            </tr>
                            </thead>
                            <?php
//                               $sql = "SELECT * FROM supplier ORDER BY id  LIMIT $start,10";

                            if($email == "" && $mobile== "" && $s_name == "") {
                                $sql = "SELECT * FROM supplier  ORDER BY id DESC LIMIT $start,10";
                            }
                            else {
                                $sql = "SELECT * FROM supplier WHERE id > 0 $emailSql$mobileSql$sNameSql ORDER BY id DESC LIMIT $start,10";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0) {
                            $sNo = $start;
                            while($row = mysqli_fetch_assoc($result)) {

                            $sNo++;
                            // if($row['access_status'] == 1){
                            //     $statColor = 'success';
                            //     $statCont = 'Active';
                            // }
                            // else {
                            //     $statColor = 'danger';
                            //     $statCont = 'In Active';
                            // }
                        //   $career_dates =   $row['career_date'];
                        //   $career_date =   date('d-F-Y');
                            ?>
                            <tbody>
                            <tr>
                                <td><strong><?php echo $sNo;?></strong></td>
                                <td><?php echo $row['supplier_name']?></td>
                                <td> <?php echo $row['supplier_email']?> </td>
                                <td> <?php echo $row['supplier_phone']?> </td>
<!--                                <td> --><?php //echo $row['country']?><!-- </td>-->
                                <td> <?php echo $row['gstin']?> </td>
                                <td> <?php echo $row['address1']?> </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="<?php echo $website?>/People/Suppliers/show_file.php?supplier_id=<?php echo $row['supplier_id']?>"
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
                                            <a class="dropdown-item" data-toggle="modal" data-target="#career_list" style="cursor: pointer" onclick="editTitle('<?php echo $row['supplier_id'];?>')">Edit</a>
                                            <?php
                                            if($_COOKIE['role'] == 'Super Admin') {
                                            ?>
                                            <a class="dropdown-item" style="cursor: pointer" onclick="delete_model('<?php echo $row['supplier_id'];?>')">Delete</a>
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

//                                    $sql = 'SELECT COUNT(id) as count FROM supplier;';
                                    if($email == "" && $mobile== "" && $s_name == "") {
                                        $sql = "SELECT COUNT(id) as count FROM supplier";
                                    }
                                    else {
                                        $sql = "SELECT COUNT(id) as count FROM supplier WHERE id > 0 $emailSql$mobileSql$sNameSql";
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

                                    <div class="form-group col-md-6" id="sup_name">
                                        <label>Supplier Name *</label>
                                        <input type="text" class="form-control" placeholder="Supplier Name" id="supplier_name" name="supplier_name" style="border-color: #181f5a;color: black">
                                        <input type="hidden" class="form-control"  id="api" name="api">
                                        <input type="hidden" class="form-control"  id="old_pa_id" name="old_pa_id">
                                        <input type="hidden" class="form-control"  id="supplier_id" name="supplier_id">
                                    </div>

                                    <div class="form-group col-md-6" id="sup_email">
                                        <label>Email *</label>
                                        <input type="email" class="form-control" placeholder="Email" id="supplier_email" name="supplier_email" onkeyup="myFunction()" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="sup_phone">
                                        <label>Phone 1 *</label>
                                        <input type="number" class="form-control" placeholder="Phone 1" id="supplier_phone" name="supplier_phone" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="sup_phone1">
                                        <label>Phone 2</label>
                                        <input type="number" class="form-control" placeholder="Phone 2" id="supplier_phone1" name="supplier_phone1" style="border-color: #181f5a;color: black">
                                    </div>

                                    <div class="form-group col-md-6" id="sup_gstin">
                                        <label>GSTIN</label>
                                        <input type="text" class="form-control" placeholder="GSTIN" id="gstin" name="gstin" style="border-color: #181f5a;color: black;text-transform: uppercase">
                                    </div>
                                    <div class="form-group col-md-6" id="sup_country">
                                        <label>Country *</label>
                                        <select name="country" id="country" class="form-control" onchange="dropdownfun()" style="border-color: #181f5a;color: black">
                                            <option value="">Select Country</option>
                                            <option value="Afghanistan">Afghanistan</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="Brunei">Brunei</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cabo Verde">Cabo Verde</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo">Congo</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Eswatini">Eswatini</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guinea-Bissau">Guinea-Bissau</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Iran">Iran</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Kosovo">Kosovo</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Laos">Laos</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Micronesia">Micronesia</option>
                                            <option value="Moldova">Moldova</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montenegro">Montenegro</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Namibia">Namibia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="North Korea">North Korea</option>
                                            <option value="North Macedonia">North Macedonia</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau">Palau</option>
                                            <option value="Palestine">Palestine</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                            <option value="Saint Lucia">Saint Lucia</option>
                                            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Serbia">Serbia</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="South Korea">South Korea</option>
                                            <option value="South Sudan">South Sudan</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syria">Syria</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Timor-Leste">Timor-Leste</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Emirates">United Arab Emirates</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="United States of America">United States of America</option>
                                            <option value="Uruguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Vatican City">Vatican City</option>
                                            <option value="Venezuela">Venezuela</option>
                                            <option value="Vietnam">Vietnam</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>
                                    </div>
<!--                                    <div class="form-group col-md-6" id="sup_country">-->
<!--                                        <label>Country *</label>-->
<!--                                        <select name="country" id="country" class="form-control" style="border-color: #181f5a;color: black">-->
<!--                                            <option value="">Select Country</option>-->
<!--                                            <option value="Afghanistan">Afghanistan</option>-->
<!--                                            <option value="Albania">Albania</option>-->
<!--                                            <option value="Algeria">Algeria</option>-->
<!--                                            <option value="Andorra">Andorra</option>-->
<!--                                            <option value="Angola">Angola</option>-->
<!--                                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>-->
<!--                                            <option value="Argentina">Argentina</option>-->
<!--                                            <option value="Armenia">Armenia</option>-->
<!--                                            <option value="Australia">Australia</option>-->
<!--                                            <option value="Austria">Austria</option>-->
<!--                                            <option value="Azerbaijan">Azerbaijan</option>-->
<!--                                            <option value="Bahamas">Bahamas</option>-->
<!--                                            <option value="Bahrain">Bahrain</option>-->
<!--                                            <option value="Bangladesh">Bangladesh</option>-->
<!--                                            <option value="Barbados">Barbados</option>-->
<!--                                            <option value="Belarus">Belarus</option>-->
<!--                                            <option value="Belgium">Belgium</option>-->
<!--                                            <option value="Belize">Belize</option>-->
<!--                                            <option value="Benin">Benin</option>-->
<!--                                            <option value="Bhutan">Bhutan</option>-->
<!--                                            <option value="Bolivia">Bolivia</option>-->
<!--                                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>-->
<!--                                            <option value="Botswana">Botswana</option>-->
<!--                                            <option value="Brazil">Brazil</option>-->
<!--                                            <option value="Brunei">Brunei</option>-->
<!--                                            <option value="Bulgaria">Bulgaria</option>-->
<!--                                            <option value="Burkina Faso">Burkina Faso</option>-->
<!--                                            <option value="Burundi">Burundi</option>-->
<!--                                            <option value="Cabo Verde">Cabo Verde</option>-->
<!--                                            <option value="Cambodia">Cambodia</option>-->
<!--                                            <option value="Cameroon">Cameroon</option>-->
<!--                                            <option value="Canada">Canada</option>-->
<!--                                            <option value="Central African Republic">Central African Republic</option>-->
<!--                                            <option value="Chad">Chad</option>-->
<!--                                            <option value="Chile">Chile</option>-->
<!--                                            <option value="China">China</option>-->
<!--                                            <option value="Colombia">Colombia</option>-->
<!--                                            <option value="Comoros">Comoros</option>-->
<!--                                            <option value="Congo">Congo</option>-->
<!--                                            <option value="Costa Rica">Costa Rica</option>-->
<!--                                            <option value="Croatia">Croatia</option>-->
<!--                                            <option value="Cuba">Cuba</option>-->
<!--                                            <option value="Cyprus">Cyprus</option>-->
<!--                                            <option value="Czech Republic">Czech Republic</option>-->
<!--                                            <option value="Denmark">Denmark</option>-->
<!--                                            <option value="Djibouti">Djibouti</option>-->
<!--                                            <option value="Dominica">Dominica</option>-->
<!--                                            <option value="Dominican Republic">Dominican Republic</option>-->
<!--                                            <option value="Ecuador">Ecuador</option>-->
<!--                                            <option value="Egypt">Egypt</option>-->
<!--                                            <option value="El Salvador">El Salvador</option>-->
<!--                                            <option value="Equatorial Guinea">Equatorial Guinea</option>-->
<!--                                            <option value="Eritrea">Eritrea</option>-->
<!--                                            <option value="Estonia">Estonia</option>-->
<!--                                            <option value="Eswatini">Eswatini</option>-->
<!--                                            <option value="Ethiopia">Ethiopia</option>-->
<!--                                            <option value="Fiji">Fiji</option>-->
<!--                                            <option value="Finland">Finland</option>-->
<!--                                            <option value="France">France</option>-->
<!--                                            <option value="Gabon">Gabon</option>-->
<!--                                            <option value="Gambia">Gambia</option>-->
<!--                                            <option value="Georgia">Georgia</option>-->
<!--                                            <option value="Germany">Germany</option>-->
<!--                                            <option value="Ghana">Ghana</option>-->
<!--                                            <option value="Greece">Greece</option>-->
<!--                                            <option value="Grenada">Grenada</option>-->
<!--                                            <option value="Guatemala">Guatemala</option>-->
<!--                                            <option value="Guinea">Guinea</option>-->
<!--                                            <option value="Guinea-Bissau">Guinea-Bissau</option>-->
<!--                                            <option value="Guyana">Guyana</option>-->
<!--                                            <option value="Haiti">Haiti</option>-->
<!--                                            <option value="Honduras">Honduras</option>-->
<!--                                            <option value="Hungary">Hungary</option>-->
<!--                                            <option value="Iceland">Iceland</option>-->
<!--                                            <option value="India">India</option>-->
<!--                                            <option value="Indonesia">Indonesia</option>-->
<!--                                            <option value="Iran">Iran</option>-->
<!--                                            <option value="Iraq">Iraq</option>-->
<!--                                            <option value="Ireland">Ireland</option>-->
<!--                                            <option value="Israel">Israel</option>-->
<!--                                            <option value="Italy">Italy</option>-->
<!--                                            <option value="Jamaica">Jamaica</option>-->
<!--                                            <option value="Japan">Japan</option>-->
<!--                                            <option value="Jordan">Jordan</option>-->
<!--                                            <option value="Kazakhstan">Kazakhstan</option>-->
<!--                                            <option value="Kenya">Kenya</option>-->
<!--                                            <option value="Kiribati">Kiribati</option>-->
<!--                                            <option value="Kosovo">Kosovo</option>-->
<!--                                            <option value="Kuwait">Kuwait</option>-->
<!--                                            <option value="Kyrgyzstan">Kyrgyzstan</option>-->
<!--                                            <option value="Laos">Laos</option>-->
<!--                                            <option value="Latvia">Latvia</option>-->
<!--                                            <option value="Lebanon">Lebanon</option>-->
<!--                                            <option value="Lesotho">Lesotho</option>-->
<!--                                            <option value="Liberia">Liberia</option>-->
<!--                                            <option value="Libya">Libya</option>-->
<!--                                            <option value="Liechtenstein">Liechtenstein</option>-->
<!--                                            <option value="Lithuania">Lithuania</option>-->
<!--                                            <option value="Luxembourg">Luxembourg</option>-->
<!--                                            <option value="Madagascar">Madagascar</option>-->
<!--                                            <option value="Malawi">Malawi</option>-->
<!--                                            <option value="Malaysia">Malaysia</option>-->
<!--                                            <option value="Maldives">Maldives</option>-->
<!--                                            <option value="Mali">Mali</option>-->
<!--                                            <option value="Malta">Malta</option>-->
<!--                                            <option value="Marshall Islands">Marshall Islands</option>-->
<!--                                            <option value="Mauritania">Mauritania</option>-->
<!--                                            <option value="Mauritius">Mauritius</option>-->
<!--                                            <option value="Mexico">Mexico</option>-->
<!--                                            <option value="Micronesia">Micronesia</option>-->
<!--                                            <option value="Moldova">Moldova</option>-->
<!--                                            <option value="Monaco">Monaco</option>-->
<!--                                            <option value="Mongolia">Mongolia</option>-->
<!--                                            <option value="Montenegro">Montenegro</option>-->
<!--                                            <option value="Morocco">Morocco</option>-->
<!--                                            <option value="Mozambique">Mozambique</option>-->
<!--                                            <option value="Myanmar">Myanmar</option>-->
<!--                                            <option value="Namibia">Namibia</option>-->
<!--                                            <option value="Nauru">Nauru</option>-->
<!--                                            <option value="Nepal">Nepal</option>-->
<!--                                            <option value="Netherlands">Netherlands</option>-->
<!--                                            <option value="New Zealand">New Zealand</option>-->
<!--                                            <option value="Nicaragua">Nicaragua</option>-->
<!--                                            <option value="Niger">Niger</option>-->
<!--                                            <option value="Nigeria">Nigeria</option>-->
<!--                                            <option value="North Korea">North Korea</option>-->
<!--                                            <option value="North Macedonia">North Macedonia</option>-->
<!--                                            <option value="Norway">Norway</option>-->
<!--                                            <option value="Oman">Oman</option>-->
<!--                                            <option value="Pakistan">Pakistan</option>-->
<!--                                            <option value="Palau">Palau</option>-->
<!--                                            <option value="Palestine">Palestine</option>-->
<!--                                            <option value="Panama">Panama</option>-->
<!--                                            <option value="Papua New Guinea">Papua New Guinea</option>-->
<!--                                            <option value="Paraguay">Paraguay</option>-->
<!--                                            <option value="Peru">Peru</option>-->
<!--                                            <option value="Philippines">Philippines</option>-->
<!--                                            <option value="Poland">Poland</option>-->
<!--                                            <option value="Portugal">Portugal</option>-->
<!--                                            <option value="Qatar">Qatar</option>-->
<!--                                            <option value="Romania">Romania</option>-->
<!--                                            <option value="Russia">Russia</option>-->
<!--                                            <option value="Rwanda">Rwanda</option>-->
<!--                                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>-->
<!--                                            <option value="Saint Lucia">Saint Lucia</option>-->
<!--                                            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>-->
<!--                                            <option value="Samoa">Samoa</option>-->
<!--                                            <option value="San Marino">San Marino</option>-->
<!--                                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>-->
<!--                                            <option value="Saudi Arabia">Saudi Arabia</option>-->
<!--                                            <option value="Senegal">Senegal</option>-->
<!--                                            <option value="Serbia">Serbia</option>-->
<!--                                            <option value="Seychelles">Seychelles</option>-->
<!--                                            <option value="Sierra Leone">Sierra Leone</option>-->
<!--                                            <option value="Singapore">Singapore</option>-->
<!--                                            <option value="Slovakia">Slovakia</option>-->
<!--                                            <option value="Slovenia">Slovenia</option>-->
<!--                                            <option value="Solomon Islands">Solomon Islands</option>-->
<!--                                            <option value="Somalia">Somalia</option>-->
<!--                                            <option value="South Africa">South Africa</option>-->
<!--                                            <option value="South Korea">South Korea</option>-->
<!--                                            <option value="South Sudan">South Sudan</option>-->
<!--                                            <option value="Spain">Spain</option>-->
<!--                                            <option value="Sri Lanka">Sri Lanka</option>-->
<!--                                            <option value="Sudan">Sudan</option>-->
<!--                                            <option value="Suriname">Suriname</option>-->
<!--                                            <option value="Sweden">Sweden</option>-->
<!--                                            <option value="Switzerland">Switzerland</option>-->
<!--                                            <option value="Syria">Syria</option>-->
<!--                                            <option value="Taiwan">Taiwan</option>-->
<!--                                            <option value="Tajikistan">Tajikistan</option>-->
<!--                                            <option value="Tanzania">Tanzania</option>-->
<!--                                            <option value="Thailand">Thailand</option>-->
<!--                                            <option value="Timor-Leste">Timor-Leste</option>-->
<!--                                            <option value="Togo">Togo</option>-->
<!--                                            <option value="Tonga">Tonga</option>-->
<!--                                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>-->
<!--                                            <option value="Tunisia">Tunisia</option>-->
<!--                                            <option value="Turkey">Turkey</option>-->
<!--                                            <option value="Turkmenistan">Turkmenistan</option>-->
<!--                                            <option value="Tuvalu">Tuvalu</option>-->
<!--                                            <option value="Uganda">Uganda</option>-->
<!--                                            <option value="Ukraine">Ukraine</option>-->
<!--                                            <option value="United Arab Emirates">United Arab Emirates</option>-->
<!--                                            <option value="United Kingdom">United Kingdom</option>-->
<!--                                            <option value="United States of America">United States of America</option>-->
<!--                                            <option value="Uruguay">Uruguay</option>-->
<!--                                            <option value="Uzbekistan">Uzbekistan</option>-->
<!--                                            <option value="Vanuatu">Vanuatu</option>-->
<!--                                            <option value="Vatican City">Vatican City</option>-->
<!--                                            <option value="Venezuela">Venezuela</option>-->
<!--                                            <option value="Vietnam">Vietnam</option>-->
<!--                                            <option value="Yemen">Yemen</option>-->
<!--                                            <option value="Zambia">Zambia</option>-->
<!--                                            <option value="Zimbabwe">Zimbabwe</option>-->
<!--                                        </select>-->
<!--                                    </div>-->
<!---->
                                    <div class="form-group col-md-6" id="sup_place">
                                        <label>Place of Supply(India)</label>
                                        <select name="supply_place" id="supply_place" class="form-control" style="border-color: #181f5a;color: black">
                                            <option value="">Selct State</option>
                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                            <option value="Assam">Assam</option>
                                            <option value="Bihar">Bihar</option>
                                            <option value="Chandigarh">Chandigarh</option>
                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                            <option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
                                            <option value="Daman and Diu">Daman and Diu</option>
                                            <option value="Delhi">Delhi</option>
                                            <option value="Lakshadweep">Lakshadweep</option>
                                            <option value="Puducherry">Puducherry</option>
                                            <option value="Goa">Goa</option>
                                            <option value="Gujarat">Gujarat</option>
                                            <option value="Haryana">Haryana</option>
                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                            <option value="Jharkhand">Jharkhand</option>
                                            <option value="Karnataka">Karnataka</option>
                                            <option value="Kerala">Kerala</option>
                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                            <option value="Maharashtra">Maharashtra</option>
                                            <option value="Manipur">Manipur</option>
                                            <option value="Meghalaya">Meghalaya</option>
                                            <option value="Mizoram">Mizoram</option>
                                            <option value="Nagaland">Nagaland</option>
                                            <option value="Odisha">Odisha</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Rajasthan">Rajasthan</option>
                                            <option value="Sikkim">Sikkim</option>
                                            <option value="Tamil Nadu" selected>Tamil Nadu</option>
                                            <option value="Telangana">Telangana</option>
                                            <option value="Tripura">Tripura</option>
                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                            <option value="Uttarakhand">Uttarakhand</option>
                                            <option value="West Bengal">West Bengal</option>
                                        </select>
<!--                                     <input type="text" class="form-control" placeholder="Place of Supply" id="supply_place" name="supply_place" style="border-color: #181f5a;color: black">-->
                                    </div>
<!--                                    <div class="form-group col-md-6" id="sup_other">-->
<!--                                        <label>Place of Supply(Foreign)</label>-->
<!--                                        <input type="text" class="form-control" placeholder="Other country state" id="supply_place" name="supply_place" style="border-color: #181f5a;color: black;text-transform: uppercase">-->
<!--                                    </div>-->
                                    <div class="form-group col-md-12" id="sup_address1">
                                        <label>Address *</label>
                                        <textarea class="form-control" placeholder="Address" id="address1" name="address1" style="border-color: #181f5a;color: black"></textarea>
                                        <!--                                    <div class="summernote"></div>-->
                                    </div>
                                    <div class="form-group col-md-12" id="sup_access_status">
                                        <label>Set Default Address</label>
                                        <label class="switch">
                                            <input type="checkbox"  id="access_status"  name="access_status" onclick="handleCheckboxClick()">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="form-group col-md-12" id="sup_address1">
                                        <label>Ship To Address</label>
                                        <textarea class="form-control" placeholder="Ship To Address" id="address2" name="address2" style="border-color: #181f5a;color: black"></textarea>
                                        <!--                                    <div class="summernote"></div>-->
                                    </div>
                                    <div class="form-group col-md-6" id="sup_bank_name">
                                        <label>Bank Name </label>
                                        <input type="text" class="form-control" placeholder="Bank Name" id="bank_name" name="bank_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="sup_acc_name">
                                        <label>Account Name </label>
                                        <input type="text" class="form-control" placeholder="Account Name" id="acc_name" name="acc_name" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="sup_acc_no">
                                        <label>Account No </label>
                                        <input type="text" class="form-control" placeholder="Account No" id="acc_no" name="acc_no" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="sup_ifsc">
                                        <label>IFSC Code  </label>
                                        <input type="text" class="form-control" placeholder="IFSC Code" id="ifsc" name="ifsc" style="border-color: #181f5a;color: black">
                                    </div>
                                    <div class="form-group col-md-6" id="sup_branch_name">
                                        <label>Branch Name  </label>
                                        <input type="text" class="form-control" placeholder="Branch Name" id="branch_name" name="branch_name" style="border-color: #181f5a;color: black">
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
                                    <label>Supplier Name </label>
                                    <input type="text"  class="form-control" placeholder="Supplier Name" id="s_name" name="s_name" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Mobile </label>
                                    <input type="text"  class="form-control" placeholder="mobile" id="mobile" name="mobile" style="border-color: #181f5a;color: black">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Email </label>
                                    <input type="text"  class="form-control" placeholder="email" id="email" name="email" onkeyup="myFunction()" style="border-color: #181f5a;color: black">
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
        let x = document.getElementById("supplier_email");
        let y = document.getElementById("email");
        x.value = x.value.toLowerCase();
        y.value = y.value.toLowerCase();
    }
    function dropdownfun(){
        const inputDateValue = document.getElementById("country").value;
        if (inputDateValue != 'India') {
            var inputElement = $('<input>').attr({
                type: 'text', // Change type to text (or any other type you desire)
                id: 'supply_place', // Set id attribute to match the original select element
                name: 'supply_place', // Set name attribute if needed
                style:"border-color: #181f5a;color: black",
                class:"form-control"
            });


            $('#supply_place').replaceWith(inputElement);
        }else{
            var selectElement = $('<select>').attr({
                id: 'supply_place', // Set id attribute to match the original select element
                name: 'supply_place',
                style: "border-color: #181f5a;color: black",
                class: "form-control"
            });

            selectElement.append($('<option>').val('Andhra Pradesh').text('Andhra Pradesh'));
            selectElement.append($('<option>').val('Andaman and Nicobar Islands').text('Andaman and Nicobar Islands'));
            selectElement.append($('<option>').val('Arunachal Pradesh').text('Arunachal Pradesh'));
            selectElement.append($('<option>').val('Assam').text('Assam'));
            selectElement.append($('<option>').val('Bihar').text('Bihar'));
            selectElement.append($('<option>').val('Chandigarh').text('Chandigarh'));
            selectElement.append($('<option>').val('Chhattisgarh').text('Chhattisgarh'));
            selectElement.append($('<option>').val('Dadar and Nagar Haveli').text('Dadar and Nagar Haveli'));
            selectElement.append($('<option>').val('Daman and Diu').text('Daman and Diu'));
            selectElement.append($('<option>').val('Delhi').text('Delhi'));
            selectElement.append($('<option>').val('Lakshadweep').text('Lakshadweep'));
            selectElement.append($('<option>').val('Puducherry').text('Puducherry'));
            selectElement.append($('<option>').val('Goa').text('Goa'));
            selectElement.append($('<option>').val('Gujarat').text('Gujarat'));
            selectElement.append($('<option>').val('Haryana').text('Haryana'));
            selectElement.append($('<option>').val('Himachal Pradesh').text('Himachal Pradesh'));
            selectElement.append($('<option>').val('Jammu and Kashmir').text('Jammu and Kashmir'));
            selectElement.append($('<option>').val('Jharkhand').text('Jharkhand'));
            selectElement.append($('<option>').val('Karnataka').text('Karnataka'));
            selectElement.append($('<option>').val('Kerala').text('Kerala'));
            selectElement.append($('<option>').val('Madhya Pradesh').text('Madhya Pradesh'));
            selectElement.append($('<option>').val('Maharashtra').text('Maharashtra'));
            selectElement.append($('<option>').val('Manipur').text('Manipur'));
            selectElement.append($('<option>').val('Meghalaya').text('Meghalaya'));
            selectElement.append($('<option>').val('Mizoram').text('Mizoram'));
            selectElement.append($('<option>').val('Nagaland').text('Nagaland'));
            selectElement.append($('<option>').val('Odisha').text('Odisha'));
            selectElement.append($('<option>').val('Punjab').text('Punjab'));
            selectElement.append($('<option>').val('Rajasthan').text('Rajasthan'));
            selectElement.append($('<option>').val('Sikkim').text('Sikkim'));
            selectElement.append($('<option>').val('Tamil Nadu').text('Tamil Nadu'));
            selectElement.append($('<option>').val('Telangana').text('Telangana'));
            selectElement.append($('<option>').val('Tripura').text('Tripura'));
            selectElement.append($('<option>').val('Uttar Pradesh').text('Uttar Pradesh'));
            selectElement.append($('<option>').val('Uttarakhand').text('Uttarakhand'));
            selectElement.append($('<option>').val('West Bengal').text('West Bengal'));

            $('#supply_place').replaceWith(selectElement);
        }
    };
    // document.addEventListener('DOMContentLoaded', function () {
    //     const country = document.getElementById('country');
    //     const sup_name = document.getElementById('sup_name');
    //     const sup_email = document.getElementById('sup_email');
    //     const sup_phone = document.getElementById('sup_phone');
    //     const sup_phone1 = document.getElementById('sup_phone1');
    //     const sup_gstin = document.getElementById('sup_gstin');
    //     const sup_country = document.getElementById('sup_country');
    //     const sup_place = document.getElementById('sup_place');
    //     const sup_other = document.getElementById('sup_other');
    //     const sup_address1 = document.getElementById('sup_address1');
    //     const sup_bank_name = document.getElementById('sup_bank_name');
    //     const sup_acc_name = document.getElementById('sup_acc_name');
    //     const sup_acc_no = document.getElementById('sup_acc_no');
    //     const sup_ifsc = document.getElementById('sup_ifsc');
    //     const sup_branch_name = document.getElementById('sup_branch_name');
    //
    //     // Add an event listener to the dropdown
    //     country.addEventListener('change', function () {
    //         a(country.value);
    //     });
    //
    //     function a(values) {
    //         if (values === 'India') {
    //             // Hide the input field when 'Hide Input Field' is selected
    //             country.style.display = 'block';
    //             sup_name.style.display = 'block';
    //             sup_email.style.display = 'block';
    //             sup_phone.style.display = 'block';
    //             sup_phone1.style.display = 'block';
    //             sup_gstin.style.display = 'block';
    //             sup_country.style.display = 'block';
    //             sup_place.style.display = 'block';
    //             sup_other.style.display = 'none';
    //             sup_address1.style.display = 'block';
    //             sup_bank_name.style.display = 'block';
    //             sup_acc_name.style.display = 'block';
    //             sup_acc_no.style.display = 'block';
    //             sup_ifsc.style.display = 'block';
    //             sup_branch_name.style.display = 'block';
    //             // other_ress.style.display = 'block';
    //
    //         }
    //         else {
    //             // Show the input field for other selections
    //             country.style.display = 'block';
    //             sup_name.style.display = 'block';
    //             sup_email.style.display = 'block';
    //             sup_phone.style.display = 'block';
    //             sup_phone1.style.display = 'block';
    //             sup_gstin.style.display = 'block';
    //             sup_country.style.display = 'block';
    //             sup_place.style.display = 'none';
    //             sup_other.style.display = 'block';
    //             sup_address1.style.display = 'block';
    //             sup_bank_name.style.display = 'block';
    //             sup_acc_name.style.display = 'block';
    //             sup_acc_no.style.display = 'block';
    //             sup_ifsc.style.display = 'block';
    //             sup_branch_name.style.display = 'block';
    //         }
    //     }
    // });

    function handleCheckboxClick() {
        var checkbox = document.getElementById("access_status");
        if (checkbox.checked) {
            var address1 = $('#address1').val();
            $('#address2').val(address1);
        }else{
            $('#address2').val();
        }
    }

   function addTitle() {
       $("#title").html("Add Supplier");
       $('#career_form')[0].reset();
       $('#api').val("add_api.php")
       // $('#game_id').prop('readonly', false);
   }

   function editTitle(data) {

       $("#title").html("Edit Supplier- "+data);
       $('#career_form')[0].reset();
       $('#api').val("edit_api.php");

       $.ajax({
           type: "POST",
           url: "view_api.php",
           data: 'supplier_id='+data,
           dataType: "json",
           success: function(res){
               if(res.status=='success')
               {
                   $("#supplier_name").val(res.supplier_name);
                   $("#supplier_email").val(res.supplier_email);
                   $("#supplier_phone").val(res.supplier_phone);
                   $("#supplier_phone1").val(res.supplier_phone1);
                   $("#gstin").val(res.gstin);
                   $('#address1').val(res.address1);
                   $('#address2').val(res.address2);
                   $("#bank_name").val(res.bank_name);
                   $("#acc_name").val(res.acc_name);
                   $("#acc_no").val(res.acc_no);
                   $("#ifsc").val(res.ifsc);
                   $("#address").val(res.address);
                   $("#branch_name").val(res.branch_name);
                   $("#supply_place").val(res.supply_place);
                   $("#country").val(res.country);
                   $("#other_state").val(res.other_state);

                   // $(".summernote").code("your text");
                   $("#old_pa_id").val(res.supplier_id);
                   $("#supplier_id").val(res.supplier_id);

                   $("#access_status").val(res.access_status);
                   // $('#game_id').prop('readonly', true);

                   if(Number(res.access_status) == 1){
                       document.getElementById("access_status").checked = true;
                   }
                   else {
                       document.getElementById("access_status").checked = false;
                   }

                   var edit_model_title = "Edit supplier - "+data;
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

            supplier_name: {
                   required: true
               },
               supplier_email: {
                required: true,
                email: true
               },
               supplier_phone: {
                   required: true,
                maxlength: 10,
                minlength: 10
               },
               // bank_name: {
               //     required: true
               // },
               country: {
                   required: true
               },
               // branch_name: {
               //     required: true
               // },
               // ifsc: {
               //     required: true
               // },
               // acc_no: {
               //     required: true
               // },
               // acc_name: {
               //     required: true
               // },
               address1: {
                   required: true
               },

           },
           // Specify validation error messages
           messages: {
            supplier_name: "*This field is required",
            supplier_email: "*This field is required",
            supplier_phone: {
                    required:"*This field is required",
                    maxlength:"*Mobile Number Should Be 10 Character",
                    minlength:"*Mobile Number Should Be 10 Character"
                },
               bank_name: "*This field is required",
               country: "*This field is required",
               branch_name: "*This field is required",
               ifsc: "*This field is required",
               acc_no: "*This field is required",
               acc_name: "*This field is required",
            address1: "*This field is required",
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
                   // data: $('#career_form').serialize(),
                   data: formData,
                   dataType: "json",
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
                    data: 'supplier_id='+data,
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

    $(document).on("click", ".excel_download", function () {
        window.location.href = "excel_download.php?&s_name=<?php echo $s_name?>&s_code=<?php echo $s_code?>&mobile=<?php echo $mobile?>&email=<?php echo $email?>";
    });
    $(document).on("click", ".pdf_download", function () {
        window.location.href = "pdf_download.php?&s_name=<?php echo $s_name?>&s_code=<?php echo $s_code?>&mobile=<?php echo $mobile?>&email=<?php echo $email?>";
    });
</script>


</body>
</html>
