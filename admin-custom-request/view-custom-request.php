<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Custom Requests</h4>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <?php
                require_once '../classes/database.class.php';
                require_once '../classes/custom-request.class.php';

                // Create an instance of CustomRequest class
                $customRequest = new CustomRequest();

                // Fetch custom requests ordered by customer
                $customRequests = $customRequest->getCustomMadeItems();
                ?>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex justify-content-center align-items-center">
                            <form class="d-flex me-2">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search requests...">
                                    <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="table-custom-requests" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th>Customer Name</th>
                                    <th>Custom Item Name</th>
                                    <th>Gender</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($customRequests):
                                    $i = 1;
                                    foreach ($customRequests as $item):
                                ?>
                                    <tr>
                                        <td class="text-start"><?= $i ?></td>
                                        <td><?= htmlspecialchars($item['customer_name']) ?></td>
                                        <td><?= htmlspecialchars($item['custom_name']) ?></td>
                                        <td><?= htmlspecialchars($item['gender']) ?></td>
                                        <td><button class="btn btn-sm btn-outline-info see-custom-details" data-id="<?= $item['custom_uniform_id'] ?>">Details</button></td>
                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                        <td><?= '₱' . number_format($item['price'], 2) ?></td>
                                        <td class="text-nowrap">
                                            <a href="#" class="btn btn-sm btn-outline-success me-1 add-price" data-id="<?= $item['custom_uniform_id'] ?>">Add Price</a>
                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                    endforeach;
                                else:
                                ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No custom requests available.</td>
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
