<?php
/**
 * API Entry Point
 * 간단한 라우팅 시스템으로 Functions 기반 API 제공
 */

// 에러 리포팅 설정
error_reporting(E_ALL);

// CommonFunctions 로드
require_once __DIR__ . '/Commons/CommonFunctions.php';
use App\Commons\CommonFunctions;

// 라우팅 처리
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];

// JSON 입력 데이터 처리
$input = [];
if ($requestMethod === 'POST') {
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true) ?? [];
}

try {
    // URL 경로 분석 - 프로젝트별 경로 처리
    $pathSegments = explode('/', trim($path, '/'));
    
    // projects/gogo/Backend 경로 제거
    if (count($pathSegments) >= 3 && 
        $pathSegments[0] === 'projects' && 
        $pathSegments[1] === 'gogo' && 
        $pathSegments[2] === 'Backend') {
        $pathSegments = array_slice($pathSegments, 3);
    }
    
    
    // 기본 라우트 (루트 경로 또는 빈 경로) - API 스펙 문서
    if (empty($pathSegments) || empty($pathSegments[0]) || 
        (count($pathSegments) == 1 && $pathSegments[0] === 'index.php')) {
        // HTML로 API 스펙 문서 표시
        $functions = CommonFunctions::getAvailableFunctions();
        ?>
        <!DOCTYPE html>
        <html lang="ko">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>GoGo Project API 스펙 문서</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
                    color: white;
                    margin: 0;
                    padding: 2rem;
                    min-height: 100vh;
                    line-height: 1.6;
                }
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                }
                h1 {
                    font-size: 3rem;
                    margin-bottom: 1rem;
                    text-align: center;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                }
                .api-info {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 15px;
                    backdrop-filter: blur(10px);
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                    padding: 2rem;
                    margin-bottom: 2rem;
                }
                .endpoints-section {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 15px;
                    backdrop-filter: blur(10px);
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                    padding: 2rem;
                    margin-bottom: 2rem;
                }
                .endpoint {
                    background: rgba(255, 255, 255, 0.05);
                    border-radius: 10px;
                    padding: 1.5rem;
                    margin-bottom: 1rem;
                    border-left: 4px solid #3498db;
                }
                .method {
                    display: inline-block;
                    padding: 0.3rem 0.8rem;
                    border-radius: 5px;
                    font-weight: bold;
                    margin-right: 1rem;
                    font-size: 0.9rem;
                }
                .method.get { background: #27ae60; }
                .method.post { background: #e74c3c; }
                .method.put { background: #f39c12; }
                .method.delete { background: #e67e22; }
                .functions-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 1.5rem;
                    margin-top: 1.5rem;
                }
                .function-card {
                    background: rgba(255, 255, 255, 0.05);
                    border-radius: 10px;
                    padding: 1.5rem;
                    border-left: 4px solid #9b59b6;
                }
                .function-name {
                    font-size: 1.2rem;
                    font-weight: bold;
                    color: #ecf0f1;
                    margin-bottom: 0.5rem;
                }
                .function-desc {
                    color: #bdc3c7;
                    margin-bottom: 1rem;
                }
                .function-versions {
                    font-size: 0.9rem;
                    color: #95a5a6;
                }
                .code {
                    background: rgba(0, 0, 0, 0.3);
                    padding: 1rem;
                    border-radius: 5px;
                    font-family: 'Courier New', monospace;
                    margin: 1rem 0;
                    overflow-x: auto;
                }
                .badge {
                    display: inline-block;
                    background: rgba(255, 255, 255, 0.2);
                    padding: 0.2rem 0.5rem;
                    border-radius: 3px;
                    font-size: 0.8rem;
                    margin-right: 0.5rem;
                }
                .code-inline {
                    background: rgba(0, 0, 0, 0.3);
                    padding: 0.2rem 0.5rem;
                    border-radius: 3px;
                    font-family: 'Courier New', monospace;
                    font-size: 0.9rem;
                }
                .code a {
                    display: block;
                    margin: 0.5rem 0;
                    text-decoration: none;
                    padding: 0.3rem;
                    border-radius: 3px;
                    transition: background 0.3s ease;
                }
                .code a:hover {
                    background: rgba(255, 255, 255, 0.1);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🚀 GoGo Project API</h1>
                
                <?php if (!empty($functions)): ?>
                <div class="endpoints-section">
                    <h2>⚙️ 사용 가능한 함수</h2>
                    <div class="functions-grid">
                        <?php foreach ($functions as $function): ?>
                            <div class="function-card">
                                <div class="function-name"><?= htmlspecialchars($function['name']) ?></div>
                                <div class="function-desc"><?= htmlspecialchars($function['description'] ?? '사용자 인증 함수') ?></div>
                                <div>
                                    <span class="badge">버전: <?= implode(', ', $function['versions'] ?? ['release']) ?></span>
                                </div>
                                <div class="code" style="margin-top: 1rem;">
                                    <a href="./api/<?= htmlspecialchars($function['name']) ?>/info" onclick="window.open(this.href, '_blank'); return false;" style="color: #3498db; cursor: pointer;">📋 함수 정보 조회</a>
                                    <a href="./api/<?= htmlspecialchars($function['name']) ?>/test" onclick="window.open(this.href, '_blank'); return false;" style="color: #27ae60; cursor: pointer;">🧪 함수 테스트</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="api-info">
                    <h2>API 기본 정보</h2>
                    <p><strong>프로젝트:</strong> gogo</p>
                    <p><strong>사용 가능한 함수:</strong> <?= count($functions) ?>개</p>
                    <p><strong>베이스 URL:</strong> <span class="code-inline"><?= (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/projects/gogo/Backend' ?></span></p>
                </div>

                <div class="endpoints-section">
                    <h2>📋 주요 엔드포인트</h2>
                    
                    <div class="endpoint">
                        <span class="method get">GET</span>
                        <strong><a href="./api/health" onclick="window.open(this.href, '_blank'); return false;" style="color: #3498db; text-decoration: none; cursor: pointer;">/api/health</a></strong>
                        <p>시스템 상태 조회</p>
                    </div>

                    <div class="endpoint">
                        <span class="method get">GET</span>
                        <strong><a href="./api/functions" onclick="window.open(this.href, '_blank'); return false;" style="color: #3498db; text-decoration: none; cursor: pointer;">/api/functions</a></strong>
                        <p>전체 함수 목록</p>
                    </div>

                    <div class="endpoint">
                        <span class="method post">POST</span>
                        <strong>/api/{function}</strong>
                        <p>함수 실행 (JSON 데이터 전송)</p>
                    </div>
                </div>


            </div>
        </body>
        </html>
        <?php
        exit;
    }

    // API 라우트 처리 (api로 시작하거나 직접 함수 호출)
    if ($pathSegments[0] === 'api' || CommonFunctions::functionExists($pathSegments[0])) {
        
        // api 접두사가 있는 경우 제거
        if ($pathSegments[0] === 'api') {
            $pathSegments = array_slice($pathSegments, 1);
        }
        
        // 빈 경로면 함수 목록 반환
        if (empty($pathSegments) || empty($pathSegments[0])) {
            $functions = CommonFunctions::getAvailableFunctions();
            echo json_encode([
                'success' => true,
                'functions' => $functions
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 헬스체크
        if (isset($pathSegments[0]) && $pathSegments[0] === 'health') {
            $functions = CommonFunctions::getAvailableFunctions();
            $stats = CommonFunctions::getCallStatistics();
            
            echo json_encode([
                'status' => 'healthy',
                'timestamp' => date('Y-m-d H:i:s'),
                'functions_count' => count($functions),
                'total_calls' => $stats['total_calls'] ?? 0,
                'available_functions' => array_column($functions, 'name')
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 함수 목록
        if (isset($pathSegments[0]) && $pathSegments[0] === 'functions') {
            $functions = CommonFunctions::getAvailableFunctions();
            echo json_encode([
                'success' => true,
                'functions' => $functions
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 함수 호출
        if (isset($pathSegments[0]) && !empty($pathSegments[0])) {
            $functionName = $pathSegments[0];
            
            // 함수 정보 조회
            if (isset($pathSegments[1]) && $pathSegments[1] === 'info') {
                if ($requestMethod === 'GET') {
                    $info = CommonFunctions::getFunctionInfo($functionName, 'release');
                    $versions = CommonFunctions::getFunctionVersions($functionName);
                    
                    echo json_encode([
                        'success' => true,
                        'function_name' => $functionName,
                        'info' => $info,
                        'versions' => $versions
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            
            // 함수 테스트 실행
            if (isset($pathSegments[1]) && $pathSegments[1] === 'test') {
                if ($requestMethod === 'GET') {
                    // 테스트 케이스가 있는지 확인
                    $testFile = __DIR__ . '/Functions/' . $functionName . '/release/Tests/RunTests.php';
                    if (file_exists($testFile)) {
                        ob_start();
                        include $testFile;
                        $testOutput = ob_get_clean();
                        
                        echo json_encode([
                            'success' => true,
                            'function_name' => $functionName,
                            'test_output' => $testOutput
                        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => '테스트 파일을 찾을 수 없습니다.',
                            'test_file' => $testFile
                        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                    exit;
                }
            }
            
            // 함수 실행
            if ($requestMethod === 'POST') {
                $version = 'release';
                
                // 버전이 지정된 경우
                if (isset($pathSegments[1]) && !empty($pathSegments[1]) && 
                    $pathSegments[1] !== 'info' && $pathSegments[1] !== 'test') {
                    $version = $pathSegments[1];
                }
                
                $result = CommonFunctions::Function($functionName, $version, $input);
                echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
    
    // 404 처리
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint not found',
        'path' => $path,
        'method' => $requestMethod
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'path' => $path ?? '',
        'method' => $requestMethod
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}