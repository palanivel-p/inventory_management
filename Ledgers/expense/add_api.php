<?php


date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['expense_date'])&& isset($_POST['amount'])) {
    Include("../../includes/connection.php");

    $expense_type = clean($_POST['category_type']);
    $expense_date = clean($_POST['expense_date']);
    $amount = $_POST['amount'];
    $credit_days = $_POST['credit_days'];
    $supplier = $_POST['supplier'];
    $ref_no_c = $_POST['ref_no_c'];
    $details = $_POST['details'];
    $payment_mode = $_POST['repayment_mode'];
    $reference_no = $_POST['ref_no'];
    $due_date = $_POST['due_date'];
    $payment_status = $_POST['payment_status'];
    $bank_name = $_POST['bank_name'];

    $date = date('Y-m-d');

    $added_by = $_COOKIE['user_id'];

    $api_key = $_COOKIE['panel_api_key'];

    if ($_COOKIE['role'] == 'Super Admin'){
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    }
    else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
    if (mysqli_num_rows($resValidateCookie) > 0) {

        $sqlValidateCheque = "SELECT * FROM `cheque_list` WHERE cheque_status=0";
        $resValidateCheque = mysqli_query($conn, $sqlValidateCheque);
        if (mysqli_num_rows($resValidateCheque) > 0) {

            $sqlInsert = "INSERT INTO `expense`(`expense_id`,`bank_name`, `expense_type`,`expense_date`,`amount`,`details`,`credit_days`,`payment_mode`,`supplier`,`ref_no_c`,`reference_no`,`due_date`,`payment_status`,`added_by`) 
                                            VALUES ('', '$bank_name','$expense_type','$expense_date','$amount','$details','$credit_days','$payment_mode','$supplier','$ref_no_c','$reference_no','$due_date','$payment_status','$added_by')";

            mysqli_query($conn, $sqlInsert);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $expense_id = "E" . ($ID);

            $sqlUpdate = "UPDATE expense SET expense_id = '$expense_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdate);

            $sqlExpenseType = "SELECT * FROM `expense_category` WHERE `category_id`='$expense_type'";
            $resExpenseType = mysqli_query($conn, $sqlExpenseType);
            $rowExpenseType = mysqli_fetch_assoc($resExpenseType);
            $ExpenseType = $rowExpenseType['category_name'];

//            if ($ref_no_c != '') {
//                $sqlUpdateCheque = "UPDATE cheque_list SET cheque_status = 1,cheque_used = '$ExpenseType' WHERE cheque_no ='$ref_no_c'";
//                mysqli_query($conn, $sqlUpdateCheque);
//            }
            if($ref_no_c != ''){
                $sqlUpdateCheque = "UPDATE cheque_list SET cheque_status = 1,cheque_used = '$ExpenseType',cheque_type='Expense',due_date='$due_date',purchase_date='$expense_date',cs_name='$supplier' WHERE cheque_no ='$ref_no_c'";
                mysqli_query($conn, $sqlUpdateCheque);
            }
            $sqlInsertBank = "INSERT INTO `bank_details`(`b_id`,`ref_id`, `bank_name`,`payment_date`,`pay_from`,`customer_name`,`pay_mode`,`amount`,`ref_no`,`type`) 
                                            VALUES ('', '$expense_id','$bank_name','$expense_date','Expense','$supplier','$payment_mode','$amount','$reference_no','Debit')";

            mysqli_query($conn, $sqlInsertBank);

            $ID = mysqli_insert_id($conn);

            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }

            $b_id = "B" . ($ID);

            $sqlUpdateBank = "UPDATE bank_details SET b_id = '$b_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdateBank);
            //log
//        $info = urlencode("Added Game - " . $game_id);
//        file_get_contents($website . "portal/includes/log.php?api_key=$api_key&info=$info");

            $role=$_COOKIE['role'];
            $staff_id=$_COOKIE['user_id'];
            $staff_name=$_COOKIE['user_name'];
            $info = urlencode("Expense Added");
            $role = urlencode($role); // Assuming $id is a variable with the emp_id value
            $staff_id = urlencode($staff_id); // Assuming $id is a variable with the emp_id value
            $staff_name = urlencode($staff_name); // Assuming $id is a variable with the emp_id value
            $url = "https://erp.aecindia.net/includes/log_api.php?message=$info&role=$role&staff_id=$staff_id&staff_name=$staff_name";
            file_get_contents($url);

            //inserted successfully

            $json_array['status'] = "success";
            $json_array['msg'] = "Added successfully !!!";
            $json_response = json_encode($json_array);
            echo $json_response;
        }

        else {
            //Parameters missing

            $json_array['status'] = "failure";
            $json_array['msg'] = "Cheque No Already Exist !!!";
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
