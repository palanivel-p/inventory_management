<?php

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['followup_id']))
{
    Include("../../../includes/connection.php");

    $followup_id = $_POST['followup_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $follow_date = clean($_POST['date_time']);
    $hours = $_POST['start_from_hr'];
    $from_min = $_POST['start_from_min'];
    $from_sec = $_POST['start_from_sec'];
    $from_am_pm = $_POST['start_from_am'];
    $time_from = sprintf("%02d:%02d:%02d", $hours, $from_min, $from_sec);
    $name = clean($_POST['sup_name']);
    $spoken = clean($_POST['spoken']);
    $mobile = clean($_POST['mobile']);
    $discussed_About = clean($_POST['about']);
    $committed_value = clean($_POST['value']);
    $next_follow = clean($_POST['follow_date']);

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

        $sqlValidate = "SELECT * FROM `follow_up` WHERE followup_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($followup_id==$old_pa_id))  {


            $sqlUpdate = "UPDATE `follow_up` SET `followup_id`='$followup_id',`follow_date`='$follow_date',`follow_time`='$time_from' ,`am_pm`='$from_am_pm',`name`='$name' ,`spoken`='$spoken',`mobile`='$mobile',`discussed_About`='$discussed_About' ,`committed_value`='$committed_value',`next_follow`='$next_follow' WHERE `followup_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);

            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Acoounts Planner Customer Edited");
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
            $json_array['msg'] = "Follow ID Is Not Valid";
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
