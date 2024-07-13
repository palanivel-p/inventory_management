<?php
if(isset($_POST['followup_id']))
{
    Include("../../../includes/connection.php");


    $followup_id = $_POST['followup_id'];
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
        $sqlData="SELECT * FROM `follow_up` WHERE followup_id='$followup_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            $time = $row['follow_time'];

            $time_parts = explode(':', $time);

            $hours = $time_parts[0];

            $minutes = $time_parts[1];

            $seconds = $time_parts[2];


            $json_array['status'] = 'success';

            $json_array['followup_id'] = $row['followup_id'];

            $json_array['follow_date'] = $row['follow_date'];

            $json_array['hours'] =$hours;

            $json_array['minutes'] =$minutes;

            $json_array['seconds'] =$seconds;

            $json_array['am_pm'] = $row['am_pm'];

            $json_array['name'] = $row['name'];

            $json_array['spoken'] = $row['spoken'];

            $json_array['mobile'] = $row['mobile'];

            $json_array['discussed_About'] = $row['discussed_About'];

            $json_array['committed_value'] = $row['committed_value'];

            $json_array['next_follow'] = $row['next_follow'];

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
