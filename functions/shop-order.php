<?php
session_start();
require_once '../config/config.php';
require_once '../controllers/ToyOrderController.php';
require_once '../controllers/ToyOrderItemController.php';


header("Content-Type: application/json");

// ✅ Decode JSON request
$data = json_decode(file_get_contents("php://input"), true);

$userId = $_SESSION['user_id'] ?? null;
$cartId = $_SESSION['cart_order_id'] ?? null;
$cartIdJS = $data["cartOrderId"] ?? null;
$productId = $data['productId'] ?? null;
$action = $data['action'] ?? null;

// Check if data is valid
if ($data === null) {
    echo json_encode(["success" => false, "message" => "Invalid JSON data"]);
    exit;
}

if ($action === 'addItem') {
    if ($cartId === null) {
        createNewOrder($userId, $productId);
    } else {
        // Validate that the cart belongs to the user
        $validCart = ($cartId == $cartIdJS);
        if (!$validCart) {
            echo json_encode(["success" => false, "message" => "Invalid cart"]);
            exit;
        }
        addProductToCart($cartId, $productId);
    }
} else if ($action === 'removeItem') {
    removeProductFromCart($cartId, $productId);
}


function createNewOrder($userID, $productId)
{
    $cartId = ToyOrderController::addOrder($userID);
    $_SESSION['cart_order_id'] = $cartId;
    addProductToCart($cartId, $productId);
}

function addProductToCart($cartId, $productId)
{
    $response = ToyOrderItemController::addOrderItem($productId, $cartId);
    if ($response) {
        $noItems = count(ToyOrderItemController::getOrderItemsByOrder($cartId));
        $_SESSION['cart_order_items_count'] = $noItems;
        echo json_encode(["success" => true, "message" => "Produkt přidán do košíku", "itemCount" => $noItems]);
    }
}

function removeProductFromCart($cartId, $productId)
{
    $response = ToyOrderItemController::removeOrderItem($productId, $cartId); // ❗ You must have this method in your controller

    if ($response) {
        $noItems = count(ToyOrderItemController::getOrderItemsByOrder($cartId));
        $_SESSION['cart_order_items_count'] = $noItems;
        echo json_encode([
            "success" => true,
            "message" => "Produkt odebrán z košíku",
            "itemCount" => $noItems
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Nepodařilo se odebrat produkt z košíku"
        ]);
    }
}
