<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['call_date'])) {
    Include("../../../includes/connection.php");

    $call_date = clean($_POST['call_date']);
    $customer_name = $_POST['customer_name'];
    $meet = $_POST['meet'];
    $mobile = $_POST['mobile'];
//    $assigned = $_POST['assigned'];
    $communication = clean($_POST['communication']);
    $notes = clean($_POST['notes']);
    $next_date = clean($_POST['next_date']);



    $added_date = date('Y-m-d');

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

        $sqlInsert = "INSERT INTO `marketing`(`marketing_id`,`visit_date`,`next_date`,`customer_name`,`communication`,`notes`,`mobile`,`meet_person`,`type`,`added_by`,`added_date`) 
                                            VALUES ('','$call_date','$next_date','$customer_name','$communication','$notes','$mobile','$meet','call','$added_by','$added_date')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $market_id="MR".($ID);

        $sqlUpdate = "UPDATE marketing SET marketing_id = '$market_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);


        $sqlInsert = "INSERT INTO `call_track`(`call_track_id`,`customer_name`,`customer_id`,`call_date`,`notes`,`next_date`) 
                                            VALUES ('','$customer_name','$customer_name','$call_date','$notes','$next_date')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $call_track_id="CL".($ID);

        $sqlUpdate = "UPDATE call_track SET call_track_id = '$call_track_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        //log
//        $info = urlencode("Added Game - " . $game_id);
//        file_get_contents($website . "portal/includes/log.php?api_key=$api_key&info=$info");

        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Market call-track Added");
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
