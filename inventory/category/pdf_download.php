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
$c_id= $_GET['c_id'];
$p_category= $_GET['p_category'];
$s_category= $_GET['s_category'];

if($c_id != ""){
//    $c_idSql= " AND category_id = '".$c_id."'";
    $c_idSql = " AND category_id LIKE '%" . $c_id . "%'";

}
else{
    $c_idSql ="";
}

if($p_category != ""){
//    $p_categorySql= " AND primary_category = '".$p_category."'";
    $p_categorySql = " AND primary_category LIKE '%" . $p_category . "%'";
}
else{
    $p_categorySql ="";
}

if($s_category != ""){
//    $s_categorySql= " AND sub_category = '".$s_category."'";
    $s_categorySql = " AND sub_category LIKE '%" . $s_category . "%'";
}
else{
    $s_categorySql ="";
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
    <h2 style="text-align: center">Category List</h2>

    <table id="example">
        <thead>
        <tr id="header">
            <th>Category Id</th>
            <th>Primary Category</th>
            <th>Sub Category</th>
            <th>Description</th>

        </tr>
        </thead>
        <?php
        if($p_category == "" && $s_category== "") {
            $sql = "SELECT * FROM category  ORDER BY id";
        }
        else {
            $sql = "SELECT * FROM category WHERE id > 0 $p_categorySql$s_categorySql ORDER BY id";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
//            $sNo = $start;
            $sNo = 1;
            while($row = mysqli_fetch_assoc($result)) {

                $sNo++;
                $description = $row['description'];
                if($description == ''){
                    $d_cription = 'NA';
                }
                else{
                    $d_cription = $description;
                }
                ?>

        <tr>
<!--            <td><strong>--><?php //echo $sNo;?><!--</strong></td>-->
            <td><?php echo $row['category_id']?></td>
            <td><?php echo $row['primary_category']?></td>
            <td> <?php echo $row['sub_category']?> </td>
            <td> <?php echo $d_cription?> </td>
<!--            <td> --><?php //echo $row['email']?><!-- </td>-->
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
            filename:     'Category.pdf',
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
        window.location.href = "<?php echo $website; ?>/inventory/category/";
    }, 800);


</script>

</body>
</html>