<?php
$page_title = "QuinStyle - Sign Up";
include_once "../includes/_head.php";
require_once '../tools/functions.php';
require_once '../classes/account.class.php';

session_start();

$username = $password = $email = $contact = '';
$accountObj = new Account();
$emailErr = $contactErr = $usernameErr = $passwordErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email']);
    $contact = clean_input($_POST['contact']);
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    // Validate email
    if (empty($email)) {
        $emailErr = "Email is required!";
    } elseif (!str_ends_with($email, '@wmsu.edu.ph')) {
        $emailErr = "Email must be a school email!";
    }

    if (empty($contact)) {
        $contactErr = "Contact is required!";
    } elseif (!preg_match('/^\d{11}$/', $contact)) {
        $contactErr = "Contact number must be exactly 11 digits!";
    }

    // Validate username
    if (empty($username)) {
        $usernameErr = "Username is required!";
    } elseif ($accountObj->usernameExist($username)) {
        $usernameErr = "Username already taken!";
    }

    // Validate password
    if (empty($password)) {
        $passwordErr = "Password is required!";
    }

    // If no errors, proceed with account creation
    if (empty($emailErr) && empty($contactErr) && empty($usernameErr) && empty($passwordErr)) {
        $accountObj->email = $email;
        $accountObj->contact = $contact;
        $accountObj->role = 'customer'; // Default role
        $accountObj->is_staff = 0; // Default staff status
        $accountObj->is_admin = 0; // Default admin status
        $accountObj->username = $username;
        $accountObj->password = $password;
        $accountObj->add();
        header("location: loginwcss.php");
        exit();
    }
}
?>

<style>
    body {
    background-image: url("../img/wmsu11.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
    }
    .img-container {
        text-align: center; 
    }
    .img-container img {
        width: 50%;  /* Resize the image to 50% of its container */
        height: auto; /* Maintain aspect ratio */
    }

    .bordered-form {
    border: 2px solid #333; /* Border width and color */
    border-radius: 20px;     /* Optional: rounded corners */
    padding: 50px;           /* Adds space inside the form */
    background-color: #f8f9fa; /* Optional: background color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: shadow for a subtle 3D effect */
}

    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, 0.1);
        border: solid rgba(0, 0, 0, 0.15);
        border-width: 1px 0;
        box-shadow: inset 0 0.5em 1.5em rgba(0, 0, 0, 0.1),
            inset 0 0.125em 0.5em rgba(0, 0, 0, 0.15);
    }

    .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
    }

    .bi {
        vertical-align: -0.125em;
        fill: currentColor;
    }

    .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
    }

    .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
    }

    .bd-mode-toggle {
        z-index: 1500;
    }

    .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
    }

    html,
    body {
        height: 100%;
    }

    .form-signin {
        max-width: 500px;
        padding: 1rem;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>

<body class="d-flex align-items-center py-4">

    <main class="form-signin w-100 m-auto">
        <form action="signup.php" method="post" class="bordered-form">
        <div class="img-container">
            <img class="mb-4" src="../img/wmsu.png" alt="WMSU Logo">
        </div>  

            <h1 class="h3 mb-3 fw-normal">Request Account Form</h1>

            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="email" value="<?= $email ?>">
                <label for="email">Email</label>
                <p class="text-danger"><?= $emailErr ?></p>
            </div>
            <div class="form-floating">
                <input type="tel" class="form-control" id="contact" name="contact" placeholder="contact" value="<?= $contact ?>">
                <label for="contact">Contact No</label>
                <p class="text-danger"><?= $contactErr ?></p>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?= $username ?>">
                <label for="username">Username</label>
                <p class="text-danger"><?= $usernameErr ?></p>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password">Password</label>
                <p class="text-danger"><?= $passwordErr ?></p>
            </div>
            <div>
          <label for="existing">Have an existing account?</label>
                <a href="loginwcss.php">Click Here!</a>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Send Request</button>
        </form>
    </main>
</body>

</html>
