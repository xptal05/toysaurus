<?php
require_once BASE_PATH . '/models/Media.php';
require_once BASE_PATH . '/config/Database.php';

class MediaController {

    // Get all media by entity type and ID (e.g., 'client', 12)
    public static function getMediaByEntity($entityType, $entityId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Media WHERE entity_type = :entityType AND entity_id = :entityId");
        $stmt->execute([
            'entityType' => $entityType,
            'entityId' => $entityId
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $media = [];
        foreach ($results as $item) {
            $media[] = new Media(
                $item['id'],
                $item['entity_type'],
                $item['entity_id'],
                $item['media_type'],
                $item['role'],
                $item['filename'],
                $item['file_path'],
                $item['mime_type'],
                $item['file_size'],
                $item['uploaded_at'],
                $item['is_active']
            );
        }

        return $media;
    }

    // Get specific media by ID
    public static function getMediaById($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Media WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return $item ? new Media(
            $item['id'],
            $item['entity_type'],
            $item['entity_id'],
            $item['media_type'],
            $item['role'],
            $item['filename'],
            $item['file_path'],
            $item['mime_type'],
            $item['file_size'],
            $item['uploaded_at'],
            $item['is_active']
        ) : null;
    }

    // Insert new media
    public static function addMedia($media) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO Media (
            entity_type, entity_id, media_type, role,
            filename, file_path, mime_type, file_size, uploaded_at, is_active
        ) VALUES (
            :entity_type, :entity_id, :media_type, :role,
            :filename, :file_path, :mime_type, :file_size, NOW(), :is_active
        )");

        return $stmt->execute([
            'entity_type' => $media->getEntityType(),
            'entity_id' => $media->getEntityId(),
            'media_type' => $media->getMediaType(),
            'role' => $media->getRole(),
            'filename' => $media->getFilename(),
            'file_path' => $media->getFilePath(),
            'mime_type' => $media->getMimeType(),
            'file_size' => $media->getFileSize(),
            'is_active' => $media->isActive()
        ]);
    }

    // Update media info (e.g. file name or role)
    public static function updateMedia($id, $fields) {
        $pdo = Database::connect();
        $set = [];
        $params = ['id' => $id];

        foreach ($fields as $key => $value) {
            $set[] = "$key = :$key";
            $params[$key] = $value;
        }

        $sql = "UPDATE Media SET " . implode(", ", $set) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete media (soft delete optional)
    public static function deleteMedia($id, $softDelete = true) {
        $pdo = Database::connect();

        if ($softDelete) {
            $stmt = $pdo->prepare("UPDATE Media SET is_active = 0 WHERE id = :id");
        } else {
            $stmt = $pdo->prepare("DELETE FROM media WHERE id = :id");
        }

        return $stmt->execute(['id' => $id]);
    }

    private static $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
        'image/gif',
        'video/mp4'
    ];
    
    private static $maxFileSize = 5 * 1024 * 1024; // 5 MB

    public static function validateUploadedFile($file) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'message' => 'No file uploaded.'];
        }
    
        if (!in_array($file['type'], self::$allowedMimeTypes)) {
            return ['success' => false, 'message' => 'File type not allowed.'];
        }
    
        if ($file['size'] > self::$maxFileSize) {
            return ['success' => false, 'message' => 'File exceeds maximum size (5MB).'];
        }
    
        return ['success' => true];
    }

    public static function uploadMedia($entityId, $type, $mediaType, $role = 'main') {
        // Check if files were uploaded
        if (!isset($_FILES['media']) || empty($_FILES['media']['name'][0])) {
            return ['success' => false, 'message' => 'No files uploaded or upload error.'];
        }
    
        $uploadedFiles = [];
        $failedFiles = [];
    
        // Loop through all the uploaded files
        foreach ($_FILES['media']['name'] as $key => $filename) {
            $tmpName = $_FILES['media']['tmp_name'][$key];
            $fileSize = $_FILES['media']['size'][$key];
            $fileType = $_FILES['media']['type'][$key];
    
            // Validate the uploaded file (file type and size)
            $validation = self::validateUploadedFile([
                'name' => $filename,
                'tmp_name' => $tmpName,
                'size' => $fileSize,
                'type' => $fileType,
            ]);
            if (!$validation['success']) {
                $failedFiles[] = $filename;  // Track failed files
                continue;  // Skip the current file and continue with others
            }
    
            // Set upload directory based on entity type (toys, clients, etc.)
            $uploadDir = BASE_PATH . '/public/uploads/' . $type . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
            }
    
            // Set target file path
            $targetPath = $uploadDir . basename($filename);
    
            // Move the file to the target directory
            if (move_uploaded_file($tmpName, $targetPath)) {
                // Create a Media object for each file
                $media = new Media(
                    null, // Let the DB auto-generate the ID
                    $type, 
                    $entityId, 
                    $mediaType, // 'image', 'document', etc.
                    $role, 
                    $filename, 
                    '/uploads/' . $type . '/' . $filename, 
                    $fileType, 
                    $fileSize, 
                    date('Y-m-d H:i:s'),
                    TRUE // isActive
                );
    
                // Add media to the database
                $addMediaResult = self::addMedia($media);
                if ($addMediaResult) {
                    $uploadedFiles[] = $filename;
                } else {
                    $failedFiles[] = $filename;  // Track failed files
                }
            } else {
                $failedFiles[] = $filename;  // Track failed files
            }
        }
    
        // Return result
        if (count($uploadedFiles) > 0) {
            $message = count($uploadedFiles) . ' file(s) uploaded successfully.';
        } else {
            $message = 'No files uploaded or failed to upload all files.';
        }
    
        if (count($failedFiles) > 0) {
            $message .= ' Failed to upload the following files: ' . implode(', ', $failedFiles);
        }
    
        return ['success' => true, 'message' => $message];
    }
    
    
    
    
}
?>
