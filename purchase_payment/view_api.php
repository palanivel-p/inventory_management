<?php
if(isset($_POST['repayment_id']))
{
    Include("../includes/connection.php");


    $repayment_id = $_POST['repayment_id'];
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
        $sqlData="SELECT * FROM `purchase_payment` WHERE repayment_id='$repayment_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);
            $purchase_id= $row['purchase_id'];
            $sqlPurchase = "SELECT * FROM `purchase` WHERE `purchase_id`='$purchase_id'";
            $resPurchase = mysqli_query($conn, $sqlPurchase);
            $rowPurchase = mysqli_fetch_assoc($resPurchase);
            $Purchase =  $rowPurchase['category_name'];

            $json_array['status'] = 'success';
            $json_array['repayment_id'] = $row['repayment_id'];
            $json_array['purchase'] = $row['purchase_id'];
            $json_array['repayment_date'] = $row['repayment_date'];
            $json_array['bank_name'] = $row['bank_name'];
            $json_array['pay_made'] = $row['pay_made'];
            $json_array['repayment_mode'] = $row['repayment_mode'];
            $json_array['ref_no'] = $row['ref_no'];
            $json_array['ref_no_c'] = $row['ref_no_c'];
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
