<?php
include ('../includes/dompdf/autoload.inc.php');
Include("../includes/connection.php");
date_default_timezone_set("Asia/Kolkata");
$current_date = date('Y-m-d');
$invoice_date = date("d-m-Y", strtotime($current_date));
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$obj = new Dompdf($options);

$purchase_id = $_POST['pr_id'];
$email = $_POST['email'];
$email_seperate = explode(',', $email);
$email1 = $email_seperate[0];
$email2 = $email_seperate[1];

$sqlPurchase = "SELECT * FROM `purchase` WHERE `purchase_id`='$purchase_id'";
$resPurchase= mysqli_query($conn, $sqlPurchase);
$rowPurchase = mysqli_fetch_assoc($resPurchase);
$grand_total =  $rowPurchase['grand_total'];
$p_date =  $rowPurchase['purchase_date'];
$purchase_date = date("d-m-Y", strtotime($p_date));
$payment_terms =  $rowPurchase['payment_terms'];
$d_date =  $rowPurchase['due_date'];
$due_date = date("d-m-Y", strtotime($d_date));
$material =  $rowPurchase['material'];
$transport =  $rowPurchase['transport'];
$supplier_id =  $rowPurchase['supplier'];
$invoice_no =  $rowPurchase['invoice_no'];
$currency_id =  $rowPurchase['currency_id'];

$sqlcurrency = "SELECT * FROM `currency` WHERE `currency_id`='$currency_id'";
$rescurrency= mysqli_query($conn, $sqlcurrency);
$rowcurrency = mysqli_fetch_assoc($rescurrency);
$currency_name =  $rowcurrency['symbol'];

$sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
$resSupplier= mysqli_query($conn, $sqlSupplier);
$rowSupplier = mysqli_fetch_assoc($resSupplier);
$supplier_name =  $rowSupplier['supplier_name'];
$palceof_supply =  $rowSupplier['supply_place'];
$billto =  $rowSupplier['address1'];
$shipto =  $rowSupplier['address2'];
$gstin =  $rowSupplier['gstin'];

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
    $totalAmount=$arrayamount['pay_made'];
}
$balance_amount= $grand_total - $totalAmount;

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
                <td style="text-align: center"><img src="<?php echo $website; ?>/purchase/AEC.png" height="80px"></td>
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
                <td style="width:50%;text-align: left">Address</td>
                <td style="width:25%;text-align: left">PO Number</td>
                <td style="width:25%;text-align: left">'.$purchase_id.'</td>
              </tr>
                <tr>
                <td style="width:50%;text-align: left" rowspan="6">'. $company_name.'<br>
                '.$address.'<br>Email: '.$email.'<br>Mobile: '.$phone.'<br>GSTIN:'.$gstin.'
                </td>
                <td style="width:25%;text-align: left">PO Date</td>
                <td style="width:25%;text-align: left">'. $purchase_date.'</td>
              </tr>
              <tr>
                <td style="width:25%;text-align: left">Payment Terms</td>
                <td style="width:25%;text-align: left">'. $payment_terms.'days</td>
                </tr>
              <tr>
                <td style="width:25%;text-align: left">Due Date</td>
                <td style="width:25%;text-align: left">'. $due_date.'</td>
                </tr>
              <tr>
                 <td style="width:25%;text-align: left">Material Despatch</td>
                <td style="width:25%;text-align: left">'. $material.'</td>
                </tr>
              <tr>
                  <td style="width:25%;text-align: left">Place Of Supply</td>
                <td style="width:25%;text-align: left">'. $state_name.'</td></tr>
              <tr>
                <td style="width:25%;text-align: left">Transport</td>
                <td style="width:25%;text-align: left">'. $transport.'</td>
                </tr>
           
            </tbody>
        </table>
  
        <table>
        <tbody>
            <tr style="background-color: #ffab7d">
            <th>S.No</th>
            <th>Product Name</th>
            <th>HSN Code</th>
         
            <th>QTY</th>
            <th>Product Rate</th>
            <th>Discount (%)</th>
            <th>Discount Value</th>
            <th>Tax (%)</th>
            <th>Tax Value</th>
            <th>Total</th>
            </tr>';

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
        $unit_cost = $row['unit_cost'];
        $qty = $row['qty'];
        $discount = $row['discount'];
        $discount_value = $row['discount_value'];
        $tax = $row['tax'];
        $tax_value = $row['tax_value'];
        $sub_total = $row['sub_total'];
        $total = $qty * $unit_cost;

        $totalDiscountValue += $discount_value;
        $totalTaxValue += $tax_value;
        $totalAmount += $total;

        $sqlProduct = "SELECT * FROM `product` WHERE `product_id`='$product_id'";
        $resProduct= mysqli_query($conn, $sqlProduct);
        $rowProduct = mysqli_fetch_assoc($resProduct);
        $product_name =  $rowProduct['product_name'];
        $hsn_code =  $rowProduct['hsn_code'];
        $product_unit =  $rowProduct['product_unit'];

        $data .= '
            <tr>
            <td>'. $sNo.'</td>
            <td>'. $product_name.'</td>
            <td>'. $hsn_code.'</td>
            <td>'. $qty.'- '.$product_unit.'</td>
            <td>₹'. $unit_cost.'</td>
            <td>'. $discount.'</td>
            <td>₹'. $discount_value.'</td>
            <td>'. $tax.'</td>
            <td>₹'. $tax_value.'</td>
            <td>₹'. $total.'</td>
            </tr>
          
            ';
    }
}
$data .= '
               </tbody>
        </table>
            ';
$data .= '
            <table>
            <tbody>  
           
            <tr>
            <th colspan="8">Grand Total In Words</th>
            <td colspan="1">Sub Total</td>
            <td colspan="2" style="text-align: right">₹'.$totalAmount.'</td>
            </tr>
            <tr>
            <th colspan="8" rowspan="2">'.$numberIntoWords.'</th>
            <td colspan="1">Discount value</td>
            <td colspan="2" style="text-align: right">(-)₹'.$totalDiscountValue.'</td>
            </tr>
             <tr>
           
            <td colspan="1">Tax ('.$tax.')</td>
            <td colspan="2" style="text-align: right">(+)₹'.$totalTaxValue.'</td>
            </tr>
            <tr>
            <td colspan="8" rowspan="3" style="text-align: left; vertical-align: top;line-height: 1.6">
            <span style="font-weight: bold">Bank Details</span><br>
            Bank Name:<span style="margin-left: 10px">'.$bank_name.'</span><br>
            Account Name :<span style="margin-left: 5px">'.$account_name.'</span><br>
            Account No:<span style="margin-left: 10px">'.$account_no.'</span><br>
            IFSC Code :<span style="margin-left: 10px">'.$ifsc_code.'</span><br>
            Branch Name:<span style="margin-left: 5px">'.$branch_name.'</span>
            </td>
            <td colspan="1">Grand Total</td>
            <td colspan="2" style="text-align: right">₹'. $grand_total.'</td>
            </tr>
            <tr>
    
            <td colspan="1">Payment Made</td>
            <td colspan="2" style="text-align: right">₹'.$balance_amount.'</td>
            </tr>
            
             <tr>
            <td colspan="1">Balance Due</td>
            <td colspan="2" style="text-align: right">₹'.$totalAmount.'</td>
            </tr>
            
            
            </tbody>
        </table>

        <h4 style="padding-left: 10px">Thanks for your business.</h4>
        
          
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
    </div>
   
</div>

</body>
</html>';

$obj->loadHTML($data);
$obj->render();
$output = $obj->output();
file_put_contents($purchase_id.'.pdf', $output);
// Send appropriate headers to force download
//header('Content-Type: application/pdf');
//header('Content-Disposition: attachment; filename="Purchase-Invoice.pdf"'); // Change the filename as needed
//readfile($_SERVER["DOCUMENT_ROOT"].'/purchase_invoice/'.$purchase_id.'.pdf');

//header('Content-Length: ' . strlen($output));

// Output the PDF content
//echo $output;
//sleep(30);
            // Email Integration

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if (isset($_POST['email'])) {
    $name = 'test';
    $email = $_POST['email'];
    $number = 'test';
    $subject = 'test';

$smtpUsername = "finance@bcsglobal.in";
$smtpPassword = "Bcsg@2023";

$emailFrom = "finance@bcsglobal.in";
$emailFromName = "AEC";

//$emailTo = "enquiry@bvipl.uk";
$emailTo = "velumsd2109@gmail.com";
$emailToName = "AEC";

//    $message = $_POST['message'];


    $mail = new PHPMailer;
    $mail->isSMTP();
//   $mail->SMTPDebug = 2; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
    $mail->Host = "smtp.hostinger.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
    $mail->Port = 587; // TLS only
    $mail->SMTPSecure = 'tls'; // ssl is depracated
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
    $mail->setFrom($emailFrom, $emailFromName);
    $mail->AddCC('velumsd2109@gmail.com');

    $mail->addAddress($emailTo, $emailToName);
    $mail->Subject = 'New Enquiry Details From AEC website';
    // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
    $website = "https://erp.aecindia.net/";
    $url = $website . "email/mail.php?name=$name&email=$email&number=$number&subject=$subject";

    $url = str_replace(" ", "%20", $url);
    $mail->msgHTML(file_get_contents($url));

//
//    $file_to_attach = 'https://erp.aecindia.net/purchase_invoice/';
//    $mail->AddAttachment( $file_to_attach , 'PR002.pdf' );
    $mail->AddAttachment( $purchase_id.'.pdf' );


//    if (file_exists($pdfFilePath)) {
//        $mail->AddAttachment($pdfFilePath);
//    } else {
//        echo "PDF file not found at: " . $pdfFilePath;
//    }
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        // echo "Message sent!";
    }

    $json_array['status'] = "success";
    $json_array['msg'] = "Thank You For Contacting Us, Our Team Will Reach You Soon!!";
    $json_response = json_encode($json_array);
    echo $json_response;
} else {
    //parameters missing
    $json_array['status'] = "failure";
    $json_array['msg'] = "failures";
    $json_response = json_encode($json_array);
    echo $json_response;
// isset($_GET['name'])&&isset($_GET['email'])&&isset($_GET['number'])&& isset($_GET['subject']) && isset($_GET['message'])
}

?>
