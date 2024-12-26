<?php
$page_title = "QuinStyle - Order";
session_start();

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
  <title>Customize Your Uniform</title>
  <link rel="stylesheet" href="../css/form-order.css">
  <style>
      body, html {
          margin: 0;
          padding: 0;
          display: flex;
          flex-direction: column;
          min-height: 100vh;
          font-family: Arial, sans-serif;
      }

      .form-container {
          flex: 1;
          padding: 20px;
          max-width: 1200px;
          margin: auto;
      }

      .form-grid {
          display: flex;
          gap: 20px;
      }

      .form-section {
          flex: 1;
          border: 1px solid #ddd;
          padding: 20px;
          border-radius: 5px;
          background-color: #f9f9f9;
      }

      .footer {
          background-color: maroon;
          color: white;
          text-align: center;
          padding: 15px 0;
          margin-top: auto;
      }

      .footer a {
          color: #fff;
          text-decoration: none;
      }

      .footer a:hover {
          color: #ccc;
      }

      .size-prompt {
          margin-bottom: 20px;
          background-color: #f4f4f9;
          padding: 10px;
          border-left: 5px solid maroon;
      }

      form label {
          display: block;
          margin-bottom: 5px;
          font-weight: bold;
      }

      form input, form select, form textarea {
          width: 100%;
          padding: 10px;
          margin-bottom: 15px;
          border: 1px solid #ddd;
          border-radius: 5px;
      }

      form button {
          background-color: maroon;
          color: white;
          border: none;
          padding: 10px 20px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 16px;
      }

      form button:hover {
          background-color: #4b0000;
      }
  </style>
</head>
<body id="home">
  <!-- Navbar -->
  <?php require_once '../includes/_topnav.php'; ?>

  <!-- Custom Uniform Section -->
  <div class="form-container">
  <form action="../cart/add-to-cart-custom.php" method="POST" onsubmit="return setUniformType()">
          <h2>Customize Your Uniform/PE</h2>
          <br>
          <div class="size-prompt">
              <p>Don't know your actual size? <strong>Go to a garment shop to measure your sizes.</strong></p>
              <p><strong>Note:</strong> You can input measurements for both shirt and pants, or choose to customize only a shirt or pants.</p>
          </div>

          <!-- Hidden input for customer ID -->
          <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer_id) ?>">

          <!-- Hidden input for uniform type -->
          <input type="hidden" id="uniform_type_hidden" name="uniform_type" value="">

          <label for="base_uniform_type">Uniform Type</label>
          <select id="base_uniform_type" name="base_uniform_type" required>
              <option value="">Select Uniform Type</option>
              <option value="School Uniform">School Uniform</option>
              <option value="PE Uniform">PE Uniform</option>
          </select>

          <label for="gender">Gender</label>
          <select id="gender" name="gender" required>
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
          </select>

          <div class="form-grid">
              <!-- Custom Shirt Section -->
              <div class="form-section">
                  <h3>Custom Shirt</h3>
                  <label for="chest_measurement">Chest Measurement (in inches)</label>
                  <input type="number" id="chest_measurement" name="chest_measurement" step="0.01" min="0" placeholder="e.g., 36.5">

                  <label for="shoulder_width">Shoulder Width (in inches)</label>
                  <input type="number" id="shoulder_width" name="shoulder_width" step="0.01" min="0" placeholder="e.g., 15.5">

                  <label for="sleeve_length">Sleeve Length (in inches)</label>
                  <input type="number" id="sleeve_length" name="sleeve_length" step="0.01" min="0" placeholder="e.g., 24.0">
              </div>

              <!-- Custom Pants Section -->
              <div class="form-section">
                  <h3>Custom Pants</h3>
                  <label for="waist_measurement">Waist Measurement (in inches)</label>
                  <input type="number" id="waist_measurement" name="waist_measurement" step="0.01" min="0" placeholder="e.g., 30.0">

                  <label for="hip_measurement">Hip Measurement (in inches)</label>
                  <input type="number" id="hip_measurement" name="hip_measurement" step="0.01" min="0" placeholder="e.g., 38.0">

                  <label for="pant_length">Pant Length (in inches)</label>
                  <input type="number" id="pant_length" name="pant_length" step="0.01" min="0" placeholder="e.g., 40.0">
              </div>
          </div>

          <label for="custom_features">Custom Features</label>
          <textarea id="custom_features" name="custom_features" rows="4" placeholder="e.g., Embroidery, School Logo"></textarea>

          <label for="quantity">Quantity</label>
          <input type="number" id="quantity" name="quantity" min="1" value="1" required>

          <button type="submit" class="btn btn-primary">Add to Cart</button>
      </form>
  </div>

  <!-- Footer -->
  <footer class="footer">
      <?php require_once '../includes/_footer.php'; ?>
  </footer>

  <!-- Footer Scripts -->
  <?php require_once '../includes/_footer-script.php'; ?>
  <script>
      function setUniformType() {
          const shirtInputs = [
              document.getElementById('chest_measurement').value,
              document.getElementById('shoulder_width').value,
              document.getElementById('sleeve_length').value
          ];

          const pantsInputs = [
              document.getElementById('waist_measurement').value,
              document.getElementById('hip_measurement').value,
              document.getElementById('pant_length').value
          ];

          const baseUniformType = document.getElementById('base_uniform_type').value;

          let uniformType = baseUniformType;
          if (shirtInputs.some(input => input)) {
              uniformType += " Shirt";
          }
          if (pantsInputs.some(input => input)) {
              uniformType += " Pants";
          }

          document.getElementById('uniform_type_hidden').value = uniformType;
      }
  </script>
</body>
</html>
