<?php
session_start(); // Start the session
require_once '../classes/manage-account.class.php';

$manageAccount = new ManageAccount();

// Fetch search and role filters


// Fetch accounts
$accounts = $manageAccount->showAll();
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Manage Accounts</h4>
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
                                <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search products...">
                                <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                    <i class="bi bi-search"></i>
                                </span>
                            </div>
                        </form>
                        <div class="d-flex align-items-center">
                            <label for="role-filter" class="me-2">Role</label>
                            <select id="role-filter" class="form-select">
                                <option value="">All</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="table-account" class="table table-centered table-nowrap mb-0" style="width: 100%; table-layout: fixed;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $i = 1;
    foreach ($accounts as $account) {
        ?>
        <tr>
            <td class="text-start"><?= $i ?></td>
            <td><?= htmlspecialchars($account['username']) ?></td>
            <td><?= htmlspecialchars($account['email']) ?></td>
            <td><?= htmlspecialchars($account['contact']) ?></td>
            <td><?= ucfirst($account['role']) ?></td>
            <td class="text-nowrap">
                <?php 
                // Restrict actions based on the account's role
                if ($account['role'] === 'admin') { 
                ?>
                    <!-- View-only for admin accounts -->
                    <span class="text-muted">View Only</span>
                <?php 
                } else { 
                ?>
                    <!-- Edit and delete actions for non-admin roles -->
                    <a href="" class="btn btn-sm btn-outline-success me-1 edit-account" data-id="<?= $account['id'] ?>">Edit</a>
                    <a href="" class="btn btn-sm btn-outline-danger me-1 delete-account" data-id="<?= $account['id'] ?>">Delete</a>
                <?php 
                }
                ?>
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

