<?php
include("../../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

function clean($data) {
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}

if(isset($_POST['user_name']) && isset($_POST['password'])) {
    $staff_id = clean($_POST['staff_id']);
    $first_name = clean($_POST['first_name']);
    $last_name = clean($_POST['last_name']);
    $user_name = clean($_POST['user_name']);
    $email = clean($_POST['email']);
    $password = clean($_POST['password']);
    $Phone = clean($_POST['Phone']);
    $role = $_POST['role'];
    $access_status = $_POST['active_status'];
    $field_status = $_POST['field_active'];
    $leave = $_POST['leave'];
    $gross = $_POST['gross'];
    $deduction = $_POST['deduction'];
    $net = $gross - $deduction;

    $api_key = $_COOKIE['panel_api_key'];
    $added_by = $_COOKIE['user_id'];

    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    } else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }

    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);

    if (mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidateCookie = "SELECT * FROM `user` WHERE staff_id='$staff_id'";
        $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
        if (mysqli_num_rows($resValidateCookie) > 0) {

            $json_array['status'] = "failure";
            $json_array['msg'] = "Already Registered !!!";
            echo json_encode($json_array);
            exit;
        }

        $sqlInsert = "INSERT INTO `user`(`user_id`,`staff_id`, `f_name`,`l_name`,`username`,`phone`,`email`,`password`,`role`,`access_status`,`gross_salary`,`net_salary`,`deduction`,`eligible_leave`,`field_app`)
                                        VALUES ('','$staff_id','$first_name','$last_name','$user_name','$Phone','$email','$password','$role','$access_status','$gross','$net','$deduction','$leave','$field_status')";
        mysqli_query($conn, $sqlInsert);

        $ID = mysqli_insert_id($conn);

        if(strlen($ID) == 1) {
            $ID = '00'.$ID;
        } elseif(strlen($ID) == 2) {
            $ID = '0'.$ID;
        }

        $user_id = "US".$ID;

        $sqlUpdate = "UPDATE user SET user_id = '$user_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);


        if($field_status == 1){
            $url = $website . "/People/Users/field_app.php?email=$email&password=$password&app=1";
        }else{
            $url = $website . "/People/Users/field_app.php?email=$email&password=$password&app=0";
        }
        $content = file_get_contents($url);


        $uploadDir = 'user_img/';
        $new_image_name = $user_id.'.jpg';
        $maxSize = 1000000;

        if (!empty($_FILES["upload_image"]["tmp_name"])) {
            if ($_FILES['upload_image']['size'] <= $maxSize) {
                $targetFilePath = $uploadDir . $new_image_name;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                $allowTypes = array('jpg', 'jpeg', 'png');

                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFilePath)) {
                        $sqlUpdates = "UPDATE user SET img = 1 WHERE user_id ='$user_id'";
                        mysqli_query($conn, $sqlUpdates);
                    } else {
                        $json_array['status'] = "success";
                        $json_array['msg'] = "Added Successfully, but Image not uploaded!!!";
                        echo json_encode($json_array);
                        exit;
                    }
                } else {
                    $json_array['status'] = "success";
                    $json_array['msg'] = "Added Successfully, but change Image type not uploaded!!!";
                    echo json_encode($json_array);
                    exit;
                }
            } else {
                $json_array['status'] = "success";
                $json_array['msg'] = "Added Successfully, but change Image size not uploaded!!!";
                echo json_encode($json_array);
                exit;
            }
        }

        $uploadDir = 'user_doc/';

        for ($i = 1; $i <= 5; $i++) {
            $new_pdf_name = $user_id . $i . '.pdf';
            $fileInputName = "upload_doc" . $i;

            if (!empty($_FILES[$fileInputName]["tmp_name"])) {
                if ($_FILES[$fileInputName]['size'] <= $maxSize) {
                    $targetFilePath = $uploadDir . $new_pdf_name;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                    $allowTypes = array('pdf');

                    if (in_array($fileType, $allowTypes)) {
                        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFilePath)) {
                            $sqlUpdates = "UPDATE user SET doc" . $i . " = 1 WHERE user_id ='$user_id'";
                            mysqli_query($conn, $sqlUpdates);
                        } else {
                            $json_array['status'] = "error";
                            $json_array['msg'] = "PDF file upload failed!";
                            echo json_encode($json_array);
                            exit;
                        }
                    }
                }
            }
        }





//        if ($field_status == 1) {
////    use PHPMailer\PHPMailer\PHPMailer;
////    use PHPMailer\PHPMailer\Exception;
//
//            require '../includes/src/Exception.php';
//            require '../includes/src/PHPMailer.php';
//            require '../includes/src/SMTP.php';
//
//            $user_email = $_POST['email'];
//            $user_password = $_POST['password'];
//            $user_link = 'https://erp.aecindia.net/field_app/';
//            $subject = 'AEC LOGIN DETAILS';
//
//            $smtpUsername = "noreply@aecindia.net";
//            $smtpPassword = "Aec_indianet1";
//
//            $emailFrom = "noreply@aecindia.net";
//            $emailFromName = "AEC";
//
//            $emailTo = $user_email;
//            $emailToName = "AEC Staff";
//
//            $mail = new PHPMailer;
//            $mail->isSMTP();
//            $mail->Host = "smtp.hostinger.com";
//            $mail->Port = 465;
//            $mail->SMTPSecure = 'tls';
//            $mail->SMTPAuth = true;
//            $mail->Username = $smtpUsername;
//            $mail->Password = $smtpPassword;
//            $mail->setFrom($emailFrom, $emailFromName);
//            $mail->addAddress($email, $emailToName);
//            $mail->Subject = 'Staff Login Details From AEC';
//
//            $website = "https://erp.aecindia.net/";
//            $url = $website . "People/Users/mail.php?user_link=$user_link&email=$user_email&password=$user_password&subject=$subject";
//
//            $url = str_replace(" ", "%20", $url);
//            $mail->msgHTML(file_get_contents($url));
//
//            if (!$mail->send()) {
//                echo "Mailer Error: " . $mail->ErrorInfo;
//            } else {
//                // echo "Message sent!";
//            }
//        }

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
        echo json_encode($json_array);
        exit;
    } else {
        $json_array['status'] = "failure";
        $json_array['msg'] = "Invalid Login !!!";
        echo json_encode($json_array);
        exit;
    }
} else {
    $json_array['status'] = "failure";
    $json_array['msg'] = "Please try after sometime !!!";
    echo json_encode($json_array);
    exit;
}



?>
