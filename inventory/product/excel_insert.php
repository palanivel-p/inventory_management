<?php
date_default_timezone_set("Asia/Kolkata");
include("../../includes/connection.php");
$current_date = date('Y-m-d');

$file = $_FILES['file']['tmp_name'];
$handle = fopen($file, "r");
$c = 0;

$statusSuccess = 0;

// Skip the header row
fgetcsv($handle);

while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
    $c++;

    $product_name = $filesop[1];
    $product_code = $filesop[2];
    $primary_category = $filesop[3];
    $sub_category = $filesop[4];
    $brand_type = $filesop[5];
    $product_cost = $filesop[6];
    $product_price = $filesop[7];
    $stock_alert = $filesop[8];
    $order_tax = $filesop[9];
    $tax_type = $filesop[10];
    $description = $filesop[11];
    $hsn_code = $filesop[12];
    $unit = $filesop[13];

    $date = date('Y-m-d');

    $api_key = $_COOKIE['panel_api_key'];
    $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);

    if (mysqli_num_rows($resValidateCookie) > 0) {
        $sqlInsert = "INSERT INTO `product`(`product_id`,`product_name`,`product_code`,`primary_category`,`sub_category`,`brand_type`,`product_cost`,`product_price`,`stock_alert`,`order_tax`,`tax_type`,`description`,`hsn_code`,`product_unit`) 
                                        VALUES ('','$product_name','$product_code','$primary_category','$sub_category','$brand_type','$product_cost','$product_price','$stock_alert','$order_tax','$tax_type','$description','$hsn_code','$unit')";
        mysqli_query($conn, $sqlInsert);

        $ID = mysqli_insert_id($conn);

        if(strlen($ID) == 1) {
            $ID = '00' . $ID;
        } elseif(strlen($ID) == 2) {
            $ID = '0' . $ID;
        }

        $product_id = "P" . $ID;

        $sqlUpdate = "UPDATE product SET product_id = '$product_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        $statusSuccess = 1;
    }
}

$json_array = array();

if ($statusSuccess == 1) {
    $json_array['status'] = "success";
    $json_array['msg'] = "Added successfully !!!";
} elseif ($statusSuccess == 0) {
    $json_array['status'] = "failure";
    $json_array['msg'] = "No New Records Added !!!";
}

echo json_encode($json_array);
?>
