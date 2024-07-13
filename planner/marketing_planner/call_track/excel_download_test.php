<?php
require_once '../../../includes/excel_generator/PHPExcel.php';
Include("../../../includes/connection.php");

$customer_name= $_GET['product_id'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-d');
}
$from_date = date('Y-m-d 00:00:00',strtotime($f_date));
$to_date = date('Y-m-d 23:59:59',strtotime($t_date));

if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}


if($customer_name != ""){
    $customer_nameSql= " AND customer_name LIKE '%".$customer_name."%'";
}
else{
    $customer_nameSql ="";
}
$cookieStaffId = $_COOKIE['staff_id'];
$cookieBranch_Id = $_COOKIE['branch_id'];

if($_COOKIE['role'] == 'Super Admin'){
    $addedBranchSerach = '';
}
else {
    if ($_COOKIE['role'] == 'Admin'){
        $addedBranchSerach = "AND branch_name='$cookieBranch_Id'";

    }
    else{
        $addedBranchSerach = "AND added_by='$cookieStaffId' AND branch_name='$cookieBranch_Id'";

    }

}

 //Create new PHPExcel object
$objPHPExcel = new PHPExcel();

 //Set document properties
$objPHPExcel->getProperties()->setCreator("Mark Baker")
    ->setLastModifiedBy("Mark Baker")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

// Add some data
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');

$objPHPExcel->getActiveSheet()->setCellValue('A2', '#');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Customer Name');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Count');

// Initialize variables
$current_date = $f_date;
$column = 3; // Starting column for data

// Loop through dates
while ($current_date <= $t_date) {
    $formatted_date = date('d-m-y', strtotime($current_date));

    // Fetch the count of distinct customers for each date
    $query = "SELECT COUNT(customer_id) AS customer_count FROM call_track WHERE call_date = '$current_date'";
    $result = mysqli_query($conn, $query);
    $row_data = mysqli_fetch_assoc($result);
    $customer_count = $row_data['customer_count'];

    // Add data to Excel
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 2, $formatted_date . "(".$customer_count.")");
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $customer_count);

    // Move to the next column
    $column++;

    // Move to the next date
    $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
}



//$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Notes');
//$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Reason');




$i = 0;

$currentDate = date('Y-m-d');
if ($customer_name == "" && $f_date != "" && $t_date != "") {
    $sql = "SELECT * FROM call_track WHERE call_date BETWEEN '$f_date' AND '$t_date' ORDER BY id DESC LIMIT $start,10";
} else if ($customer_name != "" && $f_date != "" && $t_date != "") {
    $sql = "SELECT * FROM call_track WHERE call_date BETWEEN '$f_date' AND '$t_date' AND customer_name = '$customer_name' ORDER BY id DESC LIMIT $start,10";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $sNo = $start;
    while ($row = mysqli_fetch_assoc($result)) {
        $sNo++;
        $customer_id = $row["customer_id"];
        $call_track_id = $row["call_track_id"];
        $customer_name = $row["customer_name"];
        $notes = $row["notes"];
        $call_date = $row["call_date"];
        $next_date = $row["next_date"];
        $sqlCustomer = "SELECT * FROM `customer` WHERE `customer_id`='$customer_id'";
        $resCustomer = mysqli_query($conn, $sqlCustomer);
        $rowCustomer = mysqli_fetch_assoc($resCustomer);
        $customer_names = $rowCustomer['customer_name'];
        $query = "SELECT COUNT(*) AS count FROM call_track WHERE customer_id = '$customer_id' AND call_date BETWEEN '$f_date' AND '$t_date'";
        $resl = mysqli_query($conn, $query);
        $row_count = mysqli_fetch_assoc($resl)['count'];
        $i++;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2), ($i));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2), ($customer_name));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2), ($row_count));


        $current_date = $f_date;

        $sqlcall = "SELECT * FROM `call_track` where customer_id='$customer_id'";
        $rescall = mysqli_query($conn, $sqlcall);

        if (mysqli_num_rows($rescall) > 0) {
            // Initialize an array to store counts for each date
            $counts = array();

            while ($rowCall = mysqli_fetch_assoc($rescall)) {
                $call_date = $rowCall['call_date']; // Assuming 'call_date' is the column name where date is stored
                // Increment count for the corresponding date
                if (!isset($counts[$call_date])) {
                    $counts[$call_date] = 1;
                } else {
                    $counts[$call_date]++;
                }
            }

                    $columns = 3; // Starting column for data
                    $rowCountsE = 3; // Starting column for data

                    $s = $f_date;

//        echo $s.'<br>';
//        echo $t_date;

// Loop through dates
                    while ($s <= $t_date) {
                        $formatted_date = date('d-m-y', strtotime($s));
                        $formatted_datess = date('Y-m-d', strtotime($s));
//            echo $formatted_date.'<br>';
                        if (isset($counts[$formatted_date])) {

                            $callCounts = "";
                            $sqlIntend = "SELECT * FROM `call_track` WHERE `customer_name`='$customer_name' AND call_date='$formatted_datess'";
                            $resIntend = mysqli_query($conn, $sqlIntend);
                            if (mysqli_num_rows($result) > 0) {
                                while ($rowIntend = mysqli_fetch_assoc($resIntend)) {
                                    $call_id = $rowIntend['call_track_id'];

                                    $sqlCall = "SELECT * FROM `call_track` WHERE `call_track_id`='$call_id'";
                                    $resCall = mysqli_query($conn, $sqlCall);
                                    $rowCall = mysqli_fetch_assoc($resCall);
                                    $callCounts .= "C ";
                                }

                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columns, ($i + 2), $callCounts);


                            }


                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columns, ($i + 2), "NA");

                        }
                        // Add data to Excel
//    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $customer_count);

                        // Move to the next column
                        $columns++;
                        $rowCountsE++;
                        // Move to the next date
                        $s = date('Y-m-d', strtotime($s . ' +1 day'));
                    }


//        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i + 2), "NA");
                }

    }
}


ob_clean();
$objPHPExcel->getActiveSheet()->setCellValue('A1'," Adjustment List");
$objPHPExcel->getActiveSheet()->setCellValue('A2');
$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getAlignment()
    ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()
    ->getStyle('A2')
    ->getAlignment()
    ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getFont()
    ->setSize(16)
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('A2')
    ->getFont()
    ->setSize(13)
    ->setBold(true);


 //Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');

$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getActiveSheet()
    ->getStyle('A2:B1')
    ->getProtection()->setLocked(
        PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
    );

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => $color
        )
    ));
}

cellColor('A2:E2', '#181f5a');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:E2')
    ->getFont()
    ->getColor()
    ->setRGB('FFFFFF');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:E2')
    ->getFont()
    ->setSize(12)
    ->setBold(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($_SERVER["DOCUMENT_ROOT"].'/adjustments.csv');
sleep(1);

header('Content-type: application/octet-stream');
header("Content-Disposition: attachment; filename=adjustments.csv");
readfile($_SERVER["DOCUMENT_ROOT"].'/adjustments.csv');

@unlink($_SERVER["DOCUMENT_ROOT"].'/adjustments.csv');



?>