<?php
require_once('../classes/manage-order.class.php');

$orderingObj = new Order();

$order_id = $_GET['id'];
$status = $orderingObj->getStatusById($order_id);

header('Content-Type: application/json');
echo json_encode($status);
