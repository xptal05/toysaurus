<?php
require_once BASE_PATH . '/models/Payment.php';
require_once BASE_PATH . '/config/Database.php';

class PaymentController {

    // Fetch all payments for a specific client
    public static function getAllPaymentsByClient($clientId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Payments WHERE Client_ID = :clientId ORDER BY Payment_Date DESC");
        $stmt->execute(['clientId' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all payments (admin level)
    public static function getAllPayments() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM Payments ORDER BY Payment_Date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single payment by ID
    public static function getPaymentById($paymentId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Payments WHERE Payment_ID = :paymentId");
        $stmt->execute(['paymentId' => $paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) return null;

        return new Payment(
            $payment['Payment_ID'],
            $payment['Client_ID'],
            $payment['Reference_ID'],
            $payment['Reference_Type'],
            $payment['Amount'],
            $payment['Payment_Date'],
            $payment['Refund_Date'],
            $payment['Status'],
            $payment['Progress'],
            $payment['Status_History'],
            $payment['Created_At'],
            $payment['Updated_At']
        );
    }

    // Create a new payment
    public static function addPayment($clientId, $referenceId, $referenceType, $amount, $status = 'Processing') {
        $pdo = Database::connect();

        $statusHistory = json_encode([["date" => date("Y-m-d H:i:s"), "status" => $status]]);

        $stmt = $pdo->prepare("
            INSERT INTO Payments (Client_ID, Reference_ID, Reference_Type, Amount, Payment_Date, Status, Status_History, Created_At, Updated_At) 
            VALUES (:clientId, :referenceId, :referenceType, :amount, NOW(), :status, :statusHistory, NOW(), NOW())
        ");
        return $stmt->execute([
            'clientId' => $clientId, 
            'referenceId' => $referenceId, 
            'referenceType' => $referenceType, 
            'amount' => $amount, 
            'status' => $status,
            'statusHistory' => $statusHistory
        ]);
    }

    // Update payment status
    public static function updatePaymentStatus($paymentId, $newStatus) {
        $pdo = Database::connect();

        // Fetch current payment history
        $stmt = $pdo->prepare("SELECT Status_History FROM Payments WHERE Payment_ID = :paymentId");
        $stmt->execute(['paymentId' => $paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$payment) return false;

        $statusHistory = json_decode($payment['Status_History'], true);
        $statusHistory[] = ["date" => date("Y-m-d H:i:s"), "status" => $newStatus];

        $stmt = $pdo->prepare("
            UPDATE Payments SET Status = :newStatus, Status_History = :statusHistory, Updated_At = NOW() WHERE Payment_ID = :paymentId
        ");
        return $stmt->execute([
            'paymentId' => $paymentId,
            'newStatus' => $newStatus,
            'statusHistory' => json_encode($statusHistory)
        ]);
    }

    // Delete a payment (only if it's not completed)
    public static function deletePayment($paymentId) {
        $pdo = Database::connect();
        
        // Ensure payment is not already completed
        $stmt = $pdo->prepare("SELECT Status FROM Payments WHERE Payment_ID = :paymentId");
        $stmt->execute(['paymentId' => $paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment || in_array($payment['Status'], ['Succeeded', 'Refunded'])) {
            return false; // Do not delete completed or refunded payments
        }

        $stmt = $pdo->prepare("DELETE FROM Payments WHERE Payment_ID = :paymentId");
        return $stmt->execute(['paymentId' => $paymentId]);
    }
}
?>
