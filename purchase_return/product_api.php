<?php
date_default_timezone_set("Asia/Kolkata");

if (isset($_POST['product_id'])) {
    Include("../includes/connection.php");
    $product_id = $_POST['product_id'];
    $api_key = $_COOKIE['panel_api_key'];
    $added_by = $_COOKIE['user_id'];
    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);

    if (mysqli_num_rows($resValidateCookie) > 0) {

            $sqlValidate = "SELECT * FROM `product` WHERE product_name LIKE '%$product_id%'";
        $resValidate = mysqli_query($conn, $sqlValidate);

        if (mysqli_num_rows($resValidate) > 0) {

            $product_idArr = array();
            $product_nameArr = array();
            $product_priceArr = array();
            $product_stockArr = array();
            $product_taxArr = array();
            $product_unitArr = array();

            while ($row = mysqli_fetch_assoc($resValidate)) {
                $product_idArr[] =  $row['product_id'];
                $product_nameArr[] =  $row['product_name'];
                $product_priceArr[] =  $row['product_cost'];
                $product_stockArr[] =  $row['stock_qty'];
                $product_taxArr[] =  $row['order_tax'];
                $product_unitArr[] =  $row['product_unit'];
            }

            // Inserted successfully
            $json_array['status'] = "success";
            $json_array['product_id'] =  $product_idArr;
            $json_array['product_name'] =  $product_nameArr;
            $json_array['product_price'] =  $product_priceArr;
            $json_array['product_stock'] =  $product_stockArr;
            $json_array['product_tax'] =  $product_taxArr;
            $json_array['product_unit'] =  $product_unitArr;

            $json_response = json_encode($json_array);
            echo $json_response;

        } else {
            // No products found
            $json_array['status'] = "failure";
            $json_array['msg'] = "No products found for the given ID";
            $json_response = json_encode($json_array);
            echo $json_response;
        }
    } else {
        // Invalid login
        $json_array['status'] = "failure";
        $json_array['msg'] = "Invalid Login !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }
} else {
    // Parameters missing
    $json_array['status'] = "failure";
    $json_array['msg'] = "Please try after sometime !!!";
    $json_response = json_encode($json_array);
    echo $json_response;
}
?>
