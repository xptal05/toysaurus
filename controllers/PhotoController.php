<?php
require_once BASE_PATH.'/models/Photo.php';
require_once BASE_PATH.'/config/Database.php';

class PhotoController {

    // Fetch a photo by Type and Type_ID (e.g., Client, Toy, Category)
    public static function getPhoto($type, $typeId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Photos WHERE Type = :type AND Type_ID = :typeId LIMIT 1");
        $stmt->execute(['type' => $type, 'typeId' => $typeId]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $photo ? new Photo(
            $photo['Photo_ID'],
            $photo['Name'],
            $photo['Alt'],
            $photo['Type'],
            $photo['Type_ID'],
            $photo['Date_Created']
        ) : null;
    }

    // Upload or update a photo
    public static function updatePhoto($type, $typeId, $photoName, $photoAlt) {
        $pdo = Database::connect();

        // Check if photo already exists for the entity
        $stmt = $pdo->prepare("SELECT Photo_ID FROM Photos WHERE Type = :type AND Type_ID = :typeId");
        $stmt->execute(['type' => $type, 'typeId' => $typeId]);
        $existingPhoto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingPhoto) {
            // Update existing photo
            $stmt = $pdo->prepare("UPDATE Photos SET Name = :name, Alt = :alt WHERE Photo_ID = :photoId");
            return $stmt->execute([
                'name' => $photoName,
                'alt' => $photoAlt,
                'photoId' => $existingPhoto['Photo_ID']
            ]);
        } else {
            // Insert new photo
            $stmt = $pdo->prepare("INSERT INTO Photos (Name, Alt, Type, Type_ID) VALUES (:name, :alt, :type, :typeId)");
            return $stmt->execute([
                'name' => $photoName,
                'alt' => $photoAlt,
                'type' => $type,
                'typeId' => $typeId
            ]);
        }
    }

    // Delete a photo
    public static function deletePhoto($photoId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM Photos WHERE Photo_ID = :photoId");
        return $stmt->execute(['photoId' => $photoId]);
    }
}
?>
