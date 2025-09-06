<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
        return view($viewName);
    });

    // 라우트명이 있으면 추가
    if ($routeName) {
        $route->name($routeName);
    }

    // 보호된 페이지들에 auth 미들웨어 적용
    $protectedPages = ['/dashboard', '/mypage', '/mypage/edit', '/mypage/delete', '/organizations'];
    if (in_array($path, $protectedPages)) {
        $route->middleware('auth');
    }
}

// 매개변수가 있는 특수 라우트들을 수동으로 등록
Route::get('/organizations/{id}/dashboard', function ($id) {
    return view('300-page-service.302-page-organization-dashboard.000-index');
})->name('organization.dashboard')->middleware('auth');

// 로그아웃 라우트 추가
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
