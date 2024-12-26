<?php

require_once('../tools/functions.php');
require_once('../classes/product.class.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : null; // Validate product_id
$price = '';
$priceErr = '';

$productObj = new Product();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $price = clean_input($_POST['price']); // Sanitize the input

    // Validation for price
    if (empty($price)) {
        $priceErr = 'Price is required.';
    } elseif (!is_numeric($price)) {
        $priceErr = 'Price should be a number.';
    } elseif ($price < 1) {
        $priceErr = 'Price must be greater than 0.';
    }

    // Validation for id
    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID.']);
        exit;
    }

    // If there are validation errors, return them
    if (!empty($priceErr)) {
        echo json_encode(['status' => 'error', 'priceErr' => $priceErr]);
        exit;
    }

    // If no validation errors, proceed to update
    $productObj->product_id = $id; // Set the product ID
    $productObj->price = $price; // Set the price

    if ($productObj->updatePrice()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update price.']);
    }
    exit;
}

?>
