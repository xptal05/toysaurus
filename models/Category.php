<?php
require_once BASE_PATH . '/models/BaseEntity.php';

class Category extends BaseEntity {
    public function __construct(
        int $categoryId,
        string $name,
        ?string $description,
        int $level,
        ?int $parentId
    ) {
        parent::__construct([
            'categoryId' => $categoryId,
            'name' => $name,
            'description' => $description,
            'level' => $level,
            'parentId' => $parentId
        ]);
    }
}
?>
