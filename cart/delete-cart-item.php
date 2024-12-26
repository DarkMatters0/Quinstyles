<?php
session_start();

// Include the database connection and cart class
require_once '../classes/database.class.php';
require_once '../classes/cart.class.php';

// Check if the customer is logged in
if (!isset($_SESSION['account'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to delete an item.']);
    exit;
}

// Check if the cart_id parameter is passed
if (!isset($_POST['cart_item_id']) || !is_numeric($_POST['cart_item_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid cart item.']);
    exit;
}

$cart_item_id = intval($_POST['cart_item_id']); // Get the cart ID securely

// Create a new Cart instance
$cart = new Cart();

// Attempt to delete the cart item
if ($cart->deleteCartItem($cart_item_id)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to remove item from cart. Please try again.']);
}

?>
