<?php Include("../../includes/connection.php");

error_reporting(0);
$page= $_GET['page_no'];
$e_category= $_GET['e_category'];
$s_name= $_GET['sts_name'];
$pay_mode= $_GET['pay_mode'];
$ref_no= $_GET['ref_no'];
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
$pageSql= $page-1;
$start=$pageSql*10;
$end = $start;
if($pageSql == 0) {
    $end = 10;
}
$expense_id= $_GET['expense_id'];
$e_category= $_GET['e_category'];
$s_name= $_GET['s_name'];
$pay_mode= $_GET['pay_mode'];
$ref_no= $_GET['ref_no'];


if($expense_id != ""){
//    $expense_idSql= " AND expense_id = '".$expense_id."'";
    $expense_idSql = " AND expense_id LIKE '%" . $expense_id . "%'";

}
else{
    $expense_idSql ="";
}

if($e_category != ""){
    $e_categorySql= " AND expense_type = '".$e_category."'";
//    $e_categorySql = " AND expense_type LIKE '%" . $e_category . "%'";

}
else{
    $e_categorySql ="";
}

if($s_name != ""){
    $s_nameSql= " AND payment_status = '".$s_name."'";
//    $s_nameSql = " AND payment_status LIKE '%" . $s_name . "%'";

}
else{
    $s_nameSql ="";
}

if($pay_mode != ""){
    $pay_modeSql= "AND payment_mode	 = '".$pay_mode."'";
//    $pay_modeSql = " AND payment_mode LIKE '%" . $pay_mode . "%'";

}
else {
    $pay_modeSql = "";
}
if($ref_no != ""){
//    $ref_noSql= "AND reference_no = '".$ref_no."'";
    $ref_noSql = " AND reference_no LIKE '%" . $ref_no . "%'";

}
else {
    $ref_noSql = "";
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
    <h2 style="text-align: center">Expense List</h2>

    <table id="example">
        <thead>
        <tr id="header">
            <th>Date</th>
            <th>Expense</th>
            <th>Due Date</th>
            <th>Amount </th>
        </tr>
        </thead>
        <?php
        if($e_category == "" && $s_name== "" && $pay_mode == "" && $ref_no=="") {
            $sql = "SELECT * FROM expense WHERE expense_date  BETWEEN '$from_date' AND '$to_date' ORDER BY id DESC";
        }else
        {
            $sql = "SELECT * FROM expense WHERE expense_date  BETWEEN '$from_date' AND '$to_date' $e_categorySql$s_nameSql$pay_modeSql$ref_noSql ORDER BY id DESC";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
//            $sNo = $start;
            $sNo = 1;
            while($row = mysqli_fetch_assoc($result)) {
                $amount = $row['amount'];
                $total_amount+=$amount;
                $sNo++;
                $e_date = $row['expense_date'];
                $expence_date = date('d-m-Y', strtotime($e_date));
                $d_date = $row['due_date'];
                $due_date = date('d-m-Y', strtotime($d_date));
                $expense_type=$row['expense_type'];

                $sqlExpenseType = "SELECT * FROM `expense_category` WHERE `category_id`='$expense_type'";
                $resExpenseType = mysqli_query($conn, $sqlExpenseType);
                $rowExpenseType = mysqli_fetch_assoc($resExpenseType);
                $ExpenseType =  $rowExpenseType['category_name'];
                ?>

        <tr>
<!--            <td><strong>--><?php //echo $sNo;?><!--</strong></td>-->
            <td><?php echo $expence_date?></td>
            <td><?php echo $ExpenseType?></td>
            <td> <?php echo $due_date?> </td>
            <td> <?php echo $row['amount']?> </td>
        </tr>
            <?php } }
        ?>

<tr>
    <td colspan="3">Total Amount</td>
    <td><?php echo $total_amount;?></td>
</tr>

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
            filename:     'Expense.pdf',
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
        window.location.href = "<?php echo $website; ?>/Ledgers/expense/";
    }, 800);


</script>

</body>
</html>