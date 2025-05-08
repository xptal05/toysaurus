<?php
require_once BASE_PATH . '/models/BaseEntity.php';

class ClientSubscriptionOrder extends BaseEntity {
    private array $history;

    public function __construct(
        int $clientId,
        int $subscriptionId,
        string $startDate,
        ?string $endDate,
        bool $recurring,
        string $status,
        string $historyJson
    ) {
        parent::__construct([
            'clientId' => $clientId,
            'subscriptionId' => $subscriptionId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'recurring' => $recurring,
            'status' => $status
        ]);

        $this->history = json_decode($historyJson, true) ?? [];
    }

    // Add new entry to subscription history
    public function addHistory(string $newStatus): void {
        $this->history[] = [
            'date' => date('Y-m-d H:i:s'),
            'status' => $newStatus
        ];
        //$this->status = $newStatus; // Also update current status
    }

    public function getHistory(): array {
        return $this->history;
    }
}
?>
