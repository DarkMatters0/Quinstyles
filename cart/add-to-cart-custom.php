<?php

require_once '../tools/functions.php';
require_once '../classes/custom-item.class.php';

$customItemObj = new CustomItem();

print_r($_POST);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Clean and validate inputs
    $customer_id = clean_input($_POST['customer_id']);
    $name = clean_input($_POST['uniform_type']);
    $gender = clean_input($_POST['gender']);
    $chest_measurement = clean_input($_POST['chest_measurement']) ?? null;
    $waist_measurement = clean_input($_POST['waist_measurement']) ?? null;
    $hip_measurement = clean_input($_POST['hip_measurement']) ?? null;
    $shoulder_width = clean_input($_POST['shoulder_width']) ?? null;
    $sleeve_length = clean_input($_POST['sleeve_length']) ?? null;
    $pant_length = clean_input($_POST['pant_length']) ?? null;
    $custom_features = clean_input($_POST['custom_features']) ?? null;
    $quantity = clean_input($_POST['quantity']);


    // Insert custom uniform into the database
    $custom_uniform_id = $customItemObj->createCustomUniform([
        'name' => $name,
        'gender' => $gender,
        'chest_measurement' => $chest_measurement,
        'waist_measurement' => $waist_measurement,
        'hip_measurement' => $hip_measurement,
        'shoulder_width' => $shoulder_width,
        'sleeve_length' => $sleeve_length,
        'pant_length' => $pant_length,
        'custom_features' => $custom_features,
        'price' => 0, // Optional: Pass a price if applicable
    ]);

    if (!$custom_uniform_id) {
        echo "<script>alert('Failed to create custom uniform.'); window.history.back();</script>";
        exit;
    }

    // Add the custom uniform to the cart
    $customItemObj->customer_id = $customer_id;
    $customItemObj->custom_uniform_id = $custom_uniform_id;
    $customItemObj->quantity = $quantity;

    if ($customItemObj->addToCartCustom($custom_uniform_id, $quantity)) {
        echo "<script>alert('Custom item added to cart successfully!'); window.location.href='../cart/cart.php';</script>";
    } else {
        echo "<script>alert('Failed to add custom item to cart.'); window.history.back();</script>";
    }
}
?>
