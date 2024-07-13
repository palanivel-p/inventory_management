<?php Include("../../includes/connection.php");

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
$l_id= $_GET['l_id'];
$b_name= $_GET['b_name'];
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


if($page=='') {
    $page=1;
}

if($l_id != ""){
    $l_idSql= " AND loan_id = '".$l_id."'";

}
else{
    $l_idSql ="";
}

if($b_name != ""){
    $b_nameSql = " AND borrower LIKE '%" . $b_name . "%'";
}
else{
    $b_nameSql ="";
}


//$sqldonerRecent = "SELECT * FROM doner_profile ORDER BY id DESC LIMIT 1";
//$resultdonerRecent = mysqli_query($conn, $sqldonerRecent);
//$rowdonerRecent = mysqli_fetch_assoc($resultdonerRecent);
//$lastTransAmount = $rowdonerRecent['doner_id'];

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
    <h2 style="text-align: center">Loan List</h2>

    <table id="example">
        <thead>
        <tr id="header">
            <th>Loan Id</th>
            <th>Borrower</th>
            <th>Loan Amount </th>
            <th>Balance Amount</th>
            <th>Tenure</th>

        </tr>
        </thead>
        <?php
        if($b_name == "" ) {
            $sql = "SELECT * FROM loan WHERE loan_date  BETWEEN '$from_date' AND '$to_date' ORDER BY id DESC ";
        }
        else {
            $sql = "SELECT * FROM loan WHERE loan_date  BETWEEN '$from_date' AND '$to_date' $b_nameSql ORDER BY id DESC ";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
//            $sNo = $start;
            $sNo = 1;
            while($row = mysqli_fetch_assoc($result)) {
//                $amount = $row['amount'];
//                $total_amount+=$amount;
                $sNo++;
                $loan_id =   $row['loan_id'];

                $sqlamount="SELECT SUM(repayment_amount) AS repayment_amount  FROM repayment WHERE loan_id='$loan_id'";
                $resamount=mysqli_query($conn,$sqlamount);
                if(mysqli_num_rows($resamount)>0){
                    $arrayamount=mysqli_fetch_array($resamount);
                    $amount=$arrayamount['repayment_amount'];
                }
                $loan_amount= $row['amount'];
                $balance_amount= $loan_amount - $amount;
                ?>

        <tr>
<!--            <td><strong>--><?php //echo $sNo;?><!--</strong></td>-->
            <td><?php echo $row['loan_id']?></td>
            <td><?php echo $row['borrower']?></td>
            <td> <?php echo $row['amount']?> </td>
            <td> <?php echo $balance_amount?> </td>
            <td> <?php echo $row['tenure']?> </td>
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
            filename:     'Loan.pdf',
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
        window.location.href = "<?php echo $website; ?>/Ledgers/loan/";
    }, 800);


</script>

</body>
</html>