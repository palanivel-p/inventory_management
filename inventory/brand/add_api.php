<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['brand_name'])) {
    Include("../../includes/connection.php");

    $brand_name = clean($_POST['brand_name']);
    $description = clean($_POST['description']);
   
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

        $sqlInsert = "INSERT INTO `brand`(`brand_id`, `brand_name`,`description`) 
                                            VALUES ('','$brand_name','$description')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $brand_id="B".($ID);

        $sqlUpdate = "UPDATE brand SET brand_id = '$brand_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        //log
        $uploadDir = 'brand_img/';
        $new_image_name = $brand_id.'.jpg';
//        $uploadDirPdf = '../../placements/pdf/';
//        $new_image_name_pdf = $career_id.'.pdf';

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
                $json_array['msg'] = "Added Successfully, but Image not uploaded!!!!!";
                $json_response = json_encode($json_array);
                echo $json_response;
            }
            else{
                $sqlUpdates = "UPDATE brand SET img =1 WHERE brand_id ='$brand_id'";
                mysqli_query($conn, $sqlUpdates);

                // $emp_id = $_COOKIE['staff_id'];
                // $emp_role = $_COOKIE['role'];
//                        $info = urlencode("Added Gallery" );
//                        file_get_contents($website . "portal/includes/log.php?emp_id=$emp_role&info=$info");

                $role=$_COOKIE['role'];
                $staff_id=$_COOKIE['user_id'];
                $staff_name=$_COOKIE['user_name'];
                $info = urlencode("Brand Added");
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

        }
        else {
            //allow type
            $json_array['status'] = "success";
            $json_array['msg'] = "Added Successfully, but change Image type not uploaded!!!";
            $json_response = json_encode($json_array);
            echo $json_response;
        }

    }
    else {
        // max size
        $json_array['status'] = "success";
        $json_array['msg'] = "Added Successfully, but change Image size not uploaded!!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }

}
else {
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