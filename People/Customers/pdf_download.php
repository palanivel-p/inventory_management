<?php
include("../../includes/connection.php");

error_reporting(0);
date_default_timezone_set("Asia/kolkata");
$c_name= $_GET['c_name'];
$c_code= $_GET['c_code'];
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

if($c_name != ""){
//    $sNameSql= " AND customer_name = '".$c_name."'";
    $sNameSql = " AND customer_name LIKE '%" . $c_name . "%'";

}
else{
    $sNameSql ="";
}

if($c_code != ""){
    $sCodeSql= " AND scustomer_code = '".$c_code."'";

}
else{
    $sCodeSql ="";
}

if($mobile != ""){
//    $mobileSql= " AND customer_phone = '".$mobile."'";
    $mobileSql = " AND customer_phone LIKE '%" . $mobile . "%'";

}
else{
    $mobileSql ="";
}

if($email != ""){
//    $emailSql= "AND customer_email = '".$email."'";
    $emailSql = " AND customer_email LIKE '%" . $email . "%'";

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
            /*page-break-inside: avoid;*/
        }
        th, td {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
            word-wrap: break-word;
            /*width: 25%;*/
            /*page-break-inside: avoid;*/
        }
        th {
            background-color: #1C6180;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #dddada;
        }
        /*.supplier-name {*/
        /*    width: 20%;*/
        /*}*/
        /*.email {*/
        /*    width: 25%;*/
        /*}*/
        /*.phone {*/
        /*    width: 15%;*/
        /*}*/
        /*.address {*/
        /*    width: 40%;*/
        /*}*/
    </style>
</head>
<body id="example">
<h2 style="text-align: center">Supplier List</h2>
<table id="example">
    <thead>
    <tr id="header">
        <th class="supplier-name">Supplier Name</th>
        <th class="phone">Phone</th>
        <th class="email">Email</th>
<!--        <th class="address">Address</th>-->
    </tr>
    </thead>
    <tbody>
    <?php
    if($email == "" && $mobile== "" && $c_name == "") {
        $sql = "SELECT * FROM customer ORDER BY id DESC";
    }
    else {
        $sql = "SELECT * FROM customer WHERE id > 0 $emailSql$mobileSql$sNameSql ORDER BY id DESC";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $customer_name = $row['customer_name'];
            $customer_email = $row['customer_email'];
            $customer_phone = $row['customer_phone'];
//            $address1 = $row['address1'];
            ?>
            <tr>
                <td><?php echo $customer_name; ?></td>
                <td><?php echo $customer_phone; ?></td>
                <td><?php echo $customer_email; ?></td>
<!--                <td>--><?php //echo $address1; ?><!--</td>-->
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
            filename: 'Customer.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
        timesPdf += 1;
    }

    setTimeout(() => {
        window.location.href = "<?php echo $website; ?>/People/Customers/";
    }, 800);
</script>
</body>
</html>
