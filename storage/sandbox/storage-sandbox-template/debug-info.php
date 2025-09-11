<?php
/**
 * 샌드박스 디버그 정보 페이지
 * 현재 위치와 경로 정보를 확인하기 위한 페이지
 */

require_once __DIR__ . '/common.php';

$screenInfo = getCurrentScreenInfo();
$uploadPaths = getUploadPaths();

// 백엔드 헬퍼도 테스트
require_once __DIR__ . '/backend/SandboxHelper.php';
$backendInfo = SandboxHelper::debugInfo();

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>샌드박스 디버그 정보</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">🔍 샌드박스 디버그 정보</h1>
        
        <!-- 현재 위치 정보 -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">📍 현재 위치 정보</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">화면 타입</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"><?= htmlspecialchars($screenInfo['type']) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">화면 이름</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"><?= htmlspecialchars($screenInfo['name']) ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">현재 URL</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded break-all"><?= htmlspecialchars($screenInfo['url']) ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">상대 경로</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"><?= htmlspecialchars($screenInfo['relative_path']) ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">절대 경로</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded break-all"><?= htmlspecialchars($screenInfo['full_path']) ?></p>
                </div>
            </div>
        </div>

        <!-- 업로드 경로 정보 -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">📂 업로드 경로 정보</h2>
            <div class="space-y-3">
                <?php foreach ($uploadPaths as $key => $path): ?>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium text-gray-700"><?= htmlspecialchars($key) ?></span>
                    <span class="text-sm text-gray-900 break-all"><?= htmlspecialchars($path) ?></span>
                    <span class="text-xs <?= is_dir($path) ? 'text-green-600' : 'text-red-600' ?>">
                        <?= is_dir($path) ? '✓ 존재' : '✗ 없음' ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 백엔드 정보 -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">⚙️ 백엔드 정보</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">PHP 버전</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"><?= $backendInfo['php_version'] ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">서버 시간</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"><?= $backendInfo['server_time'] ?></p>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">샌드박스 정보</label>
                <div class="bg-gray-50 p-3 rounded">
                    <?php foreach ($backendInfo['sandbox_info'] as $key => $value): ?>
                    <div class="flex justify-between py-1">
                        <span class="font-medium text-gray-700"><?= htmlspecialchars($key) ?></span>
                        <span class="text-sm text-gray-900"><?= htmlspecialchars($value) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- URL 생성 테스트 -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">🔗 URL 생성 테스트</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">다중 파일 업로드 화면</label>
                    <p class="mt-1 text-sm text-gray-900 bg-blue-50 p-2 rounded">
                        <a href="<?= getScreenUrl('frontend', '007-screen-multi-file-upload') ?>" class="text-blue-600 hover:text-blue-800">
                            <?= htmlspecialchars(getScreenUrl('frontend', '007-screen-multi-file-upload')) ?>
                        </a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">파일 목록 화면</label>
                    <p class="mt-1 text-sm text-gray-900 bg-blue-50 p-2 rounded">
                        <a href="<?= getScreenUrl('frontend', '008-screen-uploaded-files-list') ?>" class="text-blue-600 hover:text-blue-800">
                            <?= htmlspecialchars(getScreenUrl('frontend', '008-screen-uploaded-files-list')) ?>
                        </a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">파일 업로드 API</label>
                    <p class="mt-1 text-sm text-gray-900 bg-green-50 p-2 rounded"><?= htmlspecialchars(getApiUrl('file-upload')) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">업로드된 파일 API</label>
                    <p class="mt-1 text-sm text-gray-900 bg-green-50 p-2 rounded"><?= htmlspecialchars(getApiUrl('uploaded-files')) ?></p>
                </div>
            </div>
        </div>

        <!-- 서버 정보 -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">🖥️ 서버 정보</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">HTTP Host</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded"><?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Request URI</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded break-all"><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Script Name</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded break-all"><?= htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Document Root</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded break-all"><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>

        <!-- 네비게이션 -->
        <div class="mt-8 text-center">
            <div class="space-x-4">
                <a href="<?= getScreenUrl('frontend', '001-screen-dashboard') ?>" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    📊 대시보드
                </a>
                <a href="<?= getScreenUrl('frontend', '007-screen-multi-file-upload') ?>" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    📁 파일 업로드
                </a>
                <a href="<?= getScreenUrl('frontend', '008-screen-uploaded-files-list') ?>" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    📋 파일 목록
                </a>
            </div>
        </div>
    </div>

    <script>
        // 페이지 로드 시 현재 위치 정보를 콘솔에 출력
        console.log('=== 샌드박스 디버그 정보 ===');
        console.log('현재 위치:', <?= json_encode($screenInfo) ?>);
        console.log('업로드 경로:', <?= json_encode($uploadPaths) ?>);
        console.log('백엔드 정보:', <?= json_encode($backendInfo) ?>);
    </script>
</body>
</html>