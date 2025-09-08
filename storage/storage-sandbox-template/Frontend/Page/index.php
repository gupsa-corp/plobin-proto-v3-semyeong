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

// 기본 경로 처리
if ($path === '/' || empty($path)) {
    ?>
    <!DOCTYPE html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Frontend 홈페이지</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                font-size: 3rem;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            }
            p {
                font-size: 1.2rem;
                margin-bottom: 2rem;
                opacity: 0.9;
            }
            .info {
                background: rgba(255, 255, 255, 0.2);
                padding: 1rem;
                border-radius: 10px;
                margin-top: 2rem;
                font-family: 'Courier New', monospace;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🌐 Frontend 서버</h1>
            <p>Frontend 개발 서버가 정상적으로 실행 중입니다.</p>
            <div class="info">
                <strong>서버 정보:</strong><br>
                포트: 8444<br>
                PHP 버전: <?= PHP_VERSION ?><br>
                실행 경로: <?= __DIR__ ?><br>
                현재 시간: <?= date('Y-m-d H:i:s') ?>
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