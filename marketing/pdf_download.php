<?php Include("../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
if($page=='') {
    $page=1;
}
$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;
if($pageSql == 0) {
    $end = 10;
}
$product_id= $_GET['product_id'];
$f_date = $_GET['f_date'];
$t_date = $_GET['t_date'];

if($product_id != ""){
    $product_idSql= " AND material_name LIKE '%".$product_id."%'";
}
else{
    $product_idSql ="";
}
if($f_date == ''){
    $f_date = date('Y-m-01');
}
if($t_date == ''){
    $t_date = date('Y-m-d');
}
$from_date = date('Y-m-d 00:00:00',strtotime($f_date));
$to_date = date('Y-m-d 23:59:59',strtotime($t_date));
$cus_name= $_GET['cus_name'];
if($cus_name != ""){
    $cus_nameSql= " AND customer_name LIKE '%".$cus_name."%'";
}
else{
    $cus_nameSql ="";
}
$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin' || $_COOKIE['role'] == 'Admin'){
    $addBySql = "";
}
else{
    $addBySql = " AND added_by='$added_by'";
}
?>

<html>
<head>
    <title>PDF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap-grid.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Yellowtail&family=Yesteryear&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddada;
        }
        #header{
            background-color: #1C6180;
            color: #fff;
        }
    </style>
</head>

<body id="example">
<!--<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">-->
    <h2 style="text-align: center">Marketing List</h2>

    <table id="example">
        <thead>
        <tr id="header">
            <th><strong>Visit Date</strong></th>
            <th style="width: 120px"><strong>Customer Name</strong></th>
            <th><strong>Meet whom</strong></th>
            <th style="width: 120px"><strong>Material Name</strong></th>
            <th><strong>Qty</strong></th>
<!--            <th><strong>Progress</strong></th>-->


        </tr>
        </thead>
        <?php
        if($cus_name == "") {
            $sql = "SELECT * FROM marketing WHERE visit_date  BETWEEN '$from_date' AND '$to_date'$addBySql ORDER BY id DESC ";
        }
        {
            $sql = "SELECT * FROM marketing WHERE visit_date  BETWEEN '$from_date' AND '$to_date' $cus_nameSql$addBySql ORDER BY id DESC";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
//            $sNo = $start;
            $sNo = 1;
            while($row = mysqli_fetch_assoc($result)) {
                $amount = $row['amount'];
                $total_amount+=$amount;
                $sNo++;

                $v_date = $row['visit_date'];
                $visite_date = date('d-m-Y', strtotime($v_date));

                $customer_id=$row['customer_name'];
                $sqlCustomer = "SELECT * FROM `customer` WHERE `customer_id`='$customer_id'";
                $resCustomer = mysqli_query($conn, $sqlCustomer);
                $rowCustomer = mysqli_fetch_assoc($resCustomer);
                $customer_name =  $rowCustomer['customer_name'];
                $product_name =  $row['product_name'];
                if($product_name == ''){
                    $p_name ='NA';
                }
                else{
                    $p_name =$product_name;
                }
                ?>

        <tr>
            <td> <?php echo $visite_date?> </td>
            <td> <?php echo $row['customer_name']?> </td>
            <td> <?php echo $row['meet_person']?> </td>
            <td> <?php echo $p_name?> </td>
            <td> <?php echo $row['qty']?> </td>
        </tr>
            <?php } }
        ?>

    </table>

<script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"></script>
<script>
    //     var element = document.getElementById('printPdf');
    //         html2pdf(element);
    let timesPdf = 0;
    if(timesPdf == 0){
        var element = document.getElementById('example');

        useCORS: true;
        var opt = {
            margin:       0.5,
            filename:     'Marketing.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2,useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
        //  Old monolithic-style usage:
        // html2pdf(element, opt);
        timesPdf+=1;
    }

    setTimeout(() => {
        window.location.href = "<?php echo $website; ?>/marketing/";
    }, 800);


</script>

</body>
</html>