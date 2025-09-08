<?php
/**
 * FileUpload Function Test Code
 * 파일 업로드 기능을 테스트하는 코드
 */

require_once __DIR__ . '/Release/Function.php';
use App\Functions\FileUpload\FileUpload;

echo "<h2>FileUpload Function Test</h2>";

// 테스트용 FileUpload 인스턴스 생성
$fileUpload = new FileUpload();

echo "<h3>1. File Upload Test</h3>";

// 파일 업로드 시뮬레이션을 위한 테스트 파일 생성
$testFileName = 'test_file.txt';
$testContent = 'This is a test file content created at ' . date('Y-m-d H:i:s');
$tempTestFile = sys_get_temp_dir() . '/' . $testFileName;
file_put_contents($tempTestFile, $testContent);

// $_FILES 배열 시뮬레이션
$_FILES['file'] = [
    'name' => $testFileName,
    'tmp_name' => $tempTestFile,
    'size' => strlen($testContent),
    'error' => UPLOAD_ERR_OK
];

echo "<p>Testing file upload with simulated file: " . $testFileName . "</p>";
$uploadResult = $fileUpload('upload');
echo "<pre>" . json_encode($uploadResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

echo "<h3>2. File List Test</h3>";
$listResult = $fileUpload('list');
echo "<pre>" . json_encode($listResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

echo "<h3>3. File Info Test</h3>";
if ($uploadResult['success'] && isset($uploadResult['data']['saved_name'])) {
    $_GET['filename'] = $uploadResult['data']['saved_name'];
    $infoResult = $fileUpload('info');
    echo "<p>Getting info for: " . $uploadResult['data']['saved_name'] . "</p>";
    echo "<pre>" . json_encode($infoResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
}

echo "<h3>4. Invalid Action Test</h3>";
$invalidResult = $fileUpload('invalid_action');
echo "<pre>" . json_encode($invalidResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

echo "<h3>5. No File Upload Test</h3>";
unset($_FILES['file']);
$noFileResult = $fileUpload('upload');
echo "<pre>" . json_encode($noFileResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

echo "<h3>6. File Delete Test (Optional)</h3>";
if ($uploadResult['success'] && isset($uploadResult['data']['saved_name'])) {
    $_POST['filename'] = $uploadResult['data']['saved_name'];
    echo "<p>Attempting to delete: " . $uploadResult['data']['saved_name'] . "</p>";
    $deleteResult = $fileUpload('delete');
    echo "<pre>" . json_encode($deleteResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
}

// 임시 파일 정리
if (file_exists($tempTestFile)) {
    unlink($tempTestFile);
}

echo "<p><strong>Test completed!</strong></p>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Upload Test Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .upload-form { border: 1px solid #ccc; padding: 20px; margin: 20px 0; }
        input[type="file"] { margin: 10px 0; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        button:hover { background: #005a87; }
    </style>
</head>
<body>
    <div class="upload-form">
        <h3>Manual File Upload Test</h3>
        <form action="/projects/gogo/Backend/FileUpload/upload" method="post" enctype="multipart/form-data">
            <p>Select file to upload:</p>
            <input type="file" name="file" required>
            <br><br>
            <button type="submit">Upload File</button>
        </form>
        
        <h4>API Endpoints:</h4>
        <ul>
            <li><strong>POST</strong> /projects/gogo/Backend/FileUpload/upload - Upload file</li>
            <li><strong>GET</strong> /projects/gogo/Backend/FileUpload/list - List all files</li>
            <li><strong>GET</strong> /projects/gogo/Backend/FileUpload/info?filename=file.txt - Get file info</li>
            <li><strong>POST</strong> /projects/gogo/Backend/FileUpload/delete - Delete file (filename in POST data)</li>
        </ul>
    </div>
</body>
</html>