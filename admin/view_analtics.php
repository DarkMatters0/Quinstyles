<?php
session_start(); // Start the session
require_once '../classes/order.class.php';
require_once '../classes/manage-order.class.php'; // Assuming manage-order.class.php handles orders
require_once '../classes/account.class.php';


$orderObj = new Order();
$account = new Account();
$pipo = new orders();


$totalOrd = $pipo->totalOrder();
$total = $account->getTotalCustomers();
// Fetch all orders (or based on some filter criteria)
$orders = $orderObj->showAll(); // Ensure showAll() fetches order-related data
?>


<div class="container-fluid">
    <div class="row pt-4">
        <div class="col-12 col-md-12 col-lg-12 col-xl-12 d-flex flex-column">
            <div class="row flex-grow-1">
                <!-- Total Customers -->
                <div class="col-12 col-sm- col-md-6 col-xl-3 pb-4">
                    <div class="card widget-flat mb-0">
                        <div class="card-body">
                            <div class="float-end me-2">
                                <i class="bi bi-people fs-1 brand-color"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customers</h5>
                            <h3>Total Customers: <?= $total ?></h3>
                            
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-12 col-sm-6 col-md-6 col-xl-3 pb-4">
                    <div class="card widget-flat mb-0">
                        <div class="card-body">
                            <div class="float-end me-2">
                                <i class="bi bi-cart3 fs-1 brand-color"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Orders</h5>
                            <h3>Total Orders: <br> <?= $totalOrd ?></h3>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="col-12">
            <div class="card p-4">
                <div class="d-flex card-header justify-content-between align-items-center w-100 px-2">
                    <h3 class="header-title mb-0">Orders</h3>
                </div>
                <div class="card-body p-1 pt-2">
                    <!-- Scrollable Table -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="table-orders" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Order ID</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Total Amount</th>
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
                                        <td><?= htmlspecialchars($order['status']) ?></td>
                                        <td><?= htmlspecialchars($order['date_created']) ?></td>
                                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                        <td class="text-nowrap">
                                                <!-- Admin can view receipt -->
                                                <a href="#" class="btn btn-sm btn-outline-primary me-1 view-receipt" data-id="<?= $order['order_id'] ?>">View Receipt</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>
            </div>
        </div>
    </div>
</div>
