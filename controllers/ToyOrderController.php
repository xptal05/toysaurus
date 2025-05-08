<?php
require_once BASE_PATH . '/models/ToyOrder.php';
require_once BASE_PATH . '/controllers/ToyOrderItemController.php';
require_once BASE_PATH . '/config/Database.php';

class ToyOrderController
{

    // Fetch all orders
    public static function getAllOrders()
    {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM Toy_Order");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($order) {
            return new ToyOrder(
                $order['Order_ID'],
                $order['Client_ID'],
                $order['Shipment'],
                $order['Address'],
                $order['Date_Created'],
                $order['Date_Modified'],
                $order['History'],
                $order['Payment_ID'],
                $order['Status']
            );
        }, $orders);
    }

    // Fetch a single order by ID
    public static function getOrderById($id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Toy_Order WHERE Order_ID = :id");
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return null;

        return new ToyOrder(
            $order['Order_ID'],
            $order['Client_ID'],
            $order['Shipment'],
            $order['Address'],
            $order['Date_Created'],
            $order['Date_Modified'],
            $order['History'],
            $order['Payment_ID'],
            $order['Status']
        );
    }

    // Fetch a single order by ID
    public static function getCartOrderByClientId($clientId)
    {
        $cartOrderStatus = 'Draft';
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Toy_Order WHERE Client_ID = :id AND Status = :status");
        $stmt->execute(['id' => $clientId, 'status' => $cartOrderStatus]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return null;

        return new ToyOrder(
            $order['Order_ID'],
            $order['Client_ID'],
            $order['Shipment'],
            $order['Address'],
            $order['Date_Created'],
            $order['Date_Modified'],
            $order['History'],
            $order['Payment_ID'],
            $order['Status']
        );
    }

    // Create a new toy order
    public static function addOrder($clientId, $shipment = 'In-Person Pickup', $address = 'NA', $paymentId = null)
    {
        $pdo = Database::connect();

        $history = json_encode([["date" => date("Y-m-d H:i:s"), "status" => "Draft"]]);

        $stmt = $pdo->prepare("
            INSERT INTO Toy_Order (Client_ID, Shipment, Address, Date_Created, History, Payment_ID, Status) 
            VALUES (:clientId, :shipment, :address, NOW(), :history, :paymentId, 'Draft')
        ");
        if ($stmt->execute([
            'clientId' => $clientId,
            'shipment' => $shipment,
            'address' => $address,
            'history' => $history,
            'paymentId' => $paymentId
        ])) {
            return $pdo->lastInsertId();
        }
        return false;
    }

    // Update order status
    public static function updateOrderStatus($id, $status)
    {
        $pdo = Database::connect();

        // Fetch current order history
        $stmt = $pdo->prepare("SELECT History FROM Toy_Order WHERE Order_ID = :id");
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return false;

        $history = json_decode($order['History'], true);
        $history[] = ["date" => date("Y-m-d H:i:s"), "status" => $status];

        $stmt = $pdo->prepare("
            UPDATE Toy_Order SET Status = :status, Date_Modified = NOW(), History = :history WHERE Order_ID = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'history' => json_encode($history)
        ]);
    }

    // Update order details
    public static function updateOrderDetails($id, $detail, $value)
    {
        $pdo = Database::connect();

        // Validate column name to prevent SQL injection
        $allowedColumns = ['Shipment', 'Address', 'Payment_ID']; // Add allowed column names
        if (!in_array($detail, $allowedColumns)) {
            throw new Exception("Invalid column name");
        }

        // Fetch current order history
        $stmt = $pdo->prepare("SELECT History FROM Toy_Order WHERE Order_ID = :id");
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return false;

        $stmt = $pdo->prepare("
                UPDATE Toy_Order SET Status = :status, $detail = :value WHERE Order_ID = :id
            ");
        return $stmt->execute([
            'id' => $id,
            'value' => $value
        ]);
    }
    // Delete an order
    public static function deleteOrder($id)
    {
        $pdo = Database::connect();

        // Delete all order items first to maintain integrity
        // ToyOrderItemController::deleteOrderItemsByOrder($id);

        $stmt = $pdo->prepare("DELETE FROM Toy_Order WHERE Order_ID = :id");
        return $stmt->execute(['id' => $id]);
    }
}
