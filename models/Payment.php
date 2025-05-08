<?php
include_once 'BaseEntity.php';

class Payment extends BaseEntity {
    private array $statusHistory;

    public function __construct(int $paymentId, int $clientId, int $referenceId, string $referenceType, float $amount, ?string $paymentDate, ?string $refundDate, string $status, string $progress, string $statusHistoryJson) {
        parent::__construct([
            'paymentId' => $paymentId,
            'clientId' => $clientId,
            'referenceId' => $referenceId,
            'referenceType' => $referenceType,
            'amount' => $amount,
            'paymentDate' => $paymentDate,
            'refundDate' => $refundDate,
            'status' => $status,
            'progress' => $progress        ]);
        $this->statusHistory = json_decode($statusHistoryJson, true) ?? [];
    }

    public function addStatusHistory(string $newStatus): void {
        $this->statusHistory[] = [
            'date' => date('Y-m-d H:i:s'),
            'status' => $newStatus
        ];
    }

    public function getStatusHistory(): array {
        return $this->statusHistory;
    }

}
?>
