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

//            $sqlValidate = "SELECT * FROM `product` WHERE product_name LIKE '%$product_id%' OR product_varient LIKE '%$product_id%'";
//        $sqlValidate = "SELECT DISTINCT product_name, product_code,product_varient, product_id, product_price, stock_qty, order_tax, product_unit,brand_type FROM `product` WHERE product_name LIKE '%$product_id%' OR product_code LIKE '%$product_id%' AND stock_qty != 0";
        $sqlValidate = "SELECT DISTINCT product_name, product_code, product_varient, product_id, product_price, stock_qty, order_tax, product_unit, brand_type FROM `product` WHERE (product_name LIKE '%$product_id%' OR product_code LIKE '%$product_id%')";

        $resValidate = mysqli_query($conn, $sqlValidate);

        if (mysqli_num_rows($resValidate) > 0) {

            $product_idArr = array();
            $product_codeArr = array();
            $product_nameArr = array();
            $product_priceArr = array();
            $product_stockArr = array();
            $product_taxArr = array();
            $product_unitArr = array();
            $product_varientArr = array();
            $product_brandArr = array();

            while ($row = mysqli_fetch_assoc($resValidate)) {


                $productId= $row['product_id'];
                $brand_id = $row['brand_type'];
                $sqlBrand = "SELECT * FROM `brand` WHERE `brand_id`='$brand_id'";
                $resBrand = mysqli_query($conn, $sqlBrand);
                $rowBrand = mysqli_fetch_assoc($resBrand);
                $brand_name =  $rowBrand['brand_name'];

                $sqlVariantName = "SELECT DISTINCT varient_name FROM `product_varient` WHERE product_id='$productId'";
                $resVariantName = mysqli_query($conn, $sqlVariantName);

                if (mysqli_num_rows($resVariantName) > 0) {
                    while ($rowVariantName = mysqli_fetch_assoc($resVariantName)) {

                        $varient_name = $rowVariantName['varient_name'];

                        $product_idArr[] =  $row['product_id'];
                        $product_codeArr[] =  $row['product_code'];
//                        $product_nameArr[] =  $row['product_name'].'/'.$row['product_code'].'/'.$brand_name;
//                        $product_nameArr[] =  $row['product_name'].'/'.$varient_name;
                        $product_nameArr[] =  $row['product_name'];
                        $product_priceArr[] =  $row['product_price'];
                        $product_stockArr[] =  $row['stock_qty'];
                        $product_taxArr[] =  $row['order_tax'];
                        $product_unitArr[] =  $row['product_unit'];
                        $product_varientArr[] =  $varient_name;
                        $product_brandArr[] =  $brand_name;
                    }

                }else{
                    $product_idArr[] =  $row['product_id'];
                    $product_codeArr[] =  $row['product_code'];
                    $product_nameArr[] =  $row['product_name'];
                    $product_priceArr[] =  $row['product_price'];
                    $product_stockArr[] =  $row['stock_qty'];
                    $product_taxArr[] =  $row['order_tax'];
                    $product_unitArr[] =  $row['product_unit'];
                    $product_varientArr[] =  '';
                    $product_brandArr[] =  $brand_name;

                }






            }

            // Inserted successfully
            $json_array['status'] = "success";
            $json_array['product_id'] =  $product_idArr;
            $json_array['product_code'] =  $product_codeArr;
            $json_array['product_name'] =  $product_nameArr;
            $json_array['product_price'] =  $product_priceArr;
            $json_array['product_stock'] =  $product_stockArr;
            $json_array['product_tax'] =  $product_taxArr;
            $json_array['product_unit'] =  $product_unitArr;
            $json_array['product_varient'] =  $product_varientArr;
            $json_array['product_brand'] =  $product_brandArr;

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
