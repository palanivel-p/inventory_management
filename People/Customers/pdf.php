<?php
include("../../includes/connection.php");
error_reporting(0);

// Get parameters from the URL
$page = isset($_GET['page_no']) ? $_GET['page_no'] : 1;
$c_name = isset($_GET['c_name']) ? $_GET['c_name'] : '';
$c_code = isset($_GET['c_code']) ? $_GET['c_code'] : '';
$mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Calculate start and end record for pagination
$records_per_page = 10;
$start = ($page - 1) * $records_per_page;

// Construct SQL query based on filters
$sql = "SELECT * FROM customer WHERE 1=1";

if (!empty($c_name)) {
    $sql .= " AND customer_name LIKE '%" . $c_name . "%'";
}

if (!empty($c_code)) {
    $sql .= " AND scustomer_code = '" . $c_code . "'";
}

if (!empty($mobile)) {
    $sql .= " AND customer_phone LIKE '%" . $mobile . "%'";
}

if (!empty($email)) {
    $sql .= " AND customer_email LIKE '%" . $email . "%'";
}

$sql .= " ORDER BY id LIMIT $start, $records_per_page";

// Fetch records from the database
$result = mysqli_query($conn, $sql);

// HTML for the table
$html = '<html>
<head>
    <title>Customer List</title>
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        #header {
            background-color: #1C6180;
            color: white;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Customer List</h2>
    <table>
        <thead>
            <tr id="header">
                <th>Customer Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>';

// Populate table rows with data
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>';
        $html .= '<td>' . $row['customer_name'] . '</td>';
        $html .= '<td>' . $row['customer_email'] . '</td>';
        $html .= '<td>' . $row['customer_phone'] . '</td>';
        $html .= '<td>' . $row['address1'] . '</td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="4">No records found.</td></tr>';
}

$html .= '</tbody></table></body></html>';

// Output PDF
require_once 'vendor/autoload.php'; // Include Composer's autoloader

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($html);
$html2pdf->output('Customer_List.pdf');
