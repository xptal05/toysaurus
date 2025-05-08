<?php
require_once '../config/config.php';
require_once '../controllers/ClientController.php';
require_once '../controllers/ClientAccountOperationsController.php';
require_once '../controllers/ToyOrderController.php';
require_once '../controllers/ToyOrderItemController.php';


header("Content-Type: application/json");

// ✅ Decode JSON request
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['loginId']) || !isset($input['password'])) {
    echo json_encode(["success" => false, "message" => "Missing login credentials"]);
    exit;
}

// ✅ Authenticate user
$client = ClientController::authenticateClient($input['loginId'], $input['password']);

if ($client) {
    $cartOrder = ToyOrderController::getCartOrderByClientId($_SESSION['user_id']);
    $_SESSION['cart_order_id'] = ($cartOrder !== null) ? $cartOrder->get('orderId') : null;
    
    $noItems = count(ToyOrderItemController::getOrderItemsByOrder($_SESSION['cart_order_id']));
    $_SESSION['cart_order_items_count'] = $noItems;

    $Tpoints = ClientAccountOperationsController::getOperationsByType($_SESSION['user_id'], 'T-Points') ;
    $noTpoints = 0;
    foreach($Tpoints as $tPoint ){
        $noTpoints += $tPoint['Amount'] ;
    ;}
    $_SESSION['client_tPoints'] = $noTpoints;

    echo json_encode(["success" => true, "message" => "Login successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}
