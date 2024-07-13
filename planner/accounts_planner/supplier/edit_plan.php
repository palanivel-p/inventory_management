<?php
Include("../../../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['invoice_no'])) {

    $invoice_no = $_POST['invoice_no'];
    $plan_date = clean($_POST['plan_date']);
    $customer = clean($_POST['customer']);

//    $add_id = $_POST['add_id'];

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

//        $sqlValidate = "SELECT * FROM `adjustment` WHERE adjustment_id='$old_pa_id'";
//        $resValidate = mysqli_query($conn, $sqlValidate);
//        if (mysqli_num_rows($resValidate) > 0 || ($adjustment_id == $old_pa_id)) {



        $sqlUpdate = "UPDATE `sale` SET `plan_date`='$plan_date' WHERE `invoice_no`='$invoice_no'";
        mysqli_query($conn, $sqlUpdate);

        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Accounts-planner Supplier Plan Edited");
        $role = urlencode($role); // Assuming $id is a variable with the emp_id value
        $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
        $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
        $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
        file_get_contents($url);

        $json_array['status'] = "success";
        $json_array['msg'] = "Updated successfully !!!";
        $json_response = json_encode($json_array);
        echo $json_response;

//        } else {
//
//            $json_array['status'] = "failure";
//            $json_array['msg'] = "Adjustment ID Is Not Valid";
//            $json_response = json_encode($json_array);
//            echo $json_response;
//        }

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
