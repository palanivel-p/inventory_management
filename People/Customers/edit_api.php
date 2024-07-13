<?php

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['customer_id']))
{
    Include("../../includes/connection.php");

    $customer_id = $_POST['customer_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $customer_phone1 = $_POST['customer_phone1'];
    $gstin = $_POST['gstin'];
    $gst = strtoupper($gstin) ;
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $access_status = $_POST['active_status'];
    $supply_place = $_POST['supply_place'];

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

        $sqlValidate = "SELECT * FROM `customer` WHERE customer_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($customer_id==$old_pa_id))  {


            $sqlUpdate = "UPDATE `customer` SET `customer_name`='$customer_name',`customer_email`='$customer_email',`customer_phone`='$customer_phone',`gstin`='$gst',`address1`='$address1',`address2`='$address2',`customer_phone1`='$customer_phone1',`supply_place`='$supply_place',`same_address`='$access_status' WHERE `customer_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);


            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Customer Edited");
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
            $json_array['msg'] = "Customer ID Is Not Valid";
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

?>
