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
