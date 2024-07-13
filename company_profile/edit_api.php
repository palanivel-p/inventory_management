<?php
date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['company_id']))
{
    Include("../includes/connection.php");
    $company_id = $_POST['company_id'];
    $old_pa_id = $_POST['old_pa_id'];
    $company_name = $_POST['company_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $gstin = $_POST['gstin'];
    $bank_name = $_POST['bank_name'];
    $bank_name2 = $_POST['bank_name2'];
    $account_name = $_POST['acc_name'];
    $account_no = $_POST['acc_no'];
    $ifsc_code = $_POST['ifsc'];
    $branch_name = $_POST['branch_name'];

    // $date = date('Y-m-d');
    $api_key = $_COOKIE['panel_api_key'];

    $added_by = $_COOKIE['user_id'];
    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }
    $resValidateCookie=mysqli_query($conn,$sqlValidateCookie);
    if(mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidate = "SELECT * FROM `company_profile` WHERE company_id='$company_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($company_id==$company_id))  {

            $sqlUpdate = "UPDATE `company_profile` SET `company_id`='$company_id',`company_name`='$company_name',`phone`='$phone',`email`='$email',`address`='$address',`state`='$state',`gstin`='$gstin',`bank_name`='$bank_name',`bank_name2`='$bank_name2',`account_name`='$account_name',`account_no`='$account_no',`ifsc_code`='$ifsc_code',`branch_name`='$branch_name' WHERE `company_id`='$company_id'";
            mysqli_query($conn, $sqlUpdate);

            $uploadDir = 'company_logo/';
            $new_image_name = $company_id.'.jpg';

//            $uploadDirPdf = '../../placements/pdf/';
//            $new_image_name_pdf = $team_id.'.pdf';

            $maxSize =1000000;
            $uploadedFile = '';
            if (!empty($_FILES["upload_image"]["tmp_name"])) {

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
                            $sqlUpdates = "UPDATE company_profile SET img =1 WHERE company_id ='$company_id'";
                            mysqli_query($conn, $sqlUpdates);

                            // $emp_id = $_COOKIE['staff_id'];
                            // $emp_role = $_COOKIE['role'];
//                            $info = urlencode("Edited Gallery" );
//                            file_get_contents($website . "portal/includes/log.php?emp_id=$emp_role&info=$info");

                            $role=$_COOKIE['role'];
                            $staff_id=$_COOKIE['user_id'];
                            $staff_name=$_COOKIE['user_name'];
                            $info = urlencode("Company Profile Edited");
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
            $json_array['msg'] = "company ID Is Not Valid";
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
