<?php
require_once '../../includes/excel_generator/PHPExcel.php';
Include("../../includes/connection.php");

$u_name= $_GET['u_name'];
$u_code= $_GET['u_code'];
$mobile= $_GET['mobile'];
$email= $_GET['email'];

if($u_name != ""){
    $uNameSql= " AND username = '".$u_name."'";

}
else{
    $uNameSql ="";
}

if($u_code != ""){
    $uCodeSql= " AND user_id = '".$u_code."'";

}
else{
    $uCodeSql ="";
}

if($mobile != ""){
    $mobileSql= " AND phone = '".$mobile."'";

}
else{
    $mobileSql ="";
}

if($email != ""){
    $emailSql= "AND email = '".$email."'";

}
else {
    $emailSql = "";
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

$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
//$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');

$objPHPExcel->getActiveSheet()->setCellValue('A2', '#');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'User Code');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'User Name');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Phone');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Email');
//$objPHPExcel->getActiveSheet()->setCellValue('F2', 'password');
//$objPHPExcel->getActiveSheet()->setCellValue('G2', 'GSTIN');
//$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Address 1');
//$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Address 2');
//$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Place of supply');



$i = 0;

if($email == "" && $u_code == "" && $mobile== "" && $u_name == "") {
    $sql = "SELECT * FROM user";
}
else {
    $sql = "SELECT * FROM user WHERE id > 0 $emailSql$mobileSql$uCodeSql$uNameSql";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result)>0) {
    $sNo = $i;
    while($row = mysqli_fetch_assoc($result)) {

        $sNo++;


        $user_name =  $row['username'];
        $user_id =  $row['user_id'];
        $email =  $row['email'];
        $phone =  $row['phone'];
//        $supplier_phone1 =  $row['supplier_phone1'];
//        $address1 =  $row['address1'];
//        $address2 =  $row['address2'];
//        $gstin =  $row['gstin'];
//        $supply_place =  $row['supply_place'];

            $i++;

        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2) , ($i));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2) , ($user_id));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2) , ($user_name));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i + 2) ,$phone);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i + 2) ,$email);
//        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i + 2) ,$supplier_email);
//        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i + 2) ,$gstin);
//        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i + 2) ,$address1);
//        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i + 2) ,$address2);
//        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i + 2) ,$supply_place);


        }

}

ob_clean();
$objPHPExcel->getActiveSheet()->setCellValue('A1'," User List");
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
$objWriter->save($_SERVER["DOCUMENT_ROOT"].'/Users.csv');
sleep(1);

header('Content-type: application/octet-stream');
header("Content-Disposition: attachment; filename=Users.csv");
readfile($_SERVER["DOCUMENT_ROOT"].'/Users.csv');

@unlink($_SERVER["DOCUMENT_ROOT"].'/Users.csv');



?>