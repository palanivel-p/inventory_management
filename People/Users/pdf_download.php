<?php
include("../../includes/connection.php");

error_reporting(0);
$u_name= $_GET['u_name'];
$u_code= $_GET['u_code'];
$mobile= $_GET['mobile'];
$email= $_GET['email'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];
$user_doc = $_GET['userid'];

if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-d');
}

$from_date = date('Y-m-d 00:00:00',strtotime($f_date));

$to_date = date('Y-m-d 23:59:59',strtotime($t_date));

if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}

if($u_name != ""){
//    $uNameSql= " AND username = '".$u_name."'";
    $uNameSql = " AND username LIKE '%" . $u_name . "%'";

}
else{
    $uNameSql ="";
}

if($u_code != ""){
//    $uCodeSql= " AND user_id = '".$u_code."'";
    $uCodeSql = " AND user_id LIKE '%" . $u_code . "%'";
}
else{
    $uCodeSql ="";
}

if($mobile != ""){
//    $mobileSql= " AND phone = '".$mobile."'";
    $mobileSql = " AND phone LIKE '%" . $mobile . "%'";
}
else{
    $mobileSql ="";
}

if($email != ""){
//    $emailSql= "AND email = '".$email."'";
    $emailSql = " AND email LIKE '%" . $email . "%'";
}
else {
    $emailSql = "";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>PDF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap-grid.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Yellowtail&family=Yesteryear&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
            word-wrap: break-word;
        }

        tr:nth-child(even) {
            background-color: #dddada;
        }

        #header {
            background-color: #1C6180;
            color: #fff;
        }
    </style>
</head>
<body id="example">
<h2 style="text-align: center">User List</h2>

<table id="example">
    <thead>
    <tr id="header">
        <th >User Name</th>
        <th >Mobile</th>
        <th >Email</th>
<!--        <th >Address</th>-->
    </tr>
    </thead>
    <tbody>
    <?php
    if($email == "" && $mobile== "" && $u_name == "") {
        $sql = "SELECT * FROM user  ORDER BY id";
    }
    else {
        $sql = "SELECT * FROM user WHERE id > 0 $emailSql$mobileSql$uNameSql ORDER BY id";
    }    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                        <td>{$row['username']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['email']}</td>

                    </tr>";
        }
    }
    ?>
    </tbody>
</table>

<script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var element = document.getElementById('example');
        var opt = {
            margin:       0.5,
            filename:     'User.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' },
            pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            window.location.href = "<?php echo $website; ?>/People/Users/";
        });
    });
</script>
</body>
</html>
