<?php
Include("../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

// if(isset($_POST['supplier'])&&isset($_POST['purchase_date'])) {




//    $added_by = $_COOKIE['user_id'];

$api_key = $_COOKIE['panel_api_key'];

$sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
$resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
if (mysqli_num_rows($resValidateCookie) > 0) {

    $purchaseId = "SELECT purchase_id FROM purchase ORDER BY id DESC LIMIT 1";
    $respurchaseId = mysqli_query($conn, $purchaseId);
    $rowpurchaseId = mysqli_fetch_assoc($respurchaseId);
    $purchaseId =  $rowpurchaseId['purchase_id'];


    
    $tableData = json_decode($_POST['tableData'], true);

    // Loop through the data and insert into the database
    foreach ($tableData as $rowData) {
        $productName = $conn->real_escape_string($rowData['productName']);
        $netUnitCost = $conn->real_escape_string($rowData['netUnitCost']);
        $stock = $conn->real_escape_string($rowData['stock']);
//        $quantity = $conn->real_escape_string($rowData['quantity']);
        $discount = $conn->real_escape_string($rowData['discount']);
        $tax = $conn->real_escape_string($rowData['tax']);
        $subtotal = $conn->real_escape_string($rowData['subtotal']);

        // Assuming you have a table named 'products'
        $sqlInsert = "INSERT INTO purchase_details (purchase_details_id,product_id,unit_cost, stock, discount, tax, sub_total) 
                    VALUES ('','$productName', '$netUnitCost', '$stock', '$discount', '$tax', '$subtotal')";


        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $purchase_details_id="PD".($ID);
        $sqlUpdate = "UPDATE purchase_details SET purchase_details_id = '$purchase_details_id',purchase_id='$purchaseId' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
    }
    $json_array['status'] = "success";
    $json_array['msg'] = "Added success";
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
// }
// else
// {
//     //Parameters missing

//     $json_array['status'] = "failure";
//     $json_array['msg'] = "Please try after sometime !!!";
//     $json_response = json_encode($json_array);
//     echo $json_response;
// }



function clean($data) {
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}



?>
