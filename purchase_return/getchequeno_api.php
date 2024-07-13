<?php
if(isset($_POST['bank_name']))
{
    Include("../includes/connection.php");

    $bankname = $_POST['bank_name'];
//    $emp_id = $_COOKIE['emp_id'];

    $api_key = $_COOKIE['panel_api_key'];
     $added_by = $_COOKIE['user_id'];
     if ($_COOKIE['role'] == 'Super Admin'){
    $sqlValidateCookie = "SELECT * FROM `user_details` WHERE panel_api_key='$api_key'";
     }
     else {
         $sqlValidateCookie = "SELECT * FROM `user` WHERE user_id='$added_by'";
     }
    $resValidateCookie=mysqli_query($conn,$sqlValidateCookie);
    if(mysqli_num_rows($resValidateCookie) > 0)
    {

        $cheque_no_arr = array();

        $sql="SELECT * FROM `cheque_list` where upper(bank_name)=upper('$bankname') AND cheque_status = 0";
        $result=mysqli_query($conn,$sql);
        $chequenopt="<optgroup><option value=''>Select Cheque Number</option>";
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $chequenopt.="<option value='".$row['cheque_no']."'>".$row['cheque_no']."</option>";
            }
        }
        $chequenopt.="</optgroup>";
        // $json_array['status'] = 'success';
        // $json_array['cheque_no'] =  $cheque_no_arr;
        // $json_response = json_encode($json_array);
        echo $chequenopt;

        // }

    }
    else
    {
        //staff id already exist

        $json_array['status'] = "failure";
        $json_array['msg'] = "Wrong User id";
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
