<?php
session_start(); // Start the session
require_once '../classes/manage-account.class.php';

$manageAccount = new ManageAccount();

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $accountId = $_POST['id'];

    // Perform the deletion
    $isDeleted = $manageAccount->deleteAccount($accountId);

    if ($isDeleted) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Account ID is required']);
}
