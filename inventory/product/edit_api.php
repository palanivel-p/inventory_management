<?php
date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['product_id']))
{
    Include("../../includes/connection.php");
    $product_id = $_POST['product_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $product_name = $_POST['product_name'];
    $product_code = $_POST['product_code'];
    $sub_category = $_POST['sub_category'];
    $primary_category = $_POST['primary_category'];
    $brand_type = $_POST['brand_type'];
    $product_price = $_POST['product_price'];
    $product_cost = $_POST['product_cost'];
    $product_unit = $_POST['product_unit'];
    $sales_unit = $_POST['sales_unit'];
    $hsn_code = $_POST['hsn_code'];
    $description = $_POST['description'];
    $tax_type = $_POST['tax_type'];
    $order_tax = $_POST['order_tax'];
    $stock_alert = $_POST['stock_alert'];
    $purchase_unit = $_POST['purchase_unit'];
    $product_varient = $_POST['paroduct_varient'];

    // $date = date('Y-m-d');
    $api_key = $_COOKIE['panel_api_key'];

    $added_by = $_COOKIE['user_id'];
//    $sqlValidateCookie="SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }
    $resValidateCookie=mysqli_query($conn,$sqlValidateCookie);
    if(mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidate = "SELECT * FROM `product` WHERE product_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($product_id==$old_pa_id))  {

            $sqlUpdate = "UPDATE `product` SET `product_id`='$product_id',`product_name`='$product_name',`product_code`='$product_code',`sub_category`='$sub_category',`primary_category`='$primary_category',`brand_type`='$brand_type',`product_unit`='$product_unit',`sales_unit`='$sales_unit',`hsn_code`='$hsn_code',`description`='$description',`tax_type`='$tax_type',`order_tax`='$order_tax',`purchase_unit`='$purchase_unit',`stock_alert`='$stock_alert',`product_varient`='$product_varient',`request_cost`='$product_cost',`request_price`='$product_price',`edit_request`='1' WHERE `product_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);

            $uploadDir = 'product_img/';
            $new_image_name = $product_id.'.jpg';

//            $uploadDirPdf = '../../placements/pdf/';
//            $new_image_name_pdf = $team_id.'.pdf';

            $maxSize =1000000;
            $uploadedFile = '';
            if (!empty($_FILES["upload_image"]["tmp_name"])) {

                if(($_FILES['upload_image']['size']) <= $maxSize) {

                    $targetFilePath = $uploadDir . $new_image_name;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                    $allowTypes = array('jpg','jpeg');
                    if (in_array($fileType, $allowTypes)) {

                        if (!move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFilePath)) {

//not uploaded
                            $json_array['status'] = "success";
                            $json_array['msg'] = "Updated Successfully, but Image not uploaded!!!";
                            $json_response = json_encode($json_array);
                            echo $json_response;
                        }
                        else{
                            $sqlUpdates = "UPDATE product SET img =1 WHERE product_id ='$product_id'";
                            mysqli_query($conn, $sqlUpdates);

                            // $emp_id = $_COOKIE['staff_id'];
                            // $emp_role = $_COOKIE['role'];
//                            $info = urlencode("Edited Gallery" );
//                            file_get_contents($website . "portal/includes/log.php?emp_id=$emp_role&info=$info");

                            $role=$_COOKIE['role'];
                            $staff_id=$_COOKIE['user_id'];
                            $staff_name=$_COOKIE['user_name'];
                            $info = urlencode("Product Edited");
                            $role = urlencode($role); // Assuming $id is a variable with the emp_id value
                            $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
                            $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
                            $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
                            file_get_contents($url);

                            $json_array['status'] = "success";
                            $json_array['msg'] = "Updated Successfully!!!";
                            $json_response = json_encode($json_array);
                            echo $json_response;
                        }

                    }
                    else {
                        //allow type
                        $json_array['status'] = "success";
                        $json_array['msg'] = "Updated Successfully,change Image type not uploaded!!!";
                        $json_response = json_encode($json_array);
                        echo $json_response;
                    }

                }
                else {
                    // max size
                    $json_array['status'] = "success";
                    $json_array['msg'] = "Updated Successfully,change Image size not uploaded!!!";
                    $json_response = json_encode($json_array);
                    echo $json_response;
                }

            }
            else {
                //not upload
                $json_array['status'] = "success";
                $json_array['msg'] = "Updated Successfully, but Image not uploaded!!!";
                $json_response = json_encode($json_array);
                echo $json_response;
            }




        } else {


            $json_array['status'] = "failure";
            $json_array['msg'] = "Product ID Is Not Valid";
            $json_response = json_encode($json_array);
            echo $json_response;
        }
    }
    else
    {
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
