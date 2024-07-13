<?php
// Database connection
Include ('../includes/connection.php');

// Get current year and last year
$currentYear = date('Y');
$lastYear = $currentYear - 1;

// Get selected product and customer from POST request
$productName = $_POST['product_name'];
$customerName = $_POST['customer_name'];

if($customerName == ''){
    $customer_name = "";
}
else{
    $customer_name = " AND customer='$customerName'";
}
// Initialize data arrays
$currentYearData = [];
$lastYearData = [];

// Query to fetch monthly sales data for the current year
$currentYearSql = "SELECT MONTH(sale_date) as month, SUM(qty) as total_qty
                   FROM sale_details
                   WHERE product_id = '$productName'$customer_name AND YEAR(sale_date) = '$currentYear'
                   GROUP BY MONTH(sale_date)";

$currentYearResult = mysqli_query($conn, $currentYearSql);

if ($currentYearResult) {
    while ($row = mysqli_fetch_assoc($currentYearResult)) {
        $currentYearData[(int)$row['month']] = (int)$row['total_qty'];
    }
} else {
    // Handle query error
    die("Error fetching current year data: " . mysqli_error($conn));
}

// Query to fetch monthly sales data for the last year
$lastYearSql = "SELECT MONTH(sale_date) as month, SUM(qty) as total_qty
                FROM sale_details
                WHERE product_id = '$productName' $customer_name AND YEAR(sale_date) = '$lastYear'
                GROUP BY MONTH(sale_date)";

$lastYearResult = mysqli_query($conn, $lastYearSql);

if ($lastYearResult) {
    while ($row = mysqli_fetch_assoc($lastYearResult)) {
        $lastYearData[(int)$row['month']] = (int)$row['total_qty'];
    }
} else {
    // Handle query error
    die("Error fetching last year data: " . mysqli_error($conn));
}

// Prepare data arrays for Highcharts
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$currentYearSeries = [];
$lastYearSeries = [];

for ($i = 1; $i <= 12; $i++) {
    $currentYearSeries[] = $currentYearData[$i] ?? 0;
    $lastYearSeries[] = $lastYearData[$i] ?? 0;
}

// Output JSON
echo json_encode([
    'currentYear' => $currentYear,
    'lastYear' => $lastYear,
    'months' => $months,
    'currentYearSeries' => $currentYearSeries,
    'lastYearSeries' => $lastYearSeries
]);

?>
