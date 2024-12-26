<?php
$page_title = "QuinStyle - My Cart";
session_start();

// Include the database connection and cart class
require_once '../classes/cart.class.php';
require_once '../classes/custom-item.class.php';  // Include the CustomItem class
require_once '../classes/account.class.php'; // Include the Account class

if (isset($_GET['action'], $_GET['cart_id']) && $_GET['action'] === 'delete') {
    $cart_id = intval($_GET['cart_id']);
    $cart = new Cart();

    if ($cart->deleteCartItem($cart_id)) {
        echo "<script>alert('Item moved to cart bin successfully.'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Failed to delete item.'); window.location.href='cart.php';</script>";
    }
}



// Check if the customer is logged in
if (!isset($_SESSION['account'])) {
    header('location: index.php'); // Redirect to login page if not logged in
    exit;
} 

// Check if the logged-in user is a staff member, and redirect if necessary
if ($_SESSION['account']['is_staff']) {
    header('location: ../admin/dashboard.php');
    exit;
}



$customer_id = $_SESSION['account']['id'] ?? null;

// Check if a valid customer_id was returned
if ($customer_id === null) {
    echo "<script>alert('Error: Customer ID not found.'); window.location.href='index.php';</script>";
    exit;
}

$cart = new Cart();
$cart->customer_id = $customer_id;

// Fetch cart items
$cartItems = $cart->showCart(); // Fetch regular cart items

// Create an instance of CustomItem class to fetch custom items
$customItem = new CustomItem();
$customItem->customer_id = $customer_id;
$customItems = $customItem->showCustomItems(); // Fetch custom uniform items

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="../vendor/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/cart.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            overflow-x: hidden; /* Prevent horizontal scrolling */
            }

            .container-fluid {
            max-width: 100%; /* Ensure container doesn't overflow */
            padding-right: 0; /* Remove extra padding if any */
            padding-left: 5x;  /* Remove extra padding if any */
            text-align: center;
        }   

        
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Wrapper to hold content and footer */
        .wrapper {
            flex: 1; /* Pushes footer to the bottom */
        }

        /* Footer styling */
        .footer {
            background-color: #2c3e50;
            color: #fff;
            padding: 5px;
            text-align: center;
        }
        .page-title {
            color: #007bff; /* Change this to your desired color */
            font-weight: bold; /* Optional: Make it bold for better visibility */
            margin: 0;
}

    </style>
</head>

<body id="home">
    <div class="wrapper">
        <!-- Include Navigation Bar -->
        <?php require_once '../includes/_topnav.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12" style="padding: 10px">
                    <div class="page-title-box">
                            <h4 class="page-title" style="color:rgb(255, 255, 255);">Your Cart</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-container"></div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post">
                                <div class="table-responsive">
                                    <table id="table-cart" class="table table-centered table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-start">No.</th>
                                                <th>Product Name</th>
                                                <th>Gender</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($cartItems || $customItems): ?>
    <?php
    $i = 1;
    $grandTotal = 0;
    // Loop through regular cart items
    foreach ($cartItems as $item):
        $totalPrice = $item['quantity'] * $item['Price'];
    ?>
        <tr>
            <td class="text-start"><?= $i ?></td>
            <td><?= htmlspecialchars($item['Product_Name']) ?></td>
            <td><?= htmlspecialchars($item['gender']) ?></td>
            <td><?= htmlspecialchars($item['size']) ?></td>
            <td><?= htmlspecialchars($item['quantity']) ?></td>
            <td><?= '₱' . number_format($item['Price'], 2) ?></td>
            <td><?= '₱' . number_format($totalPrice, 2) ?></td>
            <td>

                <a href="#" class="btn btn-sm btn-outline-primary me-1 purchase-cart" data-id="<?= $item['cart_item_id'] ?>">Order</a>
                <a href="" class="btn btn-sm btn-outline-danger me-1 delete-cart" data-id="<?= $item['cart_item_id'] ?>">Delete</a>
            </td>
        </tr>
    <?php
        $i++;
    endforeach;

    // Loop through custom cart items (if they exist)
    if ($customItems):
        foreach ($customItems as $items):
            $totalPrice = $items['quantity'] * $items['price'];
    ?>
            <tr>
                <td class="text-start"><?= $i ?></td>
                <td><?= htmlspecialchars($items['Custom_Name']) ?></td>
                <td><?= htmlspecialchars($items['gender']) ?></td>
                <td><button class="btn btn-sm btn-outline-info see-custom-details" data-id="<?= $items['custom_uniform_id'] ?>">Details</button></td>
                <td><?= htmlspecialchars($items['quantity']) ?></td>
                <td><?= '₱' . number_format($items['price'], 2) ?></td>
                <td><?= '₱' . number_format($totalPrice, 2) ?></td>
                <td>
                    <a href="#" class="btn btn-sm btn-outline-primary me-1 order-custom-cart" data-id="<?= $items['cart_item_id'] ?>">Order</a>
                    <a href="" class="btn btn-sm btn-outline-danger me-1 delete-cart" data-id="<?= $items['cart_item_id'] ?>">Delete</a>
                </td>
            </tr>
        <?php
            $i++;
        endforeach;
    endif;
    ?>
<?php else: ?>
    <tr>
        <td colspan="10" class="text-center">Your cart is empty.</td>
    </tr>
<?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>

    <!-- Include Footer -->
    <?php require_once ('../includes/_footer.php'); ?>
    <?php require_once ('../includes/_footer-script.php'); ?>
    <script src="../js/cart.js"></script>
</body>

</html>
