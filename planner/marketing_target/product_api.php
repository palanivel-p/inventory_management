<?php
Include("../../includes/connection.php");

$sqlProducts = "SELECT * FROM `product`";
$resultProducts = mysqli_query($conn, $sqlProducts);

// Check if any products were found
if (mysqli_num_rows($resultProducts) > 0) {
    $products = array();

    // Loop through each row in the result set
    while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
        // Add each product to the products array
        $products[] = array(
            'product_id' => $rowProduct['product_id'],
            'product_name' => $rowProduct['product_name']
        );
    }

    // Return the products array as JSON
    echo json_encode($products);
} else {
    // No products found
    echo json_encode(array());
}



function clean($data) {
    return filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
}



?>
