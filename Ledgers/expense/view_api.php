<?php
if(isset($_POST['expense_id']))
{
    Include("../../includes/connection.php");


    $expense_id = $_POST['expense_id'];
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
    if(mysqli_num_rows($resValidateCookie) > 0)
    {
        $sqlData="SELECT * FROM `expense` WHERE expense_id='$expense_id'";
        $resData=mysqli_query($conn,$sqlData);
        if(mysqli_num_rows($resData) > 0)
        {
            $row = mysqli_fetch_array($resData);
            $expence_name = $row['expense_type'];
            $sqlExpenseType = "SELECT * FROM `expense_category` WHERE `category_id`='$expence_name'";
            $resExpenseType = mysqli_query($conn, $sqlExpenseType);
            $rowExpenseType = mysqli_fetch_assoc($resExpenseType);
            $ExpenseType =  $rowExpenseType['category_name'];

            $json_array['status'] = 'success';
            $json_array['expense_id'] = $row['expense_id'];
            $json_array['expense_type'] = $row['expense_type'];
            $json_array['expense_date'] = $row['expense_date'];
            $json_array['amount'] = $row['amount'];
            $json_array['bank_name'] = $row['bank_name'];
            $json_array['credit_days'] = $row['credit_days'];
            $json_array['expense_name'] = $row['expense_type'];
            $json_array['details'] = $row['details'];
            $json_array['repayment_mode'] = $row['payment_mode'];
            $json_array['reference_no'] = $row['reference_no'];
            $json_array['ref_no_c'] = $row['ref_no_c'];
            $json_array['payment_status'] = $row['payment_status'];
            $json_array['due_date'] = $row['due_date'];
            $json_array['supplier'] = $row['supplier'];

            $json_response = json_encode($json_array);
            echo $json_response;
        }


    }
    else
    {
        //staff id already exist

        $json_array['status'] = "wrong";
        $json_array['msg'] = "Login Invalid";
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
