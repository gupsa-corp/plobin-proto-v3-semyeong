<?php
/**
 * PHP Built-in Server Router
 */

// 정적 파일 요청인 경우 기본 처리
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $info = pathinfo($path);
    
    // 정적 파일이면 기본 처리
    if (isset($info['extension']) && $info['extension'] !== 'php') {
        return false;
    }
}

// 모든 요청을 index.php로 라우팅
require_once __DIR__ . '/index.php';