<?php
date_default_timezone_set("Asia/Kolkata");

//if(isset($_POST['loan_id'])) {
    Include("../../includes/connection.php");
    $loan_id = $_POST['loan_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $borrower = $_POST['borrower'];
    $loan_date = $_POST['loan_date'];
    $tenure = $_POST['tenure'];
    $amount = $_POST['amount'];
    $reason = $_POST['reason'];
$ref_noss = clean($_POST['ref_noss']);
$ref_no_c = clean($_POST['ref_no_c']);
$bank_name = clean($_POST['bank_name']);
$repayment_mode = clean($_POST['repayment_mode']);
    $date = date('Y-m-d');

    $api_key = $_COOKIE['panel_api_key'];

$added_by = $_COOKIE['user_id'];
//    $sqlValidateCookie="SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
if ($_COOKIE['role'] == 'Super Admin'){
    $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
}
else {
    $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
}
    $resValidateCookie=mysqli_query($conn,$sqlValidateCookie);
    if(mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidate = "SELECT * FROM `loan` WHERE loan_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($loan_id==$old_pa_id))  {

            $sqlUpdate = "UPDATE `loan` SET `borrower`='$borrower',`amount`='$amount',`loan_date`='$loan_date',`tenure`='$tenure',`reason`='$reason' WHERE `loan_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);

            $sqlUpdateBank = "UPDATE `bank_details` SET `ref_id`='$loan_id',`bank_name`='$bank_name',`payment_date`='$loan_date',`pay_from`='Loan',`customer_name`='$borrower',`amount`='$amount',`ref_no`='$ref_noss',`type` = 'Debit' WHERE `ref_id`='$loan_id'";
            mysqli_query($conn, $sqlUpdateBank);

            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Loan Edited");
            $role = urlencode($role); // Assuming $id is a variable with the emp_id value
            $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
            $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
            $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
            file_get_contents($url);

            //inserted successfully

            $json_array['status'] = "success";
            $json_array['msg'] = "Updated successfully !!!";
            $json_response = json_encode($json_array);
            echo $json_response;


        } else {


            $json_array['status'] = "failure";
            $json_array['msg'] = "Loan ID Is Not Valid";
            $json_response = json_encode($json_array);
            echo $json_response;
        }
    }
    else
    {
        //Parameters missing

        $json_array['status'] = "failure";
        $json_array['msg'] = "Invalid Login !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }

//}
//else
//{
//    //Parameters missing
//
//    $json_array['status'] = "failure";
//    $json_array['msg'] = "Please try after sometime !!!";
//    $json_response = json_encode($json_array);
//    echo $json_response;
//}
function clean($data) {
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}
?>
