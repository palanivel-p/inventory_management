<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['bank_name'])&&isset($_POST['acc_no'])) {
    Include("../includes/connection.php");

    $bank_name = clean($_POST['bank_name']);
    $acc_no = clean($_POST['acc_no']);
    $from_no = clean($_POST['from_no']);
    $to_no = clean($_POST['to_no']);
    $notes = clean($_POST['note']);
    $cid = clean($_POST['cid']);


    $date = date('Y-m-d');

    $added_by = $_COOKIE['user_id'];

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
        $sqlValidateAcc = "SELECT * FROM `cheque` WHERE `acc_no`='$acc_no'";
        $resValidateAcc= mysqli_query($conn, $sqlValidateAcc);
        if (mysqli_num_rows($resValidateAcc) == 0) {

        $sqlInsert = "INSERT INTO `cheque`(`cheque_id`,`bank_name`,`acc_no`,`to_number`,`from_number`,`notes`,`date`,`added_by`) 
                                            VALUES ('','$bank_name','$acc_no','$to_no','$from_no','$notes','$date','$added_by')";

        mysqli_query($conn, $sqlInsert);

        $ID=mysqli_insert_id($conn);

        if(strlen($ID)==1)
        {
            $ID='00'.$ID;

        }elseif(strlen($ID)==2)
        {
            $ID='0'.$ID;
        }

        $cheque_id="CH".($ID);

        $sqlUpdate = "UPDATE cheque SET cheque_id = '$cheque_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);

        $sqlValidateChequef = "SELECT * FROM `cheque_list` WHERE `cheque_no`='$from_no' AND `bank_name`='$bank_name'";
        $resValidateChequef= mysqli_query($conn, $sqlValidateChequef);

        if (mysqli_num_rows($resValidateChequef) == 0) {
            $sqlValidateChequet = "SELECT * FROM `cheque_list` WHERE `cheque_no`='$to_no' AND `bank_name`='$bank_name'";
            $resValidateChequet = mysqli_query($conn, $sqlValidateChequet);

            if (mysqli_num_rows($resValidateChequet) == 0) {

                for ($cheque_no = $from_no; $cheque_no <= $to_no; $cheque_no++) {
                    $sqlInsertL = "INSERT INTO `cheque_list`(`cheque_id`,`cheque_no`,`bank_name`,`acc_no`,`from_number`,`to_number`,`notes`,`date`) 
                                            VALUES ('$cheque_id','$cheque_no','$bank_name','$acc_no','$from_no','$to_no','$notes','$date')";
                    mysqli_query($conn, $sqlInsertL);


                $ID = mysqli_insert_id($conn);

                $cheque_list_id = "CHL" . ($ID);

                $sqlUpdateL = "UPDATE cheque_list SET cheque_list_id = '$cheque_list_id' WHERE id ='$ID'";
                mysqli_query($conn, $sqlUpdateL);
                }

                $role=$_COOKIE['role'];
                $staff_id=$_COOKIE['user_id'];
                $staff_name=$_COOKIE['user_name'];
                $info = urlencode("Cheque Added");
                $role = urlencode($role); // Assuming $id is a variable with the emp_id value
                $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
                $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
                $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
                file_get_contents($url);


                $json_array['status'] = "success";
                $json_array['msg'] = "Added successfully !!!";
                $json_response = json_encode($json_array);
                echo $json_response;
            } else {


                $json_array['status'] = "failure";
                $json_array['msg'] = "Cheque To Number Already Exist!!!";
                $json_response = json_encode($json_array);
                echo $json_response;
            }
        }
        else {


            $json_array['status'] = "failure";
            $json_array['msg'] = "Cheque From Number Already Exist!!!";
            $json_response = json_encode($json_array);
            echo $json_response;
        }

    }

    else {
        //Parameters missing

        $json_array['status'] = "failure";
        $json_array['msg'] = "Account Number Already Exist!!!";
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
