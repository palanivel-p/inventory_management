<?php

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['supplier_id']))
{
    Include("../../includes/connection.php");

    $supplier_id = $_POST['supplier_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $supplier_name = $_POST['supplier_name'];
    $supplier_email = $_POST['supplier_email'];
    $supplier_phone = $_POST['supplier_phone'];
    $supplier_phone1 = $_POST['supplier_phone1'];
    $gstin = $_POST['gstin'];
    $gst = strtoupper($gstin);
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $access_status = $_POST['active_status'];
    $supply_place = $_POST['supply_place'];
    $bank_name = $_POST['bank_name'];
    $account_name = $_POST['acc_name'];
    $account_no = $_POST['acc_no'];
    $ifsc_code = $_POST['ifsc'];
    $branch_name = $_POST['branch_name'];
    $country = $_POST['country'];
    $other_state = $_POST['other_state'];
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

        $sqlValidate = "SELECT * FROM `supplier` WHERE supplier_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($supplier_id==$old_pa_id))  {


            $sqlUpdate = "UPDATE `supplier` SET `supplier_id`='$supplier_id',`supplier_name`='$supplier_name',`supplier_email`='$supplier_email',`supplier_phone`='$supplier_phone',`supplier_phone1`='$supplier_phone1',`address1`='$address1',`address2`='$address2',`gstin`='$gst',`same_address`='$access_status',`bank_name`='$bank_name',`account_name`='$account_name',`account_no`='$account_no',`ifsc_code`='$ifsc_code',`branch_name`='$branch_name',`supply_place`='$supply_place',`country`='$country',`other_state`='$other_state' WHERE `supplier_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);

            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Supplier Edited");
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
            $json_array['msg'] = "Supplier ID Is Not Valid";
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
