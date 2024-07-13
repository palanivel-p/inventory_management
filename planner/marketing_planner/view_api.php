<?php
if(isset($_POST['market_id']))
{
    Include("../../includes/connection.php");


    $market_id = $_POST['market_id'];
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
        $sqlData="SELECT * FROM `marketing` WHERE marketing_id='$market_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            if($row['added_by'] == ''){
                $user_name='Super Admin';
            }
            else{
                $assigned_to = $row['added_by'];
                $sqlCustomer = "SELECT * FROM `user` WHERE `user_id`='$assigned_to'";
                $resCustomer = mysqli_query($conn, $sqlCustomer);
                $rowCustomer = mysqli_fetch_assoc($resCustomer);
                $user_name =  $rowCustomer['f_name'];
            }

            $json_array['status'] = 'success';
            $json_array['market_id'] = $row['marketing_id'];
            $json_array['meet_person'] = $row['meet_person'];
            $json_array['customer_name'] = $row['customer_name'];
//            $json_array['added_by'] = $user_name;
            $json_array['notes'] = $row['notes'];
            $json_array['communication'] = $row['communication'];
            $json_array['mobile'] = $row['mobile'];

            $json_array['next_date'] = $row['next_date'];

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
