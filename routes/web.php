<?php

use Illuminate\Support\Facades\Route;

// 웹 라우트 일괄 등록
$routes = config('routes-web');

foreach ($routes as $path => $config) {
    // 이전 버전 호환성 지원
    if (is_string($config)) {
        $viewName = $config;
        $routeName = null;
    } else {
        $viewName = $config['view'];
        $routeName = $config['name'] ?? null;
    }

    $route = Route::get($path, function () use ($viewName) {
        return view($viewName . '.index');
    });

    // 라우트명이 있으면 추가
    if ($routeName) {
        $route->name($routeName);
    }
}
