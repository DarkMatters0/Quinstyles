
<?php

require_once '../tools/functions.php';
require_once '../classes/custom-request.class.php';

$customUniformId = $_GET['id'] ?? null; // Get custom uniform ID from query string
$price = '';
$priceErr = '';

$customRequestObj = new CustomRequest();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $price = clean_input($_POST['price']); // Sanitize price input

    if (empty($price)) {
        $priceErr = 'Price is required.';
    } else if (!is_numeric($price)) {
        $priceErr = 'Price should be a number.';
    } else if ($price <= 0) {
        $priceErr = 'Price must be greater than 0.';
    }

    // Return validation errors as JSON
    if (!empty($priceErr)) {
        echo json_encode([
            'status' => 'error',
            'priceErr' => $priceErr
        ]);
        exit;
    }

    // Update price in database
    if ($customUniformId) {
        $result = $customRequestObj->updateCustomUniformPrice($customUniformId, $price);
        if ($result) {
            // Return success response without an alert message
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update price.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Custom Uniform ID.']);
    }
    
    exit;
}
