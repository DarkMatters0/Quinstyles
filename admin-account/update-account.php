<?php

require_once('../tools/functions.php');
require_once('../classes/manage-account.class.php'); // Make sure this is the correct class for managing accounts

$id = $_GET['id'];
$role = '';
$roleErr = '';

$manageAccountObj = new ManageAccount();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $role = clean_input($_POST['role']);

    // Validate the 'role' field
    if (empty($role)) {
        $roleErr = 'Role is required.';
    } elseif (!in_array($role, ['admin', 'staff', 'customer'])) { // Ensure valid role
        $roleErr = 'Invalid role selected.';
    }

    // If there are validation errors, return them as JSON
    if (!empty($roleErr)) {
        echo json_encode([
            'status' => 'error',
            'roleErr' => $roleErr
        ]);
        exit;
    }

    if (empty($roleErr)) {
        // Set the account properties
        $manageAccountObj->id = $id;
        $manageAccountObj->role = $role;

        // Update the role in the database
        if ($manageAccountObj->updateRole()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when updating the account role.']);
        }
        exit;
    }
}
