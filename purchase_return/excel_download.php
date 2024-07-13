<?php
require_once '../includes/excel_generator/PHPExcel.php';
Include("../includes/connection.php");

$pur_id= $_GET['pur_id'];
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
if($pur_id != ""){
    $pur_idSql= " AND purchase_id = '".$pur_id."'";

}
else{
    $pur_idSql ="";
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

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Mark Baker")
    ->setLastModifiedBy("Mark Baker")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

// Add some data
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
//$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');

$objPHPExcel->getActiveSheet()->setCellValue('A2', '#');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Date');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Return Id');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Supplier');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Grand Total');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Notes');

$i = 0;
if($pur_id == "" ) {
    $sql = "SELECT * FROM purchase_return WHERE return_date  BETWEEN '$from_date' AND '$to_date'";
}
else {
    $sql = "SELECT * FROM purchase_return WHERE id > 0 WHERE return_date  BETWEEN '$from_date' AND '$to_date' $pur_idSql";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result)>0) {
    $sNo = $i;
    while($row = mysqli_fetch_assoc($result)) {
        $supplier_id=$row['supplier'];
        $return_id =  $row['return_id'];
        $status =  $row['payment_status'];
        if($status == 3){
            $payment_status = 'Pending';
        }
        else if($status == 1){
            $payment_status = 'Received';
        }
        else if($status == 2){
            $payment_status = 'partially Pending';
        }
        else if($status == 4){
            $payment_status = 'Ordered';
        }
        if($row['notes'] ==''){
            $notes = 'NA';
        }
        else{
            $notes = $row['notes'];
        }
        $sNo++;

        $supplier_id=$row['supplier'];

        $sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
        $resSupplier = mysqli_query($conn, $sqlSupplier);
        $rowSupplier = mysqli_fetch_assoc($resSupplier);
        $Supplier =  $rowSupplier['supplier_name'];

        $date = $row['return_date'];
        $return_date = date('d-m-Y', strtotime($date));
        if($row['notes'] ==''){
            $notes = 'NA';
        }
        else{
            $notes = $row['notes'];
        }
            $i++;

        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2) , ($i));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2) , ($return_date));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2) , ($return_id));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i + 2) ,$Supplier);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i + 2) ,$row['grand_total']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i + 2) ,$notes);
        }
}

ob_clean();
$objPHPExcel->getActiveSheet()->setCellValue('A1'," purchase_return List");
//$objPHPExcel->getActiveSheet()->setCellValue('A2');
$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getAlignment()
    ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getFont()
    ->setSize(16)
    ->setBold(true);

// Rename worksheet
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

cellColor('A2:F2', '#181f5a');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:F2')
    ->getFont()
    ->getColor()
    ->setRGB('FFFFFF');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:F2')
    ->getFont()
    ->setSize(12)
    ->setBold(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($_SERVER["DOCUMENT_ROOT"].'/purchase_return.csv');
sleep(1);

header('Content-type: application/octet-stream');
header("Content-Disposition: attachment; filename=purchase_return.csv");
readfile($_SERVER["DOCUMENT_ROOT"].'/purchase_return.csv');

@unlink($_SERVER["DOCUMENT_ROOT"].'/purchase_return.csv');



?>