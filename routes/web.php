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
        $redirectTo = null;
    } else {
        $viewName = $config['view'] ?? null;
        $routeName = $config['name'] ?? null;
        $redirectTo = $config['redirect'] ?? null;
    }

    // 리다이렉트 처리
    if ($redirectTo) {
        $route = Route::get($path, function () use ($redirectTo) {
            return redirect($redirectTo);
        });
    } else {
        $route = Route::get($path, function () use ($viewName, $path) {
            // 조직 관련 페이지들에 조직 데이터 전달
            if (in_array($path, ['/dashboard', '/organizations', '/mypage', '/mypage/edit', '/mypage/delete', '/organizations/create'])) {
                $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
                    ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
                    ->where('organization_members.user_id', auth()->id())
                    ->where('organization_members.invitation_status', 'accepted')
                    ->orderBy('organizations.created_at', 'desc')
                    ->get();

                return view($viewName, compact('organizations'));
            }

            return view($viewName);
        });
    }

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

    return view('800-page-organization-admin.805-page-permissions-overview.000-index', compact('organizations'));
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

    return view('800-page-organization-admin.806-page-permissions-roles.000-index', compact('organizations'));
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

Route::get('/organizations/{organization}/admin/billing', [\App\Http\Billing\PaymentHistory\Controller::class, 'billing'])->name('organization.admin.billing');

// 플랜 계산기
Route::get('/organizations/{organization}/admin/billing/plan-calculator', function ($organization) {
    return view('800-page-organization-admin.803-page-billing.350-plan-calculator', compact('organization'));
})->name('organization.admin.billing.plan-calculator');

// 결제 성공/실패 페이지
Route::get('/organizations/{organization}/admin/billing/payment-success', function ($organization) {
    return view('800-page-organization-admin.803-page-billing.370-payment-success', compact('organization'));
})->name('organization.admin.billing.payment-success');

Route::get('/organizations/{organization}/admin/billing/payment-fail', function ($organization) {
    return view('800-page-organization-admin.803-page-billing.375-payment-fail', compact('organization'));
})->name('organization.admin.billing.payment-fail');

// 결제 내역 관련 라우트들
Route::get('/organizations/{organization}/admin/billing/payment-history', [\App\Http\Billing\PaymentHistory\Controller::class, 'index'])->name('organization.admin.billing.payment-history');
Route::get('/organizations/{organization}/admin/billing/payment-history/{billingHistory}', [\App\Http\Billing\PaymentDetail\Controller::class, 'show'])->name('organization.admin.billing.payment-detail');
Route::get('/organizations/{organization}/admin/billing/payment-history/{billingHistory}/receipt', [\App\Http\Billing\DownloadReceipt\Controller::class, 'download'])->name('organization.admin.billing.download-receipt');
Route::post('/organizations/{organization}/admin/billing/payment-history/{billingHistory}/retry', [\App\Http\Billing\RetryPayment\Controller::class, 'retry'])->name('organization.admin.billing.retry-payment');
Route::get('/organizations/{organization}/admin/billing/export', [\App\Http\Billing\ExportHistory\Controller::class, 'export'])->name('organization.admin.billing.export');

// AJAX 엔드포인트 (동일한 컨트롤러, AJAX 요청 처리)
Route::post('/organizations/{organization}/admin/billing/payment-history', [\App\Http\Billing\PaymentHistory\Controller::class, 'index'])->name('organization.admin.billing.payment-history.ajax');

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
Route::get('/platform/admin/users', [\App\Http\CoreView\PlatformAdmin\Controller::class, 'users'])->name('platform.admin.users');

// 플랫폼 관리자 - 요금제 관리
Route::get('/platform/admin/pricing', function () {
    return view('900-page-platform-admin.906-page-pricing.000-index');
})->name('platform.admin.pricing');

// 플랫폼 관리자 - 권한 관리 (기본적으로 역할 관리 탭으로 리다이렉트)
Route::get('/platform/admin/permissions', function () {
    return redirect()->route('platform.admin.permissions.roles');
})->name('platform.admin.permissions');

// 플랫폼 관리자 - 권한 관리 탭별 라우트
Route::get('/platform/admin/permissions/roles', function () {
    return view('900-page-platform-admin.905-page-permissions.901-tab-roles.000-index');
})->name('platform.admin.permissions.roles');

Route::get('/platform/admin/permissions/permissions', function () {
    return view('900-page-platform-admin.905-page-permissions.902-tab-permissions.000-index');
})->name('platform.admin.permissions.permissions');

Route::get('/platform/admin/permissions/users', [\App\Http\CoreView\PlatformAdmin\Controller::class, 'permissionsUsers'])->name('platform.admin.permissions.users');

Route::get('/platform/admin/permissions/audit', function () {
    return view('900-page-platform-admin.905-page-permissions.904-tab-audit.000-index');
})->name('platform.admin.permissions.audit');

// 플랫폼 관리자 - 사용자 권한 관리 API
Route::post('/platform/admin/permissions/users/change-role', [\App\Http\CoreView\PlatformAdmin\Controller::class, 'changeUserRole'])->name('platform.admin.permissions.users.change-role');
Route::post('/platform/admin/permissions/users/toggle-status', [\App\Http\CoreView\PlatformAdmin\Controller::class, 'toggleUserStatus'])->name('platform.admin.permissions.users.toggle-status');
Route::post('/platform/admin/permissions/users/update-tenant-permissions', [\App\Http\CoreView\PlatformAdmin\Controller::class, 'updateTenantPermissions'])->name('platform.admin.permissions.users.update-tenant-permissions');

// AI 샌드박스 페이지들 - 실제 존재하는 파일들만 라우트 등록
// 메인 인덱스
Route::get('/sandbox', function () {
    return view('700-page-sandbox.000-index');
})->name('sandbox.index');

// 대시보드
Route::get('/sandbox/dashboard', function () {
    return view('700-page-sandbox.701-page-dashboard.000-index');
})->name('sandbox.dashboard');

// SQL 실행기
Route::get('/sandbox/sql-executor', function () {
    return view('700-page-sandbox.702-page-sql-executor.000-index');
})->name('sandbox.sql-executor');


// 파일 에디터
Route::get('/sandbox/file-editor', function () {
    return view('700-page-sandbox.704-page-file-editor.000-index');
})->name('sandbox.file-editor');

// 데이터베이스 매니저
Route::get('/sandbox/database-manager', function () {
    return view('700-page-sandbox.705-page-database-manager.000-index');
})->name('sandbox.database-manager');

// Git 버전 관리
Route::get('/sandbox/git-version-control', function () {
    return view('700-page-sandbox.706-page-git-version-control.000-index');
})->name('sandbox.git-version-control');

// 파일 매니저 추가
Route::get('/sandbox/file-manager', function () {
    return view('700-page-sandbox.707-page-file-manager.000-index');
})->name('sandbox.file-manager');

// 스토리지 관리자 - config에서 정의한 라우트를 오버라이드
Route::get('/sandbox/storage-manager', [App\Http\CoreApi\Sandbox\StorageManager\Controller::class, 'index'])->name('sandbox.storage-manager');
Route::post('/sandbox/storage-manager/create', [App\Http\CoreApi\Sandbox\StorageManager\Controller::class, 'create'])->name('sandbox.storage.create');
Route::post('/sandbox/storage-manager/select', [App\Http\CoreApi\Sandbox\StorageManager\Controller::class, 'select'])->name('sandbox.storage.select');
Route::delete('/sandbox/storage-manager/delete', [App\Http\CoreApi\Sandbox\StorageManager\Controller::class, 'delete'])->name('sandbox.storage.delete');

// Form Creator
Route::get('/sandbox/form-creator', function () {
    return view('700-page-sandbox.709-page-form-creator.000-index');
})->name('sandbox.form-creator');

// Form Publisher - 샌드박스 폼 생성 및 관리 도구 (Livewire + Filament)
Route::prefix('sandbox/form-publisher')->group(function () {
    Route::get('/', function () {
        return view('700-page-sandbox.700-form-publisher.000-index');
    })->name('sandbox.form-publisher.list');

    Route::get('/editor', function () {
        return view('700-page-sandbox.700-form-publisher.100-editor');
    })->name('sandbox.form-publisher.editor');

    Route::post('/editor', function () {
        return view('700-page-sandbox.900-form-publisher-gateway', ['page' => 'editor']);
    })->name('sandbox.form-publisher.editor.post');


    Route::get('/preview/{id}', function ($id) {
        return view('700-page-sandbox.700-form-publisher.200-preview', compact('id'));
    })->name('sandbox.form-publisher.preview');

    Route::post('/preview/{id}', function ($id) {
        return view('700-page-sandbox.900-form-publisher-gateway', ['page' => 'preview', 'id' => $id]);
    })->name('sandbox.form-publisher.preview.post');

    Route::get('/list', function () {
        return view('700-page-sandbox.900-form-publisher-gateway', ['page' => 'list']);
    })->name('sandbox.form-publisher.list.full');

    Route::post('/list', function () {
        return view('700-page-sandbox.900-form-publisher-gateway', ['page' => 'list']);
    })->name('sandbox.form-publisher.list.post');
});

// 로그아웃 라우트 추가
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
