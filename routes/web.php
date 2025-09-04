<?php

use Illuminate\Support\Facades\Route;

// 임시 login 라우트 (API 전용 프로젝트이므로 단순 처리)
Route::get('/login', function () {
    return response()->json(['message' => 'API 전용 서비스입니다. /api/auth/login을 사용하세요.'], 404);
})->name('login');

$routes = config('routes-web');

foreach ($routes as $path => $view) {
    Route::get($path, function () use ($view) {
        return view($view . '.index');
    });
}
