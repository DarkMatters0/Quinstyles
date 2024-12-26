<?php
$page_title = "QuinStyle - Shop";
session_start();

if (isset($_SESSION['account'])) {
    if ($_SESSION['account']['is_staff']) {
        header('location: ../admin/dashboard.php');
        exit;
    }
} else {
    header('location: index.php');
}

require_once '../includes/_head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Now</title>

    <!-- Your Custom Styles -->
    <link rel="stylesheet" href="../css/shop.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body id="home">
    <!-- Navigation Bar -->
    <?php require_once '../includes/_topnav.php'; ?>
    <div class="modal-container"></div>

    <div class="container">
    

    <div class="container-row d-flex justify-content-center">
        <div class="card m-3" style="width: 18rem;">
            <img src="../img/uniform.webp" alt="Student Uniform" class="card-img-top">
            <div class="card-body">
                <h3 class="card-title"></h3>
                <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-primary buy-uniform">Standard </a>
                </div>
            </div>
        </div>

        <div class="card m-3" style="width: 18rem;">
            <img src="../img/pe.webp" alt="Student PE Uniform" class="card-img-top">
            <div class="card-body">
                <h3 class="card-title"></h3>
                <div class="d-flex justify-content-center">
                <a href="../shop/standard.php" class="btn btn-primary buy-pe">PE</a>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>


    <!-- Include Footer -->
    <?php require_once '../includes/_footer.php'; ?>
    <?php require_once '../includes/_footer-script.php'; ?>
    <script src="../js/shop.js"></script>
</body>
</html>
