<?php

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['purchase_id']))
{
    Include("../includes/connection.php");

    $purchase_id = $_POST['purchase_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $product_name = $_POST['product_name'];
    $supplier = $_POST['supplier'];
    $purchase_date = $_POST['purchase_date'];
    $stock = $_POST['stock'];
    $unit_cost = $_POST['unit_cost'];
    $qty = $_POST['qty'];
    $sub_total = $_POST['sub_total'];
    $discount = $_POST['discount'];
    $order_tax = $_POST['order_tax'];
    $shipping = $_POST['shipping'];
    $grand_total = $_POST['grand_total'];
    $payment_status = $_POST['payment_status'];
    $notes = $_POST['notes'];

    $api_key = $_COOKIE['panel_api_key'];

    $sqlValidateCookie="SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    $resValidateCookie=mysqli_query($conn,$sqlValidateCookie);
    if(mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidate = "SELECT * FROM `purchase` WHERE purchase_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($purchase_id==$old_pa_id))  {


            $sqlUpdate = "UPDATE `purchase` SET `purchase_id`='$purchase_id',`product_name`='$product_name',`supplier`='$supplier',`purchase_date`='$purchase_date',
            `stock`='$stock',`unit_cost`='$unit_cost',`qty`='$qty',`sub_total`='$sub_total',`discount`='$discount',`order_tax`='$order_tax',`shipping`='$shipping',`grand_total`='$grand_total',`payment_status`='$payment_status',`notes`='$notes' WHERE `user_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);



            //inserted successfully

            $json_array['status'] = "success";
            $json_array['msg'] = "Updated successfully !!!";
            $json_response = json_encode($json_array);
            echo $json_response;


        } else {


            $json_array['status'] = "failure";
            $json_array['msg'] = "Purchase ID Is Not Valid";
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
