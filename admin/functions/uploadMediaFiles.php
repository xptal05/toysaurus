<?php
require_once BASE_PATH . '/controllers/MediaController.php';

$entityId = $_POST['entityId'] ?? null;
$entityType = $_POST['entityType'] ?? null;
$role = $_POST['role'] ?? null;

// Add validation here if needed
$result = MediaController::uploadMedia($entityId, $entityType, 'image', $role);
echo json_encode($result);
