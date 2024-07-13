<?php
include ('dompdf/autoload.inc.php');
Include("../includes/connection.php");
date_default_timezone_set("Asia/Kolkata");
$current_date = date('Y-m-d');
$invoice_date = date("d-m-Y", strtotime($current_date));
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$obj = new Dompdf($options);

$purchase_id = $_GET['purchase_id'];

$sqlTerm = "SELECT * FROM `purchase_term` WHERE `purchase_id`='$purchase_id'";
$resTerm= mysqli_query($conn, $sqlTerm);
$rowTerm = mysqli_fetch_assoc($resTerm);
$jsonterm = json_decode($rowTerm['jsonterm'], true);
$term='';
for($i=0;$i<sizeof($jsonterm);$i++){
    $n=$i+1;
    $term.='<p> '.$n.') '.$jsonterm[$i]['termtextarea'].'</p><br>';
}
//$formatted_terms = nl2br($term);

$sqlPurchase = "SELECT * FROM `purchase` WHERE `purchase_id`='$purchase_id'";
$resPurchase= mysqli_query($conn, $sqlPurchase);
$rowPurchase = mysqli_fetch_assoc($resPurchase);
$notes =  $rowPurchase['notes'];
$grand_total =  $rowPurchase['grand_total'];
$p_date =  $rowPurchase['purchase_date'];
$purchase_date = date("d-m-Y", strtotime($p_date));
//$term =  $rowPurchase['term_condition'];
$payment_terms =  $rowPurchase['payment_terms'];
if($payment_terms == '0'){
    $p_terms = 'immediate day';
}
elseif ($payment_terms == ''){
    $p_terms = 'NA';
}
else{
    $p_terms = $payment_terms . ' days';
}
$d_date =  $rowPurchase['due_date'];
$due_date = date("d-m-Y", strtotime($d_date));
if($due_date == '30-11--0001'){
    $dd_date = 'NA';
}
else{
    $dd_date = $due_date;
}
$m_date =  $rowPurchase['material_date'];
$material_date = date("d-m-Y", strtotime($m_date));
if($material_date == '30-11--0001'){
    $Scheduled_date = 'NA';
}
else{
    $Scheduled_date = $material_date;
}

$transport =  $rowPurchase['transport'];
$supplier_id =  $rowPurchase['supplier'];
$invoice_no =  $rowPurchase['invoice_no'];
$currency_id =  $rowPurchase['currency_id'];

$sqlcurrency = "SELECT * FROM `currency` WHERE `currency_id`='$currency_id'";
$rescurrency= mysqli_query($conn, $sqlcurrency);
$rowcurrency = mysqli_fetch_assoc($rescurrency);
$currency_name =  $rowcurrency['symbol'];

if($currency_name == '₹'){
    $currency_symbol = '&#8377;';
}
else{
    $currency_symbol = $currency_name;
}
$sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
$resSupplier= mysqli_query($conn, $sqlSupplier);
$rowSupplier = mysqli_fetch_assoc($resSupplier);
$supplier_name =  $rowSupplier['supplier_name'];
$palceof_supply =  $rowSupplier['supply_place'];
$billto =  $rowSupplier['address1'];
$shipto =  $rowSupplier['address2'];
$supplier_gstin =  $rowSupplier['gstin'];
$supplier_email =  $rowSupplier['supplier_email'];
$supplier_phone =  $rowSupplier['supplier_phone'];

$sqlCompany = "SELECT * FROM `company_profile`";
$resCompany= mysqli_query($conn, $sqlCompany);
$rowCompany = mysqli_fetch_assoc($resCompany);
$bank_name =  $rowCompany['bank_name'];
$account_name =  $rowCompany['account_name'];
$account_no =  $rowCompany['account_no'];
$ifsc_code =  $rowCompany['ifsc_code'];
$branch_name =  $rowCompany['branch_name'];
$address =  $rowCompany['address'];
$email =  $rowCompany['email'];
$phone =  $rowCompany['phone'];
$company_name =  $rowCompany['company_name'];
$gstin =  $rowCompany['gstin'];
$state_name =  $rowCompany['state_name'];

$sqlamount="SELECT SUM(pay_made) AS pay_made  FROM purchase_payment WHERE purchase_id='$purchase_id'";
$resamount=mysqli_query($conn,$sqlamount);
if(mysqli_num_rows($resamount)>0){
    $arrayamount=mysqli_fetch_array($resamount);
    $totalAmountS=$arrayamount['pay_made'];
}
if($totalAmountS == ''){
    $totalAmountSS =0;
}
else{
    $totalAmountSS = $totalAmountS;
}
$made= numberFormat($totalAmountSS, 2);
$balance_amount= $grand_total - $totalAmountSS;
$bal= numberFormat($balance_amount, 2);

$numberIntoWords = getIndianCurrency($grand_total);
function getIndianCurrency($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

function numberFormat($number, $decimals=0)
{
    if (strpos($number,'.')!=null)
    {
        $decimalNumbers = substr($number, strpos($number,'.'));
        $decimalNumbers = substr($decimalNumbers, 1, $decimals);
    }
    else
    {
        $decimalNumbers = 0;
        for ($i = 2; $i <=$decimals ; $i++)
        {
            $decimalNumbers = $decimalNumbers.'0';
        }
    }


    $number = (int) $number;
    $number = strrev($number);  // reverse
    $n = '';
    $stringlength = strlen($number);

    for ($i = 0; $i < $stringlength; $i++)
    {
        // from digit 3, every 2 digit () add comma
        if($i==2 || ($i>2 && $i%2==0) ) $n = $n.$number[$i].',';
        else $n = $n.$number[$i];
    }
    $number = $n;
    $number = strrev($number); // reverse
    ($decimals!=0)? $number=$number.'.'.$decimalNumbers : $number ;
    if ($number[0] == ',') $number = substr_replace($number, '', 0, 1);
    if ($number[1] == ',' && $number[0] == '-') $number = substr_replace($number, '', 1, 1);

    return $number;
}
$g= numberFormat($grand_total, 2);

$data= '<html>
<head>
    <title>PDF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap-grid.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Yesteryear&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <style>
    body{
    font-size: 12px;
    }
        .table-container {
            overflow-x: auto;
            margin-bottom: 2px;
            font-family: Arial;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .heading-bg{
            background-color: #8a8a8a;
        }
        th{
            text-align: center;
            font-weight: bold;
        }
         .sat {
        display: table-row-group;
    }
            .sathish{
            page-break-inside: avoid;
            display: table-row;
            }    
      
        @media screen and (max-width: 600px) {
            th, td {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>

<body  style="border: 1px solid black">
<div id="printPdf">
    <div class="table-container">
        <table style="background-color: #b5b5b5;text-align: center">
            <tbody>
            <tr>
                <td style="text-align: center"><img src="https://erp.aecindia.net/purchase/AEC.png" height="80px"></td>
                <td style="width:50%"> <strong>Associated Engineering Company</strong>
                <p>S.F. No.116/3 (b2) .Annur road,Arasur Village, <br>
                Sulur Taluk ,Coimbatore- 641407<br>
                Email : '.$email.'<br>
                 Mobile : '.$phone.'<br>
                GST No: '.$gstin.'</p>
                </td>
                <td style="width:20%;text-align: center"><strong>Purchases Order</strong></td>
            </tr>
             </tbody>
        </table>
        <table>
        <tbody>
              <tr>
                <td style="width:50%;text-align: left">To Address</td>
                <td style="width:25%;text-align: left">PO Number</td>
                <td style="width:25%;text-align: left">'.$purchase_id.'</td>
              </tr>
                <tr>
                <td style="width:50%;text-align: left" rowspan="5">Name: '. $supplier_name.'<br>
                Address: '.$billto.'<br>Email: '.$supplier_email.'<br>Mobile: '.$supplier_phone.'<br>GSTIN:'.$supplier_gstin.'
                </td>
                <td style="width:25%;text-align: left">PO Date</td>
                <td style="width:25%;text-align: left">'. $purchase_date.'</td>
              </tr>
              <tr>
                <td style="width:25%;text-align: left">Payment Terms</td>
                <td style="width:25%;text-align: left">'. $p_terms.'</td>
               
                </tr>
              <tr>
                <td style="width:25%;text-align: left">Due Date</td>
                <td style="width:25%;text-align: left">'. $dd_date.'</td>
                </tr>
            <tr>
                <td style="width:25%;text-align: left">Scheduled Date</td>
                <td style="width:25%;text-align: left">'. $Scheduled_date.'</td>
                </tr>
              <tr>
                  <td style="width:25%;text-align: left">Place Of Supply</td>
                <td style="width:25%;text-align: left">'. $palceof_supply.'</td></tr>

           
            </tbody>
        </table>
  <h4 style="margin-left: 20px">Dear Sir,<br><br>
        <span style="margin-left: 40px"> Kindly supply the following material as per the terms and conditions mentioned below.</span></h4>
        <table>
        <tbody>
           <tr style="background-color: #ffab7d">
          <th rowspan="2" style="width: 3%">S.No</th>
            <th rowspan="2" style="width: 15%">Product Name</th>
            <th rowspan="2" style="width: 9%">HSN Code</th>           
            <th rowspan="2" style="width: 7%">QTY</th>
            <th rowspan="2" style="width: 13%">Rate</th>
            <th colspan="2" style="width: 19%">Discount</th>           
            <th colspan="2" style="width: 20%">Tax</th> <!-- Removed style attribute -->
            <th rowspan="2" style="width: 14%">Total</th>
            </tr>
            
            <tr style="background-color: #ffab7d">
            <th>%</th> <!-- Adjusted width -->
            <th>Value</th> <!-- Adjusted width -->
            <th>%</th> <!-- Adjusted width -->
            <th>Value</th> <!-- Adjusted width -->
            </tr>
';

            $sql = "SELECT * FROM purchase_details WHERE purchase_id = '$purchase_id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result)>0) {
            $sNo = 0;
            $totalDiscountValue = 0; // Initialize discount value accumulator
            $totalTaxValue = 0;
            $totalAmount = 0;
            while($row = mysqli_fetch_assoc($result)) {
                $sNo++;
                $product_id = $row['product_id'];
                $productDesc = $row['productDesc'];
                $pro_name = $row['product_name'];
                $pv = explode("/", $pro_name);
                $p_name= $pv[0]; // piece1
                $pv_name= $pv[1];
                if($pv_name != ''){
                    $product_varient = $pv_name;
                }
                else{
                    $product_varient = $pro_name;
                }
                $unit_cost = $row['unit_cost'];
                $qty = $row['qty'];
                $discount = $row['discount'];
                $dis_symbl = $row['dis_symbl'];
                $discount_value = $row['discount_value'];
                $dv= numberFormat($discount_value,2);
                $tax = $row['tax'];
                $a = explode('%',$tax);
                $cgst = $a[0];
                $sgst = $a[1];
                $tax_value = $row['tax_value'];
                $tv= numberFormat($tax_value,2);
                $sub_total = $row['sub_total'];
                $st= numberFormat($sub_total,2);
//                $total = $qty * $unit_cost;

                $totalDiscountValue += $discount_value;
                $dis= numberFormat($totalDiscountValue,2);
                $totalTaxValue += $tax_value;
                $t= numberFormat($totalTaxValue,2);
                $totalAmount += $sub_total;
                $sub= numberFormat($totalAmount,2);

                $sqlProduct = "SELECT * FROM `product` WHERE `product_id`='$product_id'";
                $resProduct= mysqli_query($conn, $sqlProduct);
                $rowProduct = mysqli_fetch_assoc($resProduct);

                $product_varient =  $rowProduct['product_varient'];
                $product_name =  $rowProduct['product_name'];
                if($product_varient != ''){
                    $p_name = $rowProduct['product_varient'];
                }
                else{
                    $p_name = $rowProduct['product_name'];
                }
                $hsn_code =  $rowProduct['hsn_code'];
                $product_unit =  $rowProduct['product_unit'];

                $data .= '
            <tr>
            <td style="text-align: center;">'. $sNo.'</td>
            <td style="text-align: center;">'. $p_name.'<br>'.$productDesc.'</td>
            <td style="text-align: center;">'. $hsn_code.'</td>
            <td style="">'. $qty.'- '.$product_unit.'</td>
            <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'. $unit_cost.'</td>
            <td>'. $discount.' '.$dis_symbl.'</td>
            <td><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'. $dv.'</td>
            <td>'. $tax.'</td>
            <td><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'. $tv.'</td>
            <td><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'. $st.'</td>
            </tr>
          
            ';
            }
            }
            $data .= '
               </tbody>
        </table>
     
            ';
if ((substr($tax, 0, 3) === 'CGS')){
    if($sNo == 7 || $sNo == 5 || $sNo == 6 || $sNo == 4){
        $data .= '<div style="page-break-before: always;"></div>';
    }
}
else if ((substr($tax, 0, 3) === 'IGS')){
    if($sNo == 8 || $sNo == 9 || $sNo == 10 || $sNo == 11){
        $data .= '<div style="page-break-before: always;"></div>';
    }
}
$data .= '
            <table>
            <tbody class="sat">  
           
            <tr>
            <th colspan="8">Grand Total In Words</th>
            <td colspan="1">Sub Total</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$sub.'</td>
            </tr>
          
           ';

if (substr($tax, 0, 3) === 'IGS'){
    $data .= '
           <tr class="sathish">
            <th colspan="8" rowspan="5">'.$numberIntoWords.'</th>
            <td colspan="1">Discount value (-)</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$dis.'</td>
            </tr>
            
        <tr class="sathish">                
            <td colspan="1">Tax ('.$tax.') (+)</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$t.'</td>
        </tr>
        
          <tr class="sathish">
            <td colspan="1">Grand Total</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$g.'</td>
            </tr>
            
            <tr class="sathish">
            <td colspan="1">Payment Made</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$made.'</td>
            </tr>
            
             <tr class="sathish">
            <td colspan="1" style="font-weight: bold">Balance Due</td>
            <td colspan="2" style="text-align: right;font-weight: bold"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$bal.'</td>
            </tr>
        ';
} else {
    $data .= '
            <tr class="sathish">
            <th colspan="8" rowspan="6">'.$numberIntoWords.'</th>
            <td colspan="1">Discount value (-)</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$dis.'</td>
            </tr>
        <tr class="sathish">        
            <td colspan="1">Tax ('.$cgst.'%) (+)</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.($t/2).'</td>
        </tr>
        <tr class="sathish">         
            <td colspan="1">Tax ('.$sgst.'%) (+)</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.($t/2).'</td>
        </tr>
        
          <tr class="sathish">
            <td colspan="1">Grand Total</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$g.'</td>
            </tr>
            
            <tr class="sathish">
            <td colspan="1">Payment Made</td>
            <td colspan="2" style="text-align: right"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$made.'</td>
            </tr>
            
             <tr class="sathish">
            <td colspan="1" style="font-weight: bold">Balance Due</td>
            <td colspan="2" style="text-align: right;font-weight: bold"><span style="font-family: DejaVu Sans; sans-serif;">'.$currency_symbol.'</span>'.$bal.'</td>
            </tr>
        
        
        ';
}


$data .= '
           
            </tbody>
        </table>

        <h3 style="padding-left: 10px">Thanks for your business.</h3>
        <h3 style="padding-left: 10px">Notes: '.$notes.'</h3>
          
        <div style="padding: 10px">
        <h3>TERMS AND CONDITIONS</h3>
        <p>1. All disputes on Associated Engineering Company are subject to Coimbatore Jurisdiction only. </p>
        <p>2. All materials are exactly as per the specifications and will be subject to our 100% inspection and approval at any time within 45days after delivery.</p>
        <p>When any rejections are assessed at the time of primary inspection the total quantity is liable to rejected. </p>
        <p>3. Associated Engineering Company reserves the right to cancel or amend this or this order of any part thereof without assigning any reason before delivery of material. </p>
        <p>4. All the materials in this order should be supplied within the specified schedule date of delivery. </p>
        <p>5. If this order is not executed within the specified period or time or the materials supplied is not of the contract quality or not according to the specifications required by the Associated Engineering Company, the Associated Engineering Company will be entitled to reject the materials and treat the order as cancelled and buy its requirement in the Open market on suppliers account. The rejected materials should be removed immediately from the Associated Engineering Company by this supplier on his risk and responsibility. </p>
        <p>6. ln the event of the production at any of our works interfered with breakdown or other circumstances beyond the control  Associated Engineering Company of, the Associated Engineering Company reserves the right to defer the delivery period of the order or to cancel as it #considers necessary without incurring liability. </p>
        <p>7. Inspection are at our site unless otherwise specified. </p>
        <p>8. Supplies should accompany report Test Certificate / Test bar. </p>
        <p>9. If the goods are rejected by us as non-conformance, the goods are returned to the supplier the proportionate post of freight. Loading & unloading and any other charges incidental there to should be borne by the supplier. </p>
        <p>10. Where a part of the supplies are rejected as non-conformance the Associated Engineering Company has the right to pass the bills of the supplier after deducting the value of rejected supplies. Proportionate freight and other charges etc., </p>
        <p>11. If the goods are not delivered as per the order the Associated Engineering Company will have the option not to accept the goods. If goods transportation is on supplier scope any loss, shortage, damage ,insurance is all includes on supplier scope. </p>
        <p>12. If supplies are made against documents retired through banks are rejected in whole or in part by the Associated Engineering Company. The supplier should effect payment of the value thereof. </p>
        <p>13. The supplier shall dispatch all supplies through the authorized carriers of the Associated Engineering Company </p>
        <p>14. If instructions are given to the carriers for door delivery and the carriers do not effect door delivery proportionate freight and other incidental charges that may be paid by the Associated Engineering Company shall be to the suppliers account. </p>
        <p>15. If the supplier shall not follow the sales tax regulations consequently it the Associated Engineering Company has to pay any penalty or other expenses for loading, unloading and 
Check-posts, the supplier should make such loss sustained by the Associated Engineering Company. </p>   
        <p>16. If documents sent through bank are not received in time and the Associated Engineering Company is called upon to pay interest, dermurrage and any other expenses incidental there to, such expenses are to the suppliers account. </p>  
        <p>17. The Associated Engineering Company has the right to insure the goods himself If the Associated Engineering Company does not insure he may ask the supplier to effect the insurance. </p>   
        <p>18. The Associated Engineering Company shall be entitled to a general lien on the goods in his possession under this contract for any monies for the time being the due to the  Associated Engineering Company from the supplier. </p>
        <p>19. The above terms-must not be altered or varied without prior permission in writing. </p>
        <p>20. Associated Engineering Company to recommend to follow the legal & other applicable requirements, Code of conduct (COC), Employee safety PPE adherence & Prevention of environmental pollution by suppliers. </p>
        </div>
        <div style="padding: 10px">
        '.$term.'
        </div>

    </div>
   
</div>

</body>
</html>';

$obj->loadHTML($data);
$obj->render();
$output = $obj->output();
file_put_contents('../purchase_invoice/'.$purchase_id.'.pdf', $output);
// Send appropriate headers to force download
header('Content-Type: application/pdf');
//header('Content-Disposition: attachment; filename="Purchase-Invoice.pdf"'); // Change the filename as needed

header('Content-Disposition: attachment; filename="' . $purchase_id . '.pdf"');


//readfile($_SERVER["DOCUMENT_ROOT"].'/purchase_invoice/'.$purchase_id.'.pdf');

//header('Content-Length: ' . strlen($output));

// Output the PDF content
echo $output;
?>
