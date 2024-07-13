<?php
require_once '../../includes/excel_generator/PHPExcel.php';
Include("../../includes/connection.php");

$s_name= $_GET['s_name'];
$s_code= $_GET['s_code'];
$mobile= $_GET['mobile'];
$email= $_GET['email'];
if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}

if($s_name != ""){
//    $sNameSql= " AND supplier_name = '".$s_name."'";
    $sNameSql = " AND supplier_name LIKE '%" . $s_name . "%'";

}
else{
    $sNameSql ="";
}

if($s_code != ""){
//    $sCodeSql= " AND supplier_code = '".$s_code."'";
    $sCodeSql = " AND supplier_code LIKE '%" . $s_code . "%'";

}
else{
    $sCodeSql ="";
}

if($mobile != ""){
//    $mobileSql= " AND supplier_phone = '".$mobile."'";
    $mobileSql = " AND supplier_phone LIKE '%" . $mobile . "%'";
}
else{
    $mobileSql ="";
}

if($email != ""){
//    $emailSql= "AND supplier_email = '".$email."'";
    $emailSql = " AND supplier_email LIKE '%" . $email . "%'";
}
else {
    $emailSql = "";
}
$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin'){
    $addedBy = "";
}
else{
    $addedBy = " AND added_by='$added_by'";
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

$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
//$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');

$objPHPExcel->getActiveSheet()->setCellValue('A2', '#');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Supplier Code');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Supplier Name');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Mobile 1');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Mobile 2');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'GSTIN');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Address 1');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Address 2');
$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Place of supply');



$i = 0;

if($email == "" && $mobile== "" && $s_name == "") {
    $sql = "SELECT * FROM supplier  ORDER BY id DESC LIMIT $start,10";
}
else {
    $sql = "SELECT * FROM supplier WHERE id > 0 $emailSql$mobileSql$sNameSql ORDER BY id DESC LIMIT $start,10";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result)>0) {
    $sNo = $i;
    while($row = mysqli_fetch_assoc($result)) {

        $sNo++;


        $supplier_name =  $row['supplier_name'];
        $supplier_id =  $row['supplier_id'];
        $supplier_email =  $row['supplier_email'];
        $supplier_phone =  $row['supplier_phone'];
        $supplier_phone1 =  $row['supplier_phone1'];
        $address1 =  $row['address1'];
        $address2 =  $row['address2'];
        $gstin =  $row['gstin'];
        $supply_place =  $row['supply_place'];

            $i++;

        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2) , ($i));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2) , ($supplier_id));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2) , ($supplier_name));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i + 2) ,$supplier_phone);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i + 2) ,$supplier_phone1);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i + 2) ,$supplier_email);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i + 2) ,$gstin);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i + 2) ,$address1);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i + 2) ,$address2);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i + 2) ,$supply_place);


        }

}

ob_clean();
$objPHPExcel->getActiveSheet()->setCellValue('A1'," Supplier List");
//$objPHPExcel->getActiveSheet()->setCellValue('A2');
$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getAlignment()
    ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

//$objPHPExcel->getActiveSheet()
//    ->getStyle('A2')
//    ->getAlignment()
//    ->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getFont()
    ->setSize(16)
    ->setBold(true);

//$objPHPExcel->getActiveSheet()
//    ->getStyle('A2')
//    ->getFont()
//    ->setSize(13)
//    ->setBold(true);


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

cellColor('A2:J2', '#181f5a');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:J2')
    ->getFont()
    ->getColor()
    ->setRGB('FFFFFF');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:J2')
    ->getFont()
    ->setSize(12)
    ->setBold(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($_SERVER["DOCUMENT_ROOT"].'/Suppliers.csv');
sleep(1);

header('Content-type: application/octet-stream');
header("Content-Disposition: attachment; filename=Suppliers.csv");
readfile($_SERVER["DOCUMENT_ROOT"].'/Suppliers.csv');

@unlink($_SERVER["DOCUMENT_ROOT"].'/Suppliers.csv');



?>