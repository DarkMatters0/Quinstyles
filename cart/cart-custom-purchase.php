<?php
require_once '../tools/functions.php'; // Optional: If you need a clean_input function
require_once '../classes/database.class.php';
require_once '../classes/custom-item.class.php';
session_start();
$cart_item_id = $_GET['id'] ?? null;
if (!$cart_item_id) {
    echo json_encode(['status' => 'error', 'message' => 'No cart ID provided']);
    exit;
}
$customer_id = $_SESSION['account']['id'] ?? null;

$custom_cartObj = new CustomItem();

if ($cart_item_id) {
    $result = $custom_cartObj->processPurchaseCustom($cart_item_id, $customer_id);

    if ($result) {
        // Process successful
        echo json_encode(['status' => 'success', 'message' => 'Item purchased successfully']);
    } else {
        // Process failed (e.g., no stock)
        echo json_encode(['status' => 'error', 'message' => 'Your Custom Made has still not processed yet. Wait for atleast 3 days to processed it']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to process purchase']);
}

?>
