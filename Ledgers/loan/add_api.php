<?php

date_default_timezone_set("Asia/Kolkata");
// if(isset($_POST['borrower']) &&isset($_POST['loan_date'])) {
Include("../../includes/connection.php");

$loan_date = clean($_POST['loan_date']);
$borrower = clean($_POST['borrower']);
$amount = clean($_POST['amount']);
$tenure = clean($_POST['tenure']);
$reason = clean($_POST['reason']);
$ref_noss = clean($_POST['ref_noss']);
$ref_no_c = clean($_POST['ref_no_c']);
$bank_name = clean($_POST['bank_name']);
$repayment_mode = clean($_POST['repayment_mode']);

$date = date('Y-m-d');

    $added_by = $_COOKIE['user_id'];

$api_key = $_COOKIE['panel_api_key'];

//$sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
if ($_COOKIE['role'] == 'Super Admin'){
    $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
}
else {
    $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
}
$resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
if (mysqli_num_rows($resValidateCookie) > 0) {

    $sqlInsert = "INSERT INTO `loan`(`loan_id`,`loan_date`,`borrower`,`tenure`,`amount`,`reason`,`added_by`,`ref_no_c`,`ref_no`,`repayment_mode`,`bank_name`) 
                                            VALUES ('','$loan_date','$borrower','$tenure','$amount','$reason','$added_by','$ref_no_c','$ref_noss','$repayment_mode','$bank_name')";

    mysqli_query($conn, $sqlInsert);

    $ID=mysqli_insert_id($conn);

    if(strlen($ID)==1)
    {
        $ID='00'.$ID;

    }elseif(strlen($ID)==2)
    {
        $ID='0'.$ID;
    }

    $loan_id="L".($ID);

    $sqlUpdate = "UPDATE loan SET loan_id = '$loan_id' WHERE id ='$ID'";
    mysqli_query($conn, $sqlUpdate);
    //log
//        $info = urlencode("Added Game - " . $game_id);
//        file_get_contents($website . "portal/includes/log.php?api_key=$api_key&info=$info");

    $role=$_COOKIE['role'];
    $staff_id=$_COOKIE['user_id'];
    $staff_name=$_COOKIE['user_name'];
    $info = urlencode("Loan Added");
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
// }
// else
// {
//     //Parameters missing

//     $json_array['status'] = "failure";
//     $json_array['msg'] = "Please try after sometime !!!";
//     $json_response = json_encode($json_array);
//     echo $json_response;
// }



function clean($data) {
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}



?>
