<?php
Include("../../includes/connection.php");

date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)

if(isset($_POST['type'])&& isset($_POST['lat'])&& isset($_POST['lng'])) {

    $emp_id = $_COOKIE['user_id'];

    $type = clean($_POST['type']);
    $lat = clean($_POST['lat']);
    $lng = clean($_POST['lng']);

    $date = date("Y-m-d H:i:s");
    $dates = date("Y-m-d 0:0:0");
//    $api_key = clean($_GET['api_key']);

    $sqlStaff = "SELECT * FROM user WHERE user_id = '$emp_id'";
    $resultStaff = mysqli_query($conn, $sqlStaff);
    if (mysqli_num_rows($resultStaff) > 0) {

        $row = mysqli_fetch_array($resultStaff);
        $emp_id = $row['staff_id'];


    }


    if($type=="logIn") {


        $sqlqueryss = "SELECT * FROM attendance WHERE emp_id = '$emp_id' AND login >= '$dates' ORDER BY login DESC ";
        $resultss = mysqli_query($conn, $sqlqueryss);


//        $sqlUpdates = "UPDATE `staff` SET `daily_status`='1' WHERE `staff_id`='$emp_id'";
//        mysqli_query($conn, $sqlUpdates);
        if (mysqli_num_rows($resultss) == 0) {

            $sqlInsert = "insert into `attendance`(`emp_id`,`date_time`,`login_lat`,`login_lng`,`login`,`present_status`,`remarks`) values ('$emp_id','$date','$lat','$lng','$date','A','Field')";
            mysqli_query($conn, $sqlInsert);


            $json_array['status'] = "success";
            $json_array['msg'] = "Thank You !!!";

            $json_response = json_encode($json_array);
            echo $json_response;
            exit();
        }
    }
    elseif($type=="logOut") {

        $sqlquerys = "SELECT * FROM attendance WHERE emp_id = '$emp_id' AND login >= '$dates' ORDER BY id DESC ";
        $results = mysqli_query($conn, $sqlquerys);

        if (mysqli_num_rows($results) > 0) {

            $row = mysqli_fetch_array($results);

            $logintym = strtotime($row['login']); // Convert login time to a Unix timestamp
            $current_time = time(); // Get the current time as a Unix timestamp

            $duration = $current_time - $logintym; // Calculate the duration in seconds

// You can convert the duration to a more human-readable format, for example, in hours, minutes, and seconds
            $hours = floor($duration / 3600);
            $minutes = floor(($duration % 3600) / 60);
            $seconds = $duration % 60;

            $totaltym = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            // echo $totaltym;



            $logTime = date("H:i:s", strtotime($row['login']));
            $currentTime = date("Y-m-d H:i:s");
            $a = date("H:i:s", strtotime($currentTime));

            $aa = strtotime($a);
            $bb = strtotime($logTime);

            $diff = $aa - $bb;

            $formattedDiff = gmdate("H:i:s", abs($diff));



            if($row['logout']=='0000-00-00 00:00:00') {

                $sqlInsert = "UPDATE `attendance` SET `logout`='$date',`present_status`='P',`logout_lat`='$lat',`logout_lng`='$lng' WHERE emp_id='$emp_id' order by id desc limit 1";
                mysqli_query($conn, $sqlInsert);

//                $sqlUpdates = "UPDATE `staff` SET `daily_status`='0' WHERE `staff_id`='$emp_id'";
//                mysqli_query($conn, $sqlUpdates);
            }

            $json_array['status'] = "success";
            $json_array['msg'] = "Thank You !!!";

            $json_response = json_encode($json_array);
            echo $json_response;
            exit();

        } else {
            $json_array['status'] = "failure";
            $json_array['msg'] = "login is missing";
            $json_response = json_encode($json_array);
            echo $json_response;
            exit;

        }
    }

    if($type=="breakOut") {


        $sqlqueryBreako = "SELECT * FROM break_attendance WHERE emp_id = '$emp_id' ORDER BY login DESC ";
        $resultBreako = mysqli_query($conn, $sqlqueryBreako);


//        $sqlUpdates = "UPDATE `staff` SET `daily_status`='1' WHERE `staff_id`='$emp_id'";
//        mysqli_query($conn, $sqlUpdates);
        if (mysqli_num_rows($resultBreako) == 0) {

            $sqlInsert = "insert into `break_attendance`(`emp_id`,`date`,`break_out`) values ('$emp_id','$date','$date')";
            mysqli_query($conn, $sqlInsert);


            $json_array['status'] = "success";
            $json_array['msg'] = "Thank You !!!";

            $json_response = json_encode($json_array);
            echo $json_response;
            exit();
        }
    }
    elseif($type=="breakIn") {

        $sqlqueryBreak = "SELECT * FROM break_attendance WHERE emp_id = '$emp_id' ORDER BY id DESC ";
        $resultBreak = mysqli_query($conn, $sqlqueryBreak);

        if (mysqli_num_rows($resultBreak) > 0) {

            $rowBreak = mysqli_fetch_array($resultBreak);

            $logintym = strtotime($rowBreak['login']); // Convert login time to a Unix timestamp
            $current_time = time(); // Get the current time as a Unix timestamp

            $duration = $current_time - $logintym; // Calculate the duration in seconds

// You can convert the duration to a more human-readable format, for example, in hours, minutes, and seconds
            $hours = floor($duration / 3600);
            $minutes = floor(($duration % 3600) / 60);
            $seconds = $duration % 60;

            $totaltym = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            // echo $totaltym;



            $logTime = date("H:i:s", strtotime($row['login']));
            $currentTime = date("Y-m-d H:i:s");
            $a = date("H:i:s", strtotime($currentTime));

            $aa = strtotime($a);
            $bb = strtotime($logTime);

            $diff = $aa - $bb;

            $formattedDiff = gmdate("H:i:s", abs($diff));



//            if($row['logout']=='0000-00-00 00:00:00') {

            $sqlInsertBreak = "UPDATE `break_attendance` SET `break_in`='$date' WHERE emp_id='$emp_id' order by id desc limit 1";
            mysqli_query($conn, $sqlInsertBreak);

//                $sqlUpdates = "UPDATE `staff` SET `daily_status`='0' WHERE `staff_id`='$emp_id'";
//                mysqli_query($conn, $sqlUpdates);
//            }

            $json_array['status'] = "success";
            $json_array['msg'] = "Thank You !!!";

            $json_response = json_encode($json_array);
            echo $json_response;
            exit();

        } else {
            $json_array['status'] = "failure";
            $json_array['msg'] = "login is missing";
            $json_response = json_encode($json_array);
            echo $json_response;
            exit;

        }
    }

    $json_array['status'] = "success";
    $json_array['msg'] = "Thank You !!!";

    $json_response = json_encode($json_array);
    echo $json_response;


} else {
    //device missing

    $json_array['status'] = "failure";
    $json_array['msg'] = "Parameters missing";
    $json_response = json_encode($json_array);
    echo $json_response;
}




function clean($data) {
    $data= str_replace("'","",$data);
    $data= str_replace('"',"",$data);
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}
?>