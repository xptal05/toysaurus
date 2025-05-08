<?php
require_once BASE_PATH . '/models/ClientSubscriptionOrder.php';
require_once BASE_PATH . '/config/Database.php';

class ClientSubscriptionOrderController {

    // Fetch all subscription orders
    public static function getAllSubscriptions() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM Client_Subscription_Order ORDER BY Start_Date DESC");
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($sub) {
            return new ClientSubscriptionOrder(
                $sub['Client_ID'],
                $sub['Subscription_ID'],
                $sub['Start_Date'],
                $sub['End_Date'],
                $sub['Recurring'],
                $sub['Status'],
                $sub['History']
            );
        }, $subscriptions);
    }

    // Fetch a single subscription order by ID
    public static function getSubscriptionById($clientId, $subscriptionId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Client_Subscription_Order WHERE Client_ID = :clientId AND Subscription_ID = :subscriptionId");
        $stmt->execute(['clientId' => $clientId, 'subscriptionId' => $subscriptionId]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sub) return null;

        return new ClientSubscriptionOrder(
            $sub['Client_ID'],
            $sub['Subscription_ID'],
            $sub['Start_Date'],
            $sub['End_Date'],
            $sub['Recurring'],
            $sub['Status'],
            $sub['History']
        );
    }

    // Fetch all subscription orders for a specific client
    public static function getSubscriptionsByClient($clientId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Client_Subscription_Order WHERE Client_ID = :clientId ORDER BY Start_Date DESC");
        $stmt->execute(['clientId' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new subscription order
    public static function addSubscription($clientId, $subscriptionId, $startDate, ?string $endDate, bool $recurring, string $status = "Active") {
        $pdo = Database::connect();

        $history = json_encode([["date" => date("Y-m-d H:i:s"), "status" => $status]]);

        $stmt = $pdo->prepare("
            INSERT INTO Client_Subscription_Order (Client_ID, Subscription_ID, Start_Date, End_Date, Recurring, Status, History) 
            VALUES (:clientId, :subscriptionId, :startDate, :endDate, :recurring, :status, :history)
        ");
        return $stmt->execute([
            'clientId' => $clientId,
            'subscriptionId' => $subscriptionId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'recurring' => $recurring,
            'status' => $status,
            'history' => $history
        ]);
    }

    // Update subscription status (Active, Cancelled, Expired)
    public static function updateSubscriptionStatus($clientId, $subscriptionId, $newStatus) {
        $pdo = Database::connect();

        // Fetch current subscription history
        $stmt = $pdo->prepare("SELECT History FROM Client_Subscription_Order WHERE Client_ID = :clientId AND Subscription_ID = :subscriptionId");
        $stmt->execute(['clientId' => $clientId, 'subscriptionId' => $subscriptionId]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$sub) return false;

        $history = json_decode($sub['History'], true);
        $history[] = ["date" => date("Y-m-d H:i:s"), "status" => $newStatus];

        $stmt = $pdo->prepare("
            UPDATE Client_Subscription_Order SET Status = :status, History = :history WHERE Client_ID = :clientId AND Subscription_ID = :subscriptionId
        ");
        return $stmt->execute([
            'clientId' => $clientId, 
            'subscriptionId' => $subscriptionId, 
            'status' => $newStatus, 
            'history' => json_encode($history)
        ]);
    }

    // Delete a subscription order
    public static function deleteSubscription($clientId, $subscriptionId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM Client_Subscription_Order WHERE Client_ID = :clientId AND Subscription_ID = :subscriptionId");
        return $stmt->execute(['clientId' => $clientId, 'subscriptionId' => $subscriptionId]);
    }
}
?>
