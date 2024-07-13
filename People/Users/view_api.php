<?php
if(isset($_POST['user_id']))
{
    Include("../../includes/connection.php");


    $user_id = $_POST['user_id'];
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
        $sqlData="SELECT * FROM `user` WHERE user_id='$user_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            $json_array['status'] = 'success';
            $json_array['user_id'] = $row['user_id'];
            $json_array['staff_id'] = $row['staff_id'];
            $json_array['f_name'] = $row['f_name'];
            $json_array['l_name'] = $row['l_name'];
            $json_array['username'] = $row['username'];
            $json_array['phone'] = $row['phone'];
            $json_array['email'] = $row['email'];
            $json_array['password'] = $row['password'];
            $json_array['role'] = $row['role'];
            $json_array['leave'] = $row['eligible_leave'];
            $json_array['gross'] = $row['gross_salary'];
            $json_array['deduction'] = $row['deduction'];
            $json_array['net'] = $row['net_salary'];
//            $json_array['warehouse'] = $row['warehouse'];
            $json_array['access_status'] = $row['access_status'];
            $json_array['field_status'] = $row['field_app'];

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
