<?php

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['user_id']))
{
    Include("../../includes/connection.php");
    $staff_id = clean($_POST['staff_id']);

    $user_id = $_POST['user_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $Phone = $_POST['Phone'];
    $role = $_POST['role'];
//    $warehouse = $_POST['warehouse'];
    $access_status = $_POST['active_status'];
    $field_status = $_POST['field_active'];

    $leave = $_POST['leave'];
    $gross = $_POST['gross'];
    $deduction = $_POST['deduction'];
    $net = $gross - $deduction;
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
    $resValidateCookie=mysqli_query($conn,$sqlValidateCookie);
    if(mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidate = "SELECT * FROM `user` WHERE user_id='$old_pa_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($user_id==$old_pa_id))  {


            $sqlUpdate = "UPDATE `user` SET `user_id`='$user_id',`staff_id`='$staff_id',`f_name`='$first_name',`l_name`='$last_name',`username`='$user_name',`email`='$email',`password`='$password',`phone`='$Phone',`role`='$role',`access_status`='$access_status',`eligible_leave`='$leave',`gross_salary`='$gross',`deduction`='$deduction',`net_salary`='$net',`field_app`='$field_status' WHERE `user_id`='$old_pa_id'";
            mysqli_query($conn, $sqlUpdate);


            $uploadDir = 'user_img/';
            $new_image_name = $user_id.'.jpg';

//            $uploadDirPdf = '../../placements/pdf/';
//            $new_image_name_pdf = $team_id.'.pdf';

            $maxSize =1000000;
            $uploadedFile = '';
            if (!empty($_FILES["upload_image"]["tmp_name"])) {
                // If a new image is provided, delete the old one first
                if (file_exists($uploadDir . $new_image_name)) {
                    unlink($uploadDir . $new_image_name);
                }
                if(($_FILES['upload_image']['size']) <= $maxSize) {

                    $targetFilePath = $uploadDir . $new_image_name;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                    $allowTypes = array('jpg','jpeg','png');
                    if (in_array($fileType, $allowTypes)) {

                        if (!move_uploaded_file($_FILES["upload_image"]["tmp_name"], $targetFilePath)) {

//not uploaded
                            $json_array['status'] = "success";
                            $json_array['msg'] = "Updated Successfully, but Image not uploaded!!!";
                            $json_response = json_encode($json_array);
                            echo $json_response;
                        }
                        else{
                            $sqlUpdates = "UPDATE `user` SET img =1 WHERE user_id ='$user_id'";
                            mysqli_query($conn, $sqlUpdates);

                            // $emp_id = $_COOKIE['staff_id'];
                            // $emp_role = $_COOKIE['role'];
//                            $info = urlencode("Edited Gallery" );
//                            file_get_contents($website . "portal/includes/log.php?emp_id=$emp_role&info=$info");


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


                            $role=$_COOKIE['role'];
                            $staff_id=$_COOKIE['user_id'];
                            $staff_name=$_COOKIE['user_name'];
                            $info = urlencode("User Edited");
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
            $json_array['msg'] = "User ID Is Not Valid";
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
