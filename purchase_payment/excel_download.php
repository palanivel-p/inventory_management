<?php
require_once '../../includes/excel_generator/PHPExcel.php';
Include("../../includes/connection.php");

$expense_id= $_GET['expense_id'];
$e_category= $_GET['e_category'];
$s_name= $_GET['sts_name'];
$pay_mode= $_GET['pay_mode'];
$ref_no= $_GET['ref_no'];
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

if($expense_id != ""){
//    $expense_idSql= " AND expense_id = '".$expense_id."'";
    $expense_idSql = " AND expense_id LIKE '%" . $expense_id . "%'";

}
else{
    $expense_idSql ="";
}

if($e_category != ""){
    $e_categorySql= " AND expense_type = '".$e_category."'";
//    $e_categorySql = " AND expense_type LIKE '%" . $e_category . "%'";

}
else{
    $e_categorySql ="";
}

if($s_name != ""){
    $s_nameSql= " AND payment_status = '".$s_name."'";
//    $s_nameSql = " AND payment_status LIKE '%" . $s_name . "%'";

}
else{
    $s_nameSql ="";
}

if($pay_mode != ""){
    $pay_modeSql= "AND payment_mode	 = '".$pay_mode."'";
//    $pay_modeSql = " AND payment_mode LIKE '%" . $pay_mode . "%'";

}
else {
    $pay_modeSql = "";
}
if($ref_no != ""){
//    $ref_noSql= "AND reference_no = '".$ref_no."'";
    $ref_noSql = " AND reference_no LIKE '%" . $ref_no . "%'";

}
else {
    $ref_noSql = "";
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
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Expense Date');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Expense Category');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Due Date');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Amount');
//$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Amount');
//$objPHPExcel->getActiveSheet()->setCellValue('G2', 'GSTIN');
//$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Address 1');
//$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Address 2');
//$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Place of supply');



$i = 0;

if($e_category == "" && $s_name== "" && $pay_mode == "" && $ref_no=="") {
    $sql = "SELECT * FROM expense WHERE expense_date  BETWEEN '$from_date' AND '$to_date' ORDER BY id DESC";
}else
{
    $sql = "SELECT * FROM expense WHERE expense_date  BETWEEN '$from_date' AND '$to_date' $e_categorySql$s_nameSql$pay_modeSql$ref_noSql ORDER BY id DESC";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result)>0) {
    $sNo = $i;
    while($row = mysqli_fetch_assoc($result)) {

        $sNo++;
        $amount = $row['amount'];
        $e_date = $row['expense_date'];
        $expence_date = date('d-m-Y', strtotime($e_date));
        $d_date = $row['due_date'];
        $due_date = date('d-m-Y', strtotime($d_date));
        $expense_type=$row['expense_type'];

        $sqlExpenseType = "SELECT * FROM `expense_category` WHERE `category_id`='$expense_type'";
        $resExpenseType = mysqli_query($conn, $sqlExpenseType);
        $rowExpenseType = mysqli_fetch_assoc($resExpenseType);
        $ExpenseType =  $rowExpenseType['category_name'];

            $i++;

        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2) , ($i));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2) , ($expence_date));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2) , ($ExpenseType));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i + 2) ,$due_date);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i + 2) ,$amount);
//        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i + 2) ,$amount);
//        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i + 2) ,$gstin);
//        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i + 2) ,$address1);
//        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i + 2) ,$address2);
//        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i + 2) ,$supply_place);


        }

}

ob_clean();
$objPHPExcel->getActiveSheet()->setCellValue('A1'," Expense List");
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
$objWriter->save($_SERVER["DOCUMENT_ROOT"].'/expense.csv');
sleep(1);

header('Content-type: application/octet-stream');
header("Content-Disposition: attachment; filename=expense.csv");
readfile($_SERVER["DOCUMENT_ROOT"].'/expense.csv');

@unlink($_SERVER["DOCUMENT_ROOT"].'/expense.csv');



?>