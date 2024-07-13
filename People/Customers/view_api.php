<?php
if(isset($_POST['customer_id']))
{
    Include("../../includes/connection.php");


    $customer_id = $_POST['customer_id'];
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
    if(mysqli_num_rows($resValidateCookie) > 0)
    {
        $sqlData="SELECT * FROM `customer` WHERE customer_id='$customer_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            $json_array['status'] = 'success';
            $json_array['customer_id'] = $row['customer_id'];
            $json_array['customer_name'] = $row['customer_name'];
            $json_array['customer_email'] = $row['customer_email'];
            $json_array['customer_phone'] = $row['customer_phone'];
            $json_array['customer_phone1'] = $row['customer_phone1'];
            $json_array['gstin'] = $row['gstin'];
            $json_array['address1'] = $row['address1'];
            $json_array['address2'] = $row['address2'];
            $json_array['supply_palce'] = $row['supply_palce'];
            $json_array['country'] = $row['country'];
            $json_array['other_state'] = $row['other_state'];
            $json_array['access_status'] = $row['same_address'];
            $json_array['bank_name'] = $row['bank_name'];
            $json_array['acc_name'] = $row['account_name'];
            $json_array['acc_no'] = $row['account_no'];
            $json_array['ifsc'] = $row['ifsc_code'];
            $json_array['branch_name'] = $row['branch_name'];
            $json_response = json_encode($json_array);
            echo $json_response;
        }


    }
    else
    {
        //staff id already exist

        $json_array['status'] = "wrong";
        $json_array['msg'] = "Login Invalid";
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
