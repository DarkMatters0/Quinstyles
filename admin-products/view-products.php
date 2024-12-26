<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Products</h4>
            </div>
        </div>
    </div>
    <div class="modal-container"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <?php
                        require_once '../classes/product.class.php';
                        session_start();
                        $productObj = new Product();
                        ?>
                        <div class="d-flex justify-content-center align-items-center">
                            <form class="d-flex me-2">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search products...">
                                    <span class="input-group-text bg-primary border-primary text-white brand-bg-color">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </form>
                            <div class="d-flex align-items-center me-3">
                                <label for="uni-filter" class="me-2">Category</label>
                                <select id="uni-filter" class="form-select">
                                    <option value="">All</option>
                                    <option value="polo">Uniform Polo</option>
                                    <option value="pants">Uniform pants</option>
                                    <option value="PE-shirt">PE Shier</option>
                                    <option value="PE-pants">PE pants</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center me-3">
                                <label for="gender-filter" class="me-2">Gender</label>
                                <select id="gender-filter" class="form-select">
                                    <option value="">All</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="table-products" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Gender</th>
                                    <th>Size</th>
                                    <th class="text-start">Price</th>
                                    <th class="text-center">Total Stocks</th>
                                    <th class="text-center">Available Stocks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $products = $productObj->showAll();

                                foreach ($products as $product) {
                                    // Assuming stock_in and stock_out are available fields in the product data
                                    $totalStocks = $product['total_stocks'];
                                    $availableStocks = $product['available_stocks'];
                                    
                                ?>
                                    <tr>
                                        <td class="text-start"><?= $i ?></td>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['description'] ?? 'N/A') ?></td>
                                        <td><?= ucfirst($product['gender']) ?></td>
                                        <td><?= ucfirst($product['size']) ?></td>
                                        <td><?= number_format($product['price'], 2) ?></td>
                                        <td class="text-center"><?= $totalStocks ?></td>
                                        <td class="text-center">
                                            <span class="
                                                <?php
                                                if ($availableStocks < 1) {
                                                    echo 'badge rounded-pill bg-danger px-3';
                                                } elseif ($availableStocks <= 5) {
                                                    echo 'badge rounded-pill bg-warning px-3';
                                                }
                                                ?>">
                                                <?= $availableStocks ?>
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="#" class="btn btn-sm btn-outline-primary me-1 stock-action" data-id="<?= $product['product_id'] ?>">Stock In/Out</a>
                                            <a href="" class="btn btn-sm btn-outline-success me-1 edit-product" data-id="<?= $product['product_id'] ?>">Edit</a>
                                            
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
