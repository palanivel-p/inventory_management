<?php
date_default_timezone_set("Asia/Kolkata");
if(isset($_POST['unit_name'])&&isset($_POST['unit_subname'])&& isset($_POST['base_unit'])) {
    Include("../../includes/connection.php");

    $unit_name = clean($_POST['unit_name']);
    $unit_subname = clean($_POST['unit_subname']);
    $base_unit = $_POST['base_unit'];
    $operator = $_POST['operator'];
    $operation_value = $_POST['operation_value'];
   
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

//        $sqlUnitName = "SELECT * FROM `unit` WHERE unit_name='$unit_name'";
        $sqlValidate = "SELECT * FROM `unit` WHERE unit_name='$unit_name' AND unit_subname ='$unit_subname' ";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) == 0) {

        $sqlInsert = "INSERT INTO `unit`(`unit_id`, `unit_name`,`unit_subname`,`base_unit`,`operator`,`operation_value`) 
                                            VALUES ('','$unit_name','$unit_subname','$base_unit','$operator','$operation_value')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $unit_id="U".($ID);

        $sqlUpdate = "UPDATE unit SET unit_id = '$unit_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        //log
//        $info = urlencode("Added Game - " . $game_id);
//        file_get_contents($website . "portal/includes/log.php?api_key=$api_key&info=$info");

            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Unit Added");
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
        $json_array['msg'] = "Unit Name Already Registered !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }
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
