<?php
/**
 * Frontend Entry Point
 * 페이지 라우팅 시스템
 */

// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 현재 경로 가져오기
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];

// 사용 가능한 페이지 목록 가져오기
function getAvailablePages() {
    $pagesDir = __DIR__ . '/Page';
    $pages = [];
    
    if (is_dir($pagesDir)) {
        $files = scandir($pagesDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $pageName = pathinfo($file, PATHINFO_FILENAME);
                if ($pageName !== 'index') {
                    $pages[] = [
                        'name' => $pageName,
                        'url' => '/projects/gogo/Frontend/Page/' . $pageName . '.php',
                        'file' => $file
                    ];
                }
            }
        }
    }
    
    return $pages;
}

// 기본 경로 처리
if ($path === '/' || empty($path) || $path === '/projects/gogo/Frontend/index.php') {
    $pages = getAvailablePages();
    ?>
    <!DOCTYPE html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>페이지 목록</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                margin: 0;
                padding: 2rem;
                min-height: 100vh;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
            }
            h1 {
                font-size: 3rem;
                margin-bottom: 2rem;
                text-align: center;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            }
            .pages-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 2rem;
                margin-top: 2rem;
            }
            .page-card {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 15px;
                backdrop-filter: blur(10px);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                padding: 2rem;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .page-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 48px rgba(0, 0, 0, 0.4);
            }
            .page-card h3 {
                margin: 0 0 1rem 0;
                font-size: 1.5rem;
            }
            .page-card a {
                color: #fff;
                text-decoration: none;
                padding: 0.5rem 1rem;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 8px;
                display: inline-block;
                margin-top: 1rem;
                transition: background 0.3s ease;
            }
            .page-card a:hover {
                background: rgba(255, 255, 255, 0.3);
            }
            .empty-state {
                text-align: center;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 15px;
                backdrop-filter: blur(10px);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                padding: 3rem;
                margin-top: 2rem;
            }
            .info {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
                padding: 1rem;
                margin-top: 2rem;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>📄 페이지 목록</h1>
            
            <?php if (!empty($pages)): ?>
                <div class="pages-grid">
                    <?php foreach ($pages as $page): ?>
                        <div class="page-card">
                            <h3><?= htmlspecialchars($page['name']) ?></h3>
                            <p>파일: <?= htmlspecialchars($page['file']) ?></p>
                            <a href="<?= htmlspecialchars($page['url']) ?>" target="_blank">페이지 열기</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h2>🚫 사용 가능한 페이지가 없습니다</h2>
                    <p>Page 폴더에 PHP 파일을 추가해주세요.</p>
                </div>
            <?php endif; ?>
            
            <div class="info">
                <strong>시스템 정보:</strong><br>
                페이지 디렉토리: <?= __DIR__ . '/Page' ?><br>
                PHP 버전: <?= PHP_VERSION ?><br>
                현재 시간: <?= date('Y-m-d H:i:s') ?><br>
                총 페이지 수: <?= count($pages) ?>개
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// pages 디렉토리에서 페이지 찾기
$pagesDir = __DIR__ . '/pages';
$pathSegments = explode('/', trim($path, '/'));

if (!empty($pathSegments[0])) {
    $pageName = $pathSegments[0];
    $pagePath = $pagesDir . '/' . $pageName . '.php';
    
    if (file_exists($pagePath)) {
        include $pagePath;
        exit;
    }
}

// 404 페이지
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - 페이지를 찾을 수 없습니다</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>요청하신 페이지를 찾을 수 없습니다.</p>
        <p>요청 경로: <?= htmlspecialchars($path) ?></p>
        <p><a href="/" style="color: #fff; text-decoration: underline;">홈으로 돌아가기</a></p>
    </div>
</body>
</html>