<?php
require_once BASE_PATH . '/models/BaseEntity.php';
require_once BASE_PATH . '/controllers/ToyOrderItemController.php';
require_once BASE_PATH . '/controllers/ClientController.php';


class ToyOrder extends BaseEntity {
    private ?array $history;
    private array $orderItems; // Array of ToyOrderItem objects
    private Client $clientDetails; // Expect a Client object, not an array

    public function __construct(
        int $orderId = null,
        int $clientId = null,
        string $shipment = null,
        string $address = null,
        string $dateCreated = null,
        string $dateModified = null,
        ?string $historyJson = null,
        int $paymentId = null,
        string $status = null
    ) {
        parent::__construct([
            'orderId' => $orderId,
            'clientId' => $clientId,
            'shipment' => $shipment,
            'address' => $address,
            'dateCreated' => $dateCreated,
            'dateModified' => $dateModified,
            'paymentId' => $paymentId,
            'status' => $status
        ]);

        $this->history = json_decode($historyJson, true) ?? [];
        $this->orderItems = ToyOrderItemController::getOrderItemsByOrder($orderId); // Fetch all items in this order
        $this->clientDetails = ClientController::getClientById($clientId);
    }

    public function getHistory(): array {
        return $this->history;
    }

    public function addHistory(string $date, string $status): void {
        $this->history[] = ['date' => $date, 'status' => $status];
    }

    public function getOrderItems(): array {
        return $this->orderItems;
    }

    public function getClientDetails(): Client {
        return $this->clientDetails;
    }
}
?>
