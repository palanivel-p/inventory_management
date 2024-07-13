<?php
if(isset($_POST['product_vari_id']))
{
    Include("../includes/connection.php");

    $product_vari_id = $_POST['product_vari_id'];
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
        $sqlData="SELECT * FROM `product_varient` WHERE product_vari_id='$product_vari_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            $json_array['status'] = 'success';
            $json_array['product_id'] = $row['product_id'];
            $json_array['product_vari_id'] = $row['product_vari_id'];
            $json_array['varient_name'] = $row['varient_name'];

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
