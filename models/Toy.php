<?php
require_once BASE_PATH . '/models/BaseEntity.php';
require_once BASE_PATH . '/controllers/ToyController.php';

class Toy extends BaseEntity
{
    private ?array $history;
    private array $metaData; // Store meta attributes

    public function __construct(
        int $toyId,
        string $name,
        int $tPoints,
        float $priceNew,
        string $type,
        string $status,
        string $state,
        string $dateCreated,
        ?string $historyJson,
        ?string $categories
    ) {
        parent::__construct([
            'toyId' => $toyId,
            'name' => $name,
            'tPoints' => $tPoints,
            'priceNew' => $priceNew,
            'type' => $type,
            'status' => $status,
            'state' => $state,
            'dateCreated' => $dateCreated,
            'categories' => $categories
        ]);

        $this->history = json_decode($historyJson, true) ?? [];
        $this->metaData = ToyController::getMetaByToyId($toyId); // Fetch toy meta attributes using ToyController
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function addHistory(string $date, string $status): void
    {
        $this->history[] = ['date' => $date, 'status' => $status];
    }

    public function getMetaData(): array
    {
        return $this->metaData;
    }

    public function getMetaValue(string $key): ?string
    {
        foreach ($this->metaData as $meta) {
            if ($meta['Meta_Key'] === $key) {
                return $meta['Meta_Value'];
            }
        }
        return null; // Return null if meta key not found
    }

    public function getSlug(): string
    {
        return strtolower(str_replace(' ', '_', $this->get('name')));
    }
}
