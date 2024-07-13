<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['visit_date'])&& isset($_POST['customer_name'])) {
    Include("../includes/connection.php");

    $visit_date = clean($_POST['visit_date']);
    $customer_name = clean($_POST['customer_name']);
    $meet = $_POST['meet'];
    $sample = $_POST['sample'];
    $discuss_about = $_POST['discuss_about'];
    $material_name = $_POST['material_name'];
    $qty = $_POST['qty'];
    $progress = $_POST['progress'];
    $commitment_value = $_POST['commitment_value'];
    $commitment_qty = $_POST['commitment_qty'];
    $next_follow = $_POST['next_follow'];

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

        $sqlInsert = "INSERT INTO `market`(`market_id`, `visit_date`,`customer_name`,`meet_whom`,`sample_given`,`discuss_about`,`material_name`,`qty`,`progress`,`commitment_qty`,`commitment_value`,`next_follow`,`added_by`,`created`) 
                                            VALUES ('','$visit_date','$customer_name','$meet','$sample','$discuss_about','$material_name','$qty','$progress','$commitment_qty','$commitment_value','$next_follow','$added_by','$added_by')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $market_id="M".($ID);

        $sqlUpdate = "UPDATE market SET market_id = '$market_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);

        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Market Added");
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
