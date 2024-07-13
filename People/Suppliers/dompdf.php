<?php
include ('dompdf/autoload.inc.php');
include ("../../includes/connection.php");

use Dompdf\Dompdf;
use Dompdf\Options;

date_default_timezone_set("Asia/Kolkata");
$current_date = date('Y-m-d');
$invoice_date = date("d-m-Y", strtotime($current_date));

$options = new Options();
$options->set('isRemoteEnabled', true);

$obj = new Dompdf($options);
$page = isset($_GET['page_no']) ? $_GET['page_no'] : 1;
$s_name = isset($_GET['s_name']) ? $_GET['s_name'] : '';
$s_code = isset($_GET['s_code']) ? $_GET['s_code'] : '';
$mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

$pageSql = $page - 1;
$start = $pageSql * 10;
$end = ($pageSql == 0) ? 10 : $start + 10;

$sNameSql = ($s_name != "") ? " AND supplier_name LIKE '%" . $s_name . "%'" : "";
$sCodeSql = ($s_code != "") ? " AND supplier_code LIKE '%" . $s_code . "%'" : "";
$mobileSql = ($mobile != "") ? " AND supplier_phone LIKE '%" . $mobile . "%'" : "";
$emailSql = ($email != "") ? " AND supplier_email LIKE '%" . $email . "%'" : "";

$added_by = $_COOKIE['user_id'];
$addedBy = ($_COOKIE['role'] == 'Super Admin') ? "" : " AND added_by='$added_by'";

$data = '<html>
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
        body {
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
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .heading-bg {
            background-color: #8a8a8a;
        }
        th {
            text-align: center;
            font-weight: bold;
        }
        .sat {
            display: table-row-group;
        }
        .sathish {
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
<body style="border: 1px solid black">
<div id="printPdf">
    <div class="table-container">
        <table>
        <thead>
           <tr style="background-color: #ffab7d">
            <th>S.No</th>
            <th>Supplier Name</th>
            <th>Mobile</th>           
            <th>Email</th>
            <th>Address</th>
           </tr> 
        </thead>
        <tbody>';

$sNo = $start + 1;  // Initialize $sNo
if ($email == "" && $mobile == "" && $s_name == "" && $s_code == "") {
    $sql = "SELECT * FROM supplier WHERE id > 0 $addedBy ORDER BY id DESC LIMIT $start, 10";
} else {
    $sql = "SELECT * FROM supplier WHERE id > 0 $emailSql $mobileSql $sNameSql $sCodeSql $addedBy ORDER BY id DESC LIMIT $start, 10";
}
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $supplier_name = $row['supplier_name'];
        $supplier_email = $row['supplier_email'];
        $supplier_phone = $row['supplier_phone'];
        $address1 = $row['address1'];

        $data .= '
            <tr>
            <td style="text-align: center;">' . $sNo . '</td>
            <td style="text-align: center;">' . $supplier_name . '</td>
            <td style="text-align: center;">' . $supplier_phone . '</td>
            <td style="text-align: center;">' . $supplier_email . '</td>
            <td style="text-align: center;">' . $address1 . '</td> 
            </tr>';
        $sNo++;
    }
}

$data .= '
        </tbody>
        </table>     
    </div>
</div>
</body>
</html>';

$obj->loadHTML($data);
$obj->render();
$output = $obj->output();
file_put_contents('../purchase_invoice/' . $supplier_phone . '.pdf', $output);
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $supplier_phone . '.pdf"');
echo $output;
?>
