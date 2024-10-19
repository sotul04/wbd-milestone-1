<?php

class StorageController extends Controller
{
    public function index()
    {
        header("HTTP/1.1 404 Not Found");
        echo "File not found.";
    }

    public function files(...$params)
    {
        $length = count($params);
        if ($length === 0 || $length === 1) {
            header("HTTP/1.1 404 Not Found");
            echo "File not found.";
        } else {
            $this->serve($params[0], $params[1]);
        }
    }

    public function serve($folder, $fileName)
    {
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("HTTP/1.1 403 Forbidden");
            echo "Unauthorized access.";
            exit;
        }

        // Full path to the file
        $fullPath = __DIR__ . '/../storage/files/' . $folder . '/' . $fileName;
        $role = $_SESSION['role'];

        // Check if file exists
        if (!file_exists($fullPath)) {
            header("HTTP/1.1 404 Not Found");
            echo "File not found.";
            exit;
        }

        // Access control: jobseeker can only access their own files
        if ($role === 'jobseeker') {
            $isAccessed = $this->model('ApplicationModel')->isFileOwnedByJobseeker($_SESSION['user_id'], $fileName);
            if (!$isAccessed) {
                header("HTTP/1.1 403 Forbidden");
                echo "Unauthorized access.";
                exit;
            }
        }
        // Access control: company can only access files related to their jobs
        else {
            $isAccessed = $this->model('ApplicationModel')->isFileAccessedByCompany($_SESSION['user_id'], $fileName);
            if (!$isAccessed) {
                header("HTTP/1.1 403 Forbidden");
                echo "Unauthorized access.";
                exit;
            }
        }

        $fileExtension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $mimeType = $this->getMimeType($fileExtension);

        if ($mimeType) {
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . basename($fullPath) . '"');
            readfile($fullPath);
        } else {
            header("HTTP/1.1 415 Unsupported Media Type");
            echo "Unsupported file type.";
        }
    }

    // Only allow serving mp4 and pdf files
    private function getMimeType($fileExtension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
        ];

        return $mimeTypes[$fileExtension] ?? null;
    }
}
