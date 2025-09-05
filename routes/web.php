<?php

use Illuminate\Support\Facades\Route;

// 웹 라우트 일괄 등록
$routes = config('routes-web');

foreach ($routes as $path => $config) {
    // 매개변수가 있는 라우트는 건너뛰기 (아래에서 별도 처리)
    if (str_contains($path, '{')) {
        continue;
    }

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

// 매개변수가 있는 특수 라우트들을 수동으로 등록
Route::get('/organizations/{id}/dashboard', function ($id) {
    return view('302-service-organization-dashboard.index');
})->name('organization.dashboard');
