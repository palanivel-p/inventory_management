<?php

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['market_id']))
{
    Include("../../../includes/connection.php");

    $market_id = $_POST['market_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $call_date = clean($_POST['call_date']);
    $customer_name = $_POST['customer_name'];
    $meet = $_POST['meet'];
    $mobile = $_POST['mobile'];
//    $assigned = $_POST['assigned'];
    $communication = clean($_POST['communication']);
    $notes = clean($_POST['notes']);
    $next_date = clean($_POST['next_date']);

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

        $sqlValidate = "SELECT * FROM `marketing` WHERE marketing_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($market_id==$old_pa_id))  {


            $sqlUpdate = "UPDATE `marketing` SET `marketing_id`='$market_id',`next_date`='$next_date',`visit_date`='$call_date',`customer_name`='$customer_name',`meet_person`='$meet',`mobile`='$mobile',`communication`='$communication',`notes` = '$notes' WHERE `marketing_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);


            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Market-Call Tracking Edited");
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
            $json_array['msg'] = "Market ID Is Not Valid";
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
