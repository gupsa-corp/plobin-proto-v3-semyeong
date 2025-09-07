<?php
namespace App\Functions\FileUpload;

class FileUpload
{
    private string $downloadsPath;
    private array $allowedExtensions;
    private int $maxFileSize;

    public function __construct()
    {
        $this->downloadsPath = __DIR__ . '/../../../Downloads/';
        $this->allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'doc', 'docx', 'zip'];
        $this->maxFileSize = 10 * 1024 * 1024; // 10MB
    }

    public function __invoke($action = 'upload')
    {
        switch ($action) {
            case 'upload':
                return $this->uploadFile();
            case 'list':
                return $this->listFiles();
            case 'delete':
                return $this->deleteFile();
            case 'info':
                return $this->getFileInfo();
            default:
                return ['success' => false, 'message' => 'Unknown action'];
        }
    }

    private function uploadFile(): array
    {
        try {
            // 업로드된 파일이 있는지 확인
            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false, 
                    'message' => 'No file uploaded or upload error occurred'
                ];
            }

            $file = $_FILES['file'];
            $fileName = $file['name'];
            $tempPath = $file['tmp_name'];
            $fileSize = $file['size'];

            // 파일 크기 검사
            if ($fileSize > $this->maxFileSize) {
                return [
                    'success' => false, 
                    'message' => 'File size exceeds limit (' . ($this->maxFileSize / (1024*1024)) . 'MB)'
                ];
            }

            // 파일 확장자 검사
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $this->allowedExtensions)) {
                return [
                    'success' => false, 
                    'message' => 'File type not allowed. Allowed: ' . implode(', ', $this->allowedExtensions)
                ];
            }

            // 파일명 중복 처리
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $finalFileName = $fileName;
            $counter = 1;
            
            while (file_exists($this->downloadsPath . $finalFileName)) {
                $finalFileName = $baseName . '_' . $counter . '.' . $fileExtension;
                $counter++;
            }

            $finalPath = $this->downloadsPath . $finalFileName;

            // 파일 업로드 (테스트 환경에서는 copy 사용)
            if (is_uploaded_file($tempPath) ? move_uploaded_file($tempPath, $finalPath) : copy($tempPath, $finalPath)) {
                return [
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'data' => [
                        'original_name' => $fileName,
                        'saved_name' => $finalFileName,
                        'size' => $fileSize,
                        'extension' => $fileExtension,
                        'upload_time' => date('Y-m-d H:i:s')
                    ]
                ];
            } else {
                return [
                    'success' => false, 
                    'message' => 'Failed to save file'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false, 
                'message' => 'Upload error: ' . $e->getMessage()
            ];
        }
    }

    private function listFiles(): array
    {
        try {
            $files = [];
            $directory = opendir($this->downloadsPath);
            
            if ($directory) {
                while (($file = readdir($directory)) !== false) {
                    if ($file !== '.' && $file !== '..' && !is_dir($this->downloadsPath . $file)) {
                        $filePath = $this->downloadsPath . $file;
                        $files[] = [
                            'name' => $file,
                            'size' => filesize($filePath),
                            'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                            'extension' => strtolower(pathinfo($file, PATHINFO_EXTENSION))
                        ];
                    }
                }
                closedir($directory);
            }

            return [
                'success' => true,
                'data' => $files,
                'count' => count($files)
            ];

        } catch (Exception $e) {
            return [
                'success' => false, 
                'message' => 'Error listing files: ' . $e->getMessage()
            ];
        }
    }

    private function deleteFile(): array
    {
        try {
            $fileName = $_POST['filename'] ?? $_GET['filename'] ?? null;
            
            if (!$fileName) {
                return [
                    'success' => false, 
                    'message' => 'Filename parameter required'
                ];
            }

            $filePath = $this->downloadsPath . basename($fileName);
            
            if (!file_exists($filePath)) {
                return [
                    'success' => false, 
                    'message' => 'File not found'
                ];
            }

            if (unlink($filePath)) {
                return [
                    'success' => true,
                    'message' => 'File deleted successfully',
                    'data' => ['deleted_file' => $fileName]
                ];
            } else {
                return [
                    'success' => false, 
                    'message' => 'Failed to delete file'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false, 
                'message' => 'Delete error: ' . $e->getMessage()
            ];
        }
    }

    private function getFileInfo(): array
    {
        try {
            $fileName = $_GET['filename'] ?? null;
            
            if (!$fileName) {
                return [
                    'success' => false, 
                    'message' => 'Filename parameter required'
                ];
            }

            $filePath = $this->downloadsPath . basename($fileName);
            
            if (!file_exists($filePath)) {
                return [
                    'success' => false, 
                    'message' => 'File not found'
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'name' => $fileName,
                    'size' => filesize($filePath),
                    'created' => date('Y-m-d H:i:s', filectime($filePath)),
                    'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                    'extension' => strtolower(pathinfo($fileName, PATHINFO_EXTENSION)),
                    'mime_type' => mime_content_type($filePath)
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false, 
                'message' => 'Info error: ' . $e->getMessage()
            ];
        }
    }
}