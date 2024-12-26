<?php
$page_title = "QuinStyle - My Orders";
session_start();

// Include the database connection and necessary classes
require_once '../classes/database.class.php';
require_once '../classes/account.class.php';
require_once '../classes/order.class.php';

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

$orderObj = new Orders();

// Fetch orders for the logged-in customer
$orders = $orderObj->getOrdersByCustomer($customer_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="../vendor/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/cart.css" rel="stylesheet">
</head>
<body id="home">
    <div class="wrapper">
        <!-- Include Navigation Bar -->
        <?php require_once '../includes/_topnav.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">My Orders</h4>
                    </div>
                </div>
            </div>
            <div class="modal-container"></div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No.</th>
                                            <th>OrderID</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($orders): ?>
                                            <?php
                                            $i = 1;
                                            foreach ($orders as $order):
                                            ?>
                                                <tr>
                                                    <td><?= $i ?></td>
                                                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                                                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                                                    <td><?= '₱' . number_format($order['total_amount'], 2) ?></td>
                                                    <td><?= htmlspecialchars($order['status']) ?></td>
                                                    <td>
                                                        <a href="receipt.php?order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-outline-primary">View Receipt</a>
                                                        <a href="" class="btn btn-sm btn-outline-success me-1 refund-order" data-id="<?= $order['order_id'] ?>">Return</a>
                                                    </td>
                                                </tr>
                                            <?php
                                                $i++;
                                            endforeach;
                                            ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">You have no orders.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
    <script src="../js/order.js"></script>
</body>
</html>
