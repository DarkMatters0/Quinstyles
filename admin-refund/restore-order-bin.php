<?php
require_once '../classes/order-bin.class.php';
require_once('../tools/functions.php'); // Include the functions file with the clean_input function

// Check if the request method is POST and the cart_bin_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_bin_id']) && !empty($_POST['order_bin_id'])) {
    // Sanitize the cart_bin_id using clean_input function
    $order_bin_id = clean_input($_POST['order_bin_id']);



    // Instantiate the CartBin class
    $orderBinObj = new OrderBin();

    // Call the restore method to restore the cart bin
    if ($orderBinObj->restore($order_bin_id)) {
        // Send a success response
        echo json_encode([
            'status' => 'success',
            'message' => 'Cart bin restored successfully.'
        ]);
    } else {
        // Send an error response if the restore operation fails
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to restore the cart bin.'
        ]);
    }
} else {
    // Handle invalid requests (either no POST or cart_bin_id not provided)
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Cart bin ID is required.'
    ]);
}
?>
