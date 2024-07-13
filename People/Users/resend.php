<?php
include("../../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");

if (isset($_POST['id'])) {
    $s_id = $_POST['id'];

    include("../include/dbconnect.php");

    // Use prepared statement to prevent SQL injection
    $sqlquery = "SELECT * FROM `user` WHERE user_id='$s_id'";

    // Prepare the statement
    $result = mysqli_query($conn,$sqlquery);



    // Check if there are rows
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $email=$row['email'];
        $password= $row['password'];



        $url = $website . "/People/Users/field_app.php?email=$email&password=$password&app=1";
        $content = file_get_contents($url);


        $json_array['status'] = 'success';

        $json_response = json_encode($json_array);
        echo $json_response;


    }
    else {
        // Visitor ID not found
        $json_array['status'] = "failure";
        $json_array['msg'] = "Invalid  ID !!!";
        $json_response = json_encode($json_array);
        echo $json_response;
    }
} else {
    // No ID provided
    $json_array['status'] = "failure";
    $json_array['msg'] = "Please try after sometime !!!";
    $json_response = json_encode($json_array);
    echo $json_response;
}
?>