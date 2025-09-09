<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 인증이 필요한 페이지들을 위한 라우트 그룹
Route::group(['middleware' => 'loginRequired.auth'], function () {
    // 대시보드 페이지는 인증 필요
    Route::get('/dashboard', function () {
        // 조직 관련 페이지들에 조직 데이터 전달
        $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
            ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
            ->where('organization_members.user_id', Auth::id())
            ->where('organization_members.invitation_status', 'accepted')
            ->orderBy('organizations.created_at', 'desc')
            ->get();

        // 1. 내가 소유한 프로젝트들
        $ownedProjects = \App\Models\Project::select(['projects.id', 'projects.name', 'projects.description', 'projects.created_at', 'organizations.name as organization_name', 'organizations.id as organization_id', 'projects.user_id'])
            ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
            ->where('projects.user_id', Auth::id());

        // 2. 조직 멤버십을 통해 접근 가능한 프로젝트들 (내가 소유한 프로젝트 제외)
        $memberProjects = \App\Models\Project::select(['projects.id', 'projects.name', 'projects.description', 'projects.created_at', 'organizations.name as organization_name', 'organizations.id as organization_id', 'projects.user_id'])
            ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
            ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
            ->where('organization_members.user_id', Auth::id())
            ->where('organization_members.invitation_status', 'accepted')
            ->where('projects.user_id', '!=', Auth::id());

        // 3. 두 결과를 합치기
        $projects = $ownedProjects->union($memberProjects)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // 4. 최근 페이지들 조회 (내가 접근 가능한 프로젝트의 페이지들)
        $pages = \App\Models\ProjectPage::select(['project_pages.id', 'project_pages.title', 'project_pages.content', 'project_pages.updated_at', 'projects.name as project_name', 'projects.id as project_id', 'organizations.name as organization_name', 'organizations.id as organization_id'])
            ->join('projects', 'project_pages.project_id', '=', 'projects.id')
            ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
            ->where(function($query) {
                // 내가 소유한 프로젝트의 페이지들
                $query->where('projects.user_id', Auth::id())
                      // 또는 내가 멤버인 조직의 프로젝트 페이지들
                      ->orWhereExists(function($subQuery) {
                          $subQuery->select(\DB::raw(1))
                                   ->from('organization_members')
                                   ->whereColumn('organization_members.organization_id', 'organizations.id')
                                   ->where('organization_members.user_id', Auth::id())
                                   ->where('organization_members.invitation_status', 'accepted');
                      });
            })
            ->orderBy('project_pages.updated_at', 'desc')
            ->limit(4)
            ->get();

        $viewName = config('routes-web./dashboard.view', '300-page-service.301-page-dashboard.000-index');
        return view($viewName, compact('organizations', 'projects', 'pages'));
    })->name('dashboard');

    // 조직 대시보드
    Route::get('/organizations/{id}/dashboard', function ($id) {
        $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
            ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
            ->where('organization_members.user_id', Auth::id())
            ->where('organization_members.invitation_status', 'accepted')
            ->orderBy('organizations.created_at', 'desc')
            ->get();

        return view('300-page-service.302-page-organization-dashboard.000-index', compact('organizations'));
    })->name('organization.dashboard');

    // 프로젝트 관련 라우트들
    Route::get('/organizations/{id}/projects/{projectId}', function ($id, $projectId) {
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

        return view('300-page-service.308-page-project-dashboard.000-index', ['currentPageId' => null]);
    })->name('project.dashboard');

    Route::get('/organizations/{id}/projects/{projectId}/dashboard', function ($id, $projectId) {
        return redirect()->route('project.dashboard', ['id' => $id, 'projectId' => $projectId]);
    })->name('project.dashboard.full');

    // 프로젝트 페이지 라우트들
    Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}', function ($id, $projectId, $pageId) {
        return view('300-page-service.308-page-project-dashboard.000-index', ['currentPageId' => $pageId, 'activeTab' => 'overview']);
    })->name('project.dashboard.page');

    // 프로젝트 설정 라우트들
    Route::get('/organizations/{id}/projects/{projectId}/settings/name', function ($id, $projectId) {
        return view('300-page-service.314-page-project-settings-name.000-index', [
            'currentProjectId' => $projectId,
            'activeTab' => 'name',
            'organizationId' => $id,
            'projectId' => $projectId
        ]);
    })->name('project.dashboard.project.settings.name');

    Route::post('/organizations/{id}/projects/{projectId}/settings/name', function ($id, $projectId) {
        return view('300-page-service.314-page-project-settings-name.000-index', [
            'currentProjectId' => $projectId,
            'activeTab' => 'name',
            'organizationId' => $id,
            'projectId' => $projectId
        ]);
    })->name('project.dashboard.project.settings.name.post');
});

// 웹 라우트 일괄 등록 (대시보드 제외)
$routes = config('routes-web');

foreach ($routes as $path => $config) {
    
    // 대시보드는 이미 위에서 처리했으므로 스킵
    if ($path === '/dashboard') {
        continue;
    }

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
                    ->where('organization_members.user_id', Auth::id())
                    ->where('organization_members.invitation_status', 'accepted')
                    ->orderBy('organizations.created_at', 'desc')
                    ->get();

                // 대시보드 페이지에는 프로젝트 목록도 함께 전달
                if ($path === '/dashboard') {
                    // 1. 내가 소유한 프로젝트들
                    $ownedProjects = \App\Models\Project::select(['projects.id', 'projects.name', 'projects.description', 'projects.created_at', 'organizations.name as organization_name', 'organizations.id as organization_id', 'projects.user_id'])
                        ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
                        ->where('projects.user_id', Auth::id());

                    // 2. 조직 멤버십을 통해 접근 가능한 프로젝트들 (내가 소유한 프로젝트 제외)
                    $memberProjects = \App\Models\Project::select(['projects.id', 'projects.name', 'projects.description', 'projects.created_at', 'organizations.name as organization_name', 'organizations.id as organization_id', 'projects.user_id'])
                        ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
                        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
                        ->where('organization_members.user_id', Auth::id())
                        ->where('organization_members.invitation_status', 'accepted')
                        ->where('projects.user_id', '!=', Auth::id());

                    // 3. 두 결과를 합치기
                    $projects = $ownedProjects->union($memberProjects)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();

                    // 4. 최근 페이지들 조회 (내가 접근 가능한 프로젝트의 페이지들)
                    $pages = \App\Models\ProjectPage::select(['project_pages.id', 'project_pages.title', 'project_pages.content', 'project_pages.updated_at', 'projects.name as project_name', 'projects.id as project_id', 'organizations.name as organization_name', 'organizations.id as organization_id'])
                        ->join('projects', 'project_pages.project_id', '=', 'projects.id')
                        ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
                        ->where(function($query) {
                            // 내가 소유한 프로젝트의 페이지들
                            $query->where('projects.user_id', Auth::id())
                                  // 또는 내가 멤버인 조직의 프로젝트 페이지들
                                  ->orWhereExists(function($subQuery) {
                                      $subQuery->select(\DB::raw(1))
                                               ->from('organization_members')
                                               ->whereColumn('organization_members.organization_id', 'organizations.id')
                                               ->where('organization_members.user_id', Auth::id())
                                               ->where('organization_members.invitation_status', 'accepted');
                                  });
                        })
                        ->orderBy('project_pages.updated_at', 'desc')
                        ->limit(4)
                        ->get();

                    return view($viewName, compact('organizations', 'projects', 'pages'));
                }

                return view($viewName, compact('organizations'));
            }

            return view($viewName);
        });
    }

    // 라우트명이 있으면 추가
    if ($routeName) {
        $route->name($routeName);
    }

    // // 인증 미들웨어 적용
    // $protectedPages = ['/dashboard', '/mypage', '/mypage/edit', '/mypage/delete', '/organizations'];
    // $protectedPatterns = ['/organizations/{id}/dashboard', '/organizations/{id}/projects', '/organizations/{id}/projects/{projectId}', '/organizations/{id}/projects/{projectId}/dashboard'];

    // if (in_array($path, $protectedPages) || in_array($path, $protectedPatterns)) {
    //     $route->middleware(\App\Http\Middleware\SimpleAuth::class);
    // }
}

// 위의 loginRequired.auth 그룹으로 이동된 라우트들

// 중복된 라우트들 제거됨 - loginRequired.auth 그룹에서 처리

// 페이지 설정 관련 라우트들 (더 구체적인 라우트를 먼저 등록)
// 페이지 설정 탭별 라우트들
Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/name', function ($id, $projectId, $pageId) {
    return view('300-page-service.309-page-settings-name.000-index', ['currentPageId' => $pageId, 'activeTab' => 'name']);
})->name('project.dashboard.page.settings.name');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/name', function ($id, $projectId, $pageId) {
    return view('300-page-service.309-page-settings-name.000-index', ['currentPageId' => $pageId, 'activeTab' => 'name']);
})->name('project.dashboard.page.settings.name.post');

Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/sandbox', function ($id, $projectId, $pageId) {
    return view('300-page-service.310-page-settings-sandbox.000-index', ['currentPageId' => $pageId, 'activeTab' => 'sandbox']);
})->name('project.dashboard.page.settings.sandbox');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/sandbox', function ($id, $projectId, $pageId) {
    return view('300-page-service.310-page-settings-sandbox.000-index', ['currentPageId' => $pageId, 'activeTab' => 'sandbox']);
})->name('project.dashboard.page.settings.sandbox.post');

Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/custom-screen', function ($id, $projectId, $pageId) {
    return view('300-page-service.311-page-settings-custom-screen.000-index', ['currentPageId' => $pageId, 'activeTab' => 'custom-screen']);
})->name('project.dashboard.page.settings.custom-screen');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/custom-screen', function ($id, $projectId, $pageId) {
    return view('300-page-service.311-page-settings-custom-screen.000-index', ['currentPageId' => $pageId, 'activeTab' => 'custom-screen']);
})->name('project.dashboard.page.settings.custom-screen.post');

Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/deployment', function ($id, $projectId, $pageId) {
    return view('300-page-service.313-page-settings-deployment.000-index', ['currentPageId' => $pageId, 'activeTab' => 'deployment']);
})->name('project.dashboard.page.settings.deployment');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/deployment', function ($id, $projectId, $pageId) {
    return view('300-page-service.313-page-settings-deployment.000-index', ['currentPageId' => $pageId, 'activeTab' => 'deployment']);
})->name('project.dashboard.page.settings.deployment.post');

Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/permissions', function ($id, $projectId, $pageId) {
    return view('300-page-service.320-page-settings-permissions.000-index', ['currentPageId' => $pageId, 'activeTab' => 'permissions']);
})->name('project.dashboard.page.settings.permissions');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/permissions', function ($id, $projectId, $pageId) {
    return view('300-page-service.320-page-settings-permissions.000-index', ['currentPageId' => $pageId, 'activeTab' => 'permissions']);
})->name('project.dashboard.page.settings.permissions.post');

Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/history', function ($id, $projectId, $pageId) {
    return view('300-page-service.321-page-settings-history.000-index', ['currentPageId' => $pageId, 'activeTab' => 'history']);
})->name('project.dashboard.page.settings.history');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/history', function ($id, $projectId, $pageId) {
    return view('300-page-service.321-page-settings-history.000-index', ['currentPageId' => $pageId, 'activeTab' => 'history']);
})->name('project.dashboard.page.settings.history.post');

Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/delete', function ($id, $projectId, $pageId) {
    return view('300-page-service.312-page-settings-delete.000-index', ['currentPageId' => $pageId, 'activeTab' => 'delete']);
})->name('project.dashboard.page.settings.delete');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/delete', function ($id, $projectId, $pageId) {
    return view('300-page-service.312-page-settings-delete.000-index', ['currentPageId' => $pageId, 'activeTab' => 'delete']);
})->name('project.dashboard.page.settings.delete.post');

// 페이지 설정 기본 라우트 - 기본적으로 페이지 이름 변경 탭으로 리다이렉트
Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings', function ($id, $projectId, $pageId) {
    return redirect()->route('project.dashboard.page.settings.name', ['id' => $id, 'projectId' => $projectId, 'pageId' => $pageId]);
})->name('project.dashboard.page.settings');

// 프로젝트 설정 라우트들은 loginRequired.auth 그룹으로 이동됨

Route::get('/organizations/{id}/projects/{projectId}/settings/users', function ($id, $projectId) {
    // 프로젝트 정보 조회
    $project = \App\Models\Project::with('user')->findOrFail($projectId);

    // 조직의 모든 멤버 조회 (역할 정보 포함)
    $organizationMembers = \App\Models\OrganizationMember::with(['user.roles'])
        ->where('organization_id', $id)
        ->where('invitation_status', 'accepted')
        ->get();

    return view('300-page-service.315-page-project-settings-users.000-index', [
        'currentProjectId' => $projectId,
        'activeTab' => 'users',
        'project' => $project,
        'organizationMembers' => $organizationMembers
    ]);
})->name('project.dashboard.project.settings.users');

Route::post('/organizations/{id}/projects/{projectId}/settings/users', function ($id, $projectId) {
    // 프로젝트 정보 조회
    $project = \App\Models\Project::with('user')->findOrFail($projectId);

    // 조직의 모든 멤버 조회 (역할 정보 포함)
    $organizationMembers = \App\Models\OrganizationMember::with(['user.roles'])
        ->where('organization_id', $id)
        ->where('invitation_status', 'accepted')
        ->get();

    return view('300-page-service.315-page-project-settings-users.000-index', [
        'currentProjectId' => $projectId,
        'activeTab' => 'users',
        'project' => $project,
        'organizationMembers' => $organizationMembers
    ]);
})->name('project.dashboard.project.settings.users.post');

Route::get('/organizations/{id}/projects/{projectId}/settings/permissions', function ($id, $projectId) {
    return view('300-page-service.316-page-project-settings-permissions.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'permissions']);
})->name('project.dashboard.project.settings.permissions');

Route::post('/organizations/{id}/projects/{projectId}/settings/permissions', function ($id, $projectId) {
    return view('300-page-service.316-page-project-settings-permissions.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'permissions']);
})->name('project.dashboard.project.settings.permissions.post');

Route::get('/organizations/{id}/projects/{projectId}/settings/delete', function ($id, $projectId) {
    return view('300-page-service.317-page-project-settings-delete.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'delete']);
})->name('project.dashboard.project.settings.delete');

Route::post('/organizations/{id}/projects/{projectId}/settings/delete', function ($id, $projectId) {
    return view('300-page-service.317-page-project-settings-delete.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'delete']);
})->name('project.dashboard.project.settings.delete.post');

Route::get('/organizations/{id}/projects/{projectId}/settings/sandboxes', [\App\Http\ProjectSandbox\Manage\Controller::class, 'index'])->name('project.dashboard.project.settings.sandboxes');

Route::post('/organizations/{id}/projects/{projectId}/settings/sandboxes', function ($id, $projectId, Illuminate\Http\Request $request) {
    $controller = new \App\Http\ProjectSandbox\Manage\Controller();
    
    return match($request->input('action')) {
        'create' => $controller->create($request, $id, $projectId),
        'delete' => $controller->delete($request, $id, $projectId),
        'toggle_status' => $controller->toggleStatus($request, $id, $projectId),
        default => redirect()->back()->with('error', '유효하지 않은 작업입니다.')
    };
})->name('project.dashboard.project.settings.sandboxes.post');

Route::get('/organizations/{id}/projects/{projectId}/settings/page-delete', function ($id, $projectId) {
    return view('300-page-service.318-page-project-settings-page-delete.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'page-delete']);
})->name('project.dashboard.project.settings.page-delete');

Route::post('/organizations/{id}/projects/{projectId}/settings/page-delete', function ($id, $projectId) {
    return view('300-page-service.318-page-project-settings-page-delete.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'page-delete']);
})->name('project.dashboard.project.settings.page-delete.post');

Route::get('/organizations/{id}/projects/{projectId}/settings/logs', function ($id, $projectId) {
    return view('300-page-service.319-page-project-settings-logs.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'logs']);
})->name('project.dashboard.project.settings.logs');

Route::post('/organizations/{id}/projects/{projectId}/settings/logs', function ($id, $projectId) {
    return view('300-page-service.319-page-project-settings-logs.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'logs']);
})->name('project.dashboard.project.settings.logs.post');

// 기본 프로젝트 설정 경로 (settings로 접근시 name으로 리다이렉트)
Route::get('/organizations/{id}/projects/{projectId}/settings', function ($id, $projectId) {
    return redirect()->route('project.dashboard.project.settings.name', ['id' => $id, 'projectId' => $projectId]);
});

// 페이지 생성 API 라우트
Route::post('/organizations/{id}/projects/{projectId}/pages/create', function ($id, $projectId) {
    try {
        // 조직과 프로젝트 존재 확인
        $organization = \App\Models\Organization::find($id);
        $project = \App\Models\Project::find($projectId);
        
        if (!$organization || !$project || $project->organization_id != $id) {
            return response()->json(['error' => '프로젝트를 찾을 수 없습니다.'], 404);
        }

        // 현재 페이지 개수 확인
        $pageCount = \App\Models\ProjectPage::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->count();

        // 새 페이지 생성
        $page = \App\Models\ProjectPage::create([
            'title' => '새 페이지 ' . ($pageCount + 1),
            'slug' => 'page-' . uniqid(),
            'content' => '',
            'status' => 'draft',
            'project_id' => $projectId,
            'parent_id' => null,
            'user_id' => auth()->id() ?? 1,
            'sort_order' => $pageCount
        ]);

        // 생성된 페이지로 리다이렉트
        return response()->json([
            'success' => true,
            'redirect_url' => "/organizations/{$id}/projects/{$projectId}/pages/{$page->id}"
        ]);

    } catch (\Exception $e) {
        \Log::error('Page creation error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'orgId' => $id,
            'projectId' => $projectId
        ]);
        return response()->json(['error' => '페이지 생성 중 오류가 발생했습니다: ' . $e->getMessage()], 500);
    }
})->name('project.pages.create');

// 동적 프로젝트 페이지의 탭들 (2뎁스) - 가장 일반적인 라우트는 마지막에
Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/{tab}', function ($id, $projectId, $pageId, $tab) {
    return view('300-page-service.308-page-project-dashboard.000-index', ['currentPageId' => $pageId, 'activeTab' => $tab]);
})->name('project.dashboard.page.tab');

// 조직 관리자 페이지 라우트들 (개발용 - 인증 제거) - 권한 300 이상인 조직만 표시
Route::get('/organizations/{id}/admin', function ($id) {
    // 조직 선택 드롭다운을 위한 모든 조직 목록
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', Auth::id())
        ->where('organization_members.invitation_status', 'accepted')
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.800-common.000-index', compact('organizations'));
})->name('organization.admin');

Route::get('/organizations/{id}/admin/members', function ($id) {
    // 조직 선택 드롭다운을 위한 모든 조직 목록
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', Auth::id())
        ->where('organization_members.invitation_status', 'accepted')
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
    // 조직 선택 드롭다운을 위한 모든 조직 목록
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', Auth::id())
        ->where('organization_members.invitation_status', 'accepted')
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.805-page-permissions-overview.000-index', compact('organizations'));
})->name('organization.admin.permissions.overview');

// 역할 관리 탭
Route::get('/organizations/{id}/admin/permissions/roles', function ($id) {
    // 조직 선택 드롭다운을 위한 모든 조직 목록
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', Auth::id())
        ->where('organization_members.invitation_status', 'accepted')
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.806-page-permissions-roles.000-index', compact('organizations'));
})->name('organization.admin.permissions.roles');

// 권한 관리 탭
Route::get('/organizations/{id}/admin/permissions/management', function ($id) {
    // 조직 선택 드롭다운을 위한 모든 조직 목록
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', Auth::id())
        ->where('organization_members.invitation_status', 'accepted')
        ->orderBy('organizations.created_at', 'desc')
        ->get();

    return view('800-page-organization-admin.807-page-permissions-management.000-index', compact('organizations'))->with('activeTab', 'management');
})->name('organization.admin.permissions.management');

// 동적 규칙 탭
Route::get('/organizations/{id}/admin/permissions/rules', function ($id) {
    // 조직 선택 드롭다운을 위한 모든 조직 목록
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', Auth::id())
        ->where('organization_members.invitation_status', 'accepted')
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

    // 조직 선택 드롭다운을 위한 모든 조직 목록
    $organizations = \App\Models\Organization::select(['organizations.id', 'organizations.name'])
        ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
        ->where('organization_members.user_id', Auth::id())
        ->where('organization_members.invitation_status', 'accepted')
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

// 플랫폼 관리자 - 조직 목록
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

// 샌드박스 페이지들 - 실제 존재하는 파일들만 라우트 등록
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

// Function Browser
Route::get('/sandbox/function-browser', function () {
    return view('700-page-sandbox.708-page-function-browser.000-index');
})->name('sandbox.function-browser');

// Scenario Manager
Route::get('/sandbox/scenario-manager', function () {
    return view('700-page-sandbox.711-page-scenario-manager.000-index');
})->name('sandbox.scenario-manager');

// Documentation Manager
Route::get('/sandbox/documentation-manager', function () {
    return view('700-page-sandbox.710-page-documentation-manager.000-index');
})->name('sandbox.documentation-manager');

// Custom Screens
Route::get('/sandbox/custom-screens', function () {
    return view('700-page-sandbox.706-page-custom-screens.000-index');
})->name('sandbox.custom-screens');

// Custom Screen Creator
Route::get('/sandbox/custom-screen-creator', function () {
    return view('700-page-sandbox.707-page-custom-screen-creator.000-index');
})->name('sandbox.custom-screen-creator');

// Organizations List (Generated Custom Screen)
Route::get('/sandbox/organizations-list', function () {
    return view('700-page-sandbox.713-page-organizations-list.000-index');
})->name('sandbox.organizations-list');
// 프로젝트 목록 (Generated Custom Screen)
Route::get('/sandbox/projects-list', function () {
    return view('700-page-sandbox.715-page-projects-list.000-index');
})->name('sandbox.projects-list');

// Custom Screen Preview
Route::get('/sandbox/custom-screen/preview/{id}', [\App\Http\Controllers\Sandbox\CustomScreenPreviewController::class, 'show'])->name('sandbox.custom-screen-preview');

// 샌드박스 사용 프로젝트 목록
Route::get('/sandbox/using-projects', [\App\Http\Controllers\Sandbox\UsingProjectsController::class, 'index'])->name('sandbox.using-projects');

// Global Functions 파일 다운로드
Route::get('/sandbox/download/{filename}', function ($filename) {
    $filePath = storage_path('app/sandbox-exports/' . $filename);

    // 파일 존재 여부 확인
    if (!file_exists($filePath)) {
        abort(404, '파일을 찾을 수 없습니다.');
    }

    // 파일명 검증 (보안)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}_[a-zA-Z0-9._-]+\.(xlsx|csv|pdf|txt)$/', $filename)) {
        abort(403, '잘못된 파일 형식입니다.');
    }

    // 원본 파일명 추출 (타임스탬프 제거)
    $originalFilename = preg_replace('/^\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}_/', '', $filename);

    return response()->download($filePath, $originalFilename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0'
    ]);
})->name('sandbox.download');

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


// 로그인 처리 라우트 추가 (모달용)
Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        return response()->json(['success' => true]);
    }

    return response()->json([
        'success' => false,
        'message' => '이메일 또는 비밀번호가 일치하지 않습니다.'
    ], 401);
})->name('login.post');

// 로그아웃 라우트 추가
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// 회원 탈퇴 라우트
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/delete', [\App\Http\UserAccount\Delete\Controller::class, 'show'])->name('mypage.delete');
    Route::post('/mypage/delete', [\App\Http\UserAccount\Delete\Controller::class, 'destroy'])->name('mypage.delete.process');
    Route::get('/api/user/organization-status', [\App\Http\UserAccount\Delete\Controller::class, 'checkOrganizationStatus'])->name('user.organization-status');
});
