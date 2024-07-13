<?php
$added_by = $_COOKIE['user_id'];

date_default_timezone_set("Asia/Kolkata");
$current_date = date('Y-m-d');
if(isset($_POST['month'])) {
    Include("../../includes/connection.php");
    //furnace&laddle
    $month  = clean($_POST['month']);
    $year  = clean($_POST['year']);
    $commitment_value = clean($_POST['commitment_value']);
    $no_visit = clean($_POST['no_visit']);
    $marketing_person = clean($_POST['marketing_person']);
    $customer_name = clean($_POST['customer_name']);

    $personalLoan = $_POST['personalLoan'];
    $personalLoanAr = explode(",",$personalLoan);
    $countPersonalLoans = count($personalLoanAr);


    $date = date('Y-m-d');
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

        //Furnace lining
        $sqlMonth = "SELECT * FROM `target` WHERE `month`='$month' AND `year`='$year' AND `marketing_person`='$marketing_person'";
        $resMonth = mysqli_query($conn, $sqlMonth);

        if (mysqli_num_rows($resMonth) == 0) {
             $sqlInsert = "INSERT INTO `target`(`target_id`, `month`, `year`,`commitment_value`,`no_visit`,`marketing_person`,`customer_name`)
                                           VALUES ('','$month', '$year', '$commitment_value','$no_visit','$marketing_person','$customer_name')";
            mysqli_query($conn, $sqlInsert);
            $ID = mysqli_insert_id($conn);
            if (strlen($ID) == 1) {
                $ID = '00' . $ID;

            } elseif (strlen($ID) == 2) {
                $ID = '0' . $ID;
            }
            $target_id = "T" . ($ID);
            $sqlUpdate = "UPDATE target SET target_id = '$target_id' WHERE id ='$ID'";
            mysqli_query($conn, $sqlUpdate);


            for ($pl = 0; $pl < $countPersonalLoans; $pl++) {
                $plValues = $personalLoanAr[$pl];
                $plValues = substr($plValues, 0, -1);
                $plValuesAr = explode("%", $plValues);


                $sqlInsertPLoan = "INSERT INTO `target_detail`(`target_id`, `product_id`, `qty`)
                                           VALUES ('$target_id','$plValuesAr[0]','$plValuesAr[1]')";
                mysqli_query($conn, $sqlInsertPLoan);
                $IDs = mysqli_insert_id($conn);
                if (strlen($IDs) == 1) {
                    $IDs = '00' . $IDs;

                } elseif (strlen($IDs) == 2) {
                    $IDs = '0' . $IDs;
                }
                $target_detail_id = "TD" . ($IDs);
                $sqlUpdates = "UPDATE target_detail SET target_detail_id = '$target_detail_id' WHERE id ='$IDs'";
                mysqli_query($conn, $sqlUpdates);
            }

            $role = $_COOKIE['role'];
            $staff_id = $_COOKIE['user_id'];
            $staff_name = $_COOKIE['user_name'];
            $info = urlencode("Market-Target Added");
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
        $json_array['msg'] = "Month Or Year Already Existing !!!";
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
