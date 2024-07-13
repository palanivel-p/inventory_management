<?php
    Include("../../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['supplier'])&&isset($_POST['purchase_date'])) {


    $product_name = clean($_POST['product_name']);
    $supplier = clean($_POST['supplier']);
    $purchase_date = clean($_POST['purchase_date']);
    $stock = clean($_POST['stock']);
    $unit_cost = clean($_POST['unit_cost']);
    $qty = clean($_POST['qty']);
    $sub_total = $_POST['sub_total'];
    $discount = $_POST['discount'];
    $order_tax = $_POST['order_tax'];
    $shipping = $_POST['shipping'];
    $grand_total = $_POST['grand_total'];
    $sub_total = $_POST['sub_total'];
    $payment_status = $_POST['payment_status'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    $amended_date = $_POST['amended_date'];
    $material = $_POST['material'];
    $transport = $_POST['transport'];

//    $added_by = $_COOKIE['user_id'];

    $api_key = $_COOKIE['panel_api_key'];

    $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
    if (mysqli_num_rows($resValidateCookie) > 0) {


        $sqlInsert = "INSERT INTO `purchase`(`purchase_id`,`supplier`,`purchase_date`,`discount`,`shipping`,`order_tax`,`grand_total`,`payment_status`,`notes`,`transport`,`material`,`amended_date`,`status`) 
                                            VALUES ('','$supplier','$purchase_date','$discount','$shipping','$order_tax','$grand_total','$payment_status','$notes','$transport','$material','$amended_date','$status')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $purchase_id="P".($ID);

        $sqlUpdate = "UPDATE purchase SET purchase_id = '$purchase_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
       
        $json_array['status'] = "success";
        $json_array['msg'] = " PO Added successfully !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }


    else {
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



function clean($data) {
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}



?>
