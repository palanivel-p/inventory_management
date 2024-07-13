<?php
if(isset($_POST['primary_category']))
{
    Include("../../includes/connection.php");

    $primary_category = $_POST['primary_category'];
        //$emp_id = $_COOKIE['emp_id'];

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

        $sub_category = array();
        $category_sub_id = array();

        $sql="SELECT * FROM category WHERE primary_category='$primary_category'";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {

                $sub_category[] = $row['sub_category'];
                $category_sub_id[] = $row['category_id'];

            }
        }
        $json_array['status'] = 'success';
        $json_array['sub_category'] =  $sub_category;
        $json_array['category_sub_id'] =  $category_sub_id;
        $json_response = json_encode($json_array);
        echo $json_response;

        // }

    }
    else
    {
        //staff id already exist

        $json_array['status'] = "failure";
        $json_array['msg'] = "Wrong staff id";
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
