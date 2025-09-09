<?php
/**
 * Functions 폴더 기반 자동 API 생성
 * 최소화된 동적 라우팅 시스템
 */

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// Load system's routing file
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

// Router setup
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// CommonFunctions 로드
require_once APPPATH . '../Functions/CommonFunctions.php';
use App\Functions\CommonFunctions;

/*
 * Functions 폴더 스캔 및 동적 라우트 생성
 */
$functionsPath = APPPATH . '../Functions';

if (is_dir($functionsPath)) {
    $functionDirectories = scandir($functionsPath);
    
    foreach ($functionDirectories as $functionDir) {
        // 디렉토리만 처리 (파일 제외)
        if ($functionDir === '.' || $functionDir === '..' || 
            !is_dir($functionsPath . '/' . $functionDir) || 
            $functionDir === 'CommonFunctions.php') {
            continue;
        }
        
        $apiPath = strtolower($functionDir);
        
        // 함수 실행 (POST)
        $routes->post("api/{$apiPath}", function($functionName = $functionDir) {
            $request = service('request');
            $jsonData = $request->getJSON(true);
            
            try {
                $result = CommonFunctions::Function($functionName, 'release', $jsonData ?: []);
                return service('response')->setJSON($result);
            } catch (\Exception $e) {
                return service('response')->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        });
        
        // 버전별 실행 (POST)
        $routes->post("api/{$apiPath}/(:segment)", function($functionName = $functionDir, $version) {
            $request = service('request');
            $jsonData = $request->getJSON(true);
            
            try {
                $result = CommonFunctions::Function($functionName, $version, $jsonData ?: []);
                return service('response')->setJSON($result);
            } catch (\Exception $e) {
                return service('response')->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        });
        
        // 함수 정보 (GET)
        $routes->get("api/{$apiPath}/info", function($functionName = $functionDir) {
            try {
                $info = CommonFunctions::getFunctionInfo($functionName, 'release');
                $versions = CommonFunctions::getFunctionVersions($functionName);
                
                return service('response')->setJSON([
                    'success' => true,
                    'function_name' => $functionName,
                    'info' => $info,
                    'versions' => $versions
                ]);
            } catch (\Exception $e) {
                return service('response')->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        });
    }
}

/*
 * 시스템 API
 */

// 함수 목록
$routes->get('api/functions', function() {
    try {
        $functions = CommonFunctions::getAvailableFunctions();
        return service('response')->setJSON([
            'success' => true,
            'functions' => $functions
        ]);
    } catch (\Exception $e) {
        return service('response')->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
});

// 시스템 상태
$routes->get('api/health', function() {
    try {
        $functions = CommonFunctions::getAvailableFunctions();
        $stats = CommonFunctions::getCallStatistics();
        
        return service('response')->setJSON([
            'status' => 'healthy',
            'functions_count' => count($functions),
            'total_calls' => $stats['total_calls'],
            'available_functions' => array_column($functions, 'name')
        ]);
    } catch (\Exception $e) {
        return service('response')->setStatusCode(503)->setJSON([
            'status' => 'unhealthy',
            'error' => $e->getMessage()
        ]);
    }
});

// 기본 라우트
$routes->get('/', function() {
    return service('response')->setJSON([
        'message' => 'Functions API Server',
        'endpoints' => [
            'GET /api/health' => 'System status',
            'GET /api/functions' => 'Available functions',
            'POST /api/{function}' => 'Execute function',
            'GET /api/{function}/info' => 'Function info',
            'GET /api/{function}/test' => 'Run tests'
        ]
    ]);
});