<?php
require_once BASE_PATH . '/models/BaseEntity.php';
require_once BASE_PATH . '/controllers/MediaController.php';

class Media extends BaseEntity {
    public function __construct(
        $id,
        $entityType, //'client', 'toy', 'general'
        $entityId,
        $mediaType, //'image', 'document'
        $role, //'profile', 'document', 'main_photo', 'gallery_photo', 'other'
        $filename,
        $filePath,
        $mimeType,
        $fileSize,
        $uploadedAt,
        $isActive
    ) {
        parent::__construct([
            'mediaId'     => $id,
            'entityType'  => $entityType,
            'entityId'    => $entityId,
            'mediaType'   => $mediaType,
            'role'        => $role,
            'filename'    => $filename,
            'filePath'    => $filePath,
            'mimeType'    => $mimeType,
            'fileSize'    => $fileSize,
            'uploadedAt'  => $uploadedAt,
            'isActive'    => $isActive,
        ]);
    }
}
?>
