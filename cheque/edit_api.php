<?php

date_default_timezone_set("Asia/Kolkata");


if(isset($_POST['cheque_id']))
{
    Include("../includes/connection.php");

    $cheque_id = $_POST['cheque_id'];
    $bank_name = $_POST['bank_name'];
    $acc_no = $_POST['acc_no'];
    $from_no = $_POST['from_no'];
    $to_no = $_POST['to_no'];
    $notes = $_POST['notes'];

    $date = date('Y-m-d');

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

        $sqlValidate = "SELECT * FROM `cheque` WHERE cheque_id='$cheque_id'";
        $resValidate = mysqli_query($conn, $sqlValidate);
        if (mysqli_num_rows($resValidate) > 0 || ($cheque_id==$cheque_id)) {
//            $sqlValidateChequef = "SELECT * FROM `cheque` WHERE `cheque_no`='$from_no' ";
//            $resValidateChequef = mysqli_query($conn, $sqlValidateChequef);
//
//            if (mysqli_num_rows($resValidateChequef) == 0) {
//                $sqlValidateChequet = "SELECT * FROM `cheque` WHERE `cheque_no`='$to_no' ";
//                $resValidateChequet = mysqli_query($conn, $sqlValidateChequet);
//
//                if (mysqli_num_rows($resValidateChequet) == 0) {

                    for ($cheque_no = $from_no; $cheque_no <= $to_no; $cheque_no++) {

                        $sqlUpdate = "UPDATE `cheque` SET `cheque_id`='$cheque_id',`cheque_no`= '$cheque_no',`bank_name`='$bank_name',`acc_no`='$acc_no',`from_number`='$from_no',`to_number`='$to_no',`notes`='$notes' WHERE `cheque_id`='$cheque_id';";
                        mysqli_query($conn, $sqlUpdate);

                    }

            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Cheque Edited");
            $role = urlencode($role); // Assuming $id is a variable with the emp_id value
            $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
            $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
            $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
            file_get_contents($url);

                    //inserted successfully
                    $json_array['status'] = "success";
                    $json_array['msg'] = "Updated successfully !!!";
                    $json_response = json_encode($json_array);
                    echo $json_response;

                }
//
// else {
//
//
//                    $json_array['status'] = "failure";
//                    $json_array['msg'] = "Cheque To Number Already Exist!!!";
//                    $json_response = json_encode($json_array);
//                    echo $json_response;
//                }
//            } else {
//
//
//                $json_array['status'] = "failure";
//                $json_array['msg'] = "Cheque From Number Already Exist!!!";
//                $json_response = json_encode($json_array);
//                echo $json_response;
//            }

        else {
            //Parameters missing

            $json_array['status'] = "failure";
            $json_array['msg'] = "Cheque ID Is Not Valid";
            $json_response = json_encode($json_array);
            echo $json_response;
        }

              }  else {


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

?>
