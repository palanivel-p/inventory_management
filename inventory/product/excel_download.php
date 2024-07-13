<?php
require_once '../../includes/excel_generator/PHPExcel.php';
Include("../../includes/connection.php");

$p_name= $_GET['p_name'];
$p_code= $_GET['p_code'];
$s_category= $_GET['s_category'];
$p_category= $_GET['p_category'];
$brand= $_GET['brand'];

if($p_name != ""){
    $pNameSql= " AND product_name = '".$p_name."'";

}
else{
    $pNameSql ="";
}

if($p_code != ""){
    $pCodeSql= " AND product_code = '".$p_code."'";

}
else{
    $pCodeSql ="";
}

if($s_category != ""){
    $categorySql= " AND sub_category = '".$s_category."'";

}
else{
    $categorySql ="";
}
if($p_category != ""){
    $pcategorySql= " AND primary_category = '".$p_category."'";

}
else{
    $pcategorySql ="";
}

if($brand != ""){
    $brandSql= "AND brand_type = '".$brand."'";

}
else {
    $brandSql = "";
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

$objPHPExcel->getActiveSheet()->mergeCells('A1:R1');
//$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');



$objPHPExcel->getActiveSheet()->setCellValue('A2', '#');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Product Id');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Product Code');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Product Name');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'HSN / SAC Code');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Primary Category');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Sub Category');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Brand Type');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Stock');
$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Product Cost');
$objPHPExcel->getActiveSheet()->setCellValue('K2', 'Product Price');
//$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Product unit');
//$objPHPExcel->getActiveSheet()->setCellValue('M2', 'Sales Unit');
$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Purchase Unit');
$objPHPExcel->getActiveSheet()->setCellValue('M2', 'Stock Alert');
$objPHPExcel->getActiveSheet()->setCellValue('N2', 'Order Tax');
$objPHPExcel->getActiveSheet()->setCellValue('O2', 'Tax Type');
$objPHPExcel->getActiveSheet()->setCellValue('P2', 'Description');
$i = 0;

if($brand == "" && $p_code == "" && $s_category== "" && $p_category == ""&& $p_name == "") {
    $sql = "SELECT * FROM product";
}
else {
    $sql = "SELECT * FROM product WHERE id > 0 $brandSql$categorySql$pCodeSql$pNameSql$pcategorySql";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result)>0) {
    $sNo = $i;
    while($row = mysqli_fetch_assoc($result)) {

        $sNo++;


        $product_name =  $row['product_name'];
        $product_id =  $row['product_id'];
        $product_code =  $row['product_code'];
        $hsn_code =  $row['hsn_code'];
        $primary_category =  $row['primary_category'];
        $sub_category =  $row['sub_category'];
        $product_price =  $row['product_price'];
        $brand_type=  $row['brand_type'];
        $stock_qty=  $row['stock_qty'];
        $product_cost =  $row['product_cost'];
//        $product_price =  $row['product_price'];
        $product_unit =  $row['product_unit'];
        $sales_unit =  $row['sales_unit'];
        $purchase_unit =  $row['purchase_unit'];
        $stock_alert =  $row['stock_alert'];
        $order_tax =  $row['order_tax'];
        $tax_type =  $row['tax_type'];
        $description =  $row['description'];

        $sqlSubCategory = "SELECT * FROM `category` WHERE `category_id`='$sub_category'";
        $resSubCategory = mysqli_query($conn, $sqlSubCategory);
        $rowSubCategory = mysqli_fetch_assoc($resSubCategory);
        $SubCategory =  $rowSubCategory['sub_category'];

        $sqlBrand = "SELECT * FROM `brand` WHERE `brand_id`='$brand_type'";
        $resBrand = mysqli_query($conn, $sqlBrand);
        $rowBrand = mysqli_fetch_assoc($resBrand);
        $brand_name =  $rowBrand['brand_name'];

            $i++;

        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2) , ($i));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2) , ($product_id));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2) , ($product_code));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i + 2) , ($product_name));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i + 2) ,$hsn_code);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i + 2) ,$primary_category);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i + 2) ,$SubCategory);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i + 2) ,$brand_name);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i + 2) ,$stock_qty);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i + 2) ,$product_cost);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i + 2) ,$product_price);
        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i + 2) ,$product_unit);
//        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i + 2) ,$sales_unit);
//        $objPHPExcel->getActiveSheet()->setCellValue('N' . ($i + 2) ,$purchase_unit);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i + 2) ,$stock_alert);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . ($i + 2) ,$order_tax);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . ($i + 2) ,$tax_type);
        $objPHPExcel->getActiveSheet()->setCellValue('P' . ($i + 2) ,$description);



        }

}

ob_clean();
$objPHPExcel->getActiveSheet()->setCellValue('A1'," Product List");
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

cellColor('A2:R2', '#181f5a');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:R2')
    ->getFont()
    ->getColor()
    ->setRGB('FFFFFF');

$objPHPExcel->getActiveSheet()
    ->getStyle('A2:R2')
    ->getFont()
    ->setSize(12)
    ->setBold(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($_SERVER["DOCUMENT_ROOT"].'/product.csv');
sleep(1);

header('Content-type: application/octet-stream');
header("Content-Disposition: attachment; filename=product.csv");
readfile($_SERVER["DOCUMENT_ROOT"].'/product.csv');

@unlink($_SERVER["DOCUMENT_ROOT"].'/product.csv');



?>