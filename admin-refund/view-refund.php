<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Refund</h4>
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
                        require_once '../classes/refund.class.php';
                        session_start();
                        $refundObj = new Refund();
                        ?>
                        <div class="d-flex justify-content-center align-items-center">
                            <form class="d-flex me-2">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control form-control-light" id="custom-search" placeholder="Search refund...">
                                    <span class="input-group-text bg-primary border-primary text-white">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="page-title-right d-flex align-items-center">
                            <button id="refresh-refund" class="btn btn-primary">Refresh</button>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="table-refund" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $refundItems = $refundObj->showAll();

                                foreach ($refundItems as $item) {
                                    ?>
                                    <tr>
                                        <td class="text-start"><?= $i ?></td>
                                        <td><?= $item['order_id'] ?></td>
                                        <td><?= $item['created_at'] ?></td>
                                        <td><?= number_format($item['amount'], 2) ?></td>
                                        <td><?= htmlspecialchars($item['description']) ?></td>
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
