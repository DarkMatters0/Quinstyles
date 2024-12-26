<?php 
require_once('../tools/functions.php');
require_once('../classes/manage-order.class.php'); // Ensure the correct class is included

$order_id = $_GET['id']; // Get the order ID from the URL
$status = '';
$statusErr = '';

$orderObj = new Order();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $status = clean_input($_POST['status']); // Sanitize input

    // Perform validation on status, if needed
    if (empty($status)) {
        $statusErr = 'Status is required.';
    } elseif (!in_array($status, ['paid', 'completed', 'refund'])) { // Validate against valid statuses
        $statusErr = 'Invalid status.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($statusErr)) {
        echo json_encode([
            'status' => 'error',
            'statusErr' => $statusErr
        ]);
        exit;
    }

    // Update the status in the database
    if ($orderObj->updateStatus($order_id, $status)) { // Pass $order_id and $status as arguments
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong when updating the order status.']);
    }
    exit;
}
?>