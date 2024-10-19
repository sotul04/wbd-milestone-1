<?php

class FileUploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/'; 
        
        // Check if the upload directory exists, create it if it does not
        if (!file_exists(__DIR__.$this->uploadDir)) {
            if (!mkdir(__DIR__.$this->uploadDir, 0777, true)) {
                throw new Exception('Failed to create upload directory: ' . $this->uploadDir);
            }
            // json_response_fail('Dir NYA GADA');
            // exit;
        } 
    }

    public function uploadFile($file, $userId, $allowedTypes = [], $maxFileSize = null)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['status' => 'error', 'message' => 'File upload error.'];
        }

        if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
            return ['status' => 'error', 'message' => 'Unsupported file type.'];
        }

        if ($maxFileSize !== null && $file['size'] > $maxFileSize) {
            return ['status' => 'error', 'message' => 'File is too large.'];
        }

        $uniqueId = uniqid($userId . '-', true);
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = $uniqueId . '.' . $fileExtension;
        $destination = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], __DIR__.$destination)) {
            return ['status' => 'success', 'filePath' => $destination, 'fileName' => $fileName];
        } else {
            return ['status' => 'error', 'message' => 'Failed to move uploaded file.'];
        }
    }
}
