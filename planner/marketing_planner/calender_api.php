<?php
include("../../includes/connection.php");

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    $query = "SELECT next_date, customer_name FROM marketing WHERE next_date BETWEEN '$start_date' AND '$end_date'";
    $result = mysqli_query($conn, $query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
}
?>
