<?php
session_start(); // Start the session
require_once '../classes/manage-order.class.php'; // Assuming manage-order.class.php handles orders

$orderObj = new Order();


// Fetch all orders (or based on some filter criteria)
$orders = $orderObj->showAll(); // Ensure showAll() fetches order-related data
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">View Orders</h4>
            </div>
        </div>
    </div>

    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <form class="d-flex w-50">
                            <div class="input-group w-100">
                                <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search orders...">
                                <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                    <i class="bi bi-search"></i>
                                </span>
                                <div class="d-flex align-items-center me-3">
                                <label for="status-filter" class="me-2">Status</label>
                                <select id="status-filter" class="form-select">
                                    <option value="">All</option>
                                    <option value="paid">Paid</option>
                                    <option value="completed">Completed</option>
                                    <option value="claimed">Claimed</option>
                                    <option value="refund">Refund</option>
                                </select>
                            </div>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="table-orders" class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
    <tr>
        <th class="text-start">No.</th>
        <th>Username</th>
        <th>Email</th>
        <th>Order ID</th>
        <th>Date Created</th>
        <th>Total Amount</th>
        <th>Status</th> <!-- Add this -->
        <th>Action</th>
    </tr>
</thead>

<tbody>
    <?php
    $i = 1;
    foreach ($orders as $order) {
        ?>
        <tr>
            <td class="text-start"><?= $i ?></td>
            <td><?= htmlspecialchars($order['username']) ?></td>
            <td><?= htmlspecialchars($order['email']) ?></td>
            <td><?= htmlspecialchars($order['order_id']) ?></td>
            <td><?= htmlspecialchars($order['date_created']) ?></td>
            <td>$<?= number_format($order['total_amount'], 2) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td> <!-- Display status -->
            <td class="text-nowrap">
                <a href="" class="btn btn-sm btn-outline-success me-1 edit-order" data-id="<?= $order['order_id'] ?>">Edit</a>
                <a href="../orders/receipt.php?order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-outline-primary">View Receipt</a>
            </td>
        </tr>
        <?php
        $i++;
    }
    ?>
</tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
