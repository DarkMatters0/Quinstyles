<?php
session_start(); // Start the session
require_once '../classes/order-bin.class.php';

$orderbinObj = new OrderBin();

if (isset($_POST['order_bin_id']) && !empty($_POST['order_bin_id'])) {
    $order_bin_id = $_POST['order_bin_id'];

    // Perform the deletion
    $isDeleted = $orderbinObj->deletePermanently($order_bin_id);

    if ($isDeleted) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cart Bin ID is required']);
}
