<?php
Include("../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['intend_date'])) {
    $product_name = clean($_POST['product_name']);
    $supplier = clean($_POST['supplier']);
    $parts = explode('_', $supplier);
    $supplier_id = $parts[0];
    $supply_place = $parts[1];

    $intend_date = clean($_POST['intend_date']);
    $discount = $_POST['discount'];
    $totaltax = $_POST['tax'];
    $grand_total = $_POST['grand_total'];
    $notes = $_POST['notes'];

    $add_id = $_POST['add_id'];
    $api_key = $_COOKIE['panel_api_key'];
    $added_by = $_COOKIE['user_id'];
//    $added_by = 'test';

    $added_by = $_COOKIE['user_id'];
    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
    if (mysqli_num_rows($resValidateCookie) > 0) {


        $sqlInsert = "INSERT INTO `intend`(`intend_id`,`intend_date`,`discount`,`grand_total`,`notes`,`total_tax`,`added_by`) 
                                            VALUES ('','$intend_date','$discount','$grand_total','$notes','$totaltax','$added_by')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $intend_id="I".($ID);
        $invoice_no="AECI".($ID);

        $sqlUpdate = "UPDATE intend SET intend_id = '$intend_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);



        $tableData = json_decode($_POST['tableData'], true);

        // Loop through the data and insert into the database
        foreach ($tableData as $rowData) {
            $productId = $conn->real_escape_string($rowData['productId']);
            $productCode = $conn->real_escape_string($rowData['productCode']);
            $productName = $conn->real_escape_string($rowData['productName']);
//            $productDesc = $conn->real_escape_string($rowData['productDesc']);
            $netUnitCost = $conn->real_escape_string($rowData['netUnitCost']);
            $unit = $conn->real_escape_string($rowData['unit']);
            $stockUnit = $conn->real_escape_string($rowData['stockUnit']);
            $stockValue = $conn->real_escape_string($rowData['stockValue']);
            $persymbl = $conn->real_escape_string($rowData['persymbl']);
            $discount = $conn->real_escape_string($rowData['discount']);
            $reasonType = $conn->real_escape_string($rowData['reasonType']);

//            $stock = $conn->real_escape_string($rowData['stock']);
//            $stk = explode('-', $stock);
//            $stock_value = $stk[0];
//            $stock_unit = $stk[1];

//            $unit_symbol = explode('>', $stock_unit);
//            $unit_sym = $unit_symbol[0];
//            $unit_bol = $unit_symbol[1];

            $quantity = $conn->real_escape_string($rowData['quantity']);
//            $quantity_r=  str_replace("+","",$quantity);
//            $quantity_r=  str_replace("-","",$quantity_r);

            $sqlProduct = "SELECT * FROM `product` WHERE `product_id`='$productId'";
            $resProduct = mysqli_query($conn, $sqlProduct);
            $rowProduct = mysqli_fetch_assoc($resProduct);
            $stock_qty =  $rowProduct['stock_qty'];

            if($unit === 'MT'){
                $total_stock = (int)$quantity * 1000;
                $stock_add = $stock_qty + (int)$total_stock;
//                $stock_add = $stock_value + $total_stock;
            }
            else{
                $total_stock = (int)$quantity * 1;
                $stock_add = $stock_qty + (int)$total_stock;
//                $stock_add = (int)$quantity_r + $total_stock;
            }

//            $discount = $conn->real_escape_string($rowData['discount']);
//            $discount_type = (strpos($discount, '%') !== false) ? 1 : 2;
//            $discount = str_replace('%', '', $discount);
            if($persymbl == '%'){
                $symbl = 1;
            }
            else if($persymbl == '₹'){
                $symbl = 2;
            }
            $discount_value = $conn->real_escape_string($rowData['discount_value']);
            $tax = $conn->real_escape_string($rowData['tax']);
            $tax_value = $conn->real_escape_string($rowData['tax_value']);
            $subtotal = $conn->real_escape_string($rowData['subtotal']);

            // Assuming you have a table named 'products'
            $sqlInsersts = "INSERT INTO intend_details (intend_details_id,product_id,product_code,product_name,unit,unit_cost, stock,Stock_count ,discount,dis_symbl, tax, sub_total,qty,tax_value,discount_value,discount_type,reasonType) 
                    VALUES ('','$productId','$productCode','$productName','$unit', '$netUnitCost', '$stockValue','$stock_add', '$discount','$persymbl', '$tax', '$subtotal','$quantity','$tax_value','$discount_value','$symbl','$reasonType')";

            mysqli_query($conn, $sqlInsersts);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $intend_details_id = "ID" . ($ID);
            $sqlUpdate = "UPDATE intend_details SET intend_details_id = '$intend_details_id',intend_id='$intend_id',intend_date='$intend_date' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdate);

        }
        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Intend Added");
        $role = urlencode($role); // Assuming $id is a variable with the emp_id value
        $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
        $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
        $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
        file_get_contents($url);


        $json_array['status'] = "success";
        $json_array['msg'] = " Intend Raised successfully !!!";
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
