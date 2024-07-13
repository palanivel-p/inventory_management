<?php
    Include("../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['supplier'])&&isset($_POST['purchase_date'])) {


    $product_name = clean($_POST['product_name']);
    $supplier = clean($_POST['supplier']);
    $parts = explode('_', $supplier);
    $supplier_id = $parts[0];
    $supply_place = $parts[1];

    $purchase_date = clean($_POST['purchase_date']);
    $currency = clean($_POST['currency']);
    $payment_terms = clean($_POST['payment_terms']);
    $discount = $_POST['discount'];
    $totaltax = $_POST['tax'];
    $grand_total = $_POST['grand_total'];
    $payment_status = $_POST['payment_status'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    $d_date = $_POST['d_date'];
    $material = $_POST['material'];
    $transport = $_POST['transport'];

    $add_id = $_POST['add_id'];

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


        $sqlInsert = "INSERT INTO `purchase`(`purchase_id`,`supplier`,`purchase_date`,`discount`,`grand_total`,`payment_status`,`notes`,`status`,`transport`,`material`,`payment_terms`,`currency_id`,`due_date`,`total_tax`,`added_by`) 
                                            VALUES ('','$supplier_id','$purchase_date','$discount','$grand_total','$payment_status','$notes','$status','$transport','$material','$payment_terms','$currency','$d_date','$totaltax','$added_by')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $purchase_id="PR".($ID);
            $invoice_no="AECP".($ID);

        $sqlUpdate = "UPDATE purchase SET purchase_id = '$purchase_id',invoice_no='$invoice_no' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);



        $tableData = json_decode($_POST['tableData'], true);

        // Loop through the data and insert into the database
        foreach ($tableData as $rowData) {
            $productId = $conn->real_escape_string($rowData['productId']);
            $productName = $conn->real_escape_string($rowData['productName']);
            $netUnitCost = $conn->real_escape_string($rowData['netUnitCost']);
            $unit = $conn->real_escape_string($rowData['unit']);

            $stock = $conn->real_escape_string($rowData['stock']);
            $stk = explode('-', $stock);
            $stock_value = $stk[0];
            $stock_unit = $stk[1];

//            $unit_symbol = explode('>', $stock_unit);
//            $unit_sym = $unit_symbol[0];
//            $unit_bol = $unit_symbol[1];

            $quantity = $conn->real_escape_string($rowData['quantity']);
            $quantity_r=  str_replace("+","",$quantity);
            $quantity_r=  str_replace("-","",$quantity_r);

//            if($unit_bol === 'MT'){
//                $total_stock = (int)$quantity_r * 1000;
//                $stock_add = $stock_value + $total_stock;
//            }
//            else{
//                $total_stock = $stock_value;
//                $stock_add = (int)$quantity_r + $total_stock;
//            }
            if($unit === 'MT'){
                $total_stock = (int)$quantity * 1000;
                $stock_add = $stock_value - (int)$total_stock;
//                $stock_add = $stock_value + $total_stock;
            }
            else{
                $total_stock = (int)$quantity * 1;
                $stock_add = $stock_value - (int)$total_stock;
//                $stock_add = (int)$quantity_r + $total_stock;
            }

            $discount = $conn->real_escape_string($rowData['discount']);
            $discount_type = (strpos($discount, '%') !== false) ? 1 : 2;
//            $discount = str_replace('%', '', $discount);

            $discount_value = $conn->real_escape_string($rowData['discount_value']);
            $tax = $conn->real_escape_string($rowData['tax']);
            $tax_value = $conn->real_escape_string($rowData['tax_value']);
            $subtotal = $conn->real_escape_string($rowData['subtotal']);

            // Assuming you have a table named 'products'
            $sqlInsersts = "INSERT INTO purchase_details (purchase_details_id,product_id,product_name,unit,unit_cost, stock,Stock_count ,discount, tax, sub_total,qty,tax_value,discount_value,discount_type) 
                    VALUES ('','$productId','$productName','$unit', '$netUnitCost', '$stock','$stock_add', '$discount', '$tax', '$subtotal','$quantity','$tax_value','$discount_value','$discount_type')";

            mysqli_query($conn, $sqlInsersts);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $purchase_details_id = "PD" . ($ID);
            $sqlUpdate = "UPDATE purchase_details SET purchase_details_id = '$purchase_details_id',purchase_id='$purchase_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdate);

            $sqlStock = "SELECT * FROM `product` WHERE `product_id`='$productId'";
            $resStock = mysqli_query($conn, $sqlStock);
            $rowStock = mysqli_fetch_assoc($resStock);
            $total_stock =  $rowStock['stock_qty'];

            $sqlStockUpdate = "UPDATE product SET stock_qty = '$stock_add' WHERE product_id ='$productId'";
            mysqli_query($conn, $sqlStockUpdate);
        }
        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Purchase-Return Added");
        $role = urlencode($role); // Assuming $id is a variable with the emp_id value
        $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
        $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
        $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
        file_get_contents($url);

       
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
