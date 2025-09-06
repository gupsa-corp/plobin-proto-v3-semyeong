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

// 조직 관리자 페이지 라우트들 - 권한 300 이상인 조직만 표시
Route::get('/organizations/{id}/admin', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();
        
    return view('800-page-organization-admin.800-common.000-index', compact('organizations'));
})->name('organization.admin')->middleware('auth');

Route::get('/organizations/{id}/admin/members', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();
        
    return view('800-page-organization-admin.801-page-members.000-index', compact('id', 'organizations'));
})->name('organization.admin.members')->middleware('auth');

Route::get('/organizations/{id}/admin/permissions', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();
        
    return view('800-page-organization-admin.802-page-permissions.000-index', compact('organizations'));
})->name('organization.admin.permissions')->middleware('auth');

Route::get('/organizations/{id}/admin/billing', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();
        
    return view('800-page-organization-admin.803-page-billing.300-billing', compact('id', 'organizations'));
})->name('organization.admin.billing')->middleware('auth');

Route::get('/organizations/{id}/admin/projects', function ($id) {
    $projects = \App\Models\Project::where('organization_id', $id)
        ->with(['user', 'organization'])
        ->orderBy('created_at', 'desc')
        ->get();
    
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300) // ORGANIZATION_ADMIN 이상
        ->orderBy('organizations.created_at', 'desc')
        ->get();
        
    return view('800-page-organization-admin.804-page-projects.000-index', compact('projects', 'id', 'organizations'));
})->name('organization.admin.projects')->middleware('auth');

// 로그아웃 라우트 추가
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
