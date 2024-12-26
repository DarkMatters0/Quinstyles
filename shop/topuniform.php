<?php
session_start();
require_once '../tools/functions.php';
require_once '../classes/cart.class.php';

$size = $quantity = '';
$sizeErr = $quantityErr = '';

$name = 'Polo';
$gender = 'male';

$cartObj = new Cart();

if (isset($_SESSION['account'])) {
    $customer_id = $_SESSION['account']['id'];
} else {
    echo 'Error: You must be logged in to add items to the cart.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $size = clean_input($_POST['size']);
    $quantity = clean_input($_POST['quantity']);

    if (empty($size)) {
        echo 'Error: Size is required.';
        exit;
    } elseif (!is_numeric($size) || $size <= 0) {
        echo 'Error: Invalid size value.';
        exit;
    }

    if (empty($quantity)) {
        echo 'Error: Quantity is required.';
        exit;
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        echo 'Error: Quantity must be a positive number.';
        exit;
    }

    $product_id = $cartObj->getProductId($name, $gender, $size); // Pass name and gender
    if (!$product_id) {
        echo 'Error: Product not found.';
        exit;
    } else {
        $cartObj->customer_id = $customer_id;
        $cartObj->product_id = $product_id;
        $cartObj->quantity = $quantity;

        if ($cartObj->addToCart()) {
            echo 'Success: Product added to cart.';
        } else {
            echo 'Error: Something went wrong when adding the product to the cart.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Now</title>

    <!-- Your Custom Styles -->
    <link rel="stylesheet" href="../css/shop.css">
    <link rel="stylesheet" href="../css/top-uniform.css">
    <link rel="stylesheet" href="../css/footer.css">

</head>
<body id="home">
    <!-- Navigation Bar -->
    <?php require_once '../includes/_topnav.php'; ?>
    
    <div class="shop-container">
        <!-- Product Card -->
        <div class="card">
            <img src="../img/topuni.webp" alt="Student Uniform" class="card-img-top">
            <div class="card-body">
                <h3 class="card-title">Uniform</h3>
                <p>Tanginang uniform to bat pang Highschool.</p>
            </div>
        </div>

        <!-- Sizes and Add to Cart Form -->
        <div class="form-container">
            <form action="" method="POST">
                <div class="size-list">
                <div class="size-title">Select Size:</div>

            <div class="size-options" id="size-options">
                <!-- Small Sizes -->
                <div class="size-group">
                    <div class="size-label">Small:</div>
                    <div class="size-options-row">
                        <div class="size-option" data-value="29">#29</div>
                        <div class="size-option" data-value="30">#30</div>
                        <div class="size-option" data-value="31">#31</div>
                        <div class="size-option" data-value="32">#32</div>
                        <div class="size-option" data-value="33">#33</div>
                        <div class="size-option" data-value="34">#34</div>
                        <div class="size-option" data-value="35">#35</div>
                    </div>
                </div>

                <!-- Medium Sizes -->
                <div class="size-group">
                    <div class="size-label">Medium:</div>
                    <div class="size-options-row">
                        <div class="size-option" data-value="36">#36</div>
                        <div class="size-option" data-value="37">#37</div>
                        <div class="size-option" data-value="38">#38</div>
                        <div class="size-option" data-value="39">#39</div>
                        <div class="size-option" data-value="40">#40</div>
                    </div>
                </div>

                <!-- Large Sizes -->
                <div class="size-group">
                    <div class="size-label">Large:</div>
                    <div class="size-options-row">
                        <div class="size-option" data-value="41">#41</div>
                        <div class="size-option" data-value="42">#42</div>
                        <div class="size-option" data-value="43">#43</div>
                        <div class="size-option" data-value="44">#44</div>
                        <div class="size-option" data-value="45">#45</div>
                    </div>
                </div>
            </div>


                <input type="hidden" id="selected-size" name="size" value="">
                <div class="quantity-control">
                    <label for="quantity">Quantity:</label>
                    <button type="button" onclick="changeQuantity(-1)">-</button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1">
                    <button type="button" onclick="changeQuantity(1)">+</button>
                </div>
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Include Footer -->
    <?php require_once '../includes/_footer.php'; ?>
    <?php require_once '../includes/_footer-script.php'; ?>
    <script>
        // Quantity control functionality
        function changeQuantity(amount) {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            currentValue = isNaN(currentValue) ? 1 : currentValue;
            currentValue += amount;
            if (currentValue < 1) currentValue = 1; // Prevent negative or zero quantity
            quantityInput.value = currentValue;
        }

        // Size selection functionality
        const sizeOptions = document.querySelectorAll('.size-option');
        const selectedSizeInput = document.getElementById('selected-size');

        sizeOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove the selected class from all options
                sizeOptions.forEach(opt => opt.classList.remove('selected'));
                // Add the selected class to the clicked option
                option.classList.add('selected');
                // Update the hidden input with the selected size
                selectedSizeInput.value = option.dataset.value;
            });
        });
    </script>
</body>
</html>