<?php
namespace App\Controllers;

use App\Core\Controller;

class DebugUploadController extends Controller
{
    public function uploadTest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['testfile'])) {
            $file = $_FILES['testfile'];
            $targetDir = __DIR__ . '/../../public/images/products/tenis';
            $targetFile = $targetDir . '/test_' . time() . '.txt';

            $result = [
                'file_name' => $file['name'],
                'file_error' => $file['error'],
                'tmp_exists' => file_exists($file['tmp_name']),
                'is_uploaded_file' => is_uploaded_file($file['tmp_name']),
                'target_dir' => $targetDir,
                'dir_exists' => is_dir($targetDir),
                'dir_writable' => is_writable($targetDir),
                'dir_perms' => substr(sprintf('%o', fileperms($targetDir)), -4),
            ];

            if (is_dir($targetDir) && is_writable($targetDir) && is_uploaded_file($file['tmp_name'])) {
                $moved = move_uploaded_file($file['tmp_name'], $targetFile);
                $result['move_result'] = $moved;
                $result['file_created'] = file_exists($targetFile);
            }

            header('Content-Type: application/json');
            echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        echo '
<html>
<head><title>Debug Upload</title></head>
<body>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="testfile" required>
    <button>Test Upload</button>
</form>
</body>
</html>
        ';
    }
}

