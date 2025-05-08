<?php
require_once BASE_PATH . '/models/BaseEntity.php';
require_once BASE_PATH . '/controllers/ClientAccountOperationsController.php';
require_once BASE_PATH . '/controllers/ClientController.php';

require_once BASE_PATH . '/models/Photo.php';
require_once BASE_PATH . '/models/Media.php';

class Client extends BaseEntity {
    private ?Photo $photo;
    private array $accountOperations; // Store account operations

    public function __construct(
        int $clientId = null,
        string $firstName = null,
        string $lastName = null,
        string $email = null,
        string $phone = null,
        string $loginId = null,
        string $password = null,
        string $addressJson = '{ "city": "","number": "","street": "","postal_code": ""}',
        ?Photo $photo = null
    ) {
        parent::__construct([
            'clientId' => $clientId,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'loginId' => $loginId,
            'password' => $password,
            'address' => json_decode($addressJson, true) // Convert JSON to array
        ]);

        $this->photo = $photo;
        $this->accountOperations = ClientAccountOperationsController::getAllOperationsByClient($clientId); // Fetch account operations
    }

    public function getPhoto(): ?Photo {
        return $this->photo;
    }

    public function setPhoto(Photo $photo): void {
        $this->photo = $photo;
    }

    public function getAccountOperations(): array {
        return $this->accountOperations;
    }
}
?>
