<?php
$added_by = $_COOKIE['user_id'];

date_default_timezone_set("Asia/Kolkata");
$current_date = date('Y-m-d');
if(isset($_POST['market_profile_id'])) {
    Include("../includes/connection.php");
    //market Profile
    $customer_name = clean($_POST['customer_name']);
    $market_profile_id = clean($_POST['market_profile_id']);
    $material_type = clean($_POST['market_profile_type']);
    $type = clean($_POST['material_type']);
    $location = clean($_POST['location']);
    $monthly_production = clean($_POST['monthly_production']);
    $shift = clean($_POST['shift']);

    //market_details
    $personalLoan = $_POST['personalLoan'];
    $personalLoanAr = explode(",",$personalLoan);
    $countPersonalLoans = count($personalLoanAr);

    //market_laddle
    $homeLoan = $_POST['homeLoan'];
    $homeLoanAr = explode(",",$homeLoan);
    $countHomeLoans = count($homeLoanAr);

    //market_contact
    $cardLoan = $_POST['cardLoan'];
    $cardLoans = explode(",",$cardLoan);
    $countCardLoans = count($cardLoans);

    //market_requirement
    $goldLoan = clean($_POST['goldLoan']);
    $goldLoans = explode(",",$goldLoan);
    $countGoldLoans = count($goldLoans);

    $date = date('Y-m-d');
//    $added_by = $_COOKIE['user_id'];
    $api_key = $_COOKIE['panel_api_key'];

    $added_by = $_COOKIE['user_id'];
//    $sqlValidateCookie="SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
    if (mysqli_num_rows($resValidateCookie) > 0) {
        if($material_type == "company_profile") {
            $sqlUpdate = "UPDATE `market_profile` SET `customer_name`='$customer_name',`material_type`='$type',`location`='$location',`shift`='$shift',`monthly_production`='$monthly_production' WHERE market_profile_id ='$market_profile_id'";
            mysqli_query($conn, $sqlUpdate);
        }
            if($material_type == "furnace_details") {

                //market_details
                $sqlUpdate1 = "DELETE FROM `market_details` WHERE market_profile_id='$market_profile_id'";
                mysqli_query($conn, $sqlUpdate1);
                for ($pl = 0; $pl < $countPersonalLoans; $pl++) {
                    $plValues = $personalLoanAr[$pl];
                    $plValues = substr($plValues, 0, -1);
                    $plValuesAr = explode("%", $plValues);

                    $sqlInsertPLoan = "INSERT INTO `market_details`(`market_profile_id`,`type`, `capacity`, `no_laddle`,`compititer`,`tapping_temperature`,`power`,`sg`,`grey`,`fork_height`,`linning_material`,`linning_lite`,`patching`,`base_metal`,`tapping`,`furnace_dia`,`former_dia`,`coilcoat_dia`,`wall_thickness`,`bottom_height`,`furnace_height`,`former_height`,`gld_height`)
                                           VALUES ('$market_profile_id','furnace','$plValuesAr[0]','$plValuesAr[1]','$plValuesAr[2]','$plValuesAr[3]','$plValuesAr[4]','$plValuesAr[5]','$plValuesAr[6]','$plValuesAr[7]','$plValuesAr[8]','$plValuesAr[9]','$plValuesAr[10]','$plValuesAr[11]','$plValuesAr[12]','$plValuesAr[13]','$plValuesAr[14]','$plValuesAr[15]','$plValuesAr[16]','$plValuesAr[17]','$plValuesAr[18]','$plValuesAr[19]','$plValuesAr[20]')";
                    mysqli_query($conn, $sqlInsertPLoan);
                }
            }
        //market_laddle
        if($material_type == "laddle_details") {
            $sqlUpdate2 = "DELETE FROM `market_laddle` WHERE market_profile_id='$market_profile_id'";
            mysqli_query($conn, $sqlUpdate2);
            for ($hl = 0; $hl < $countHomeLoans; $hl++) {
                $hlValues = $homeLoanAr[$hl];
                $hlValues = substr($hlValues, 0, -1);
                $hlValuesAr = explode("%", $hlValues);

                $sqlInsertHLoan = "INSERT INTO `market_laddle`(`market_profile_id`,`pre_heating`, `laddle_type`, `laddle_shape`,`capacity`,`qty`,`current_linning`,`patching_material`,`coating_material`,`linninglite`,`competitor`,`sg`,`grey`,`laddle_dia`,`laddle_former_dia`,`laddle_thickness`,`laddle_height`,`laddle_bottom_height`,`laddle_former_height`) 
                                           VALUES ('$market_profile_id','$plValuesAr[0]','$plValuesAr[1]','$plValuesAr[2]','$plValuesAr[3]','$plValuesAr[4]','$plValuesAr[5]','$plValuesAr[6]','$plValuesAr[7]','$plValuesAr[8]','$plValuesAr[9]','$plValuesAr[10]','$plValuesAr[11]','$plValuesAr[12]','$plValuesAr[13]','$plValuesAr[14]','$plValuesAr[15]','$plValuesAr[16]','$plValuesAr[17]')";
                mysqli_query($conn, $sqlInsertHLoan);
            }
        }
        //market_contact
        if($material_type == "contact_details") {
            $sqlUpdate3 = "DELETE FROM `market_contact` WHERE market_profile_id='$market_profile_id'";
            mysqli_query($conn, $sqlUpdate3);
            for ($cl = 0; $cl < $countCardLoans; $cl++) {
                $clValues = $cardLoans[$cl];
                $clValues = substr($clValues, 0, -1);
                $clValuesAr = explode("%", $clValues);


                $sqlInsertCLoan = "INSERT INTO `market_contact`(`market_profile_id`, `name`, `position`, `mobile`, `email`) 
                                           VALUES ('$market_profile_id','$clValuesAr[0]','$clValuesAr[1]','$clValuesAr[2]','$clValuesAr[3]')";
                mysqli_query($conn, $sqlInsertCLoan);

            }
        }
        //market_requirement
        if($material_type == "requirement_details") {
            $sqlUpdate3 = "DELETE FROM `market_requirement` WHERE market_profile_id='$market_profile_id'";
            mysqli_query($conn, $sqlUpdate3);
            for ($gl = 0; $gl < $countGoldLoans; $gl++) {
                $glValues = $goldLoans[$gl];
                $glValues = substr($glValues, 0, -1);
                $glValuesAr = explode("%", $glValues);

                $sqlInsertGLoan = "INSERT INTO `market_requirement`(`market_profile_id`, `product_name`, `category`, `supplier`, `qty`) 
                                           VALUES ('$market_profile_id','$glValuesAr[0]','$glValuesAr[1]','$glValuesAr[2]','$glValuesAr[3]')";

                mysqli_query($conn, $sqlInsertGLoan);

            }
        }
        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Market Company Edited");
        $role = urlencode($role); // Assuming $id is a variable with the emp_id value
        $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
        $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
        $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
        file_get_contents($url);

        //inserted successfully
        $json_array['status'] = "success";
        $json_array['msg'] = "Added successfully !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }


    else {
        //Parameters missing

        $json_array['status'] = "failure";
        $json_array['msg'] = "Invalid Login !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }
}
else
{
    //Parameters missing

    $json_array['status'] = "failure";
    $json_array['msg'] = "Please try after sometime !!!";
    $json_response = json_encode($json_array);
    echo $json_response;
}



function clean($data) {
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}



?>
