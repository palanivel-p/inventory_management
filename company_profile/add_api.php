<?php
Include("../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['gstin'])&&isset($_POST['address'])) {


    $company_name = clean($_POST['company_name']);
    $phone = clean($_POST['phone']);
    $email = clean($_POST['email']);
    $address = clean($_POST['address']);
    $state = clean($_POST['state']);
    $gstin = clean($_POST['gstin']);
    $bank_name = clean($_POST['bank_name']);
    $bank_name2 = clean($_POST['bank_name2']);
    $account_name = clean($_POST['acc_name']);
    $account_no = clean($_POST['acc_no']);
    $ifsc_code = clean($_POST['ifsc']);
    $branch_name = clean($_POST['branch_name']);

//    $warehouse = $_POST['warehouse'];

//    $added_by = $_COOKIE['user_id'];

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


        $sqlInsert = "INSERT INTO `company_profile`(`company_id`, `company_name`,`phone`,`email`,`address`,`state`,`gstin`,`bank_name`,`bank_name2`,`account_name`,`account_no`,`ifsc_code`,`branch_name`) 
                                            VALUES ('','$company_name','$phone','$email','$address','$state','$gstin','$bank_name','$bank_name2','$account_name','$account_no','$ifsc_code','$branch_name')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $company_id="C".($ID);

        $sqlUpdate = "UPDATE company_profile SET company_id = '$company_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        $uploadDir = 'company_logo/';
        $new_image_name = $company_id.'.jpg';
        $maxSize =1000000;
        $uploadedFile = '';
        //log
//        $info = urlencode("Added Game - " . $game_id);
//        file_get_contents($website . "portal/includes/log.php?api_key=$api_key&info=$info");

        //inserted successfully

        if (!empty($_FILES["upload_image"]["tmp_name"])) {


            if(($_FILES['upload_image']['size']) <= $maxSize) {

                $targetFilePath = $uploadDir . $new_image_name;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                $allowTypes = array('jpg','jpeg');
                if (in_array($fileType, $allowTypes)) {

                    if (!move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFilePath)) {

                        //not uploaded
                        $json_array['status'] = "success";
                        $json_array['msg'] = "Added Successfully, but Image not uploaded!!!";
                        $json_response = json_encode($json_array);
                        echo $json_response;
                    }
                    else{
                        $sqlUpdates = "UPDATE company_profile SET img =1 WHERE company_id ='$company_id'";
                        mysqli_query($conn, $sqlUpdates);

                        $role=$_COOKIE['role'];
                        $staff_id=$_COOKIE['user_id'];
                        $staff_name=$_COOKIE['user_name'];
                        $info = urlencode("Company Profile Added");
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

function isRandomInRange($mRandom) {
    if(($mRandom >=58 && $mRandom <= 64) ||
        (($mRandom >=91 && $mRandom <= 96))) {
        return 0;
    } else {
        return $mRandom;
    }
}

function findRandom() {
    $mRandom = rand(48, 122);
    return $mRandom;
}

?>