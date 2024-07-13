<?php
    Include("../../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['user_name'])&&isset($_POST['password'])) {


    $first_name = clean($_POST['first_name']);
    $last_name = clean($_POST['last_name']);
    $user_name = clean($_POST['user_name']);
    $email = clean($_POST['email']);
    $password = clean($_POST['password']);
    $Phone = clean($_POST['Phone']);
    $role = $_POST['role'];
    $access_status = $_POST['active_status'];
    $field_status = $_POST['field_status'];
     $leave = $_POST['leave'];
        $gross = $_POST['gross'];
        $deduction = $_POST['deduction'];
    $net=$gross-$deduction;
//    $warehouse = $_POST['warehouse'];

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


        $sqlInsert = "INSERT INTO `user`(`user_id`, `f_name`,`l_name`,`username`,`phone`,`email`,`password`,`role`,`access_status`,`gross_salary`,`net_salary`,`deduction`,`eligible_leave`,`field_app`)
                                            VALUES ('','$first_name','$last_name','$user_name','$Phone','$email','$password','$role','$access_status','$gross','$net','$deduction','$leave','$field_status')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $user_id="US".($ID);

        $sqlUpdate = "UPDATE user SET user_id = '$user_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        $uploadDir = 'user_img/';
        $new_image_name = $user_id.'.jpg';
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

                $allowTypes = array('jpg','jpeg','png');
                if (in_array($fileType, $allowTypes)) {

                    if (!move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFilePath)) {


                        //not uploaded
                        $json_array['status'] = "success";
                        $json_array['msg'] = "Added Successfully, but Image not uploaded!!!";
                        $json_response = json_encode($json_array);
                        echo $json_response;
                    }
                    else{
                        $sqlUpdates = "UPDATE user SET img =1 WHERE user_id ='$user_id'";
                        mysqli_query($conn, $sqlUpdates);

                        $uploadDir = 'user_doc/';
                        $new_pdf_name=$user_id.'1.pdf';

                        if (!empty($_FILES["upload_doc1"]["tmp_name"])) {
                            if(($_FILES['upload_doc1']['size']) <= $maxSize) {
                                $targetFilePath = $uploadDir . $new_pdf_name;
                                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                                $allowTypes = array('pdf'); // Allow only PDF files
                                if (in_array($fileType, $allowTypes)) {
                                    if (!move_uploaded_file($_FILES["upload_doc1"]["tmp_name"], $targetFilePath)) {
                                        // File not uploaded
                                        $json_array['status'] = "error";
                                        $json_array['msg'] = "PDF file upload failed!";
                                    } else {
                                        // File uploaded successfully
                                        $sqlUpdates = "UPDATE user SET doc1 =1 WHERE user_id ='$user_id'";
                                        mysqli_query($conn, $sqlUpdates);

                                        $json_array['status'] = "success";
                                        $json_array['msg'] = "PDF file uploaded successfully!";
                                    }
                                }
                            }
                        }


                        $new_pdf_name=$user_id.'2.pdf';

                        if (!empty($_FILES["upload_doc2"]["tmp_name"])) {
                            if(($_FILES['upload_doc2']['size']) <= $maxSize) {
                                $targetFilePath = $uploadDir . $new_pdf_name;
                                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                                $allowTypes = array('pdf'); // Allow only PDF files
                                if (in_array($fileType, $allowTypes)) {
                                    if (!move_uploaded_file($_FILES["upload_doc2"]["tmp_name"], $targetFilePath)) {
                                        // File not uploaded
                                        $json_array['status'] = "error";
                                        $json_array['msg'] = "PDF file upload failed!";
                                    } else {
                                        // File uploaded successfully
                                        $sqlUpdates = "UPDATE user SET doc2 =1 WHERE user_id ='$user_id'";
                                        mysqli_query($conn, $sqlUpdates);

                                        $json_array['status'] = "success";
                                        $json_array['msg'] = "PDF file uploaded successfully!";
                                    }
                                }
                            }
                        }


                        $new_pdf_name=$user_id.'3.pdf';

                        if (!empty($_FILES["upload_doc3"]["tmp_name"])) {
                            if(($_FILES['upload_doc3']['size']) <= $maxSize) {
                                $targetFilePath = $uploadDir . $new_pdf_name;
                                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                                $allowTypes = array('pdf'); // Allow only PDF files
                                if (in_array($fileType, $allowTypes)) {
                                    if (!move_uploaded_file($_FILES["upload_doc3"]["tmp_name"], $targetFilePath)) {
                                        // File not uploaded
                                        $json_array['status'] = "error";
                                        $json_array['msg'] = "PDF file upload failed!";
                                    } else {
                                        // File uploaded successfully
                                        $sqlUpdates = "UPDATE user SET doc3 =1 WHERE user_id ='$user_id'";
                                        mysqli_query($conn, $sqlUpdates);

                                        $json_array['status'] = "success";
                                        $json_array['msg'] = "PDF file uploaded successfully!";
                                    }
                                }
                            }
                        }



                        $new_pdf_name=$user_id.'4.pdf';

                        if (!empty($_FILES["upload_doc4"]["tmp_name"])) {
                            if(($_FILES['upload_doc4']['size']) <= $maxSize) {
                                $targetFilePath = $uploadDir . $new_pdf_name;
                                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                                $allowTypes = array('pdf'); // Allow only PDF files
                                if (in_array($fileType, $allowTypes)) {
                                    if (!move_uploaded_file($_FILES["upload_doc4"]["tmp_name"], $targetFilePath)) {
                                        // File not uploaded
                                        $json_array['status'] = "error";
                                        $json_array['msg'] = "PDF file upload failed!";
                                    } else {
                                        // File uploaded successfully
                                        $sqlUpdates = "UPDATE user SET doc4 =1 WHERE user_id ='$user_id'";
                                        mysqli_query($conn, $sqlUpdates);

                                        $json_array['status'] = "success";
                                        $json_array['msg'] = "PDF file uploaded successfully!";
                                    }
                                }
                            }
                        }



                        $new_pdf_name=$user_id.'5.pdf';

                        if (!empty($_FILES["upload_doc5"]["tmp_name"])) {
                            if(($_FILES['upload_doc5']['size']) <= $maxSize) {
                                $targetFilePath = $uploadDir . $new_pdf_name;
                                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                                $allowTypes = array('pdf'); // Allow only PDF files
                                if (in_array($fileType, $allowTypes)) {
                                    if (!move_uploaded_file($_FILES["upload_doc5"]["tmp_name"], $targetFilePath)) {
                                        // File not uploaded
                                        $json_array['status'] = "error";
                                        $json_array['msg'] = "PDF file upload failed!";
                                    } else {
                                        // File uploaded successfully
                                        $sqlUpdates = "UPDATE user SET doc5 =1 WHERE user_id ='$user_id'";
                                        mysqli_query($conn, $sqlUpdates);

                                        $json_array['status'] = "success";
                                        $json_array['msg'] = "PDF file uploaded successfully!";
                                    }
                                }
                            }
                        }


                        $role=$_COOKIE['role'];
                        $staff_id=$_COOKIE['user_id'];
                        $staff_name=$_COOKIE['user_name'];
                        $info = urlencode("User Added");
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