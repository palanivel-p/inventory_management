<?php
Include("../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['sts_date'])&& isset($_POST['received_status'])) {


    $sts_date = clean($_POST['sts_date']);
    $received_status = clean($_POST['received_status']);
    $pro_sts = clean($_POST['pro_sts']);
    $prs_id = clean($_POST['prss_id']);
    $bill_no = clean($_POST['bl_no']);

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
        $sqlValidateAcc = "SELECT * FROM `status` WHERE `bill_no`='$bill_no'";
        $resValidateAcc= mysqli_query($conn, $sqlValidateAcc);
        if (mysqli_num_rows($resValidateAcc) == 0) {


        $tableData = json_decode($_POST['tableData'], true);

        // Loop through the data and insert into the database
        foreach ($tableData as $rowData) {

            $productName = $conn->real_escape_string($rowData['productName']);
            $productId = $conn->real_escape_string($rowData['productId']);
            $quantity = $conn->real_escape_string($rowData['quantity']);
            $receivedMaterial = $conn->real_escape_string($rowData['receivedMaterial']);
            $remaining = $conn->real_escape_string($rowData['remaining']);
            $batch = $conn->real_escape_string($rowData['batch']);

            // Assuming you have a table named 'products'
             $sqlInsersts = "INSERT INTO status (status_id,purchase_id,status_date,status_type,bill_no, product_id,product_name,quantity ,material, remaining,batch) 
                    VALUES ('','$prs_id','$sts_date', '$received_status','$bill_no','$productId', '$productName','$quantity', '$receivedMaterial', '$remaining','$batch')";

            mysqli_query($conn, $sqlInsersts);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $status_id = "ST" . ($ID);
             $sqlUpdate = "UPDATE status SET status_id = '$status_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdate);

            $sqlStock = "SELECT * FROM `product` WHERE `product_id`='$productId'";
            $resStock = mysqli_query($conn, $sqlStock);
            $rowStock = mysqli_fetch_assoc($resStock);
            $total_stock =  $rowStock['stock_qty'];
            $added_stock =  $total_stock + $receivedMaterial;

              $sqlStockUpdate = "UPDATE product SET stock_qty = '$added_stock' WHERE product_id ='$productId'";
            mysqli_query($conn, $sqlStockUpdate);
        }
        $role=$_COOKIE['role'];
        $staff_id=$_COOKIE['user_id'];
        $staff_name=$_COOKIE['user_name'];
        $info = urlencode("Purchase-Status Added");
        $role = urlencode($role); // Assuming $id is a variable with the emp_id value
        $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
        $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
        $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
        file_get_contents($url);


        $json_array['status'] = "success";
        $json_array['msg'] = " Status Added successfully !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }


    else {
        //Parameters missing

        $json_array['status'] = "failure";
        $json_array['msg'] = "Bill No Already Exist !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }
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
