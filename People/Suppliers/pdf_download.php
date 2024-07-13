<?php
include("../../includes/connection.php");

error_reporting(0);
date_default_timezone_set("Asia/kolkata");
$s_name = $_GET['s_name'];
$s_code = $_GET['s_code'];
$mobile = $_GET['mobile'];
$email = $_GET['email'];
$page = $_GET['page'];

if ($page == '') {
    $page = 1;
}

$pageSql = $page - 1;
$start = $pageSql * 10;
$end = $start;

if ($pageSql == 0) {
    $end = 10;
}

$sNameSql = $s_name != "" ? " AND supplier_name LIKE '%" . $s_name . "%'" : "";
$sCodeSql = $s_code != "" ? " AND supplier_code LIKE '%" . $s_code . "%'" : "";
$mobileSql = $mobile != "" ? " AND supplier_phone LIKE '%" . $mobile . "%'" : "";
$emailSql = $email != "" ? " AND supplier_email LIKE '%" . $email . "%'" : "";

$added_by = $_COOKIE['user_id'];
if ($_COOKIE['role'] == 'Super Admin') {
    $addedBy = "";
} else {
    $addedBy = " AND added_by='$added_by'";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier List PDF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap-grid.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
            word-wrap: break-word;
            /*page-break-inside: avoid;*/
        }
        th {
            background-color: #1C6180;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #dddada;
        }
        .supplier-name {
            width: 20%;
        }
        .email {
            width: 25%;
        }
        .phone {
            width: 15%;
        }
        .address {
            width: 40%;
        }
    </style>
</head>
<body id="example">
<h2 style="text-align: center">Supplier List</h2>
<table id="example">
    <thead>
    <tr id="header">
        <th class="supplier-name">Supplier Name</th>
        <th class="email">Email</th>
        <th class="phone">Phone</th>
        <th class="address">Address</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($email == "" && $mobile == "" && $s_name == "") {
        $sql = "SELECT * FROM supplier ORDER BY id DESC LIMIT $start, $end";
    } else {
        $sql = "SELECT * FROM supplier WHERE id > 0 $emailSql$mobileSql$sNameSql$sCodeSql ORDER BY id DESC LIMIT $start, $end";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $supplier_name = $row['supplier_name'];
            $supplier_email = $row['supplier_email'];
            $supplier_phone = $row['supplier_phone'];
            $address1 = $row['address1'];
            ?>
            <tr>
                <td><?php echo $supplier_name; ?></td>
                <td><?php echo $supplier_email; ?></td>
                <td><?php echo $supplier_phone; ?></td>
                <td><?php echo $address1; ?></td>
            </tr>
        <?php } } ?>
    </tbody>
</table>

<script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
<script>
    let timesPdf = 0;
    if (timesPdf == 0) {
        const element = document.getElementById('example');
        const opt = {
            margin: 0.5,
            filename: 'Supplier.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
        timesPdf += 1;
    }

    setTimeout(() => {
        window.location.href = "<?php echo $website; ?>/People/Suppliers/";
    }, 800);
</script>
</body>
</html>
