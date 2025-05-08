<?php
include_once 'BaseEntity.php';

class Photo extends BaseEntity {
    public function __construct(int $photoId, string $name, string $alt, string $type, int $typeId, string $dateCreated) {
        parent::__construct([
            'photoId' => $photoId,
            'name' => $name,
            'alt' => $alt,
            'type' => $type,
            'typeId' => $typeId,
            'dateCreated' => $dateCreated
        ]);
    }
}
?>
