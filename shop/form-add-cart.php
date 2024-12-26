<?php
$page_title = "QuinStyle - Order";
session_start();

// Retrieve the image from the query parameter, default to 'img/uniform.webp' if not set
$image = isset($_GET['img']) ? htmlspecialchars($_GET['img']) : '../img/uniform.webp';

if (isset($_SESSION['account'])) {
    if ($_SESSION['account']['is_staff']) {
        header('location: ../admin/dashboard.php');
        exit;
    }
} else {
    header('location: index.php');
}

$customer_id = $_SESSION['account']['id'] ?? null;

require_once '../includes/_head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Place Order</title>
  <link rel="stylesheet" href="../css/form-order.css">
</head>
<body id="home">
  <!-- Navbar -->
  <?php require_once '../includes/_topnav.php'; ?>

  <!-- Order Section -->
  <div class="order-section">
    <!-- Display the dynamic image -->
    <div class="image-container">
      <img src="<?= $image ?>" alt="Selected Item" class="uniform-image">
    </div>

    <!-- Form -->
    <div class="form-container">
      <form action="../cart/add_to_cart.php" method="POST">
        <h2>Place Your Order</h2>

        <!-- Hidden input for customer ID -->
        <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer_id) ?>">

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>

        <label for="uniform-type">Uniform Type</label>
        <select id="uniform-type" name="uniform-type" required>
          <option value="Uniform">Uniform</option>
          <option value="pe">Physical Education Uniform</option>
        </select>

        <label for="size">Size</label>
        <select id="size" name="size" required>
          <option value="small">Small</option>
          <option value="medium">Medium</option>
          <option value="large">Large</option>
        </select>

        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required>

        <button type="submit" class="btn btn-primary">Add to Cart</button>
      </form>
    </div>
  </div>

  <!-- Include Footer -->
  <?php require_once '../includes/_footer.php'; ?>
  <?php require_once '../includes/_footer-script.php'; ?>
</body>
</html>
