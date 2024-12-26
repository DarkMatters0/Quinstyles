<?php
require_once('../classes/manage-account.class.php');

$manageAccountObj = new ManageAccount();

$id = $_GET['id'];
$account = $manageAccountObj->getAccountById($id);

header('Content-Type: application/json');
echo json_encode($account);
