<?php
$page_title = "QuinStyle - Dashboard";
session_start();

if (isset($_SESSION['account'])) {
    if (!$_SESSION['account']['is_staff']) {
        header('location: ../account/loginwcss.php');
    }
} else {
    header('location: index.php');
}

require_once '../includes/_head.php';
?>

<body id="dashboard">
    <div class="wrapper">
        <?php
        require_once '../includes/_admin_topnav.php';
        require_once '../includes/_sidebar.php';
        ?>
        <div class="content-page px-3">
            <!-- dynamic content here -->
        </div>
    </div>
    </div>
    <?php
    require_once '../includes/_footer-script.php';
    ?>
</body>

</html>