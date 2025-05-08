<?php
require_once BASE_PATH . '/config/Database.php';

class ClientAccountOperationsController {

    // Fetch all account operations for a client
    public static function getAllOperationsByClient($clientId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Client_Account_Operations WHERE Client_ID = :clientId ORDER BY Date DESC");
        $stmt->execute(['clientId' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return array instead of entity objects
    }

    // Fetch account operations by type (Deposit, T-Points) for a client
    public static function getOperationsByType($clientId, $type) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Client_Account_Operations WHERE Client_ID = :clientId AND Type = :type ORDER BY Date DESC");
        $stmt->execute(['clientId' => $clientId, 'type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new account operation (Deposit or T-Points adjustment)
    public static function addOperation($clientId, $type, $amount, $operation, $comments = null) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO Client_Account_Operations (Client_ID, Type, Amount, Date, Operation, Comments) 
            VALUES (:clientId, :type, :amount, NOW(), :operation, :comments)
        ");
        return $stmt->execute([
            'clientId' => $clientId, 
            'type' => $type, 
            'amount' => $amount, 
            'operation' => $operation, 
            'comments' => $comments
        ]);
    }

    // Delete an operation
    public static function deleteOperation($operationId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM Client_Account_Operations WHERE Operation_ID = :operationId");
        return $stmt->execute(['operationId' => $operationId]);
    }
}
?>
