<?php Include("../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
$pur_id= $_GET['pur_id'];
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
if($pur_id != ""){
    $pur_idSql= " AND intend_id = '".$pur_id."'";

}
else{
    $pur_idSql ="";
}
if($page=='') {
    $page=1;
}

$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;

if($pageSql == 0) {
    $end = 10;
}
$added_by = $_COOKIE['user_id'];
if($_COOKIE['role'] == 'Super Admin'| $_COOKIE['role'] == 'Admin'){
    $addedBy = "";
}
else{
    $addedBy = " AND added_by='$added_by'";
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
    <h2 style="text-align: center">Intend List</h2>

    <table id="example">
        <thead>
        <tr id="header">
            <th>Intend Id</th>
            <th>Intend Date</th>
            <th>Notes</th>
            <th>Added By</th>

        </tr>
        </thead>
        <?php
        if($pur_id == "" ) {
            $sql = "SELECT * FROM intend WHERE intend_date  BETWEEN '$from_date' AND '$to_date' $addedBy ORDER BY id DESC ";
        }
        else {
            $sql = "SELECT * FROM intend WHERE intend_date  BETWEEN '$from_date' AND '$to_date' $pur_idSql $addedBy ORDER BY id DESC";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
            $sNo = 1;
            while($row = mysqli_fetch_assoc($result)) {

                $status =  $row['payment_status'];
                if($status == 3){
                    $payment_status = 'Pending';
                }
                else if($status == 1){
                    $payment_status = 'Received';
                }
                else if($status == 2){
                    $payment_status = 'partially Pending';
                }
                else if($status == 4){
                    $payment_status = 'Ordered';
                }
                $sNo++;
                $supplier_id=$row['supplier'];

                $sqlSupplier = "SELECT * FROM `supplier` WHERE `supplier_id`='$supplier_id'";
                $resSupplier = mysqli_query($conn, $sqlSupplier);
                $rowSupplier = mysqli_fetch_assoc($resSupplier);
                $Supplier =  $rowSupplier['supplier_name'];

                $intend_date=$row['intend_date'];
                $int_date = date('d-m-Y', strtotime($intend_date));
                if($row['notes'] == ''){
                    $notes = 'NA';
                }
                elseif ($row['notes']!= ''){
                    $notes = $row['notes'];
                }
                if($row['added_by'] == ''){
                    $added_by = 'Super Admin';
                }
                elseif ($row['added_by']!= ''){
                    $added_by = $row['added_by'];
                }
                $added_bys = $row['added_by'];
                $sqlUser = "SELECT * FROM `user` WHERE `user_id`='$added_bys'";
                $resUser = mysqli_query($conn, $sqlUser);
                $rowUser = mysqli_fetch_assoc($resUser);
                $User_name =  $rowUser['f_name'];
                if($added_bys != ''){
                    $user=$User_name;
                }
                else if($added_bys == ''){
                    $user = 'Super Admin';
                }
                ?>

        <tr>
<!--            <td><strong>--><?php //echo $sNo;?><!--</strong></td>-->
            <td><?php echo $row['intend_id']?></td>
            <td><?php echo $int_date?></td>
            <td> <?php echo $notes?> </td>
            <td> <?php echo $user?> </td>

        </tr>
            <?php } }
        ?>

    </table>
<!--</table>-->


<script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"></script>
<script>
    //
    //     var element = document.getElementById('printPdf');
    //         html2pdf(element);

    let timesPdf = 0;
    if(timesPdf == 0){
        var element = document.getElementById('example');

        useCORS: true;
        var opt = {
            margin:       0.5,
            filename:     'Intent.pdf',
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
        window.location.href = "<?php echo $website; ?>/intend/all_intend.php";
    }, 800);


</script>

</body>
</html>