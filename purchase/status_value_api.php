<?php
include("../includes/connection.php");

if (isset($_POST['status_id'])) {
    $status_id = $_POST['status_id'];
    $api_key = $_COOKIE['panel_api_key'];
    $added_by = $_COOKIE['user_id'];

    if ($_COOKIE['role'] == 'Super Admin') {
        $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
    } else {
        $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
    }

    $resValidateCookie = mysqli_query($conn, $sqlValidateCookie);
    if (mysqli_num_rows($resValidateCookie) > 0) {
        $sqlData = "SELECT pd.product_id, pd.qty, p.product_name FROM `purchase_details` pd JOIN `product` p ON pd.product_id = p.product_id WHERE pd.purchase_id='$status_id'";
        $resData = mysqli_query($conn, $sqlData);

        if (mysqli_num_rows($resData) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($resData)) {
                $data[] = $row;
            }

            $json_array['status'] = 'success';
            $json_array['data'] = $data;
        } else {
            $json_array['status'] = 'failure';
            $json_array['msg'] = 'No data found';
        }
    } else {
        $json_array['status'] = "wrong";
        $json_array['msg'] = "Login Invalid";
    }
} else {
    $json_array['status'] = "failure";
    $json_array['msg'] = "Please try after sometime !!!";
}

echo json_encode($json_array);
?>
