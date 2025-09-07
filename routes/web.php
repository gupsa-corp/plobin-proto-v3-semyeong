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

    // 개발용 - 인증 미들웨어 제거
    // $protectedPages = ['/dashboard', '/mypage', '/mypage/edit', '/mypage/delete', '/organizations'];
    // $protectedPatterns = ['/organizations/{id}/dashboard', '/organizations/{id}/projects', '/organizations/{id}/projects/{projectId}', '/organizations/{id}/projects/{projectId}/dashboard'];

    // if (in_array($path, $protectedPages) || in_array($path, $protectedPatterns)) {
    //     $route->middleware('auth');
    // }
}

// 매개변수가 있는 특수 라우트들을 수동으로 등록 (개발용 - 인증 제거)
Route::get('/organizations/{id}/dashboard', function ($id) {
    return view('300-page-service.302-page-organization-dashboard.000-index');
})->name('organization.dashboard');

// 프로젝트 대시보드 라우트들 - 첫 번째 페이지로 리다이렉트
Route::get('/organizations/{id}/projects/{projectId}', function ($id, $projectId) {
    // 첫 번째 프로젝트 페이지로 리다이렉트
    $firstPage = \App\Models\ProjectPage::where('project_id', $projectId)
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->first();

    if ($firstPage) {
        return redirect()->route('project.dashboard.page', [
            'id' => $id,
            'projectId' => $projectId,
            'pageId' => $firstPage->id
        ]);
    }

    // 페이지가 없으면 기본 뷰 표시 (빈 상태)
    return view('300-page-service.308-page-project-dashboard.000-index', ['currentPageId' => null]);
})->name('project.dashboard');

Route::get('/organizations/{id}/projects/{projectId}/dashboard', function ($id, $projectId) {
    return redirect()->route('project.dashboard', ['id' => $id, 'projectId' => $projectId]);
})->name('project.dashboard.full');

// 동적 프로젝트 페이지 라우트들 (1뎁스)
Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}', function ($id, $projectId, $pageId) {
    return view('300-page-service.308-page-project-dashboard.000-index', ['currentPageId' => $pageId, 'activeTab' => 'overview']);
})->name('project.dashboard.page');

// 동적 프로젝트 페이지의 탭들 (2뎁스)
Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/{tab}', function ($id, $projectId, $pageId, $tab) {
    return view('300-page-service.308-page-project-dashboard.000-index', ['currentPageId' => $pageId, 'activeTab' => $tab]);
})->name('project.dashboard.page.tab');

// 조직 관리자 페이지 라우트들 (개발용 - 인증 제거) - 권한 300 이상인 조직만 표시
Route::get('/organizations/{id}/admin', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.800-common.000-index', compact('organizations'));
})->name('organization.admin');

Route::get('/organizations/{id}/admin/members', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.801-page-members.000-index', compact('id', 'organizations'));
})->name('organization.admin.members');

// 권한 관리 기본 라우트 - 개요 탭으로 리다이렉트
Route::get('/organizations/{id}/admin/permissions', function ($id) {
    return redirect()->route('organization.admin.permissions.overview', ['id' => $id]);
})->name('organization.admin.permissions');

// 권한 개요 탭
Route::get('/organizations/{id}/admin/permissions/overview', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.805-page-permissions-overview.000-index', compact('organizations'))->with('activeTab', 'overview');
})->name('organization.admin.permissions.overview');

// 역할 관리 탭
Route::get('/organizations/{id}/admin/permissions/roles', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.806-page-permissions-roles.000-index', compact('organizations'))->with('activeTab', 'roles');
})->name('organization.admin.permissions.roles');

// 권한 관리 탭
Route::get('/organizations/{id}/admin/permissions/management', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.807-page-permissions-management.000-index', compact('organizations'))->with('activeTab', 'management');
})->name('organization.admin.permissions.management');

// 동적 규칙 탭
Route::get('/organizations/{id}/admin/permissions/rules', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.808-page-permissions-rules.000-index', compact('organizations'))->with('activeTab', 'rules');
})->name('organization.admin.permissions.rules');

Route::get('/organizations/{id}/admin/billing', function ($id) {
    // 사용자가 권한 300 이상인 조직만 가져오기
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', auth()->id())
        ->where('organization_members.permission_level', '>=', 300)
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.803-page-billing.300-billing', compact('id', 'organizations'));
})->name('organization.admin.billing');

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
})->name('organization.admin.projects');

// 플랫폼 관리자 라우트들 (platform_admin 권한 필요) - 개발용으로 일시적으로 인증 제거
// 추후 배포시 ->middleware(['auth', 'role:platform_admin']) 적용 예정

// 플랫폼 관리자 메인 대시보드
Route::get('/platform/admin', function () {
    return view('900-page-platform-admin.901-page-dashboard.000-index');
})->name('platform.admin.dashboard');

// 플랫폼 관리자 대시보드 (명시적 경로)
Route::get('/platform/admin/dashboard', function () {
    return view('900-page-platform-admin.901-page-dashboard.000-index');
})->name('platform.admin.dashboard.full');

// 플랫폼 관리자 - 조직 관리
Route::get('/platform/admin/organizations', function () {
    return view('900-page-platform-admin.902-page-organizations.000-index');
})->name('platform.admin.organizations');

// 플랫폼 관리자 - 사용자 관리
Route::get('/platform/admin/users', [\App\Http\Controllers\PlatformAdminController::class, 'users'])->name('platform.admin.users');

// 플랫폼 관리자 - 시스템 설정
Route::get('/platform/admin/system-settings', function () {
    return view('900-page-platform-admin.904-page-system-settings.000-index');
})->name('platform.admin.system-settings');

// 플랫폼 관리자 - 권한 관리
Route::get('/platform/admin/permissions', function () {
    return view('900-page-platform-admin.905-page-permissions.000-index');
})->name('platform.admin.permissions');

// AI 샌드박스 페이지들 - 각 기능별 독립 라우트
// 메인 인덱스
Route::get('/sandbox', function () {
    return view('700-page-sandbox.000-index');
})->name('sandbox.index');

// 파일 관리 관련 페이지들
Route::get('/sandbox/file-manager', function () {
    return view('700-page-sandbox.701-page-file-manager.000-index');
})->name('sandbox.file-manager');

Route::get('/sandbox/file-list', function () {
    return view('700-page-sandbox.703-page-file-list.000-index');
})->name('sandbox.file-list');

Route::get('/sandbox/file-editor', function () {
    return view('700-page-sandbox.704-page-file-editor.000-index');
})->name('sandbox.file-editor');

Route::get('/sandbox/file-preview', function () {
    return view('700-page-sandbox.705-page-file-preview.000-index');
})->name('sandbox.file-preview');

// 시스템 도구들
Route::get('/sandbox/sql-executor', function () {
    return view('700-page-sandbox.702-page-sql-executor.000-index');
})->name('sandbox.sql-executor');

Route::get('/sandbox/table-manager', function () {
    return view('700-page-sandbox.703-page-table-manager.000-index');
})->name('sandbox.table-manager');

Route::get('/sandbox/code-executor', function () {
    return view('700-page-sandbox.704-page-code-executor.000-index');
})->name('sandbox.code-executor');

// 기타 페이지들
Route::get('/sandbox/alert-messages', function () {
    return view('700-page-sandbox.701-page-alert-messages.000-index');
})->name('sandbox.alert-messages');

Route::get('/sandbox/directory-buttons', function () {
    return view('700-page-sandbox.702-page-directory-buttons.000-index');
})->name('sandbox.directory-buttons');

// 로그아웃 라우트 추가
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
