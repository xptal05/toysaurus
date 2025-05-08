<?php
require_once BASE_PATH . '/models/BaseEntity.php';
require_once BASE_PATH . '/controllers/ToyController.php';

class ToyOrderItem extends BaseEntity {
    private ?array $history;
    private ?Toy $toy; // Store the full Toy object

    public function __construct(
        int $orderItemId,
        int $orderItemTPoints,
        int $toyId,
        int $orderId,
        string $status,
        string $dateCreated,
        string $dateModified,
        ?string $historyJson,
        ?string $dateReturned
    ) {
        parent::__construct([
            'orderItemId' => $orderItemId,
            'orderItemTPoints' => $orderItemTPoints,
            'toyId' => $toyId,
            'orderId' => $orderId,
            'status' => $status,
            'dateCreated' => $dateCreated,
            'dateModified' => $dateModified,
            'dateReturned' => $dateReturned
        ]);

        $this->history = json_decode($historyJson, true) ?? [];
        $this->toy = ToyController::getToyById($toyId); // Fetch toy details via ToyController
    }

    public function getHistory(): array {
        return $this->history;
    }

    public function addHistory(string $date, string $status): void {
        $this->history[] = ['date' => $date, 'status' => $status];
    }

    public function getToy(): ?Toy {
        return $this->toy;
    }
}
?>
