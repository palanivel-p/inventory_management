<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['product_name'])) {
    Include("../../includes/connection.php");

    $product_name = clean($_POST['product_name']);
    $product_code = clean($_POST['product_code']);
    $primary_category = clean($_POST['primary_category']);
    $sub_category = clean($_POST['sub_category']);
    $brand_type = clean($_POST['brand_type']);
    $product_cost = clean($_POST['product_cost']);
    $product_price = clean($_POST['product_price']);
    $product_unit = clean($_POST['product_unit']);
    $sales_unit = clean($_POST['sales_unit']);
    $purchase_unit = clean($_POST['purchase_unit']);
    $stock_alert = clean($_POST['stock_alert']);
    $order_tax = clean($_POST['order_tax']);
    $tax_type = clean($_POST['tax_type']);
    $description = clean($_POST['description']);
    $hsn_code = clean($_POST['hsn_code']);
    $stock_qty = clean($_POST['stock_qty']);
    $product_varient = clean($_POST['paroduct_varient']);

    // $date = date('Y-m-d');

//    $added_by = $_COOKIE['user_id'];

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

        $sqlValidateSchoolID = "SELECT * FROM `product` WHERE product_name='$product_name'";
        $resValidateSchoolID = mysqli_query($conn, $sqlValidateSchoolID);

        if (mysqli_num_rows($resValidateSchoolID) == 0) {
            $sqlProductCode = "SELECT * FROM `product` WHERE product_code='$product_code'";
            $resProductCode = mysqli_query($conn, $sqlProductCode);

            if (mysqli_num_rows($resProductCode) == 0) {
            $sqlInsert = "INSERT INTO `product`(`product_id`,`product_name`,`product_code`,`primary_category`,`sub_category`,`brand_type`,`product_cost`,`product_price`,`product_unit`,`sales_unit`,`purchase_unit`,`stock_alert`,`order_tax`,`tax_type`,`description`,`hsn_code`,`stock_qty`,`product_varient`,`request_cost`,`request_price`) 
                                            VALUES ('','$product_name','$product_code','$primary_category','$sub_category','$brand_type','$product_cost','$product_price','$product_unit','$sales_unit','$purchase_unit','$stock_alert','$order_tax','$tax_type','$description','$hsn_code','$stock_qty','$product_varient','$product_cost','$product_price')";

            mysqli_query($conn, $sqlInsert);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $product_id = "P" . ($ID);

            $sqlUpdate = "UPDATE product SET product_id = '$product_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdate);
            //log
            $uploadDir = 'product_img/';
            $new_image_name = $product_id . '.jpg';
//        $uploadDirPdf = '../../placements/pdf/';
//        $new_image_name_pdf = $career_id.'.pdf';

            $maxSize = 1000000;

            $uploadedFile = '';
            if (!empty($_FILES["upload_image"]["tmp_name"])) {

                if (($_FILES['upload_image']['size']) <= $maxSize) {

                    $targetFilePath = $uploadDir . $new_image_name;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                    $allowTypes = array('jpg', 'jpeg');
                    if (in_array($fileType, $allowTypes)) {

                        if (!move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFilePath)) {

//not uploaded
                            $json_array['status'] = "success";
                            $json_array['msg'] = "Added Successfully, but Image not uploaded!!!!!";
                            $json_response = json_encode($json_array);
                            echo $json_response;
                        } else {
                            $sqlUpdates = "UPDATE product SET img =1 WHERE product_id ='$product_id'";
                            mysqli_query($conn, $sqlUpdates);

                            // $emp_id = $_COOKIE['staff_id'];
                            // $emp_role = $_COOKIE['role'];
//                        $info = urlencode("Added Gallery" );
//                        file_get_contents($website . "portal/includes/log.php?emp_id=$emp_role&info=$info");

                            $role=$_COOKIE['role'];
                            $staff_id=$_COOKIE['user_id'];
                            $staff_name=$_COOKIE['user_name'];
                            $info = urlencode("Product Added");
                            $role = urlencode($role); // Assuming $id is a variable with the emp_id value
                            $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
                            $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
                            $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
                            file_get_contents($url);

                            $json_array['status'] = "success";
                            $json_array['msg'] = "Added Successfully !!!";
                            $json_response = json_encode($json_array);
                            echo $json_response;

                        }

                    } else {
                        //allow type
                        $json_array['status'] = "success";
                        $json_array['msg'] = "Added Successfully, but change Image type not uploaded!!!";
                        $json_response = json_encode($json_array);
                        echo $json_response;
                    }

                } else {
                    // max size
                    $json_array['status'] = "success";
                    $json_array['msg'] = "Added Successfully, but change Image size not uploaded!!!";
                    $json_response = json_encode($json_array);
                    echo $json_response;
                }

            } else {
                //not upload
                $json_array['status'] = "success";
                $json_array['msg'] = "Added Successfully, but Image not uploaded!!!";
                $json_response = json_encode($json_array);
                echo $json_response;
            }
        }
        else {
//Parameters missing

                $json_array['status'] = "failure";
                $json_array['msg'] = "Product Code Is Already Exist !!!";
                $json_response = json_encode($json_array);
                echo $json_response;
            }
}

    else {
//Parameters missing

        $json_array['status'] = "failure";
        $json_array['msg'] = "Product Name Is Already Exist !!!";
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
    $data= str_replace("'","",$data);
    $data= str_replace('"',"",$data);
return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}



?>