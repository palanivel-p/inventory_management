<?php
if(isset($_POST['purchase_id']))
{
    Include("../includes/connection.php");


    $purchase_id = $_POST['purchase_id'];
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
        $sqlData="SELECT * FROM `purchase` WHERE purchase_id='$purchase_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);

            $json_array['status'] = 'success';
            $json_array['purchase_id'] = $row['purchase_id'];
            $json_array['product_name'] = $row['product_name'];
            $json_array['supplier'] = $row['supplier'];
            $json_array['purchase_date'] = $row['purchase_date'];
            $json_array['stock'] = $row['stock'];
            $json_array['unit_cost'] = $row['unit_cost'];
            $json_array['qty'] = $row['qty'];
            $json_array['sub_total'] = $row['sub_total'];
            $json_array['discount'] = $row['discount'];
            $json_array['order_tax'] = $row['order_tax'];
            $json_array['shipping'] = $row['shipping'];
            $json_array['grand_total'] = $row['grand_total'];
            $json_array['payment_status'] = $row['payment_status'];
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
