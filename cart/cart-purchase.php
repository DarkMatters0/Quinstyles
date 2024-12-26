<?php
require_once '../tools/functions.php'; // Optional: If you need a clean_input function
require_once '../classes/database.class.php';
require_once '../classes/cart.class.php';
session_start();
$cart_item_id = $_GET['id'] ?? null;
if (!$cart_item_id) {
    echo json_encode(['status' => 'error', 'message' => 'No cart ID provided']);
    exit;
}
$customer_id = $_SESSION['account']['id'] ?? null;

$cartObj = new Cart();

if ($cart_item_id) {
    $result = $cartObj->processPurchases($cart_item_id, $customer_id);

    if ($result) {
        // Process successful
        echo json_encode(['status' => 'success', 'message' => 'Item purchased successfully']);
    } else {
        // Process failed (e.g., no stock)
        echo json_encode(['status' => 'error', 'message' => 'There is currently no stock available']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to process purchase']);
}

?>
