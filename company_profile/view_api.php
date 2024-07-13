<?php
if(isset($_POST['company_id']))
{
    Include("../includes/connection.php");

    $company_id = $_POST['company_id'];
    $api_key = $_COOKIE['panel_api_key'];

    $added_by = $_COOKIE['user_id'];
    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }

    $resValidateCookie=mysqli_query($conn,$sqlValidateCookie);
    if(mysqli_num_rows($resValidateCookie) > 0)
    {
        $sqlData="SELECT * FROM `company_profile` WHERE company_id='$company_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            $json_array['status'] = 'success';
            $json_array['company_id'] = $row['company_id'];
            $json_array['company_name'] = $row['company_name'];
            $json_array['state'] = $row['state'];
            $json_array['address'] = $row['address'];
            $json_array['gstin'] = $row['gstin'];
            $json_array['phone'] = $row['phone'];
            $json_array['email'] = $row['email'];
            $json_array['bank_name'] = $row['bank_name'];
            $json_array['bank_name2'] = $row['bank_name2'];
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
