
<?php

$cookieStaffId = $_COOKIE['staff_id'];
$cookieBranch_Id = $_COOKIE['branch_id'];
if($_COOKIE['role'] == 'Super Admin'){
    $addedBranchSerach = '';
}
else {
    if ($_COOKIE['role'] == 'Admin'){
        $addedBranchSerach = "AND branch_name='$cookieBranch_Id'";
    }
    else{
        $addedBranchSerach = "AND added_by='$cookieStaffId' AND branch_name='$cookieBranch_Id'";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard</title>

    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon_New.png">
    <link href="../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/chartist/css/chartist.min.css">

    <link href="../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        .outer {
            width: 1px;
            height: 100%;
            margin: auto;
            position: relative;
            overflow: hidden;
        }
        .inner {
            position: absolute;
            width:100%;
            height: 40%;
            background: grey;
            top: 30%;
            box-shadow: 0px 0px 30px 20px grey;
        }
    </style>
</head>

<body>


<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>




<div id="main-wrapper">


    <?php
    $header_name='Dashboard';
    Include ('../includes/header.php');
    Include ('../includes/connection.php');
    $currentMonthName = date('F Y');
    /*************** statistics ************/



    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d');

    /******* Admin or Super Admin Login *****/

    if($_COOKIE['role'] == "Admin" || $_COOKIE['role']=="Super Admin") {

        $sqlSale= "SELECT COUNT(id) as count FROM sale";
        $resultSale = mysqli_query($conn, $sqlSale);
        $rowSale = mysqli_fetch_assoc($resultSale);
        $totalSale = $rowSale['count'];

        $sqlPurchase= "SELECT COUNT(id) as count FROM purchase";
        $resultPurchase = mysqli_query($conn, $sqlPurchase);
        $rowPurchase = mysqli_fetch_assoc($resultPurchase);
        $totalPurchase = $rowPurchase['count'];

        $sqlSaleReturn= "SELECT COUNT(id) as count FROM sale_return";
        $resultSaleReturn = mysqli_query($conn, $sqlSaleReturn);
        $rowSaleReturn = mysqli_fetch_assoc($resultSaleReturn);
        $totalSaleReturn = $rowSaleReturn['count'];

        $sqlPurchaseReturn= "SELECT COUNT(id) as count FROM purchase_return";
        $resultPurchaseReturn = mysqli_query($conn, $sqlPurchaseReturn);
        $rowPurchaseReturn = mysqli_fetch_assoc($resultPurchaseReturn);
        $totalPurchaseReturn = $rowPurchaseReturn['count'];

    if ($_COOKIE['role'] != "Staff") {

        $sqlstaff = "SELECT COUNT(id) as count FROM staff_profile WHERE id>0 $addedBranchSerach";
        $resultstaff = mysqli_query($conn, $sqlstaff);
        $rowstaff = mysqli_fetch_assoc($resultstaff);
        $totalstaff = $rowstaff['count'];
    }


    /*************** Graph Data ************/
    $yearArrayData =  date("Y");;
    $date = $yearArrayData - 1;

    $fromDate1 = $date .'-12-01';
    $end_date1 = $yearArrayData.'-11-30';

    $monthly_mobile_vist='[';
    $monthly_desktop_vist='[';
    $monthly_offline='[';
    $monthly_online='[';

    while (strtotime($fromDate1) < strtotime($end_date1))
    {
        $fromDate1 = date ("Y-m-d 00:00:00", strtotime("+1 month", strtotime($fromDate1)));
        $toDate=date ("Y-m-d 00:00:00", strtotime("+1 month", strtotime($fromDate1)));
        $toDate=date ("Y-m-d 23:59:59", strtotime("-1 day", strtotime($toDate)));

        $sqlmobileChartoff="SELECT COUNT(id) as count FROM purchase WHERE purchase_date BETWEEN '$fromDate1' AND '$toDate'";
        $resMobileChartoff=mysqli_query($conn,$sqlmobileChartoff);
        $arrayMobileChartoff=mysqli_fetch_array($resMobileChartoff);
        $mobileChartCountoff=$arrayMobileChartoff['count'];
        if($mobileChartCountoff == "") {
            $mobileChartCountoff= 0;
        }
        $monthly_offline.=$mobileChartCountoff.',';

        $sqlmobileCharton="SELECT COUNT(id) as count FROM sale WHERE sale_date BETWEEN '$fromDate1' AND '$toDate'";
        $resMobileCharton=mysqli_query($conn,$sqlmobileCharton);
        $arrayMobileCharton=mysqli_fetch_array($resMobileCharton);
        $mobileChartCounton=$arrayMobileCharton['count'];
        if($mobileChartCounton == "") {
            $mobileChartCounton= 0;
        }
        $monthly_online.=$mobileChartCounton.',';
    }

    $monthly_mobile_vist.=']';
    $monthly_desktop_vist.=']';
    $monthly_offline.=']';
    $monthly_online.=']';
    print_r($monthly_offline);

    //market Target Graph
        /***************Market Graph Data ************/
        // Get the current year and the last year
        $currentYear = date("Y");
        $lastYear = $currentYear - 1;

        $fromDate = "$lastYear-12-01";
        $endDate = "$currentYear-11-30";

// Initialize arrays to hold data
        $targetM = [];
        $achievedM = [];

// Loop through each month between the date range
        while (strtotime($fromDate) < strtotime($endDate)) {
            // Increment the month
            $toDate = date("Y-m-d 23:59:59", strtotime("+1 month", strtotime($fromDate)));
            $fromDate = date("Y-m-d 00:00:00", strtotime("+1 month", strtotime($fromDate)));

            // Query to get the target count
            $targetChartSql = "SELECT SUM(target_qty) as count FROM target WHERE `month` BETWEEN '$fromDate' AND '$toDate'";
            $resTargetChart = mysqli_query($conn, $targetChartSql);
            $arrayTargetChart = mysqli_fetch_array($resTargetChart);
            $targetCount = $arrayTargetChart['count'] ?: 0; // Default to 0 if no data

            // Query to get the achieved count
            $achievedChartSql = "SELECT SUM(qty) as count FROM sale_details WHERE sale_date BETWEEN '$fromDate' AND '$toDate'";
            $resAchievedChart = mysqli_query($conn, $achievedChartSql);
            $arrayAchievedChart = mysqli_fetch_array($resAchievedChart);
            $achievedCount = $arrayAchievedChart['count'] ?: 0; // Default to 0 if no data

            // Add counts to arrays
            $targetM[] = $targetCount;
            $achievedM[] = $achievedCount;
        }

// Convert arrays to JSON
        $targetMJson = json_encode($targetM);
        $achievedMJson = json_encode($achievedM);
    //Weekly
    // Get current date and find the start (Sunday) and end (Saturday) of the current week
    $currentDate = date('Y-m-d');
    $startOfWeek = date('Y-m-d', strtotime('last Sunday', strtotime($currentDate)));
    $endOfWeek = date('Y-m-d', strtotime('next Saturday', strtotime($currentDate)));

    // Initialize an array for weekly expenses
    $weeklyExpenses = array('Sun' => 0, 'Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0);
    // Query to fetch debit transactions within the current week
    $sql = "SELECT DAYOFWEEK(payment_date) as day, SUM(amount) as total_amount
        FROM bank_details
        WHERE type = 'Debit' AND payment_date BETWEEN '$startOfWeek' AND '$endOfWeek'
        GROUP BY DAYOFWEEK(payment_date)";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dayOfWeek = $row['day'];
            $totalAmount = $row['total_amount'];
            switch ($dayOfWeek) {
                case 1:
                    $weeklyExpenses['Sun'] = (float) $totalAmount;
                    break;
                case 2:
                    $weeklyExpenses['Mon'] = (float) $totalAmount;
                    break;
                case 3:
                    $weeklyExpenses['Tue'] = (float) $totalAmount;
                    break;
                case 4:
                    $weeklyExpenses['Wed'] = (float) $totalAmount;
                    break;
                case 5:
                    $weeklyExpenses['Thu'] = (float) $totalAmount;
                    break;
                case 6:
                    $weeklyExpenses['Fri'] = (float) $totalAmount;
                    break;
                case 7:
                    $weeklyExpenses['Sat'] = (float) $totalAmount;
                    break;
            }
        }
    }
    // Query to get the total expense for the current week
    $totalExpenseSql = "SELECT SUM(amount) as total_expense
                    FROM bank_details
                    WHERE type = 'Debit' AND payment_date BETWEEN '$startOfWeek' AND '$endOfWeek'";
    $totalExpenseResult = mysqli_query($conn, $totalExpenseSql);
    $totalExpenseRow = mysqli_fetch_assoc($totalExpenseResult);
    $totalExpense = $totalExpenseRow['total_expense'] ? (float)$totalExpenseRow['total_expense'] : 0;

    // Prepare the data for Highcharts
    $weeklyExpensesData = json_encode(array_values($weeklyExpenses));
    ?>
    <div class="content-body">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <?php
                        if($_COOKIE['role'] != "") {
                            if($_COOKIE['role'] != "") {
                                ?>
                                <div class="col-xl-3 col-lg-6 col-sm-6">
                                    <div class="widget-stat card bg-danger">
                                        <div class="card-body  p-4">
                                            <div class="media">
                                                <div class="media-body text-white">
                                                    <p class="mb-1" style="text-align: center;">Total Purchase</p>
                                                    <!-- <h3 class="text-white"><?php //echo $total_visits; ?></h3> -->
                                                    <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalPurchase; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-success">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Total Sale</p>
                                                <!-- <h3 class="text-white"><?php //echo $total_desktops; ?></h3> -->
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSale; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-xl-3 col-lg-6 col-sm-6">
                            <div class="widget-stat card bg-info">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="media-body text-white">
                                            <p class="mb-1" style="text-align: center;">Purchase Return</p>
                                            <!-- <h3 class="text-white"><?php //echo $total_mobiles; ?></h3> -->
                                            <h3 class="text-white"style="margin-top: 30px; text-align: center;"><?php echo $totalPurchaseReturn == ''?0:$totalPurchaseReturn; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php

                        ?>
                        <div class="col-xl-3 col-lg-6 col-sm-6">
                            <div class="widget-stat card bg-primary">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <div class="media-body text-white">
                                            <p class="mb-1" style="text-align: center;">Sales Return</p>
                                            <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSaleReturn == ''?0:$totalSaleReturn; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                   <h4 class="card-title">Total Sales And Purchase - <?php echo date('Y'); ?></h4>
                                </div>
                                <div class="card-body">
                                    <div id="currMonthGraph" style="width: 100%; height: auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Not Paid Within Over Due Date Past week-->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Not Paid Within Over Due Date - <span> Past Week Records</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive-md" style="text-align: center;">
                                    <thead style="background-color: #b1b1b1;">
                                    <tr>
                                        <th class="width80"><strong>#</strong></th>
                                        <th><strong>Due Date</strong></th>
                                        <th><strong>Po No</strong></th>
                                        <th><strong>Customer Name</strong></th>
                                        <th><strong>Total Amount</strong></th>
                                        <th><strong>Paid Amount</strong></th>
                                        <th><strong>Balance Amount</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $pageOD= $_GET['page_noOD'];
                                    if($pageOD=='') {
                                        $pageOD=1;
                                    }
                                    $pageSqlOD= $pageOD-1;
                                    $startOD=$pageSqlOD*10;
                                    $endOD = $startOD;

                                    if($pageSqlOD == 0) {
                                        $endOD = 10;
                                    }
                                    // Calculate the start and end date of the last week
                                    $currentDateOD = date('Y-m-d');
                                    $startOfLastWeekOD = date('Y-m-d', strtotime('last Sunday -1 week'));
                                    $endOfLastWeekOD = date('Y-m-d', strtotime('last Saturday'));

                                    $sqlOD = "SELECT * FROM sale WHERE due_date BETWEEN '$startOfLastWeekOD' AND '$endOfLastWeekOD' ORDER BY id DESC";
                                    $resultOD = mysqli_query($conn, $sqlOD);

                                    if (mysqli_num_rows($resultOD) > 0) {
                                        $sNoOD = 0;
                                        $sNoOD = $startOD;
                                        while ($rowOD = mysqli_fetch_assoc($resultOD)) {
                                            $sNoOD++;
                                            $customer_idOD = $rowOD['customer'];
                                            $sale_idOD = $rowOD['sale_id'];

                                            $sqlAmountOD = "SELECT SUM(pay_made) AS pay_made FROM sale_payment WHERE sale_id = '$sale_idOD'";
                                            $resAmountOD = mysqli_query($conn, $sqlAmountOD);
                                            $totalAmountOD = 0;

                                            if (mysqli_num_rows($resAmountOD) > 0) {
                                                $arrayAmountOD = mysqli_fetch_assoc($resAmountOD);
                                                $totalAmountOD = $arrayAmountOD['pay_made'];
                                            }
                                            $grand_totalOD = $rowOD['grand_total'];
                                            $balance_amountOD = $grand_totalOD - $totalAmountOD;
                                            $balanceAmount_formattedOD = number_format($balance_amountOD, 2, '.', '');

                                            if ($totalAmountOD == '') {
                                                $totalAmount_formattedOD = 'NA';
                                            } else {
                                                $totalAmount_formattedOD = number_format($totalAmountOD, 2, '.', '');
                                            }
                                            $sqlCustomerOD = "SELECT * FROM customer WHERE customer_id = '$customer_idOD'";
                                            $resCustomerOD = mysqli_query($conn, $sqlCustomerOD);
                                            $rowCustomerOD = mysqli_fetch_assoc($resCustomerOD);
                                            $customer_nameOD = $rowCustomerOD['customer_name'];
                                            $d_date = $rowOD['due_date'];
                                            $d_date = date('d-m-Y', strtotime($d_date));
                                            ?>
                                            <tr>
                                                <td><strong><?php echo $sNoOD; ?></strong></td>
                                                <td><?php echo $d_date; ?></td>
                                                <td><?php echo $rowOD['po_no']; ?></td>
                                                <td><?php echo $customer_nameOD; ?></td>
                                                <td><?php echo number_format($rowOD['grand_total'], 2, '.', ''); ?></td>
                                                <td><?php echo $totalAmount_formattedOD; ?></td>
                                                <td><?php echo $balanceAmount_formattedOD; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7">No Record Found</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <div class="col-12 pl-3" style="display: flex;justify-content: center;">
                                    <nav>
                                        <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">

                                            <?php

                                            $prevPageOD=abs($pageOD-1);
                                            if ($prevPageOD > 0) {
                                                ?>
                                                <li class="page-item page-indicator"><a class="page-link"
                                                                                        href="?page_noOD=<?php echo 1 ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-angles-left"></i></a></li>
                                                <?php
                                            }

                                            if($prevPageOD==0)
                                            {
                                                ?>
                                                <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <li class="page-item page-indicator"><a class="page-link" href="?page_noOD=<?php echo $prevPageOD?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-less-than"></i></a></li>
                                                <?php
                                            }
                                            $sqlOD = "SELECT COUNT(id) as count FROM sale WHERE due_date BETWEEN '$startOfLastWeekOD' AND '$endOfLastWeekOD' ORDER BY id DESC";

                                            $resultOD = mysqli_query($conn, $sqlOD);

                                            if (mysqli_num_rows($resultOD)) {

                                                $rowOD = mysqli_fetch_assoc($resultOD);
                                                $countOD = $rowOD['count'];
                                                $showOD = 10;

                                                $getOD = $countOD / $showOD;

                                                $pageFooterOD = floor($getOD);

                                                if ($getOD > $pageFooterOD) {
                                                    $pageFooterOD++;
                                                }

                                                for ($iOD = 1; $iOD <= $pageFooterOD; $iOD++) {

                                                    if($iOD==$pageOD) {
                                                        $activeOD = "active";
                                                    }
                                                    else {
                                                        $activeOD = "";
                                                    }

                                                    if($iOD<=($pageSqlOD+10) && $i>$pageSqlOD || $pageFooterOD<=10) {

                                                        ?>

                                                        <li class="page-item <?php echo $activeOD ?>"><a class="page-link"
                                                                                                         href="?page_noOD=<?php echo $iOD ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><?php echo $iOD ?></a>
                                                        </li>
                                                        <?php
                                                    }
                                                }

                                                $nextPageOD=$pageOD+1;

                                                if($nextPageOD>$pageFooterOD)
                                                {
                                                    ?>
                                                    <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a></li>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <li class="page-item page-indicator"><a class="page-link" href="?page_noOD=<?php echo $nextPageOD ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-greater-than"></i></a></li>
                                                    <?php
                                                }
                                                if($nextPageOD<$pageFooterOD)
                                                {
                                                    ?>
                                                    <li class="page-item page-indicator"><a class="page-link" href="?page_noOD=<?php echo $pageFooterOD ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-angles-right"></i></a></li>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Not visit planner-->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Not visit planner</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive-md" style="text-align: center;">
                                    <thead style="background-color: #b1b1b1;">
                                    <tr>
                                        <th class="width80"><strong>#</strong></th>
                                        <th><strong>Marketing Person Name</strong></th>
                                        <th><strong>Customer Name</strong></th>
                                        <th><strong>Plan Date</strong></th>
                                        <th><strong>Visit Date</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Define the number of results per page
                                    $results_per_pageV = 10;

                                    // Determine the current page number
                                    $pageV = isset($_GET['page_noV']) ? (int)$_GET['page_noV'] : 1;
                                    if ($pageV < 1) $pageV = 1;

                                    // Calculate the starting limit for the SQL query
                                    $start_limitV = ($pageV - 1) * $results_per_pageV;

                                    // Get the current date
                                    $currentDateV = date('Y-m-d');

                                    // Get the total number of records
                                    $total_records_sqlV = "
                        SELECT COUNT(*) as total 
                        FROM marketing 
                        WHERE (visit_date IS NULL OR visit_date > next_date) 
                            AND next_date <= '$currentDateV'
                    ";
                                    $total_records_resultV = mysqli_query($conn, $total_records_sqlV);
                                    $total_recordsV = mysqli_fetch_assoc($total_records_resultV)['total'];

                                    // Fetch the records for the current page
                                    $sqlV = "
                        SELECT 
                            meet_person,
                            customer_name,
                            next_date,
                            visit_date
                        FROM 
                            marketing
                        WHERE 
                            (visit_date IS NULL OR visit_date > next_date)
                            AND next_date <= '$currentDateV'
                        ORDER BY 
                            next_date ASC
                        LIMIT $start_limitV, $results_per_pageV
                    ";
                                    $resultV = mysqli_query($conn, $sqlV);

                                    if (mysqli_num_rows($resultV) > 0) {
                                        $sNoV = $start_limitV;
                                        while ($rowV = mysqli_fetch_assoc($resultV)) {
                                            $sNoV++;
                                            $v_date = $rowV['visit_date'];

                                            if ($v_date == '0000-00-00') {
                                                $v_date = 'Not Visit';
                                            } else {
                                                $v_date = $rowV['visit_date'];
                                                $v_date = date('d-m-Y', strtotime($v_date));
                                            }
                                            $n_date = $rowV['next_date'];
                                            $n_date = date('d-m-Y', strtotime($n_date));
                                            ?>
                                            <tr>
                                                <td><strong><?php echo $sNoV; ?></strong></td>
                                                <td><?php echo $rowV['meet_person']; ?></td>
                                                <td><?php echo $rowV['customer_name']; ?></td>
                                                <td><?php echo $n_date; ?></td>
                                                <td><?php echo $v_date; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">No Record Found in planner</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <div class="col-12 pl-3" style="display: flex; justify-content: center;">
                                    <nav>
                                        <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                                            <?php
                                            // Calculate the total number of pages
                                            $total_pagesV = ceil($total_recordsV / $results_per_pageV);

                                            // Display previous page link
                                            if ($pageV > 1) {
                                                echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noV=1"><i class="fa-solid fa-angles-left"></i></a></li>';
                                                echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noV=' . ($pageV - 1) . '"><i class="fa-solid fa-less-than"></i></a></li>';
                                            } else {
                                                echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-left"></i></a></li>';
                                                echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>';
                                            }

                                            // Display page numbers
                                            for ($iV = 1; $iV <= $total_pagesV; $iV++) {
                                                if ($iV == $pageV) {
                                                    echo '<li class="page-item active"><a class="page-link" href="?page_noV=' . $iV . '">' . $iV . '</a></li>';
                                                } else {
                                                    echo '<li class="page-item"><a class="page-link" href="?page_noV=' . $iV . '">' . $iV . '</a></li>';
                                                }
                                            }

                                            // Display next page link
                                            if ($pageV < $total_pagesV) {
                                                echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noV=' . ($pageV + 1) . '"><i class="fa-solid fa-greater-than"></i></a></li>';
                                                echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noV=' . $total_pagesV . '"><i class="fa-solid fa-angles-right"></i></a></li>';
                                            } else {
                                                echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a></li>';
                                                echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-right"></i></a></li>';
                                            }
                                            ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product-Based Sales Graph -->
                <div class="col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
<!--                                    <h4 class="card-title">-->
                                        <div class="form-group col-md-6">
                                            <label>Product Name</label>
                                            <select class="form-control" id="product_name" name="product_name" style="border-color: #181f5a;color: black;border-radius:15px ">
                                                <?php
                                                $sqlProduct = "SELECT * FROM `product`";
                                                $resultProduct = mysqli_query($conn, $sqlProduct);
                                                if (mysqli_num_rows($resultProduct) > 0) {
                                                    while ($rowProduct = mysqli_fetch_array($resultProduct)) {
                                                        ?>
                                                        <option value='<?php echo $rowProduct['product_id']; ?>'><?php echo strtoupper($rowProduct['product_name']); ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Customer Name</label>
                                            <select class="form-control" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black;border-radius:15px ">
                                               <option value="">Select Customer</option>
                                                <?php
                                                $sqlCustomer = "SELECT * FROM `customer`";
                                                $resultCustomer = mysqli_query($conn, $sqlCustomer);
                                                if (mysqli_num_rows($resultCustomer) > 0) {
                                                    while ($rowCustomer = mysqli_fetch_array($resultCustomer)) {
                                                        ?>
                                                        <option value='<?php echo $rowCustomer['customer_id']; ?>'><?php echo strtoupper($rowCustomer['customer_name']); ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
<!--                                    </h4>-->
                                </div>
                                <div class="card-body">
                                    <div id="salesGraph" style="width: 100%; height: auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
<!--    top 5 selling product-->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top 5 Selling Products - <?php echo $currentMonthName; ?></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead style="background-color: #b1b1b1;">
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Product Name</strong></th>
                                <th><strong>Qty</strong></th>
<!--                                <th><strong>Amount</strong></th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $currentMonthStart = date('Y-m-01');
                            $currentMonthEnd = date('Y-m-t');

                            $sql = "SELECT product_name, SUM(qty) AS total_qty,SUM(sub_total) AS total_amount FROM sale_details WHERE sale_date BETWEEN '$currentMonthStart' AND '$currentMonthEnd' GROUP BY product_name ORDER BY total_qty DESC LIMIT 5";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                $sNo = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $sNo++;
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $sNo; ?></strong></td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo $row['total_qty']; ?></td>
<!--                                        <td>--><?php //echo number_format($row['total_amount'], 2); ?><!--</td>-->
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4">No data available for the current month.</td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<!--   top 5 selling customer-->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top 5 Selling Customer - <?php echo $currentMonthName; ?></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead style="background-color: #b1b1b1;">
                            <tr>
                                <th class="width80"><strong>#</strong></th>
<!--                                <th><strong>Product Name</strong></th>-->
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Total Amount</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $currentMonthStart = date('Y-m-01');
                            $currentMonthEnd = date('Y-m-t');

                            $sql = "SELECT product_name, customer, SUM(sub_total) AS total_amount FROM sale_details WHERE sale_date BETWEEN '$currentMonthStart' AND '$currentMonthEnd' GROUP BY product_name, customer ORDER BY total_amount DESC LIMIT 5";

                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $sNo = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $sNo++;
                                    $customer_id = $row['customer'];
                                    $sqlCustomer = "SELECT * FROM `customer` WHERE `customer_id`='$customer_id'";
                                    $resCustomer = mysqli_query($conn, $sqlCustomer);
                                    $rowCustomer = mysqli_fetch_assoc($resCustomer);
                                    $Customer_name =  $rowCustomer['customer_name'];
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $sNo; ?></strong></td>
<!--                                        <td>--><?php //echo $row['product_name']; ?><!--</td>-->
                                        <td><?php echo $Customer_name; ?></td>
                                        <td><?php echo number_format($row['total_amount'], 2); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4">No data available for the current month.</td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
     <!--low stock alert-->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Low Stock Alert</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead style="background-color: #b1b1b1;">
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Product Name</strong></th>
<!--                                <th><strong>Actual Stock Qty</strong></th>-->
                                <th><strong>Current Stock Qty</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Define the number of results per page
                            $results_per_pageL = 10;

                            // Determine the current page number
                            $pageL = isset($_GET['page_noL']) ? (int)$_GET['page_noL'] : 1;
                            if ($pageL < 1) $pageL = 1;

                            // Calculate the starting limit for the SQL query
                            $start_limitL = ($pageL - 1) * $results_per_pageL;

                            // Adjust the threshold as needed; here, we're assuming a low stock threshold of 10
                            $lowStockThresholdL = 10;

                            // Get the total number of records
                            $total_records_sqlL = "
                        SELECT COUNT(*) as total 
                        FROM product 
                        WHERE stock_qty <= stock_alert
                    ";
                            $total_records_resultL = mysqli_query($conn, $total_records_sqlL);
                            $total_recordsL = mysqli_fetch_assoc($total_records_resultL)['total'];

                            // Fetch the records for the current page
                            $sqlL = "
                        SELECT 
                            product_name,
                            stock_alert,
                            stock_qty
                        FROM 
                            product
                        WHERE 
                            stock_qty <= stock_alert
                        ORDER BY 
                            stock_qty ASC
                        LIMIT $start_limitL, $results_per_pageL
                    ";
                            $resultL = mysqli_query($conn, $sqlL);

                            if (mysqli_num_rows($resultL) > 0) {
                                $sNoL = $start_limitL;
                                while ($rowL = mysqli_fetch_assoc($resultL)) {
                                    $sNoL++;
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $sNoL; ?></strong></td>
                                        <td><?php echo $rowL['product_name']; ?></td>
<!--                                        <td>--><?php //echo $rowL['stock_alert']; ?><!--</td>-->
                                        <td><?php echo $rowL['stock_qty']; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4">No products are currently in low stock.</td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <div class="col-12 pl-3" style="display: flex; justify-content: center;">
                            <nav>
                                <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                                    <?php
                                    // Calculate the total number of pages
                                    $total_pagesL = ceil($total_recordsL / $results_per_pageL);

                                    // Display previous page link
                                    if ($pageL > 1) {
                                        echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=1"><i class="fa-solid fa-angles-left"></i></a></li>';
                                        echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . ($pageL - 1) . '"><i class="fa-solid fa-less-than"></i></a></li>';
                                    } else {
                                        echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-left"></i></a></li>';
                                        echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>';
                                    }

                                    // Display page numbers
                                    for ($iL = 1; $iL <= $total_pagesL; $iL++) {
                                        if ($iL == $pageL) {
                                            echo '<li class="page-item active"><a class="page-link" href="?page_noL=' . $iL . '">' . $iL . '</a></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="?page_noL=' . $iL . '">' . $iL . '</a></li>';
                                        }
                                    }

                                    // Display next page link
                                    if ($pageL < $total_pagesL) {
                                        echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . ($pageL + 1) . '"><i class="fa-solid fa-greater-than"></i></a></li>';
                                        echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . $total_pagesL . '"><i class="fa-solid fa-angles-right"></i></a></li>';
                                    } else {
                                        echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a></li>';
                                        echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-right"></i></a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     <!-- Not paid Within Due date Current week -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Not Paid Within Due Date - <span> Current Week Records</span></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="text-align: center;">
                            <thead style="background-color: #b1b1b1;">
                            <tr>
                                <th class="width80"><strong>#</strong></th>
                                <th><strong>Due Date</strong></th>
                                <th><strong>Po No</strong></th>
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Total Amount</strong></th>
                                <th><strong>Paid Amount</strong></th>
                                <th><strong>Balance Amount</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $pageD = isset($_GET['page_noD']) ? (int)$_GET['page_noD'] : 1;
                            if ($pageD <= 0) {
                                $pageD = 1;
                            }

                            $recordsPerPageD = 10;
                            $offsetD = ($pageD - 1) * $recordsPerPageD;

                            // Calculate the current week's start and end date
                            $currentDateD = date('Y-m-d');
                            $startOfWeekD = date('Y-m-d', strtotime('last Sunday', strtotime($currentDateD)));
                            $endOfWeekD = date('Y-m-d', strtotime('next Saturday', strtotime($currentDateD)));

                            // Fetch total number of records
                            $countSqlD = "SELECT COUNT(id) AS total FROM sale WHERE due_date BETWEEN '$startOfWeekD' AND '$endOfWeekD'";
                            $countResultD = mysqli_query($conn, $countSqlD);
                            $rowD = mysqli_fetch_assoc($countResultD);
                            $totalRecordsD = $rowD['total'];
                            $totalPagesD = ceil($totalRecordsD / $recordsPerPageD);

                            // Fetch paginated records
                            $sqlD = "SELECT * FROM sale WHERE due_date BETWEEN '$startOfWeekD' AND '$endOfWeekD' ORDER BY id DESC LIMIT $offsetD, $recordsPerPageD";
                            $resultD = mysqli_query($conn, $sqlD);

                            if (mysqli_num_rows($resultD) > 0) {
                                $sNoD = $offsetD;
                                while ($rowD = mysqli_fetch_assoc($resultD)) {
                                    $sNoD++;
                                    $customer_idD = $rowD['customer'];
                                    $sale_id = $rowD['sale_id'];

                                    $sqlAmountD = "SELECT SUM(pay_made) AS pay_made FROM sale_payment WHERE sale_id = '$sale_id'";
                                    $resAmountD = mysqli_query($conn, $sqlAmountD);
                                    $totalAmountD = 0;

                                    if (mysqli_num_rows($resAmountD) > 0) {
                                        $arrayAmountD = mysqli_fetch_assoc($resAmountD);
                                        $totalAmountD = $arrayAmountD['pay_made'];
                                    }
                                    $grand_totalD = $rowD['grand_total'];
                                    $balance_amountD = $grand_totalD - $totalAmountD;
                                    $balanceAmount_formattedD = number_format($balance_amountD, 2, '.', '');

                                    if ($totalAmountD == '') {
                                        $totalAmount_formattedD = 'NA';
                                    } else {
                                        $totalAmount_formattedD = number_format($totalAmountD, 2, '.', '');
                                    }
                                    $sqlCustomerD = "SELECT * FROM customer WHERE customer_id = '$customer_idD'";
                                    $resCustomerD = mysqli_query($conn, $sqlCustomerD);
                                    $rowCustomerD = mysqli_fetch_assoc($resCustomerD);
                                    $customer_nameD = $rowCustomerD['customer_name'];
                                    $d_date = $rowD['due_date'];
                                    $d_date = date('d-m-Y', strtotime($d_date));
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $sNoD; ?></strong></td>
                                        <td><?php echo $d_date; ?></td>
                                        <td><?php echo $rowD['po_no']; ?></td>
                                        <td><?php echo $customer_nameD; ?></td>
                                        <td><?php echo number_format($rowD['grand_total'], 2, '.', ''); ?></td>
                                        <td><?php echo $totalAmount_formattedD; ?></td>
                                        <td><?php echo $balanceAmount_formattedD; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7">No Record Found</td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="col-12 pl-3" style="display: flex; justify-content: center;">
                            <nav>
                                <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                                    <?php if ($pageD > 1): ?>
                                        <li class="page-item page-indicator">
                                            <a class="page-link" href="?page_noD=1"><i class="fa-solid fa-angles-left"></i></a>
                                        </li>
                                        <li class="page-item page-indicator">
                                            <a class="page-link" href="?page_noD=<?php echo $pageD - 1; ?>"><i class="fa-solid fa-less-than"></i></a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item page-indicator disabled">
                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-left"></i></a>
                                        </li>
                                        <li class="page-item page-indicator disabled">
                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($iD = 1; $iD <= $totalPagesD; $iD++): ?>
                                        <li class="page-item <?php echo $iD == $pageD ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page_noD=<?php echo $iD; ?>"><?php echo $iD; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pageD < $totalPagesD): ?>
                                        <li class="page-item page-indicator">
                                            <a class="page-link" href="?page_noD=<?php echo $pageD + 1; ?>"><i class="fa-solid fa-greater-than"></i></a>
                                        </li>
                                        <li class="page-item page-indicator">
                                            <a class="page-link" href="?page_noD=<?php echo $totalPagesD; ?>"><i class="fa-solid fa-angles-right"></i></a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item page-indicator disabled">
                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a>
                                        </li>
                                        <li class="page-item page-indicator disabled">
                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-right"></i></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <!-- End of Pagination -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Weekly Expense Chart -->
        <div class="col-lg-6 col-md-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Weekly Expense Graph - <?php echo date('Y'); ?></h4>
                        </div>
                        <div class="card-body">
                            <div id="expenseGraph" style="width: 100%; height: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
    </div>

    <?php
    }
    ?>
    <!--    Sales login-->
    <?php
         if($_COOKIE['role'] == "Sales") {
             $sqlSaleS= "SELECT COUNT(id) as count FROM sale";
             $resultSaleS = mysqli_query($conn, $sqlSaleS);
             $rowSaleS = mysqli_fetch_assoc($resultSaleS);
             $totalSaleS = $rowSaleS['count'];

             $sqlSaleReturnS= "SELECT COUNT(id) as count FROM sale_return";
             $resultSaleReturnS = mysqli_query($conn, $sqlSaleReturnS);
             $rowSaleReturnS = mysqli_fetch_assoc($resultSaleReturnS);
             $totalSaleReturnS = $rowSaleReturnS['count'];

           echo  $sqlProduct= "SELECT COUNT(bill_no) as count FROM status";
             $resultProduct = mysqli_query($conn, $sqlProduct);
             $rowProduct = mysqli_fetch_assoc($resultProduct);
             $totalProduct= $rowProduct['count'];
      ?>
             <div class="content-body">
                 <div class="container-fluid">
                     <div class="row">
                         <div class="col-xl-12">
                             <div class="row">
                                 <div class="col-xl-3 col-lg-6 col-sm-6">
                                         <div class="widget-stat card bg-success">
                                             <div class="card-body p-4">
                                                 <div class="media">
                                                     <div class="media-body text-white">
                                                         <p class="mb-1" style="text-align: center;">Total Sale</p>
                                                         <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSaleS; ?></h3>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 <div class="col-xl-3 col-lg-6 col-sm-6">
                                     <div class="widget-stat card bg-danger">
                                         <div class="card-body p-4">
                                             <div class="media">
                                                 <div class="media-body text-white">
                                                     <p class="mb-1" style="text-align: center;">Sales Return</p>
                                                     <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSaleReturnS == ''?0:$totalSaleReturnS; ?></h3>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-xl-3 col-lg-6 col-sm-6">
                                     <div class="widget-stat card bg-primary">
                                         <div class="card-body p-4">
                                             <div class="media">
                                                 <div class="media-body text-white">
                                                     <p class="mb-1" style="text-align: center;">PO Received</p>
                                                     <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalProduct == ''?0:$totalProduct; ?></h3>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="row">
                 <!--    top 5 selling product-->
                 <div class="col-lg-6 col-md-12">
                     <div class="card">
                         <div class="card-header">
                             <h4 class="card-title">Top 5 Selling Products - <?php echo $currentMonthName; ?></h4>
                         </div>
                         <div class="card-body">
                             <div class="table-responsive">
                                 <table class="table table-responsive-md" style="text-align: center;">
                                     <thead style="background-color: #b1b1b1;">
                                     <tr>
                                         <th class="width80"><strong>#</strong></th>
                                         <th><strong>Product Name</strong></th>
                                         <th><strong>Qty</strong></th>
                                         <th><strong>Amount</strong></th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     <?php
                                     $currentMonthStart = date('Y-m-01');
                                     $currentMonthEnd = date('Y-m-t');

                                     $sql = "SELECT product_name, SUM(qty) AS total_qty,SUM(sub_total) AS total_amount FROM sale_details WHERE sale_date BETWEEN '$currentMonthStart' AND '$currentMonthEnd' GROUP BY product_name ORDER BY total_qty DESC LIMIT 5";
                                     $result = mysqli_query($conn, $sql);
                                     if (mysqli_num_rows($result) > 0) {
                                         $sNo = 0;
                                         while ($row = mysqli_fetch_assoc($result)) {
                                             $sNo++;
                                             ?>
                                             <tr>
                                                 <td><strong><?php echo $sNo; ?></strong></td>
                                                 <td><?php echo $row['product_name']; ?></td>
                                                 <td><?php echo $row['total_qty']; ?></td>
                                                 <td><?php echo number_format($row['total_amount'], 2); ?></td>
                                             </tr>
                                             <?php
                                         }
                                     } else {
                                         ?>
                                         <tr>
                                             <td colspan="4">No data available for the current month.</td>
                                         </tr>
                                         <?php
                                     }
                                     ?>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!--   top 5 selling customer-->
                 <div class="col-lg-6 col-md-12">
                     <div class="card">
                         <div class="card-header">
                             <h4 class="card-title">Top 5 Selling Customer - <?php echo $currentMonthName; ?></h4>
                         </div>
                         <div class="card-body">
                             <div class="table-responsive">
                                 <table class="table table-responsive-md" style="text-align: center;">
                                     <thead style="background-color: #b1b1b1;">
                                     <tr>
                                         <th class="width80"><strong>#</strong></th>
                                         <th><strong>Product Name</strong></th>
                                         <th><strong>Customer Name</strong></th>
                                         <th><strong>Total Amount</strong></th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     <?php
                                     $currentMonthStart = date('Y-m-01');
                                     $currentMonthEnd = date('Y-m-t');

                                     $sql = "SELECT product_name, customer, SUM(sub_total) AS total_amount FROM sale_details WHERE sale_date BETWEEN '$currentMonthStart' AND '$currentMonthEnd' GROUP BY product_name, customer ORDER BY total_amount DESC LIMIT 5";

                                     $result = mysqli_query($conn, $sql);

                                     if (mysqli_num_rows($result) > 0) {
                                         $sNo = 0;
                                         while ($row = mysqli_fetch_assoc($result)) {
                                             $sNo++;
                                             $customer_id = $row['customer'];
                                             $sqlCustomer = "SELECT * FROM `customer` WHERE `customer_id`='$customer_id'";
                                             $resCustomer = mysqli_query($conn, $sqlCustomer);
                                             $rowCustomer = mysqli_fetch_assoc($resCustomer);
                                             $Customer_name =  $rowCustomer['customer_name'];
                                             ?>
                                             <tr>
                                                 <td><strong><?php echo $sNo; ?></strong></td>
                                                 <td><?php echo $row['product_name']; ?></td>
                                                 <td><?php echo $Customer_name; ?></td>
                                                 <td><?php echo number_format($row['total_amount'], 2); ?></td>
                                             </tr>
                                             <?php
                                         }
                                     } else {
                                         ?>
                                         <tr>
                                             <td colspan="4">No data available for the current month.</td>
                                         </tr>
                                         <?php
                                     }
                                     ?>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!--low stock alert-->
                 <div class="col-lg-6 col-md-12">
                     <div class="card">
                         <div class="card-header">
                             <h4 class="card-title">Low Stock Alert</h4>
                         </div>
                         <div class="card-body">
                             <div class="table-responsive">
                                 <table class="table table-responsive-md" style="text-align: center;">
                                     <thead style="background-color: #b1b1b1;">
                                     <tr>
                                         <th class="width80"><strong>#</strong></th>
                                         <th><strong>Product Name</strong></th>
<!--                                         <th><strong>Actual Stock Qty</strong></th>-->
                                         <th><strong>Current Stock Qty</strong></th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     <?php
                                     // Define the number of results per page
                                     $results_per_pageL = 10;

                                     // Determine the current page number
                                     $pageL = isset($_GET['page_noL']) ? (int)$_GET['page_noL'] : 1;
                                     if ($pageL < 1) $pageL = 1;

                                     // Calculate the starting limit for the SQL query
                                     $start_limitL = ($pageL - 1) * $results_per_pageL;

                                     // Adjust the threshold as needed; here, we're assuming a low stock threshold of 10
                                     $lowStockThresholdL = 10;

                                     // Get the total number of records
                                     $total_records_sqlL = "
                        SELECT COUNT(*) as total 
                        FROM product 
                        WHERE stock_qty <= stock_alert
                    ";
                                     $total_records_resultL = mysqli_query($conn, $total_records_sqlL);
                                     $total_recordsL = mysqli_fetch_assoc($total_records_resultL)['total'];

                                     // Fetch the records for the current page
                                     $sqlL = "
                        SELECT 
                            product_name,
                            stock_alert,
                            stock_qty
                        FROM 
                            product
                        WHERE 
                            stock_qty <= stock_alert
                        ORDER BY 
                            stock_qty ASC
                        LIMIT $start_limitL, $results_per_pageL
                    ";
                                     $resultL = mysqli_query($conn, $sqlL);

                                     if (mysqli_num_rows($resultL) > 0) {
                                         $sNoL = $start_limitL;
                                         while ($rowL = mysqli_fetch_assoc($resultL)) {
                                             $sNoL++;
                                             ?>
                                             <tr>
                                                 <td><strong><?php echo $sNoL; ?></strong></td>
                                                 <td><?php echo $rowL['product_name']; ?></td>
<!--                                                 <td>--><?php //echo $rowL['stock_alert']; ?><!--</td>-->
                                                 <td><?php echo $rowL['stock_qty']; ?></td>
                                             </tr>
                                             <?php
                                         }
                                     } else {
                                         ?>
                                         <tr>
                                             <td colspan="4">No products are currently in low stock.</td>
                                         </tr>
                                         <?php
                                     }
                                     ?>
                                     </tbody>
                                 </table>
                                 <div class="col-12 pl-3" style="display: flex; justify-content: center;">
                                     <nav>
                                         <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                                             <?php
                                             // Calculate the total number of pages
                                             $total_pagesL = ceil($total_recordsL / $results_per_pageL);

                                             // Display previous page link
                                             if ($pageL > 1) {
                                                 echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=1"><i class="fa-solid fa-angles-left"></i></a></li>';
                                                 echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . ($pageL - 1) . '"><i class="fa-solid fa-less-than"></i></a></li>';
                                             } else {
                                                 echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-left"></i></a></li>';
                                                 echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>';
                                             }

                                             // Display page numbers
                                             for ($iL = 1; $iL <= $total_pagesL; $iL++) {
                                                 if ($iL == $pageL) {
                                                     echo '<li class="page-item active"><a class="page-link" href="?page_noL=' . $iL . '">' . $iL . '</a></li>';
                                                 } else {
                                                     echo '<li class="page-item"><a class="page-link" href="?page_noL=' . $iL . '">' . $iL . '</a></li>';
                                                 }
                                             }

                                             // Display next page link
                                             if ($pageL < $total_pagesL) {
                                                 echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . ($pageL + 1) . '"><i class="fa-solid fa-greater-than"></i></a></li>';
                                                 echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . $total_pagesL . '"><i class="fa-solid fa-angles-right"></i></a></li>';
                                             } else {
                                                 echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a></li>';
                                                 echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-right"></i></a></li>';
                                             }
                                             ?>
                                         </ul>
                                     </nav>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 </div>
             </div>
     <?php
         }
    ?>
<!--    Accounts login-->
    <?php
    if($_COOKIE['role'] == "Accounts") {
        $sqlSale= "SELECT COUNT(id) as count FROM sale";
        $resultSale = mysqli_query($conn, $sqlSale);
        $rowSale = mysqli_fetch_assoc($resultSale);
        $totalSale = $rowSale['count'];

        $sqlPurchase= "SELECT COUNT(id) as count FROM purchase";
        $resultPurchase = mysqli_query($conn, $sqlPurchase);
        $rowPurchase = mysqli_fetch_assoc($resultPurchase);
        $totalPurchase = $rowPurchase['count'];

        $sqlSaleReturn= "SELECT COUNT(id) as count FROM sale_return";
        $resultSaleReturn = mysqli_query($conn, $sqlSaleReturn);
        $rowSaleReturn = mysqli_fetch_assoc($resultSaleReturn);
        $totalSaleReturn = $rowSaleReturn['count'];

        $sqlPurchaseReturn= "SELECT COUNT(id) as count FROM purchase_return";
        $resultPurchaseReturn = mysqli_query($conn, $sqlPurchaseReturn);
        $rowPurchaseReturn = mysqli_fetch_assoc($resultPurchaseReturn);
        $totalPurchaseReturn = $rowPurchaseReturn['count'];
        ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <?php
                            if($_COOKIE['role'] != "") {
                                if($_COOKIE['role'] != "") {
                                    ?>
                                    <div class="col-xl-3 col-lg-6 col-sm-6">
                                        <div class="widget-stat card bg-danger">
                                            <div class="card-body  p-4">
                                                <div class="media">
                                                    <div class="media-body text-white">
                                                        <p class="mb-1" style="text-align: center;">Total Purchase</p>
                                                        <!-- <h3 class="text-white"><?php //echo $total_visits; ?></h3> -->
                                                        <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalPurchase; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="col-xl-3 col-lg-6 col-sm-6">
                                    <div class="widget-stat card bg-success">
                                        <div class="card-body p-4">
                                            <div class="media">
                                                <div class="media-body text-white">
                                                    <p class="mb-1" style="text-align: center;">Total Sale</p>
                                                    <!-- <h3 class="text-white"><?php //echo $total_desktops; ?></h3> -->
                                                    <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSale; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-info">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Purchase Return</p>
                                                <!-- <h3 class="text-white"><?php //echo $total_mobiles; ?></h3> -->
                                                <h3 class="text-white"style="margin-top: 30px; text-align: center;"><?php echo $totalPurchaseReturn == ''?0:$totalPurchaseReturn; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php

                            ?>
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-primary">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Sales Return</p>
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSaleReturn == ''?0:$totalSaleReturn; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            <!-- Not paid Within Due date Current week -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Not Paid Within Due Date - <span> Current Week Records</span></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" style="text-align: center;">
                                <thead style="background-color: #b1b1b1;">
                                <tr>
                                    <th class="width80"><strong>#</strong></th>
                                    <th><strong>Due Date</strong></th>
                                    <th><strong>Po No</strong></th>
                                    <th><strong>Customer Name</strong></th>
                                    <th><strong>Total Amount</strong></th>
                                    <th><strong>Paid Amount</strong></th>
                                    <th><strong>Balance Amount</strong></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $pageD = isset($_GET['page_noD']) ? (int)$_GET['page_noD'] : 1;
                                if ($pageD <= 0) {
                                    $pageD = 1;
                                }

                                $recordsPerPageD = 10;
                                $offsetD = ($pageD - 1) * $recordsPerPageD;

                                // Calculate the current week's start and end date
                                $currentDateD = date('Y-m-d');
                                $startOfWeekD = date('Y-m-d', strtotime('last Sunday', strtotime($currentDateD)));
                                $endOfWeekD = date('Y-m-d', strtotime('next Saturday', strtotime($currentDateD)));

                                // Fetch total number of records
                                $countSqlD = "SELECT COUNT(id) AS total FROM sale WHERE due_date BETWEEN '$startOfWeekD' AND '$endOfWeekD'";
                                $countResultD = mysqli_query($conn, $countSqlD);
                                $rowD = mysqli_fetch_assoc($countResultD);
                                $totalRecordsD = $rowD['total'];
                                $totalPagesD = ceil($totalRecordsD / $recordsPerPageD);

                                // Fetch paginated records
                                $sqlD = "SELECT * FROM sale WHERE due_date BETWEEN '$startOfWeekD' AND '$endOfWeekD' ORDER BY id DESC LIMIT $offsetD, $recordsPerPageD";
                                $resultD = mysqli_query($conn, $sqlD);

                                if (mysqli_num_rows($resultD) > 0) {
                                    $sNoD = $offsetD;
                                    while ($rowD = mysqli_fetch_assoc($resultD)) {
                                        $sNoD++;
                                        $customer_idD = $rowD['customer'];
                                        $sale_id = $rowD['sale_id'];

                                        $sqlAmountD = "SELECT SUM(pay_made) AS pay_made FROM sale_payment WHERE sale_id = '$sale_id'";
                                        $resAmountD = mysqli_query($conn, $sqlAmountD);
                                        $totalAmountD = 0;

                                        if (mysqli_num_rows($resAmountD) > 0) {
                                            $arrayAmountD = mysqli_fetch_assoc($resAmountD);
                                            $totalAmountD = $arrayAmountD['pay_made'];
                                        }
                                        $grand_totalD = $rowD['grand_total'];
                                        $balance_amountD = $grand_totalD - $totalAmountD;
                                        $balanceAmount_formattedD = number_format($balance_amountD, 2, '.', '');

                                        if ($totalAmountD == '') {
                                            $totalAmount_formattedD = 'NA';
                                        } else {
                                            $totalAmount_formattedD = number_format($totalAmountD, 2, '.', '');
                                        }
                                        $sqlCustomerD = "SELECT * FROM customer WHERE customer_id = '$customer_idD'";
                                        $resCustomerD = mysqli_query($conn, $sqlCustomerD);
                                        $rowCustomerD = mysqli_fetch_assoc($resCustomerD);
                                        $customer_nameD = $rowCustomerD['customer_name'];
                                        $d_date = $rowD['due_date'];
                                        $d_date = date('d-m-Y', strtotime($d_date));
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $sNoD; ?></strong></td>
                                            <td><?php echo $d_date; ?></td>
                                            <td><?php echo $rowD['po_no']; ?></td>
                                            <td><?php echo $customer_nameD; ?></td>
                                            <td><?php echo number_format($rowD['grand_total'], 2, '.', ''); ?></td>
                                            <td><?php echo $totalAmount_formattedD; ?></td>
                                            <td><?php echo $balanceAmount_formattedD; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">No Record Found</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <div class="col-12 pl-3" style="display: flex; justify-content: center;">
                                <nav>
                                    <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                                        <?php if ($pageD > 1): ?>
                                            <li class="page-item page-indicator">
                                                <a class="page-link" href="?page_noD=1"><i class="fa-solid fa-angles-left"></i></a>
                                            </li>
                                            <li class="page-item page-indicator">
                                                <a class="page-link" href="?page_noD=<?php echo $pageD - 1; ?>"><i class="fa-solid fa-less-than"></i></a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item page-indicator disabled">
                                                <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-left"></i></a>
                                            </li>
                                            <li class="page-item page-indicator disabled">
                                                <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($iD = 1; $iD <= $totalPagesD; $iD++): ?>
                                            <li class="page-item <?php echo $iD == $pageD ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page_noD=<?php echo $iD; ?>"><?php echo $iD; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($pageD < $totalPagesD): ?>
                                            <li class="page-item page-indicator">
                                                <a class="page-link" href="?page_noD=<?php echo $pageD + 1; ?>"><i class="fa-solid fa-greater-than"></i></a>
                                            </li>
                                            <li class="page-item page-indicator">
                                                <a class="page-link" href="?page_noD=<?php echo $totalPagesD; ?>"><i class="fa-solid fa-angles-right"></i></a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item page-indicator disabled">
                                                <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a>
                                            </li>
                                            <li class="page-item page-indicator disabled">
                                                <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-right"></i></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <!-- End of Pagination -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Not Paid Within Over Due Date Past week-->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Not Paid Within Over Due Date - <span> Past Week Records</span></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" style="text-align: center;">
                                <thead style="background-color: #b1b1b1;">
                                <tr>
                                    <th class="width80"><strong>#</strong></th>
                                    <th><strong>Due Date</strong></th>
                                    <th><strong>Po No</strong></th>
                                    <th><strong>Customer Name</strong></th>
                                    <th><strong>Total Amount</strong></th>
                                    <th><strong>Paid Amount</strong></th>
                                    <th><strong>Balance Amount</strong></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $pageOD= $_GET['page_noOD'];
                                if($pageOD=='') {
                                    $pageOD=1;
                                }
                                $pageSqlOD= $pageOD-1;
                                $startOD=$pageSqlOD*10;
                                $endOD = $startOD;

                                if($pageSqlOD == 0) {
                                    $endOD = 10;
                                }
                                // Calculate the start and end date of the last week
                                $currentDateOD = date('Y-m-d');
                                $startOfLastWeekOD = date('Y-m-d', strtotime('last Sunday -1 week'));
                                $endOfLastWeekOD = date('Y-m-d', strtotime('last Saturday'));

                                $sqlOD = "SELECT * FROM sale WHERE due_date BETWEEN '$startOfLastWeekOD' AND '$endOfLastWeekOD' ORDER BY id DESC";
                                $resultOD = mysqli_query($conn, $sqlOD);

                                if (mysqli_num_rows($resultOD) > 0) {
                                    $sNoOD = 0;
                                    $sNoOD = $startOD;
                                    while ($rowOD = mysqli_fetch_assoc($resultOD)) {
                                        $sNoOD++;
                                        $customer_idOD = $rowOD['customer'];
                                        $sale_idOD = $rowOD['sale_id'];

                                        $sqlAmountOD = "SELECT SUM(pay_made) AS pay_made FROM sale_payment WHERE sale_id = '$sale_idOD'";
                                        $resAmountOD = mysqli_query($conn, $sqlAmountOD);
                                        $totalAmountOD = 0;

                                        if (mysqli_num_rows($resAmountOD) > 0) {
                                            $arrayAmountOD = mysqli_fetch_assoc($resAmountOD);
                                            $totalAmountOD = $arrayAmountOD['pay_made'];
                                        }
                                        $grand_totalOD = $rowOD['grand_total'];
                                        $balance_amountOD = $grand_totalOD - $totalAmountOD;
                                        $balanceAmount_formattedOD = number_format($balance_amountOD, 2, '.', '');

                                        if ($totalAmountOD == '') {
                                            $totalAmount_formattedOD = 'NA';
                                        } else {
                                            $totalAmount_formattedOD = number_format($totalAmountOD, 2, '.', '');
                                        }
                                        $sqlCustomerOD = "SELECT * FROM customer WHERE customer_id = '$customer_idOD'";
                                        $resCustomerOD = mysqli_query($conn, $sqlCustomerOD);
                                        $rowCustomerOD = mysqli_fetch_assoc($resCustomerOD);
                                        $customer_nameOD = $rowCustomerOD['customer_name'];
                                        $d_date = $rowOD['due_date'];
                                        $d_date = date('d-m-Y', strtotime($d_date));
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $sNoOD; ?></strong></td>
                                            <td><?php echo $d_date; ?></td>
                                            <td><?php echo $rowOD['po_no']; ?></td>
                                            <td><?php echo $customer_nameOD; ?></td>
                                            <td><?php echo number_format($rowOD['grand_total'], 2, '.', ''); ?></td>
                                            <td><?php echo $totalAmount_formattedOD; ?></td>
                                            <td><?php echo $balanceAmount_formattedOD; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">No Record Found</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="col-12 pl-3" style="display: flex;justify-content: center;">
                                <nav>
                                    <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">

                                        <?php

                                        $prevPageOD=abs($pageOD-1);
                                        if ($prevPageOD > 0) {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link"
                                                                                    href="?page_noOD=<?php echo 1 ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-angles-left"></i></a></li>
                                            <?php
                                        }

                                        if($prevPageOD==0)
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <li class="page-item page-indicator"><a class="page-link" href="?page_noOD=<?php echo $prevPageOD?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-less-than"></i></a></li>
                                            <?php
                                        }
                                        $sqlOD = "SELECT COUNT(id) as count FROM sale WHERE due_date BETWEEN '$startOfLastWeekOD' AND '$endOfLastWeekOD' ORDER BY id DESC";

                                        $resultOD = mysqli_query($conn, $sqlOD);

                                        if (mysqli_num_rows($resultOD)) {

                                            $rowOD = mysqli_fetch_assoc($resultOD);
                                            $countOD = $rowOD['count'];
                                            $showOD = 10;

                                            $getOD = $countOD / $showOD;

                                            $pageFooterOD = floor($getOD);

                                            if ($getOD > $pageFooterOD) {
                                                $pageFooterOD++;
                                            }

                                            for ($iOD = 1; $iOD <= $pageFooterOD; $iOD++) {

                                                if($iOD==$pageOD) {
                                                    $activeOD = "active";
                                                }
                                                else {
                                                    $activeOD = "";
                                                }

                                                if($iOD<=($pageSqlOD+10) && $i>$pageSqlOD || $pageFooterOD<=10) {

                                                    ?>

                                                    <li class="page-item <?php echo $activeOD ?>"><a class="page-link"
                                                                                                     href="?page_noOD=<?php echo $iOD ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><?php echo $iOD ?></a>
                                                    </li>
                                                    <?php
                                                }
                                            }

                                            $nextPageOD=$pageOD+1;

                                            if($nextPageOD>$pageFooterOD)
                                            {
                                                ?>
                                                <li class="page-item page-indicator"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a></li>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <li class="page-item page-indicator"><a class="page-link" href="?page_noOD=<?php echo $nextPageOD ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-greater-than"></i></a></li>
                                                <?php
                                            }
                                            if($nextPageOD<$pageFooterOD)
                                            {
                                                ?>
                                                <li class="page-item page-indicator"><a class="page-link" href="?page_noOD=<?php echo $pageFooterOD ?>&pur_id=<?php echo $pur_id ?>&f_date=<?php echo $from_date?>&t_date=<?php echo $to_date?>"><i class="fa-solid fa-angles-right"></i></a></li>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <?php
    }
    ?>
    <!--  Stores login-->
    <?php
    if($_COOKIE['role'] == "Stores") {
        $sqlSaleS= "SELECT COUNT(id) as count FROM sale";
        $resultSaleS = mysqli_query($conn, $sqlSaleS);
        $rowSaleS = mysqli_fetch_assoc($resultSaleS);
        $totalSaleS = $rowSaleS['count'];

        $sqlSaleReturnS= "SELECT COUNT(id) as count FROM sale_return";
        $resultSaleReturnS = mysqli_query($conn, $sqlSaleReturnS);
        $rowSaleReturnS = mysqli_fetch_assoc($resultSaleReturnS);
        $totalSaleReturnS = $rowSaleReturnS['count'];

        $sqlProduct= "SELECT COUNT(id) as count FROM purchase_status";
        $resultProduct = mysqli_query($conn, $sqlProduct);
        $rowProduct = mysqli_fetch_assoc($resultProduct);
        $totalProduct= $rowProduct['count'];
        ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-success">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Total Sale</p>
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSaleS; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-danger">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Sales Return</p>
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalSaleReturnS == ''?0:$totalSaleReturnS; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-primary">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Product Received</p>
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalProduct == ''?0:$totalProduct; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--low stock alert-->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Low Stock Alert</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" style="text-align: center;">
                                <thead style="background-color: #b1b1b1;">
                                <tr>
                                    <th class="width80"><strong>#</strong></th>
                                    <th><strong>Product Name</strong></th>
<!--                                    <th><strong>Actual Stock Qty</strong></th>-->
                                    <th><strong>Current Stock Qty</strong></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Define the number of results per page
                                $results_per_pageL = 10;

                                // Determine the current page number
                                $pageL = isset($_GET['page_noL']) ? (int)$_GET['page_noL'] : 1;
                                if ($pageL < 1) $pageL = 1;

                                // Calculate the starting limit for the SQL query
                                $start_limitL = ($pageL - 1) * $results_per_pageL;

                                // Adjust the threshold as needed; here, we're assuming a low stock threshold of 10
                                $lowStockThresholdL = 10;

                                // Get the total number of records
                                $total_records_sqlL = "
                        SELECT COUNT(*) as total 
                        FROM product 
                        WHERE stock_qty <= stock_alert
                    ";
                                $total_records_resultL = mysqli_query($conn, $total_records_sqlL);
                                $total_recordsL = mysqli_fetch_assoc($total_records_resultL)['total'];

                                // Fetch the records for the current page
                                $sqlL = "
                        SELECT 
                            product_name,
                            stock_alert,
                            stock_qty
                        FROM 
                            product
                        WHERE 
                            stock_qty <= stock_alert
                        ORDER BY 
                            stock_qty ASC
                        LIMIT $start_limitL, $results_per_pageL
                    ";
                                $resultL = mysqli_query($conn, $sqlL);

                                if (mysqli_num_rows($resultL) > 0) {
                                    $sNoL = $start_limitL;
                                    while ($rowL = mysqli_fetch_assoc($resultL)) {
                                        $sNoL++;
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $sNoL; ?></strong></td>
                                            <td><?php echo $rowL['product_name']; ?></td>
<!--                                            <td>--><?php //echo $rowL['stock_alert']; ?><!--</td>-->
                                            <td><?php echo $rowL['stock_qty']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4">No products are currently in low stock.</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="col-12 pl-3" style="display: flex; justify-content: center;">
                                <nav>
                                    <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                                        <?php
                                        // Calculate the total number of pages
                                        $total_pagesL = ceil($total_recordsL / $results_per_pageL);

                                        // Display previous page link
                                        if ($pageL > 1) {
                                            echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=1"><i class="fa-solid fa-angles-left"></i></a></li>';
                                            echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . ($pageL - 1) . '"><i class="fa-solid fa-less-than"></i></a></li>';
                                        } else {
                                            echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-left"></i></a></li>';
                                            echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a></li>';
                                        }

                                        // Display page numbers
                                        for ($iL = 1; $iL <= $total_pagesL; $iL++) {
                                            if ($iL == $pageL) {
                                                echo '<li class="page-item active"><a class="page-link" href="?page_noL=' . $iL . '">' . $iL . '</a></li>';
                                            } else {
                                                echo '<li class="page-item"><a class="page-link" href="?page_noL=' . $iL . '">' . $iL . '</a></li>';
                                            }
                                        }

                                        // Display next page link
                                        if ($pageL < $total_pagesL) {
                                            echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . ($pageL + 1) . '"><i class="fa-solid fa-greater-than"></i></a></li>';
                                            echo '<li class="page-item page-indicator"><a class="page-link" href="?page_noL=' . $total_pagesL . '"><i class="fa-solid fa-angles-right"></i></a></li>';
                                        } else {
                                            echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a></li>';
                                            echo '<li class="page-item page-indicator disabled"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-right"></i></a></li>';
                                        }
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <!--    Market login-->
    <?php
    if($_COOKIE['role'] == "Market") {
        // Get the current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

// Fetch total visits and commitments for the current month from the target table
        $sqlTarget = "SELECT 
                SUM(no_visit) AS total_visits, 
                SUM(commitment_value) AS total_commitments 
              FROM 
                target 
              WHERE 
                month = '$currentMonth' AND year = '$currentYear'";
        $resultTarget = mysqli_query($conn, $sqlTarget);
        $rowTarget = mysqli_fetch_assoc($resultTarget);
        $totalVisits = ($rowTarget['total_visits']) ? $rowTarget['total_visits'] : 0;
        $totalCommitments = ($rowTarget['total_commitments']) ? $rowTarget['total_commitments'] : 0;

// Fetch achieved visits for the current month from the marketing table
        $sqlAchievedVisits = "SELECT COUNT(*) AS achieved_visits 
                      FROM marketing 
                      WHERE MONTH(visit_date) = '$currentMonth' AND YEAR(visit_date) = '$currentYear'";
        $resultAchievedVisits = mysqli_query($conn, $sqlAchievedVisits);
        $rowAchievedVisits = mysqli_fetch_assoc($resultAchievedVisits);
        $achievedVisits = ($rowAchievedVisits['achieved_visits']) ? $rowAchievedVisits['achieved_visits'] : 0;

// Fetch achieved commitments for the current month from the marketing table
        $sqlAchievedCommitments = "SELECT SUM(commitment_value) AS achieved_commitments 
                           FROM marketing 
                           WHERE MONTH(visit_date) = '$currentMonth' AND YEAR(visit_date) = '$currentYear'";
        $resultAchievedCommitments = mysqli_query($conn, $sqlAchievedCommitments);
        $rowAchievedCommitments = mysqli_fetch_assoc($resultAchievedCommitments);
        $achievedCommitments = ($rowAchievedCommitments['achieved_commitments']) ? $rowAchievedCommitments['achieved_commitments'] : 0;

// Display the results
        $monthNames = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];
        $currentMonthName = $monthNames[$currentMonth];
        ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                                    <div class="col-xl-3 col-lg-6 col-sm-6">
                                        <div class="widget-stat card bg-danger">
                                            <div class="card-body  p-4">
                                                <div class="media">
                                                    <div class="media-body text-white">
                                                        <p class="mb-1" style="text-align: center;">Target Visit</p>
                                                        <!-- <h3 class="text-white"><?php //echo $total_visits; ?></h3> -->
                                                        <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalVisits; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="col-xl-3 col-lg-6 col-sm-6">
                                    <div class="widget-stat card bg-success">
                                        <div class="card-body p-4">
                                            <div class="media">
                                                <div class="media-body text-white">
                                                    <p class="mb-1" style="text-align: center;">Achieved Visit</p>
                                                    <!-- <h3 class="text-white"><?php //echo $total_desktops; ?></h3> -->
                                                    <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $achievedVisits; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-info">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Target Commitment</p>
                                                <!-- <h3 class="text-white"><?php //echo $total_mobiles; ?></h3> -->
                                                <h3 class="text-white"style="margin-top: 30px; text-align: center;"><?php echo $totalCommitments == ''?0:$totalCommitments; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-primary">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Achieved Commitment</p>
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $achievedCommitments == ''?0:$achievedCommitments; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product-Based Sales Graph -->
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
<!--                                        <h4 class="card-title">-->
                                            <div class="form-group col-md-6">
                                                <label>Product Name</label>
                                                <select class="form-control" id="product_name" name="product_name" style="border-color: #181f5a;color: black;border-radius:15px ">
                                                    <?php
                                                    $sqlProduct = "SELECT * FROM `product`";
                                                    $resultProduct = mysqli_query($conn, $sqlProduct);
                                                    if (mysqli_num_rows($resultProduct) > 0) {
                                                        while ($rowProduct = mysqli_fetch_array($resultProduct)) {
                                                            ?>
                                                            <option value='<?php echo $rowProduct['product_id']; ?>'><?php echo strtoupper($rowProduct['product_name']); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Customer Name</label>
                                                <select class="form-control" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black;border-radius:15px ">
                                                   <option value="">Select Option </option>
                                                    <?php
                                                    $sqlCustomer = "SELECT * FROM `customer`";
                                                    $resultCustomer = mysqli_query($conn, $sqlCustomer);
                                                    if (mysqli_num_rows($resultCustomer) > 0) {
                                                        while ($rowCustomer = mysqli_fetch_array($resultCustomer)) {
                                                            ?>
                                                            <option value='<?php echo $rowCustomer['customer_id']; ?>'><?php echo strtoupper($rowCustomer['customer_name']); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
<!--                                        </h4>-->
                                    </div>
                                    <div class="card-body">
                                        <div id="salesGraph" style="width: 100%; height: auto;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Get the current year
                    $currentYear = date('Y');

                    // Initialize arrays to store monthly data
                    $targetVisits = array_fill(0, 12, 0);
                    $achievedVisits = array_fill(0, 12, 0);

                    // Fetch target visits for each month from the target table
                    $sqlTarget = "SELECT month, SUM(no_visit) AS total_visits FROM target WHERE year = '$currentYear' GROUP BY month";
                    $resultTarget = mysqli_query($conn, $sqlTarget);
                    while ($row = mysqli_fetch_assoc($resultTarget)) {
                        $monthIndex = intval($row['month']) - 1;
                        $targetVisits[$monthIndex] = intval($row['total_visits']);
                    }

                    // Fetch achieved visits for each month from the marketing table
                    $sqlAchieved = "SELECT MONTH(visit_date) AS month, COUNT(*) AS achieved_visits 
                FROM marketing 
                WHERE YEAR(visit_date) = '$currentYear' 
                GROUP BY MONTH(visit_date)";
                    $resultAchieved = mysqli_query($conn, $sqlAchieved);
                    while ($row = mysqli_fetch_assoc($resultAchieved)) {
                        $monthIndex = intval($row['month']) - 1;
                        $achievedVisits[$monthIndex] = intval($row['achieved_visits']);
                    }

                    // Convert data to JSON format for Highcharts
                    $targetMJson = json_encode($targetVisits);
                    $achievedMJson = json_encode($achievedVisits);
                    ?>
                    <!-- market Target Graph -->
                    <div class="col-lg-6 col-md-12">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Total Target And Achieved - <?php echo $currentYear; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="marketGraph" style="width: 100%; height: auto;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Current week marketing planning-->
                        <div class="col-lg-6 col-md-12">
                             <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Current Week Marketing Planning - <span>Current Week Records</span></h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-responsive-md" style="text-align: center;">
                                            <thead style="background-color: #b1b1b1;">
                                            <tr>
                                                <th class="width80"><strong>#</strong></th>
                                                <th><strong>Plan Date</strong></th>
                                                <th><strong>Customer Name</strong></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            // Calculate the current week's start and end date
                                            $currentDate = date('Y-m-d');
                                            $startOfWeek = date('Y-m-d', strtotime('last Sunday', strtotime($currentDate)));
                                            $endOfWeek = date('Y-m-d', strtotime('next Saturday', strtotime($currentDate)));

                                            // Get the current page number
                                            $page = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
                                            if ($page <= 0) {
                                                $page = 1;
                                            }

                                            $recordsPerPage = 10;
                                            $offset = ($page - 1) * $recordsPerPage;

                                            // Fetch total number of records
                                            $countSql = "SELECT COUNT(id) AS total FROM marketing WHERE next_date BETWEEN '$startOfWeek' AND '$endOfWeek'";
                                            $countResult = mysqli_query($conn, $countSql);
                                            $row = mysqli_fetch_assoc($countResult);
                                            $totalRecords = $row['total'];
                                            $totalPages = ceil($totalRecords / $recordsPerPage);

                                            // Fetch paginated records
                                            $sql = "SELECT * FROM marketing WHERE next_date BETWEEN '$startOfWeek' AND '$endOfWeek' ORDER BY next_date ASC LIMIT $offset, $recordsPerPage";
                                            $result = mysqli_query($conn, $sql);
                                            if (mysqli_num_rows($result) > 0) {
                                                $sNo = $offset;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $sNo++;
                                                    $customer_name = $row['customer_name'];
                                                    $next_date = date('d-m-Y', strtotime($row['next_date']));
                                                    ?>
                                                    <tr>
                                                        <td><strong><?php echo $sNo; ?></strong></td>
                                                        <td><?php echo $next_date; ?></td>
                                                        <td><?php echo $customer_name; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="3">No Record Found</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <!-- Pagination -->
                                        <div class="col-12 pl-3" style="display: flex; justify-content: center;">
                                            <nav>
                                                <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                                                    <?php if ($page > 1): ?>
                                                        <li class="page-item page-indicator">
                                                            <a class="page-link" href="?page_no=1"><i class="fa-solid fa-angles-left"></i></a>
                                                        </li>
                                                        <li class="page-item page-indicator">
                                                            <a class="page-link" href="?page_no=<?php echo $page - 1; ?>"><i class="fa-solid fa-less-than"></i></a>
                                                        </li>
                                                    <?php else: ?>
                                                        <li class="page-item page-indicator disabled">
                                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-left"></i></a>
                                                        </li>
                                                        <li class="page-item page-indicator disabled">
                                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-less-than"></i></a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                            <a class="page-link" href="?page_no=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                        </li>
                                                    <?php endfor; ?>

                                                    <?php if ($page < $totalPages): ?>
                                                        <li class="page-item page-indicator">
                                                            <a class="page-link" href="?page_no=<?php echo $page + 1; ?>"><i class="fa-solid fa-greater-than"></i></a>
                                                        </li>
                                                        <li class="page-item page-indicator">
                                                            <a class="page-link" href="?page_no=<?php echo $totalPages; ?>"><i class="fa-solid fa-angles-right"></i></a>
                                                        </li>
                                                    <?php else: ?>
                                                        <li class="page-item page-indicator disabled">
                                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-greater-than"></i></a>
                                                        </li>
                                                        <li class="page-item page-indicator disabled">
                                                            <a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angles-right"></i></a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </nav>
                                        </div>
                                        <!-- End of Pagination -->
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <!--    Service login-->
    <?php
    if($_COOKIE['role'] == "Service") {
        // Get the current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

// Fetch total visits and commitments for the current month from the target table
        $sqlTarget = "SELECT 
                SUM(no_visit) AS total_visits, 
                SUM(commitment_value) AS total_commitments 
              FROM 
                service_target 
              WHERE 
                month = '$currentMonth' AND year = '$currentYear'";
        $resultTarget = mysqli_query($conn, $sqlTarget);
        $rowTarget = mysqli_fetch_assoc($resultTarget);
        $totalVisits = ($rowTarget['total_visits']) ? $rowTarget['total_visits'] : 0;
        $totalCommitments = ($rowTarget['total_commitments']) ? $rowTarget['total_commitments'] : 0;

// Fetch achieved visits for the current month from the marketing table
        $sqlAchievedVisits = "SELECT COUNT(*) AS achieved_visits 
                      FROM service 
                      WHERE MONTH(visit_date) = '$currentMonth' AND YEAR(visit_date) = '$currentYear'";
        $resultAchievedVisits = mysqli_query($conn, $sqlAchievedVisits);
        $rowAchievedVisits = mysqli_fetch_assoc($resultAchievedVisits);
        $achievedVisits = ($rowAchievedVisits['achieved_visits']) ? $rowAchievedVisits['achieved_visits'] : 0;

// Fetch achieved commitments for the current month from the marketing table
        $sqlAchievedCommitments = "SELECT SUM(commitment_value) AS achieved_commitments 
                           FROM service 
                           WHERE MONTH(visit_date) = '$currentMonth' AND YEAR(visit_date) = '$currentYear'";
        $resultAchievedCommitments = mysqli_query($conn, $sqlAchievedCommitments);
        $rowAchievedCommitments = mysqli_fetch_assoc($resultAchievedCommitments);
        $achievedCommitments = ($rowAchievedCommitments['achieved_commitments']) ? $rowAchievedCommitments['achieved_commitments'] : 0;

// Display the results
        $monthNames = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];
        $currentMonthName = $monthNames[$currentMonth];
        ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-danger">
                                    <div class="card-body  p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Target Visit</p>
                                                <!-- <h3 class="text-white"><?php //echo $total_visits; ?></h3> -->
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $totalVisits; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-success">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Achieved Visit</p>
                                                <!-- <h3 class="text-white"><?php //echo $total_desktops; ?></h3> -->
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $achievedVisits; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-info">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Target Commitment</p>
                                                <!-- <h3 class="text-white"><?php //echo $total_mobiles; ?></h3> -->
                                                <h3 class="text-white"style="margin-top: 30px; text-align: center;"><?php echo $totalCommitments == ''?0:$totalCommitments; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-sm-6">
                                <div class="widget-stat card bg-primary">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <div class="media-body text-white">
                                                <p class="mb-1" style="text-align: center;">Achieved Commitment</p>
                                                <h3 class="text-white" style="margin-top: 30px; text-align: center;"><?php echo $achievedCommitments == ''?0:$achievedCommitments; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product-Based Sales Graph -->
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
<!--                                        <h4 class="card-title">-->
                                            <div class="form-group col-md-6">
                                                <label>Product Name</label>
                                                <select class="form-control" id="product_name" name="product_name" style="border-color: #181f5a;color: black;border-radius:15px ">
                                                    <?php
                                                    $sqlProduct = "SELECT * FROM `product`";
                                                    $resultProduct = mysqli_query($conn, $sqlProduct);
                                                    if (mysqli_num_rows($resultProduct) > 0) {
                                                        while ($rowProduct = mysqli_fetch_array($resultProduct)) {
                                                            ?>
                                                            <option value='<?php echo $rowProduct['product_id']; ?>'><?php echo strtoupper($rowProduct['product_name']); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Customer Name</label>
                                                <select class="form-control" id="customer_name" name="customer_name" style="border-color: #181f5a;color: black;border-radius:15px ">
                                                    <option value="">Select Option</option>
                                                    <?php
                                                    $sqlCustomer = "SELECT * FROM `customer`";
                                                    $resultCustomer = mysqli_query($conn, $sqlCustomer);
                                                    if (mysqli_num_rows($resultCustomer) > 0) {
                                                        while ($rowCustomer = mysqli_fetch_array($resultCustomer)) {
                                                            ?>
                                                            <option value='<?php echo $rowCustomer['customer_id']; ?>'><?php echo strtoupper($rowCustomer['customer_name']); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
<!--                                        </h4>-->
                                    </div>
                                    <div class="card-body">
                                        <div id="salesGraph" style="width: 100%; height: auto;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php Include ('../includes/footer.php') ?>
</div>

<script src="../vendor/global/global.min.js"></script>
<script src="../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../vendor/chart.js/Chart.bundle.min.js"></script>
<script src="../js/custom.min.js"></script>
<script src="../js/dlabnav-init.js"></script>
<script src="../vendor/owl-carousel/owl.carousel.js"></script>
<script src="../vendor/peity/jquery.peity.min.js"></script>
<!--<script src="../vendor/apexchart/apexchart.js"></script>-->
<!--<script src="../js/dashboard/dashboard-1.js"></script>-->
<script src="../js/highCharts.js"></script>

<script>
    $( document ).ready(function() {

        Highcharts.chart('currYearGraph', {

            chart: {
                type: 'column'
            },

            title: {
                text: '<?php echo date("Y"); ?> Monthly Chart'
            },

            xAxis: {
                categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'No of Doners'
                }
            },

            tooltip: {
                formatter: function () {
                    return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '<br/>'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
            series: [
                {
                    name: 'Doners',
                    data: <?php echo $monthly_mobile_vist; ?>,
                    //stack: 'Offline',
                    color: '#2bc155'
                }
            ]
        });
    });

</script>
<script>
    $( document ).ready(function() {
        Highcharts.chart('currMonthGraph', {
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo date("Y"); ?> Monthly Sales And Purchase Chart'
            },
            xAxis: {
                categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'No of Orders'
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '<br/>'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: 'Sales',
                data: <?php echo $monthly_online; ?>,
                stack: 'Sales',
                color: '#2db3ff'
            },{
                name: 'Purchase',
                data: <?php echo $monthly_offline ?>,
                stack: 'Purchase',
                color: '#2bc155'
            }]
        });
    });

</script>

<!--Weekly expense-->
<script>
    $(document).ready(function() {
        Highcharts.chart('expenseGraph', {
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo $totalExpense; ?> - Total Expense In Week'
            },
            xAxis: {
                categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Amount'
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '<br/>';
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: 'Expense',
                data: <?php echo $weeklyExpensesData; ?>,
                stack: 'Expense',
                color: '#FF802B'
            }]
        });
    });
</script>

<!-- Ajax to product and customer handle the graph -->
<script>
    $(document).ready(function() {
        function renderGraph(data) {
            Highcharts.chart('salesGraph', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Product Sales Comparison'
                },
                xAxis: {
                    categories: data.months,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Quantity Sold'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>', // Format to 0 decimal places
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: data.currentYear + ' Sales',
                    data: data.currentYearSeries
                }, {
                    name: data.lastYear + ' Sales',
                    data: data.lastYearSeries
                }]
            });
        }

        $('#product_name, #customer_name').change(function() {
            var productId = $('#product_name').val();
            var customerName = $('#customer_name').val();

            $.ajax({
                url: 'fetch_sales_data.php',
                type: 'POST',
                data: {
                    product_name: productId,
                    customer_name: customerName
                },
                dataType: 'json',
                success: function(data) {
                    renderGraph(data);
                },
                // error: function(xhr, status, error) {
                //     console.error('Error fetching data:', error);
                // }
            });
        });

        // Trigger the change event to load the graph initially
        $('#product_name, #customer_name').trigger('change');
    });
</script>

<!-- market target -->
<script>
    $(document).ready(function() {
        Highcharts.chart('marketGraph', {
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo $currentYear; ?> Monthly Target And Achieved Chart'
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'No of Qty'
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '<br/>';
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: 'Target',
                data: <?php echo $targetMJson; ?>,
                stack: 'Target',
                color: '#FF2038'
            },{
                name: 'Achieved',
                data: <?php echo $achievedMJson; ?>,
                stack: 'Achieved',
                color: '#2bc155'
            }]
        });
    });
</script>
</body>
</html>