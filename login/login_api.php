<?php
Include("../includes/connection.php");

if(isset($_POST['email']) && isset($_POST['password'])) {

    $email = clean($_POST['email']);

    $password = clean($_POST['password']);
//
//    $salt = 'GB#$20deeSp%22';
//      $pw_hash = sha1($salt.$password);


    $remember = 0;

    $sqlValidate="SELECT * FROM `user_details` WHERE user_name='$email' AND password='$password'";
    $resValidate=mysqli_query($conn,$sqlValidate);
    if(mysqli_num_rows($resValidate) > 0)
    {
        if($remember==1)
        {
            $cookie_set=120;
        }
        else
        {
            $cookie_set=1;
        }
        $cookie_set=120;

        $row = mysqli_fetch_array($resValidate);

        setcookie("panel_api_key", $row['panel_api_key'], time() + (3600 *$cookie_set), "/"); // To set Login for 1 hr
        setcookie("user_id", $row['user_name'], time() + (3600 *$cookie_set), "/"); // To set Login for 1 hr



        echo "{\"result\":\"success\"}";

    }
    else
    {


            echo "{\"result\":\"wrong\"}";

//        echo "{\"result\":\"wrong\"}";
    }



}

else
{

    echo "{\"result\":\"failure\"}";


}
function clean($data){
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}

setcookie('username',$username,time()+60*60*24*365);
// 'Force' the cookie to exists
$_COOKIE['username'] = $username;