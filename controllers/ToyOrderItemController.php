<?php
require_once BASE_PATH.'/models/ToyOrderItem.php';
require_once BASE_PATH.'/controllers/ToyController.php';
require_once BASE_PATH.'/config/Database.php';

class ToyOrderItemController {

    // Fetch all toy order items
    public static function getAllOrderItems() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM Toy_Order_Item");
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($item) {
            return new ToyOrderItem(
                $item['Order_Item_ID'],
                $item['Order_Item_tPoints'],
                $item['Toy_ID'],
                $item['Order_ID'],
                $item['Status'],
                $item['Date_Created'],
                $item['Date_Modified'],
                $item['History'],
                $item['Date_Returned'],
                ToyController::getToyById($item['Toy_ID']) // Fetch toy using ToyController
            );
        }, $orderItems);
    }

    // Fetch a single toy order item by ID
    public static function getOrderItemById($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Toy_Order_Item WHERE Order_Item_ID = :id");
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) return null;

        return new ToyOrderItem(
            $item['Order_Item_ID'],
            $item['Order_Item_tPoints'],
            $item['Toy_ID'],
            $item['Order_ID'],
            $item['Status'],
            $item['Date_Created'],
            $item['Date_Modified'],
            $item['History'],
            $item['Date_Returned'],
            ToyController::getToyById($item['Toy_ID']) // Fetch toy using ToyController
        );
    }

    // Fetch all items in a specific order
    public static function getOrderItemsByOrder($orderId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Toy_Order_Item WHERE Order_ID = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($item) {
            return new ToyOrderItem(
                $item['Order_Item_ID'],
                $item['Order_Item_tPoints'],
                $item['Toy_ID'],
                $item['Order_ID'],
                $item['Status'],
                $item['Date_Created'],
                $item['Date_Modified'],
                $item['History'],
                $item['Date_Returned'],
                ToyController::getToyById($item['Toy_ID']) // Fetch toy using ToyController
            );
        }, $orderItems);
    }

    // Add a toy to an order
    public static function addOrderItem($toyId, $orderId) {
        $pdo = Database::connect();

        // Validate if toy exists
        $toy = ToyController::getToyById($toyId);
        if (!$toy) return false;
        $toyTPoints = $toy->get('tPoints');

        $history = json_encode([["date" => date("Y-m-d H:i:s"), "status" => "Pending Payment"]]);

        $stmt = $pdo->prepare("
            INSERT INTO Toy_Order_Item (Toy_ID, Order_ID, Status, Date_Created, History, Order_Item_tPoints) 
            VALUES (:toyId, :orderId, 'Pending Payment', NOW(), :history, :toyTPoints)
        ");
        return $stmt->execute([
            'toyId' => $toyId, 
            'orderId' => $orderId, 
            'history' => $history,
            'toyTPoints' => $toyTPoints
        ]);
    }

    // Remove a toy from an order
public static function removeOrderItem($toyId, $orderId) {
    $pdo = Database::connect();

    // Delete only one instance of the toy from the order (in case multiples exist)
    $stmt = $pdo->prepare("
        DELETE FROM Toy_Order_Item 
        WHERE Toy_ID = :toyId AND Order_ID = :orderId 
        ORDER BY Date_Created ASC 
        LIMIT 1
    ");

    return $stmt->execute([
        'toyId' => $toyId, 
        'orderId' => $orderId
    ]);
}


    // Update toy order item status
    public static function updateOrderItemStatus($id, $status) {
        $pdo = Database::connect();

        // Fetch current order item history
        $stmt = $pdo->prepare("SELECT History FROM Toy_Order_Item WHERE Order_Item_ID = :id");
        $stmt->execute(['id' => $id]);
        $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$orderItem) return false;

        $history = json_decode($orderItem['History'], true);
        $history[] = ["date" => date("Y-m-d H:i:s"), "status" => $status];

        $stmt = $pdo->prepare("
            UPDATE Toy_Order_Item SET Status = :status, Date_Modified = NOW(), History = :history WHERE Order_Item_ID = :id
        ");
        return $stmt->execute([
            'id' => $id, 
            'status' => $status, 
            'history' => json_encode($history)
        ]);
    }

    // Mark an order item as returned
    public static function markAsReturned($id) {
        $pdo = Database::connect();

        // Fetch current order item history
        $stmt = $pdo->prepare("SELECT History FROM Toy_Order_Item WHERE Order_Item_ID = :id");
        $stmt->execute(['id' => $id]);
        $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$orderItem) return false;

        $history = json_decode($orderItem['History'], true);
        $history[] = ["date" => date("Y-m-d H:i:s"), "status" => "Returned"];

        $stmt = $pdo->prepare("
            UPDATE Toy_Order_Item SET Status = 'Returned', Date_Modified = NOW(), Date_Returned = NOW(), History = :history 
            WHERE Order_Item_ID = :id
        ");
        return $stmt->execute([
            'id' => $id, 
            'history' => json_encode($history)
        ]);
    }

    // Delete an order item
    public static function deleteOrderItem($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM Toy_Order_Item WHERE Order_Item_ID = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
