<?php
require_once BASE_PATH . '/config/Database.php';

class ToyMetaController {

    // Fetch all meta attributes for a toy
    public static function getMetaByToyId($toyId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Toy_Meta WHERE Toy_ID = :toyId");
        $stmt->execute(['toyId' => $toyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new meta attribute to a toy
    public static function addMeta($toyId, $metaKey, $metaValue) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO Toy_Meta (Toy_ID, Meta_Key, Meta_Value) 
            VALUES (:toyId, :metaKey, :metaValue)
        ");
        return $stmt->execute([
            'toyId' => $toyId, 
            'metaKey' => $metaKey, 
            'metaValue' => $metaValue
        ]);
    }

    // Delete a meta attribute from a toy
    public static function deleteMeta($metaId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM Toy_Meta WHERE Meta_ID = :metaId");
        return $stmt->execute(['metaId' => $metaId]);
    }
}
?>
