<?php
require_once('../classes/order.class.php');

// Initialize variables
$order_id = $description = '';
$order_idErr = $descriptionErr = '';

$orderObj = new Orders();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    // Validate order_id
    if (empty($order_id)) {
        $order_idErr = 'Order ID is required.';
    } elseif (!is_numeric($order_id)) {
        $order_idErr = 'Invalid Order ID format.';
    }

    // Validate description
    if (empty($description)) {
        $descriptionErr = 'Reason for return is required.';
    }

    // If validation errors exist, return them as JSON
    if (!empty($order_idErr) || !empty($descriptionErr)) {
        echo json_encode([
            'status' => 'error',
            'order_idErr' => $order_idErr,
            'descriptionErr' => $descriptionErr,
        ]);
        exit;
    }

    // Proceed with refund operation
    if ($orderObj->refund($order_id, $description)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to process the refund. Please try again.']);
    }
}
?>
