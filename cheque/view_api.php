<?php
if(isset($_POST['cheque_id']))
{
    Include("../includes/connection.php");


    $cheque_id = $_POST['cheque_id'];
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
        $sqlData="SELECT * FROM `cheque` WHERE cheque_id='$cheque_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            $json_array['status'] = 'success';
            $json_array['cheque_id'] = $row['cheque_id'];
            $json_array['cheque_no'] = $row['cheque_no'];
            $json_array['bank_name'] = $row['bank_name'];
            $json_array['acc_no'] = $row['acc_no'];
            $json_array['from_number'] = $row['from_number'];
            $json_array['to_number'] = $row['to_number'];
            $json_array['notes'] = $row['notes'];

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
