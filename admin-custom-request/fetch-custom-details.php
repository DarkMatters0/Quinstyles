<?php
require_once '../classes/custom-request.class.php';

if (isset($_GET['custom_uniform_id'])) {
    $customUniformId = intval($_GET['custom_uniform_id']);
    $customRequest = new CustomRequest();
    $details = $customRequest->getCustomUniformDetails($customUniformId);

    if ($details) {
        echo json_encode($details);
    } else {
        echo json_encode(['error' => 'No details found for this custom uniform.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
