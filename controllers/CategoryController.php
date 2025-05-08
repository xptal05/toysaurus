<?php
require_once BASE_PATH . '/models/Category.php';
require_once BASE_PATH . '/config/Database.php';

class CategoryController {

    // Fetch all categories
    public static function getAllCategories() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM Categories ORDER BY Level ASC, Name ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($cat) {
            return new Category(
                $cat['Category_ID'],
                $cat['Name'],
                $cat['Description'],
                $cat['Level'],
                $cat['Parent_ID']
            );
        }, $categories);
    }

    // Fetch all nested categories
    public static function getNestedCategories() {
        $categories = self::getAllCategories();
        $categoryMap = [];
    
        //  Prepare all categories indexed by ID
        foreach ($categories as $category) {
            $categoryMap[$category->getCategoryId()] = [
                'category' => $category,
                'children' => []
            ];
        }
        $nested = [];
    
        // Group them into a nested array
        foreach ($categoryMap as $id => &$item) {
            $parentId = $item['category']->getParentId();
    
            if ($parentId && isset($categoryMap[$parentId])) {
                $categoryMap[$parentId]['children'][] = &$item;
            } else {
                // Top-level category
                $nested[] = &$item;
            }
        }
    
        return $nested;
    }
    

    // Fetch a single category by ID
    public static function getCategoryById($categoryId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Categories WHERE Category_ID = :categoryId");
        $stmt->execute(['categoryId' => $categoryId]);
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cat) return null;

        return new Category(
            $cat['Category_ID'],
            $cat['Name'],
            $cat['Description'],
            $cat['Level'],
            $cat['Parent_ID']
        );
    }

    // Fetch all subcategories of a given category
    public static function getSubcategories($parentId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Categories WHERE Parent_ID = :parentId ORDER BY Name ASC");
        $stmt->execute(['parentId' => $parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new category
    public static function addCategory($name, ?string $description, int $level, ?int $parentId) {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("
            INSERT INTO Categories (Name, Description, Level, Parent_ID) 
            VALUES (:name, :description, :level, :parentId)
        ");
        return $stmt->execute([
            'name' => $name, 
            'description' => $description, 
            'level' => $level, 
            'parentId' => $parentId
        ]);
    }

    // Update category details
    public static function updateCategory($categoryId, $name, ?string $description, int $level, ?int $parentId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE Categories SET Name = :name, Description = :description, Level = :level, Parent_ID = :parentId 
            WHERE Category_ID = :categoryId
        ");
        return $stmt->execute([
            'categoryId' => $categoryId,
            'name' => $name, 
            'description' => $description, 
            'level' => $level, 
            'parentId' => $parentId
        ]);
    }

    // Delete a category
    public static function deleteCategory($categoryId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM Categories WHERE Category_ID = :categoryId");
        return $stmt->execute(['categoryId' => $categoryId]);
    }
}
?>
