<?php
date_default_timezone_set("Asia/Kolkata");
include("../includes/connection.php");
$current_date = date('Y-m-d');
$date = date('Y-m-d');
$added_by = $_COOKIE['role'];
$file = $_FILES['file']['tmp_name'];
$handle = fopen($file, "r");
$c = 0;

$statusSuccess = 0;

// Skip the header row
fgetcsv($handle);

while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
    $c++;

    $company_name = $filesop[1];
    $assigned_to = $filesop[2];
    $zone = $filesop[3];

    $date = date('Y-m-d');

    $api_key = $_COOKIE['panel_api_key'];
    $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);

    if (mysqli_num_rows($resValidateCookie) > 0) {
        $sqlInsert = "INSERT INTO `market_profile`(`market_profile_id`,`customer_name`,`assigned_to`,`zone`,`added_date`,`added_by`) 
                                        VALUES ('','$company_name','$assigned_to','$zone','$date','$added_by')";
        mysqli_query($conn, $sqlInsert);

        $ID = mysqli_insert_id($conn);

        if(strlen($ID) == 1) {
            $ID = '00' . $ID;
        } elseif(strlen($ID) == 2) {
            $ID = '0' . $ID;
        }

        $market_profile_id = "MP" . $ID;

        $sqlUpdate = "UPDATE market_profile SET market_profile_id = '$market_profile_id' WHERE id ='$ID'";
        mysqli_query($conn, $sqlUpdate);
        $statusSuccess = 1;
    }
}

$json_array = array();

if ($statusSuccess == 1) {
    $json_array['status'] = "success";
    $json_array['msg'] = "Added successfully !!!";
} elseif ($statusSuccess == 0) {
    $json_array['status'] = "failure";
    $json_array['msg'] = "No New Records Added !!!";
}

echo json_encode($json_array);
?>
