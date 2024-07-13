<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['call_date'])) {
    Include("../../../includes/connection.php");

    $plan_date = clean($_POST['call_date']);
    $customer_name = $_POST['customer_name'];
    $meet = $_POST['meet'];
    $mobile = $_POST['mobile'];
//    $assigned = $_POST['assigned'];
    $communication = clean($_POST['communication']);
    $notes = clean($_POST['notes']);
    $next_date = clean($_POST['next_date']);



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

        $sqlInsert = "INSERT INTO `service`(`service_id`,`visit_date`,`next_follow`,`customer_name`,`communication`,`added_by`,`notes`,`mobile`,`meet_whom`,`type`) 
                                            VALUES ('','$plan_date','$next_date','$customer_name','$communication','$added_by','$notes','$mobile','$meet','call')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $service_id="SC".($ID);

        $sqlUpdate = "UPDATE service SET service_id = '$service_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);


        $sqlInsert = "INSERT INTO `call_service`(`call_service_id`,`customer_name`,`customer_id`,`call_date`,`notes`,`next_date`) 
                                            VALUES ('','$customer_name','$customer_name','$plan_date','$notes','$next_date')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $call_track_id="ST".($ID);

        $sqlUpdate = "UPDATE call_service SET call_service_id = '$call_track_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        //log
//        $info = urlencode("Added Game - " . $game_id);
//        file_get_contents($website . "portal/includes/log.php?api_key=$api_key&info=$info");

        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Service-Planner Call Tracking Added");
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
