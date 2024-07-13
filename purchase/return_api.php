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
    $material = $_POST['material_date'];
    $transport = $_POST['transport'];
    $purchase_id = $_POST['purchase_id'];

    $add_id = $_POST['add_id'];
    $date = date('Y-m-d');
    $api_key = $_COOKIE['panel_api_key'];

    $added_by = $_COOKIE['user_id'];
//    $sqlValidateCookie="SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
    if (mysqli_num_rows($resValidateCookie) > 0) {

        $sqlPurchase = "SELECT * FROM `purchase` WHERE `purchase_id`='$purchase_id'";
        $resPurchase = mysqli_query($conn, $sqlPurchase);
        $rowPurchase = mysqli_fetch_assoc($resPurchase);
        $Purchase_total_tax =  $rowPurchase['total_tax'];
        $Purchase_grand_total =  $rowPurchase['grand_total'];

        //purchase return update
        $purchaseU_tt = $Purchase_total_tax - $totaltax;
        $purchaseU_gt = $Purchase_grand_total - $grand_total;

        $sqlUpdateP = "UPDATE purchase SET total_tax = '$purchaseU_tt',grand_total='$purchaseU_gt' WHERE purchase_id ='$purchase_id'";
        mysqli_query($conn, $sqlUpdateP);

        //return insert
        $sqlInsert = "INSERT INTO `purchase_return`(`return_id`,`purchase_id`,`supplier`,`purchase_date`,`return_date`,`discount`,`grand_total`,`payment_status`,`notes`,`status`,`transport`,`material_date`,`payment_terms`,`currency_id`,`due_date`,`total_tax`) 
                                            VALUES ('','$purchase_id','$supplier_id','$purchase_date','$date','$discount','$grand_total','$payment_status','$notes','$status','$transport','$material','$payment_terms','$currency','$d_date','$totaltax')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $return_id="R".($ID);
        $invoice_no="AECPR".($ID);

        $sqlUpdate = "UPDATE purchase_return SET return_id = '$return_id',invoice_no='$invoice_no' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);



        $tableData = json_decode($_POST['tableData'], true);

        // Loop through the data and insert into the database
        foreach ($tableData as $rowData) {
            $productId = $conn->real_escape_string($rowData['productId']);
            $productName = $conn->real_escape_string($rowData['productName']);
            $netUnitCost = $conn->real_escape_string($rowData['netUnitCost']);
            $unit = $conn->real_escape_string($rowData['unit']);
            $stockUnit = $conn->real_escape_string($rowData['stockUnit']);
            $stockValue = $conn->real_escape_string($rowData['stockValue']);
            $persymbl = $conn->real_escape_string($rowData['persymbl']);
            $discount = $conn->real_escape_string($rowData['discount']);
            $quantity = $conn->real_escape_string($rowData['quantity']);

            $sqlProduct = "SELECT * FROM `product` WHERE `product_id`='$productId'";
            $resProduct = mysqli_query($conn, $sqlProduct);
            $rowProduct = mysqli_fetch_assoc($resProduct);
            $stock_qty =  $rowProduct['stock_qty'];

//            if($unit === 'MT'){
//                $total_stock = (int)$quantity * 1000;
//                $stock_add = $stock_qty - (int)$total_stock;
////                $stock_add = $stock_value + $total_stock;
//            }
            if($unit === 'MT'){
                $total_stock = (int)$quantity * 1000;
                $stock_add = $stock_qty + (int)$total_stock;
//                $stock_add = $stock_value + $total_stock;
            }
            elseif ($unit === 'mm'){
                $total_stock = (int)$quantity * 12;
                $stock_add = $stock_qty + (int)$total_stock;
            }
            elseif ($unit === 'bgs'){
                $total_stock = (int)$quantity * 25;
                $stock_add = $stock_qty + (int)$total_stock;
            }
            else{
                $total_stock = (int)$quantity * 1;
                $stock_add = $stock_qty + (int)$total_stock;
//                $stock_add = (int)$quantity_r + $total_stock;
            }
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
            $sqlInsersts = "INSERT INTO purchase_return_details (purchase_return_details_id,product_id,product_name,unit,unit_cost, stock,Stock_count ,discount,dis_symbl, tax, sub_total,qty,tax_value,discount_value,discount_type,return_date) 
                    VALUES ('','$productId','$productName','$unit', '$netUnitCost', '$stockValue','$stock_add', '$discount','$persymbl', '$tax', '$subtotal','$quantity','$tax_value','$discount_value','$symbl','$date')";

            mysqli_query($conn, $sqlInsersts);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $purchase_return_details_id = "PRD" . ($ID);
            $sqlUpdates = "UPDATE purchase_return_details SET purchase_return_details_id = '$purchase_return_details_id',purchase_id='$purchase_id',return_id= '$return_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdates);

            $sqlStock = "SELECT * FROM `product` WHERE `product_id`='$productId'";
            $resStock = mysqli_query($conn, $sqlStock);
            $rowStock = mysqli_fetch_assoc($resStock);
            $total_stock =  $rowStock['stock_qty'];

            $sqlStockUpdate = "UPDATE product SET stock_qty = '$stock_add' WHERE product_id ='$productId'";
            mysqli_query($conn, $sqlStockUpdate);

            //update purchase details
            $sqlPurchaseD = "SELECT * FROM `purchase_details` WHERE `purchase_id`='$purchase_id' AND `product_id`= '$productId'";
            $resPurchaseD = mysqli_query($conn, $sqlPurchaseD);
            $rowPurchaseD = mysqli_fetch_assoc($resPurchaseD);
            $PurchaseD_qty =  $rowPurchaseD['qty'];
            $PurchaseD_discount_value =  $rowPurchaseD['discount_value'];
            $PurchaseD_tax_value =  $rowPurchaseD['tax_value'];
            $PurchaseD_sub_total =  $rowPurchaseD['sub_total'];
            $purchaseD_product_id =  $rowPurchaseD['product_id'];

            //Sub purchase to return
            $return_qty = $PurchaseD_qty - $quantity;
            $return_discount_value = $PurchaseD_discount_value - $discount_value;
            $return_tax_value = $PurchaseD_tax_value - $tax_value;
            $return_sub_total = $PurchaseD_sub_total - $subtotal;

            $sqlUpdateReturnD = "UPDATE purchase_details SET sub_total = '$return_sub_total',tax_value = '$return_tax_value',discount_value='$return_discount_value',qty='$return_qty' WHERE `purchase_id`='$purchase_id' AND `product_id`= '$productId'";
            mysqli_query($conn, $sqlUpdateReturnD);
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
        $json_array['msg'] = " Returns Added successfully !!!";
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
