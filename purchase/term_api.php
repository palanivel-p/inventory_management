<?php

date_default_timezone_set("Asia/Kolkata");
// if(isset($_POST['borrower']) &&isset($_POST['loan_date'])) {
Include("../includes/connection.php");
if(isset($_POST['term_purchase_id']) && isset($_POST['supplier_name'])&& isset($_POST['term_condition'])){
$term_purchase_id = clean($_POST['term_purchase_id']);
$supplier_name = clean($_POST['supplier_name']);
$term_condition = clean($_POST['term_condition']);
$jsonterm = clean($_POST['jsonterm']);
    $jsonterms =base64_decode($jsonterm);
    $term =  str_replace("'", "", $term_condition);

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

    $sqlInsert = "INSERT INTO `purchase_term`(`purchase_id`,`term_condition`,`jsonterm`) 
                                            VALUES ('$term_purchase_id','$term','$jsonterms')";

    mysqli_query($conn, $sqlInsert);

    $ID=mysqli_insert_id($conn);

    if(strlen($ID)==1)
    {
        $ID='00'.$ID;

    }elseif(strlen($ID)==2)
    {
        $ID='0'.$ID;
    }

    $term_id="PT".($ID);

    $sqlUpdate = "UPDATE purchase_term SET term_id = '$term_id' WHERE id ='$ID'";
    mysqli_query($conn, $sqlUpdate);

    $role=$_COOKIE['role'];
    $staff_id=$_COOKIE['user_id'];
    $staff_name=$_COOKIE['user_name'];
    $info = urlencode("Terms Added");
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
