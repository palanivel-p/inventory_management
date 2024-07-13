<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['product_id'])) {
    Include("../includes/connection.php");

    $product_id = clean($_POST['product_id']);
    $product_varient_name = clean($_POST['product_varient_name']);


    // $date = date('Y-m-d');

//    $added_by = $_COOKIE['user_id'];

    $api_key = $_COOKIE['panel_api_key'];


    $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
    if (mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidateSchoolID = "SELECT * FROM `product_varient` WHERE varient_name='$product_varient_name' And product_id = '$product_id'";
        $resValidateSchoolID = mysqli_query($conn, $sqlValidateSchoolID);
        if (mysqli_num_rows($resValidateSchoolID) == 0) {

            $sqlInsert = "INSERT INTO `product_varient`(`product_vari_id`,`product_id`,`varient_name`) 
                                            VALUES ('','$product_id','$product_varient_name')";

            mysqli_query($conn, $sqlInsert);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $productV_id = "PV" . ($ID);

            $sqlUpdate = "UPDATE product_varient SET product_vari_id = '$productV_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdate);
            //log
            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Product Varient Added");
            $role = urlencode($role); // Assuming $id is a variable with the emp_id value
            $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
            $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
            $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
            file_get_contents($url);

            $json_array['status'] = "success";
            $json_array['msg'] = "Added Successfully!!!";
            $json_response = json_encode($json_array);
            echo $json_response;
        }
        else {
//Parameters missing

            $json_array['status'] = "failure";
            $json_array['msg'] = "Varient Name Is Already Exist !!!";
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