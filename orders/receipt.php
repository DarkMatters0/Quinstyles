<?php
require_once '../classes/database.class.php';
require_once '../classes/order.class.php';

// Validate and fetch the order ID
$order_id = $_GET['order_id'] ?? null;

$orderObj = new Orders();
$order = $orderObj->getOrderDetails($order_id);


// Fetch receipt details
$receiptDetails = $orderObj->getReceiptDetails($order_id);

if (!$receiptDetails) {
    echo "<script>alert('Receipt details not found.'); window.location.href='orders.php';</script>";
    exit;
}

$customer_name = htmlspecialchars($order['customer_name']);
$order_date = htmlspecialchars($order['order_date']);
$items = $order['items']; // Assuming 'items' contains product details
$total_amount = number_format($order['total_amount'], 2);

// Receipt-specific details
$receipt_number = htmlspecialchars($receiptDetails['receipt_number']);
$payment_date = date('F j, Y', strtotime($receiptDetails['payment_date']));
$payment_method = htmlspecialchars($receiptDetails['payment_method']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <link href="../vendor/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .receipt-container {
            background-color: white;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .receipt-header h1 {
            font-size: 32px;
            color: #007bff;
            margin-bottom: 5px;
        }
        .receipt-header p {
            margin: 0;
            font-size: 14px;
        }
        .customer-info h2, .receipt-info h2, .order-details h2 {
            margin-top: 20px;
            font-size: 24px;
            color: #343a40;
        }
        .customer-info p, .receipt-info p {
            font-size: 16px;
            line-height: 1.5;
        }
        .order-details table {
            width: 100%;
            margin-top: 20px;
            font-size: 14px;
        }
        .order-details th, .order-details td {
            text-align: center;
            padding: 8px;
        }
        .order-details th {
            background-color: #007bff;
            color: white;
        }
        .order-details .total-row td {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .text-center {
            margin-top: 20px;
        }
        .btn-print {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Receipt Header -->
        <div class="receipt-header text-center">
            <h1>QuinStyle Garments</h1>
            <p>123 Fashion Avenue, Manila, Philippines</p>
            <p>Phone: +63 912 345 6789 | Email: support@quinstyle.com</p>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <h2>Customer Details</h2>
            <p><strong>Name:</strong> <?= $customer_name ?></p>
            <p><strong>Order ID:</strong> <?= $order_id ?></p>
            <p><strong>Date:</strong> <?= date('F j, Y', strtotime($order_date)) ?></p>
        </div>

        <!-- Receipt Info -->
        <div class="receipt-info">
            <h2>Receipt Details</h2>
            <p><strong>Receipt Number:</strong> <?= $receipt_number ?></p>
            <p><strong>Payment Date:</strong> <?= $payment_date ?></p>
            <p><strong>Payment Method:</strong> <?= $payment_method ?></p>
        </div>

        <!-- Order Details -->
        <div class="order-details">
            <h2>Order Summary</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Product Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= htmlspecialchars($item['size']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>₱<?= number_format($item['total'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5" class="text-end">Grand Total</td>
                        <td>₱<?= $total_amount ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Note -->
        <div class="text-center">
            <p>Thank you for shopping with us!</p>
            <p>For inquiries, contact support@quinstyle.com</p>
        </div>

        <!-- Print Button -->
        <div class="text-center">
            <button class="btn btn-primary btn-print" onclick="window.print()">Print Receipt</button>
        </div>
    </div>

    <script src="../vendor/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
