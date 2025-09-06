<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
        return view($viewName);
    });

    // 라우트명이 있으면 추가
    if ($routeName) {
        $route->name($routeName);
    }

    // 보호된 페이지들에 auth 미들웨어 적용
    $protectedPages = ['/dashboard', '/mypage', '/mypage/edit', '/mypage/delete', '/organizations'];
    $protectedPatterns = ['/organizations/{id}/dashboard', '/organizations/{id}/projects', '/organizations/{id}/projects/{projectId}', '/organizations/{id}/projects/{projectId}/dashboard'];

    if (in_array($path, $protectedPages) || in_array($path, $protectedPatterns)) {
        $route->middleware('auth');
    }
}

// 매개변수가 있는 특수 라우트들을 수동으로 등록
Route::get('/organizations/{id}/dashboard', function ($id) {
    return view('300-page-service.302-page-organization-dashboard.000-index');
})->name('organization.dashboard')->middleware('auth');

// 조직 관리자 페이지 라우트들
Route::get('/organizations/{id}/admin', function ($id) {
    return view('300-page-service.310-organization-admin.000-index');
})->name('organization.admin')->middleware('auth');

Route::get('/organizations/{id}/admin/members', function ($id) {
    return view('300-page-service.310-organization-admin.100-members', compact('id'));
})->name('organization.admin.members')->middleware('auth');

Route::get('/organizations/{id}/admin/permissions', function ($id) {
    return view('300-page-service.310-organization-admin.200-permissions');
})->name('organization.admin.permissions')->middleware('auth');

Route::get('/organizations/{id}/admin/billing', function ($id) {
    return view('300-page-service.310-organization-admin.300-billing');
})->name('organization.admin.billing')->middleware('auth');

Route::get('/organizations/{id}/admin/projects', function ($id) {
    $projects = \App\Models\Project::where('organization_id', $id)
        ->with(['user', 'organization'])
        ->orderBy('created_at', 'desc')
        ->get();
        
    return view('300-page-service.310-organization-admin.400-projects', compact('projects', 'id'));
})->name('organization.admin.projects')->middleware('auth');

// 로그아웃 라우트 추가
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
