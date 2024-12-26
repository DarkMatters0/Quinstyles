<?php
session_start();

// Redirect if the user is not logged in or not an admin
if (isset($_SESSION['account'])) {
    if (!$_SESSION['account']['is_admin']) {
        header('location: login.php');
    }
} else {
    header('location: login.php');
}

// Include necessary files for utility functions and the ManageAccount class
require_once('../tools/functions.php');
require_once('../classes/manage-account.class.php');

// Initialize variables to hold form input values and error messages
$username = $role = '';
$usernameErr = $roleErr = '';
$manageAccount = new ManageAccount(); // Initialize the ManageAccount object

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Handle GET request to fetch and display the account details for editing
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $manageAccount->fetchAccountById($id); // Fetch account details by ID
        if (!empty($record)) {
            // Populate form fields with existing account details for editing
            $username = $record['username'];
            $role = $record['role'];
        } else {
            echo 'No account found';
            exit;
        }
    } else {
        echo 'No account found';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle POST request to update the account details

    // Clean and assign the input values to variables using the clean_input function
    $id = clean_input($_GET['id']);
    $username = clean_input($_POST['username']);
    $role = clean_input($_POST['role']);

    // Validate the 'username' field
    if (empty($username)) {
        $usernameErr = 'Username is required';
    } elseif ($manageAccount->usernameExists($username, $id)) {
        $usernameErr = 'Username already exists';
    }

    // Validate the 'role' field
    if (empty($role)) {
        $roleErr = 'Role is required';
    }

    // If there are no validation errors, proceed to update the account in the database
    if (empty($usernameErr) && empty($roleErr)) {
        // Set the account properties
        $manageAccount->id = $id;
        $manageAccount->username = $username;
        $manageAccount->role = $role;

        // Try to update the account in the database
        if ($manageAccount->editAccount()) {
            // If successful, redirect to the account list page
            header('Location: view-users.php');
        } else {
            // If there's an issue, display an error message
            echo 'Something went wrong when updating the account';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <!-- Form to collect account details for editing -->
    <form action="?id=<?= $id ?>" method="post"> <!-- Pass the id in the form action -->

        <!-- Display a note indicating required fields -->
        <span class="error">* are required fields</span>
        <br>

        <!-- Username field with validation error display -->
        <label for="username">Username</label><span class="error">*</span>
        <br>
        <input type="text" name="username" id="username" value="<?= $username ?>"> <!-- Retain entered values -->
        <br>
        <?php if (!empty($usernameErr)): ?>
            <span class="error"><?= $usernameErr ?></span><br>
        <?php endif; ?>

        <!-- Role dropdown with validation error display -->
        <label for="role">Role</label><span class="error">*</span>
        <br>
        <select name="role" id="role">
            <option value="">--Select--</option>
            <option value="admin" <?= ($role == 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="staff" <?= ($role == 'staff') ? 'selected' : '' ?>>Staff</option>
            <option value="customer" <?= ($role == 'customer') ? 'selected' : '' ?>>Customer</option>
        </select>
        <br>
        <?php if (!empty($roleErr)): ?>
            <span class="error"><?= $roleErr ?></span><br>
        <?php endif; ?>

        <!-- Submit button -->
        <br>
        <input type="submit" value="Update Account">
    </form>
</body>

</html>
