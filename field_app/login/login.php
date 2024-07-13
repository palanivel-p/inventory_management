<?php
Include("../../includes/connection.php");

if(isset($_POST['email']) && isset($_POST['password'])) {

    $email = clean($_POST['email']);
    $password = clean($_POST['password']);

//    $salt = 'GB#$20deeSp%22';
//      $pw_hash = sha1($salt.$password);
    $remember = 0;

//    $sqlValidate = "SELECT * FROM `user_details` WHERE user_name='$email' AND password='$password'";
//    $resValidate = mysqli_query($conn, $sqlValidate);
//    if (mysqli_num_rows($resValidate) > 0) {
//        if ($remember == 1) {
//            $cookie_set = 120;
//        } else {
//            $cookie_set = 1;
//        }
//        $cookie_set = 120;
//
//        $row = mysqli_fetch_array($resValidate);
//        setcookie("field_api_key", $row['panel_api_key'], time() + (3600 * $cookie_set), "/"); // To set Login for 1 hr
//        setcookie("role", 'Super Admin', time() + (3600 * $cookie_set), "/"); // To set Login for 1 hr
//        setcookie("user_name", '', time() + (3600 * $cookie_set), "/"); // To set Login for 1 h
//
//
//        $json_array['result'] = "success";
//        $json_response = json_encode($json_array);
//        echo $json_response;

//    }  else {
        $sqlValidateS = "SELECT * FROM `user` WHERE email='$email' AND password='$password' AND access_status=1 AND field_app=1";
        $resValidateS = mysqli_query($conn, $sqlValidateS);
        if (mysqli_num_rows($resValidateS) > 0) {
            if ($remember == 1) {
                $cookie_set = 120;
            } else {
                $cookie_set = 1;
            }
            $cookie_set = 120;
            $rowS = mysqli_fetch_array($resValidateS);

            setcookie("field_api_key", $rowS['panel_api_key'], time() + (3600 *$cookie_set), "/"); // To set Login for 1 hr
            setcookie("user_name", $rowS['f_name'], time() + (3600 * $cookie_set), "/"); // To set Login for 1 h
            setcookie("role", $rowS['role'], time() + (3600 * $cookie_set), "/"); // To set Login for 1 hr
            setcookie("user_id", $rowS['user_id'], time() + (3600 * $cookie_set), "/"); // To set Login for 1 hr


            //  echo "{\"result\":\"success\"}";
            $json_array['result'] = "success";
            //            $json_array['msg'] = "success";
            $json_response = json_encode($json_array);
            echo $json_response;
        }
        else {
            $json_array['result'] = "failure";
            $json_array['msg'] = "Email Or Password Was Wrong !!";
            $json_response = json_encode($json_array);
            echo $json_response;
            //echo "{\"result\":\"wrong\"}";
        }
//    }
}

else
{

    $json_array['result'] = "failure";
    $json_array['msg'] = "Email Or Password Was Wrong !!";
    $json_response = json_encode($json_array);
    echo $json_response;
    //echo "{\"result\":\"wrong\"}";


}
function clean($data){
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}

//setcookie('username',$username,time()+60*60*24*365);
// 'Force' the cookie to exists
//$_COOKIE['username'] = $username;