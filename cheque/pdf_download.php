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
$c_no= $_GET['c_no'];
$b_name = $_GET['b_name'];
$acc_no = $_GET['acc_no'];


if($c_no != ""){
    $c_noSql= " AND cheque_id LIKE '%".$c_no."%'";

}
else{
    $c_noSql ="";
}

if($b_name != ""){
    $b_nameSql= " AND bank_name LIKE '%".$b_name."%'";

}
else{
    $b_nameSql ="";
}

if($acc_no != ""){
    $acc_noSql= " AND acc_no LIKE '%".$acc_no."%'";

}
else{
    $acc_noSql ="";
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
    <h2 style="text-align: center">Cheque List</h2>

    <table id="example">
        <thead>
        <tr id="header">
            <th>Cheque No</th>
            <th>Account No</th>
            <th>Bank Name </th>


        </tr>
        </thead>
        <?php
        if($acc_no == "" && $b_name == "" && $c_no == "") {
            $sql = "SELECT * FROM cheque";
        }
        else {
            $sql = "SELECT * FROM cheque WHERE id>0 $acc_noSql$b_nameSql$c_noSql ";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
//            $sNo = $start;
            $sNo = 1;
            while($row = mysqli_fetch_assoc($result)) {
//                $amount = $row['amount'];
//                $total_amount+=$amount;
                $sNo++;
                $cheque_no =$row['cheque_no'];
                $bank_name =$row['bank_name'];
                $account_no =$row['acc_no'];
                ?>

        <tr>

            <td><?php echo $cheque_no?></td>
            <td> <?php echo $account_no?> </td>
            <td> <?php echo $bank_name?> </td>
        </tr>
            <?php } }
        ?>

<!--<tr>-->
<!--    <td colspan="4">Total Amount</td>-->
<!--    <td>--><?php //echo $total_amount;?><!--</td>-->
<!--</tr>-->

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
            filename:     'Cheque.pdf',
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
        window.location.href = "<?php echo $website; ?>/cheque/";
    }, 800);


</script>

</body>
</html>