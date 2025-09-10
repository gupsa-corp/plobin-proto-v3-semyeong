<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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
        // 조직과 프로젝트 정보 가져오기
        $organization = \App\Models\Organization::find($id);
        $project = \App\Models\Project::find($projectId);

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

        return view('300-page-service.308-page-project-dashboard.000-index', [
            'currentPageId' => null,
            'organization' => $organization,
            'project' => $project,
            'page' => null
        ]);
    })->name('project.dashboard');

    Route::get('/organizations/{id}/projects/{projectId}/dashboard', function ($id, $projectId) {
        return redirect()->route('project.dashboard', ['id' => $id, 'projectId' => $projectId]);
    })->name('project.dashboard.full');

    // 페이지 생성 라우트
    Route::post('/organizations/{id}/projects/{projectId}/pages/create', function ($id, $projectId) {
        try {
            // 프로젝트 존재 확인
            $project = \App\Models\Project::where('id', $projectId)
                ->whereHas('organization', function($query) use ($id) {
                    $query->where('id', $id);
                })
                ->first();

            if (!$project) {
                return response()->json(['success' => false, 'error' => '프로젝트를 찾을 수 없습니다.']);
            }

            // 권한 확인 (프로젝트 소유자이거나 조직 멤버여야 함)
            $hasAccess = $project->user_id === Auth::id() ||
                \App\Models\OrganizationMember::where('organization_id', $id)
                    ->where('user_id', Auth::id())
                    ->where('invitation_status', 'accepted')
                    ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'error' => '권한이 없습니다.']);
            }

            // 요청에서 parent_id와 title 가져오기
            $parentId = request()->input('parent_id');
            $title = request()->input('title', '새 페이지');

            // 페이지 순서 계산 (같은 parent_id를 가진 페이지들 기준)
            $sortOrder = \App\Models\ProjectPage::where('project_id', $projectId)
                ->where(function($query) use ($parentId) {
                    if ($parentId) {
                        $query->where('parent_id', $parentId);
                    } else {
                        $query->whereNull('parent_id');
                    }
                })
                ->max('sort_order') + 1;

            // 새 페이지 생성
            $page = \App\Models\ProjectPage::create([
                'project_id' => $projectId,
                'title' => $title,
                'slug' => 'new-page-' . time(), // 고유한 slug 생성
                'content' => '',
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
                'user_id' => Auth::id() // 현재 사용자 ID 추가
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => route('project.dashboard.page', [
                    'id' => $id,
                    'projectId' => $projectId,
                    'pageId' => $page->id
                ])
            ]);

        } catch (\Exception $e) {
            \Log::error('페이지 생성 오류: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => '페이지 생성 중 오류가 발생했습니다.']);
        }
    });

    // 페이지 제목 업데이트 라우트
    Route::patch('/organizations/{id}/projects/{projectId}/pages/{pageId}/title', function ($id, $projectId, $pageId) {
        try {
            // 프로젝트 존재 확인
            $project = \App\Models\Project::where('id', $projectId)
                ->whereHas('organization', function($query) use ($id) {
                    $query->where('id', $id);
                })
                ->first();

            if (!$project) {
                return response()->json(['success' => false, 'error' => '프로젝트를 찾을 수 없습니다.']);
            }

            // 권한 확인 (프로젝트 소유자이거나 조직 멤버여야 함)
            $hasAccess = $project->user_id === Auth::id() ||
                \App\Models\OrganizationMember::where('organization_id', $id)
                    ->where('user_id', Auth::id())
                    ->where('invitation_status', 'accepted')
                    ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'error' => '권한이 없습니다.']);
            }

            // 페이지 존재 확인
            $page = \App\Models\ProjectPage::where('id', $pageId)
                ->where('project_id', $projectId)
                ->first();

            if (!$page) {
                return response()->json(['success' => false, 'error' => '페이지를 찾을 수 없습니다.']);
            }

            // 제목 업데이트
            $title = request()->input('title');
            if (empty($title)) {
                return response()->json(['success' => false, 'error' => '제목을 입력해주세요.']);
            }

            $page->title = $title;
            $page->save();

            return response()->json(['success' => true, 'message' => '페이지 제목이 변경되었습니다.']);

        } catch (\Exception $e) {
            \Log::error('페이지 제목 업데이트 오류: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => '페이지 제목 변경 중 오류가 발생했습니다.']);
        }
    });

    // 프로젝트 페이지 라우트들
    Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}', function ($id, $projectId, $pageId) {
        // Fetch the organization, project, and page objects
        $organization = \App\Models\Organization::find($id);
        $project = \App\Models\Project::find($projectId);
        $page = \App\Models\ProjectPage::find($pageId);

        // 프로젝트 레벨 또는 페이지 레벨에서 샌드박스 타입 확인
        $sandboxName = null;
        $customScreenSettings = null;

        // 우선순위: 페이지 레벨 > 프로젝트 레벨
        if ($page && !empty($page->sandbox_name)) {
            $sandboxName = $page->sandbox_name;
            $customScreenSettings = $page->custom_screen_settings;
        } elseif ($project && !empty($project->sandbox_name)) {
            $sandboxName = $project->sandbox_name;
            $customScreenSettings = null; // 프로젝트 레벨에서는 커스텀 화면 설정이 없음
        }

        // 커스텀 화면이 있는지 확인 (메인 데이터베이스에서)
        $customScreen = null;
        if (!empty($sandboxName) && !empty($customScreenSettings)) {
            try {
                // 커스텀 화면 설정에서 screen_id 또는 template_path 가져오기
                $customScreenSettings = is_string($customScreenSettings)
                    ? json_decode($customScreenSettings, true)
                    : $customScreenSettings;

                $screenId = $customScreenSettings['screen_id'] ?? null;
                $templatePath = $customScreenSettings['template_path'] ?? null;
                $enabled = $customScreenSettings['enabled'] ?? true; // template_path 방식은 기본적으로 enabled


                // screen_type에서 screenId 생성 (예: "table view" -> "screen-table-view")
                if (!$screenId && isset($customScreenSettings['screen_type'])) {
                    $screenId = 'screen-' . str_replace(' ', '-', $customScreenSettings['screen_type']);
                }


                // screen_id 방식 처리 (새로운 방식)
                if ($screenId && $enabled) {
                    $storagePath = storage_path('sandbox/storage-sandbox-template/frontend');

                    if (\File::exists($storagePath)) {
                        $folders = \File::directories($storagePath);

                        foreach ($folders as $folder) {
                            $folderName = basename($folder);
                            $contentFile = $folder . '/000-content.blade.php';

                            // screen_id 매칭 로직 수정: template_005-screen-calendar-view => 005-screen-calendar-view
                            $templateId = 'template_' . $folderName;


                            if ($templateId === $screenId && \File::exists($contentFile)) {
                                // 폴더명에서 화면 정보 추출
                                $parts = explode('-', $folderName, 3);
                                $screenName = $parts[2] ?? 'unnamed';

                                // Blade 템플릿을 실제 데이터로 렌더링
                                try {
                                    // 임시 블레이드 파일 생성 및 렌더링
                                    $tempViewPath = 'project-renderer-temp-' . time() . '-' . rand(1000, 9999);
                                    $tempViewFile = resource_path('views/' . $tempViewPath . '.blade.php');

                                    $templateContent = File::get($contentFile);
                                    File::put($tempViewFile, $templateContent);

                                    try {
                                        // 실제 프로젝트 데이터 사용
                                        $renderedContent = view($tempViewPath, [
                                            'title' => $page->title ?? str_replace('-', ' ', $screenName),
                                            'description' => $page->content ?? '프로젝트 페이지',
                                            'organization' => $organization,
                                            'project' => $project,
                                            'page' => $page,
                                            'organizations' => collect([$organization]),
                                            'projects' => collect([$project]),
                                            'users' => collect([]),
                                            'activities' => collect([])
                                        ])->render();
                                    } catch (\Exception $e) {
                                        $renderedContent = '<div class="p-4 bg-red-50 border border-red-200 rounded">
                                            <h3 class="text-red-800 font-bold">템플릿 렌더링 오류</h3>
                                            <p class="text-red-700 mt-2">' . $e->getMessage() . '</p>
                                        </div>';
                                    } finally {
                                        // 임시 파일 삭제
                                        if (File::exists($tempViewFile)) {
                                            File::delete($tempViewFile);
                                        }
                                    }
                                } catch (Exception $e) {
                                    $renderedContent = '<div class="p-4 bg-red-50 border border-red-200 rounded">
                                        <h3 class="text-red-800 font-bold">파일 처리 오류</h3>
                                        <p class="text-red-700 mt-2">' . $e->getMessage() . '</p>
                                    </div>';
                                }

                                $customScreen = [
                                    'id' => $templateId,
                                    'title' => $page->title ?? str_replace('-', ' ', $screenName),
                                    'description' => $page->content ?? '프로젝트 페이지',
                                    'type' => 'template',
                                    'content' => $renderedContent
                                ];
                                break;
                            }
                        }
                    }
                }
                // template_path 방식 처리 (기존 방식)
                elseif ($templatePath) {
                    $fullPath = storage_path('sandbox/storage-sandbox-template/' . $templatePath);

                    if (\File::exists($fullPath)) {
                        // 템플릿 파일 내용 읽기
                        $fileContent = \File::get($fullPath);

                        // 화면 타입에서 제목 추출
                        $screenType = $customScreenSettings['screen_type'] ?? 'custom screen';

                        $screenData = [
                            'title' => $screenType,
                            'description' => '템플릿 화면 - ' . $screenType,
                        ];

                        $renderedContent = $renderer::render($fullPath, $screenData);

                        $customScreen = [
                            'id' => 'template_' . str_replace(['/', '\\'], '-', $templatePath),
                            'title' => $screenType,
                            'description' => '템플릿 화면 - ' . $screenType,
                            'type' => 'template',
                            'content' => $renderedContent
                        ];
                    }
                }
            } catch (\Exception $e) {
                // 커스텀 화면을 찾지 못한 경우 기본 대시보드로
                \Log::info('커스텀 화면 로드 실패', [
                    'pageId' => $pageId,
                    'sandbox_name' => $sandboxName,
                    'custom_screen_settings' => $customScreenSettings,
                    'error' => $e->getMessage()
                ]);
            }
        }


        // 기존 프로젝트 대시보드 뷰를 사용하되, 커스텀 화면 데이터도 함께 전달
        return view('300-page-service.308-page-project-dashboard.000-index', [
            'currentPageId' => $pageId,
            'activeTab' => 'overview',
            'organization' => $organization,
            'project' => $project,
            'page' => $page,
            'customScreen' => $customScreen,
            'sandboxName' => $sandboxName
        ]);
    })->name('project.dashboard.page');

    // 페이지 설정 라우트들
    Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings', function ($id, $projectId, $pageId) {
        return redirect()->route('project.dashboard.page.settings.name', ['id' => $id, 'projectId' => $projectId, 'pageId' => $pageId]);
    })->name('project.dashboard.page.settings');

    Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/name', function ($id, $projectId, $pageId) {
        return view('300-page-service.309-page-settings-name.000-index', ['currentPageId' => $pageId, 'activeTab' => 'name']);
    })->name('project.dashboard.page.settings.name');

    Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/name', function ($id, $projectId, $pageId) {
        return view('300-page-service.309-page-settings-name.000-index', ['currentPageId' => $pageId, 'activeTab' => 'name']);
    })->name('project.dashboard.page.settings.name.post');

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

    // 프로젝트 샌드박스 설정 라우트들
    Route::get('/organizations/{id}/projects/{projectId}/settings/sandbox', function ($id, $projectId) {
        // 프로젝트 정보 가져오기
        $project = \App\Models\Project::where('id', $projectId)
            ->whereHas('organization', function($query) use ($id) {
                $query->where('id', $id);
            })->first();

        $currentSandboxType = $project ? $project->sandbox_name : null;

        return view('300-page-service.315-page-project-settings-sandbox.000-index', [
            'currentProjectId' => $projectId,
            'activeTab' => 'sandbox',
            'organizationId' => $id,
            'projectId' => $projectId,
            'currentSandboxType' => $currentSandboxType
        ]);
    })->name('project.dashboard.project.settings.sandbox');

    Route::post('/organizations/{id}/projects/{projectId}/settings/sandbox', function ($id, $projectId, Illuminate\Http\Request $request) {
        try {
            // 프로젝트 존재 여부 확인
            $project = \App\Models\Project::where('id', $projectId)
                ->whereHas('organization', function($query) use ($id) {
                    $query->where('id', $id);
                })->first();

            if (!$project) {
                return redirect()->back()->with('error', '프로젝트를 찾을 수 없습니다.');
            }

            // 샌드박스 설정 저장
            $sandboxName = $request->input('sandbox', '');
            if (empty($sandboxName)) {
                $sandboxName = null; // 빈 값을 null로 변환
            }

            $project->update([
                'sandbox_name' => $sandboxName
            ]);

            return redirect()->back()->with('success', '샌드박스 설정이 저장되었습니다.');
        } catch (\Exception $e) {
            \Log::error('샌드박스 설정 저장 오류', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', '설정 저장 중 오류가 발생했습니다.');
        }
    })->name('project.dashboard.project.settings.sandbox.post');

    Route::get('/organizations/{id}/projects/{projectId}/settings/users', function ($id, $projectId) {
        // 프로젝트 정보 가져오기
        $project = \App\Models\Project::where('id', $projectId)
            ->whereHas('organization', function($query) use ($id) {
                $query->where('id', $id);
            })->first();

        if (!$project) {
            return redirect()->route('project.dashboard', ['id' => $id, 'projectId' => $projectId])
                ->with('error', '프로젝트를 찾을 수 없습니다.');
        }

        // 조직 멤버들 가져오기
        $organizationMembers = \App\Models\OrganizationMember::where('organization_id', $id)
            ->where('invitation_status', 'accepted')
            ->with('user')
            ->get();

        return view('300-page-service.315-page-project-settings-users.000-index', [
            'currentProjectId' => $projectId,
            'activeTab' => 'users',
            'organizationId' => $id,
            'projectId' => $projectId,
            'project' => $project,
            'organizationMembers' => $organizationMembers
        ]);
    })->name('project.dashboard.project.settings.users');

    Route::get('/organizations/{id}/projects/{projectId}/settings/permissions', function ($id, $projectId) {
        // 프로젝트 정보 가져오기
        $project = \App\Models\Project::where('id', $projectId)
            ->whereHas('organization', function($query) use ($id) {
                $query->where('id', $id);
            })->first();

        if (!$project) {
            return redirect()->route('project.dashboard', ['id' => $id, 'projectId' => $projectId])
                ->with('error', '프로젝트를 찾을 수 없습니다.');
        }

        // 조직 멤버들 가져오기
        $organizationMembers = \App\Models\OrganizationMember::where('organization_id', $id)
            ->where('invitation_status', 'accepted')
            ->with('user')
            ->get();

        return view('300-page-service.316-page-project-settings-permissions.000-index', [
            'currentProjectId' => $projectId,
            'activeTab' => 'permissions',
            'organizationId' => $id,
            'projectId' => $projectId,
            'project' => $project,
            'organizationMembers' => $organizationMembers
        ]);
    })->name('project.dashboard.project.settings.permissions');

    Route::get('/organizations/{id}/projects/{projectId}/settings/delete', function ($id, $projectId) {
        return view('300-page-service.317-page-project-settings-delete.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'delete', 'organizationId' => $id, 'projectId' => $projectId]);
    })->name('project.dashboard.project.settings.delete');

    Route::get('/organizations/{id}/projects/{projectId}/settings/page-delete', function ($id, $projectId) {
        return view('300-page-service.318-page-project-settings-page-delete.000-index', ['currentProjectId' => $projectId, 'activeTab' => 'page-delete', 'organizationId' => $id, 'projectId' => $projectId]);
    })->name('project.dashboard.project.settings.page-delete');

    // 페이지 삭제 API
    Route::delete('/organizations/{id}/projects/{projectId}/pages/{pageId}', function ($id, $projectId, $pageId) {
        try {
            // 프로젝트 존재 확인
            $project = \App\Models\Project::where('id', $projectId)
                ->whereHas('organization', function($query) use ($id) {
                    $query->where('id', $id);
                })
                ->first();

            if (!$project) {
                return response()->json(['success' => false, 'error' => '프로젝트를 찾을 수 없습니다.']);
            }

            // 권한 확인 (프로젝트 소유자이거나 조직 멤버여야 함)
            $hasAccess = $project->user_id === Auth::id() ||
                \App\Models\OrganizationMember::where('organization_id', $id)
                    ->where('user_id', Auth::id())
                    ->where('invitation_status', 'accepted')
                    ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'error' => '권한이 없습니다.']);
            }

            // 페이지 존재 확인
            $page = \App\Models\ProjectPage::where('id', $pageId)
                ->where('project_id', $projectId)
                ->first();

            if (!$page) {
                return response()->json(['success' => false, 'error' => '페이지를 찾을 수 없습니다.']);
            }

            // 하위 페이지가 있는지 확인
            $hasChildren = \App\Models\ProjectPage::where('parent_id', $pageId)->exists();
            if ($hasChildren) {
                return response()->json(['success' => false, 'error' => '하위 페이지가 있는 페이지는 삭제할 수 없습니다. 먼저 하위 페이지를 삭제해주세요.']);
            }

            // 페이지 삭제
            $page->delete();

            // 프로젝트의 첫 번째 페이지로 리다이렉트할 URL 생성
            $firstPage = \App\Models\ProjectPage::where('project_id', $projectId)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->first();

            $redirectUrl = $firstPage
                ? route('project.dashboard.page', [
                    'id' => $id,
                    'projectId' => $projectId,
                    'pageId' => $firstPage->id
                ])
                : route('project.dashboard', ['id' => $id, 'projectId' => $projectId]);

            return response()->json([
                'success' => true,
                'message' => '페이지가 삭제되었습니다.',
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Exception $e) {
            \Log::error('페이지 삭제 오류: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => '페이지 삭제 중 오류가 발생했습니다.']);
        }
    })->name('pages.delete');
});

// 웹 라우트 일괄 등록 (대시보드 제외)
$routes = config('routes-web');

foreach ($routes as $path => $config) {

    // 이미 loginRequired.auth 그룹에서 처리된 라우트들 스킵
    $protectedPaths = [
        '/dashboard',
        '/organizations/{id}/dashboard',
        '/organizations/{id}/projects/{projectId}/dashboard'
    ];

    if (in_array($path, $protectedPaths)) {
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
            // /mypage/edit 경로는 특별 처리 - 비밀번호 확인 후 접근
            if ($path === '/mypage/edit') {
                // 세션에 password_verified가 없으면 /mypage로 리다이렉트
                if (!session('password_verified')) {
                    return redirect('/mypage')->with('show_password_modal', true);
                }

                // 비밀번호 확인이 완료된 경우 세션 삭제하고 진행
                session()->forget('password_verified');
            }

            // 조직 관련 페이지들에 조직 데이터 전달
            if (in_array($path, ['/dashboard', '/organizations', '/mypage', '/mypage/edit', '/mypage/delete', '/organizations/create'])) {
                // 조직 관련 페이지는 인증이 필요
                if (!Auth::check()) {
                    return redirect('/login');
                }

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
    // 페이지 정보 가져오기
    $page = \App\Models\Page::where('id', $pageId)
        ->whereHas('project', function($query) use ($projectId, $id) {
            $query->where('id', $projectId)
                  ->whereHas('organization', function($q) use ($id) {
                      $q->where('id', $id);
                  });
        })->first();

    $currentSandboxType = $page ? $page->sandbox_name : null;

    return view('300-page-service.310-page-settings-sandbox.000-index', [
        'currentPageId' => $pageId,
        'activeTab' => 'sandbox',
        'currentSandboxType' => $currentSandboxType
    ]);
})->name('project.dashboard.page.settings.sandbox');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/sandbox', function ($id, $projectId, $pageId, Illuminate\Http\Request $request) {
    try {
        // 페이지 존재 여부 확인
        $page = \App\Models\Page::where('id', $pageId)
            ->whereHas('project', function($query) use ($projectId, $id) {
                $query->where('id', $projectId)
                      ->whereHas('organization', function($q) use ($id) {
                          $q->where('id', $id);
                      });
            })->first();

        if (!$page) {
            return redirect()->back()->with('error', '페이지를 찾을 수 없습니다.');
        }

        // 샌드박스 설정 저장
        $sandboxName = $request->input('sandbox', '');
        if (empty($sandboxName)) {
            $sandboxName = null; // 빈 값을 null로 변환
        }

        $page->update([
            'sandbox_name' => $sandboxName
        ]);

        return redirect()->back()->with('success', '샌드박스 설정이 저장되었습니다.');
    } catch (\Exception $e) {
        \Log::error('샌드박스 설정 저장 오류', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', '설정 저장 중 오류가 발생했습니다.');
    }
})->name('project.dashboard.page.settings.sandbox.post');

Route::get('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/custom-screen', function ($id, $projectId, $pageId) {
    $page = \App\Models\ProjectPage::where('id', $pageId)->whereHas('project', function($query) use ($projectId, $id) {
        $query->where('id', $projectId)->whereHas('organization', function($q) use ($id) {
            $q->where('id', $id);
        });
    })->first();

    $currentSandboxType = $page ? $page->sandbox_name : null;
    $currentCustomScreenSettings = $page ? $page->custom_screen_settings : null;

    // 템플릿 파일에서 직접 커스텀 화면 데이터 가져오기 (샌드박스 브라우저 컴포넌트와 동일한 로직)
    $customScreens = [];
    if (!empty($currentSandboxType)) {
        try {
            $templatePath = storage_path('sandbox/storage-sandbox-template/frontend');

            if (\File::exists($templatePath)) {
                $folders = \File::directories($templatePath);

                foreach ($folders as $folder) {
                    $folderName = basename($folder);
                    $contentFile = $folder . '/000-content.blade.php';

                    if (\File::exists($contentFile)) {
                        // 폴더명에서 화면 정보 추출
                        $parts = explode('-', $folderName, 3);
                        $screenId = $parts[0] ?? '000';
                        $screenType = $parts[1] ?? 'screen';
                        $screenName = $parts[2] ?? 'unnamed';

                        // 파일 내용 읽기
                        $fileContent = \File::get($contentFile);

                        $customScreens[] = [
                            'id' => 'template_' . $folderName, // 전체 폴더명을 사용해서 고유한 ID 생성
                            'title' => str_replace('-', ' ', $screenName),
                            'description' => '템플릿 화면 - ' . str_replace('-', ' ', $screenName),
                            'type' => $screenType,
                            'folder_name' => $folderName,
                            'file_path' => 'frontend/' . $folderName . '/000-content.blade.php',
                            'created_at' => date('Y-m-d H:i:s', \File::lastModified($contentFile)),
                            'file_exists' => true,
                            'full_path' => $contentFile,
                            'file_size' => \File::size($contentFile),
                            'file_modified' => date('Y-m-d H:i:s', \File::lastModified($contentFile)),
                            'is_template' => true,
                            'blade_template' => $fileContent,
                        ];
                    }
                }
            }

            // 생성 날짜 기준 내림차순 정렬
            usort($customScreens, function($a, $b) {
                return strcmp($b['created_at'], $a['created_at']);
            });

        } catch (\Exception $e) {
            \Log::error('커스텀 화면 데이터 로드 오류', ['error' => $e->getMessage(), 'sandbox_name' => $currentSandboxType]);
            $customScreens = [];
        }
    }

    return view('300-page-service.311-page-settings-custom-screen.000-index', [
        'currentPageId' => $pageId,
        'activeTab' => 'custom-screen',
        'page' => $page,
        'currentSandboxType' => $currentSandboxType,
        'currentCustomScreenSettings' => $currentCustomScreenSettings,
        'customScreens' => $customScreens
    ]);
})->name('project.dashboard.page.settings.custom-screen');

Route::post('/organizations/{id}/projects/{projectId}/pages/{pageId}/settings/custom-screen', function ($id, $projectId, $pageId, Illuminate\Http\Request $request) {
    try {
        $page = \App\Models\ProjectPage::where('id', $pageId)->whereHas('project', function($query) use ($projectId, $id) {
            $query->where('id', $projectId)->whereHas('organization', function($q) use ($id) {
                $q->where('id', $id);
            });
        })->first();

        if (!$page) {
            return redirect()->back()->with('error', '페이지를 찾을 수 없습니다.');
        }

        // 샌드박스가 설정되어 있는지 확인
        if (empty($page->sandbox_name)) {
            return redirect()->back()->with('error', '커스텀 화면을 사용하려면 먼저 샌드박스를 선택해야 합니다.');
        }

        $customScreenId = $request->input('custom_screen', '');
        $customScreenSettings = [];

        if (!empty($customScreenId)) {
            $customScreenSettings = [
                'screen_id' => $customScreenId,
                'enabled' => true,
                'applied_at' => now()->format('Y-m-d H:i:s')
            ];
        }

        $page->update([
            'custom_screen_settings' => !empty($customScreenSettings) ? $customScreenSettings : null
        ]);

        return redirect()->back()->with('success', '커스텀 화면 설정이 저장되었습니다.');
    } catch (\Exception $e) {
        \Log::error('커스텀 화면 설정 저장 오류', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', '설정 저장 중 오류가 발생했습니다.');
    }
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

// 조직 관리자 페이지 라우트들
Route::get('/organizations/{id}/admin/members', function ($id) {
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

// 플랫폼 관리자 - 샌드박스 관리
Route::get('/platform/admin/sandboxes', function () {
    return view('900-page-platform-admin.907-page-sandboxes.000-index');
})->name('platform.admin.sandboxes');

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

// 실시간 템플릿 화면들 - 직접 경로
Route::get('/sandbox/live-dashboard', function () {
    return view('700-page-sandbox.706-page-custom-screens.100-live-dashboard');
})->name('sandbox.live-dashboard');

Route::get('/sandbox/live-project-list', function () {
    return view('700-page-sandbox.706-page-custom-screens.101-live-project-list');
})->name('sandbox.live-project-list');

Route::get('/sandbox/live-table-view', function () {
    return view('700-page-sandbox.706-page-custom-screens.102-live-table-view');
})->name('sandbox.live-table-view');

Route::get('/sandbox/live-kanban-board', function () {
    return view('700-page-sandbox.706-page-custom-screens.103-live-kanban-board');
})->name('sandbox.live-kanban-board');

Route::get('/sandbox/live-gantt-chart', function () {
    return view('700-page-sandbox.706-page-custom-screens.104-live-gantt-chart');
})->name('sandbox.live-gantt-chart');

Route::get('/sandbox/live-calendar-view', function () {
    return view('700-page-sandbox.706-page-custom-screens.105-live-calendar-view');
})->name('sandbox.live-calendar-view');

// Custom Screen Creator
Route::get('/sandbox/custom-screen-creator', function () {
    return view('700-page-sandbox.707-page-custom-screen-creator.000-index');
})->name('sandbox.custom-screen-creator');

// Organizations List (Generated Custom Screen)
Route::get('/sandbox/organizations-list', function () {
    return view('700-page-sandbox.713-page-organizations-list.000-index');
})->name('sandbox.organizations-list');
// 화면 (Generated Custom Screen)
Route::get('/sandbox/custom-screen-1757421612', function () {
    return view('700-page-sandbox.717-page-custom-screen-1757421612.000-index');
})->name('sandbox.custom-screen-1757421612');
// 프로젝트 목록 (Generated Custom Screen)
Route::get('/sandbox/projects-list', function () {
    return view('700-page-sandbox.715-page-projects-list.000-index');
})->name('sandbox.projects-list');

// 샌드박스 사용 프로젝트 목록
Route::get('/sandbox/using-projects', [\App\Http\CoreApi\Sandbox\UsingProjectsController::class, 'index'])->name('sandbox.using-projects');

// 자료 다운로드 페이지
Route::get('/sandbox/downloads', function () {
    return view('700-page-sandbox.718-page-downloads.000-index');
})->name('sandbox.downloads');

// 자료 다운로드 API
Route::get('/sandbox/downloads/file/{filename}', [\App\Http\Sandbox\Downloads\Controller::class, 'download'])->name('sandbox.downloads.file');
Route::get('/sandbox/downloads/stats', [\App\Http\Sandbox\Downloads\Controller::class, 'getStats'])->name('sandbox.downloads.stats');

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



// 샌드박스 템플릿 전용 프리뷰 (새창에서 보기)
Route::get('/sandbox/custom-screen/preview/{id}', function ($id) {
    $sandboxPath = storage_path('sandbox/storage-sandbox-template/frontend');

    if (!File::exists($sandboxPath)) {
        abort(404, '샌드박스 폴더를 찾을 수 없습니다.');
    }

    // 템플릿 폴더 찾기
    $folders = File::directories($sandboxPath);
    $contentFile = null;
    $screenName = 'unknown';

    foreach ($folders as $folder) {
        $folderName = basename($folder);
        $templateId = explode('-', $folderName, 2)[1] ?? '';

        if ($templateId === $id) {
            $contentFile = $folder . '/000-content.blade.php';
            $parts = explode('-', $folderName, 3);
            $screenName = $parts[2] ?? 'unnamed';
            break;
        }
    }

    if (!$contentFile || !File::exists($contentFile)) {
        abort(404, '템플릿 파일을 찾을 수 없습니다.');
    }

    // 샘플 데이터 준비
    $title = str_replace('-', ' ', $screenName);
    $description = '템플릿 화면 - ' . str_replace('-', ' ', $screenName);
    $organizations = collect([
        ['id' => 1, 'name' => '샘플 조직 1', 'members' => 15, 'created_at' => '2025-09-01'],
        ['id' => 2, 'name' => '샘플 조직 2', 'members' => 8, 'created_at' => '2025-08-15'],
    ]);
    $projects = collect([
        ['id' => 1, 'name' => '프로젝트 A', 'status' => 'active', 'progress' => 75],
        ['id' => 2, 'name' => '프로젝트 B', 'status' => 'pending', 'progress' => 30],
        ['id' => 3, 'name' => '프로젝트 C', 'status' => 'completed', 'progress' => 100]
    ]);
    $users = collect([
        ['id' => 1, 'name' => '홍길동', 'email' => 'hong@example.com', 'status' => 'active'],
        ['id' => 2, 'name' => '김철수', 'email' => 'kim@example.com', 'status' => 'inactive'],
        ['id' => 3, 'name' => '이영희', 'email' => 'lee@example.com', 'status' => 'active']
    ]);
    $activities = collect([
        ['action' => '새 프로젝트 생성', 'user' => '홍길동', 'timestamp' => '5분 전'],
        ['action' => '사용자 추가', 'user' => '김영희', 'timestamp' => '1시간 전'],
    ]);

    // Blade 템플릿을 샘드박스 방식으로 렌더링
    try {
        // 임시 블레이드 파일 생성 및 렌더링 (샌드박스 성공 방식 적용)
        $tempViewPath = 'preview-renderer-temp-' . time() . '-' . rand(1000, 9999);
        $tempViewFile = resource_path('views/' . $tempViewPath . '.blade.php');

        $templateContent = File::get($contentFile);
        File::put($tempViewFile, $templateContent);

        try {
            $renderedContent = view($tempViewPath, compact(
                'title', 'description', 'organizations', 'projects', 'users', 'activities'
            ))->render();
        } catch (\Exception $e) {
            $renderedContent = '<div class="p-4 bg-red-50 border border-red-200 rounded">
                <h3 class="text-red-800 font-bold">템플릿 렌더링 오류</h3>
                <p class="text-red-700 mt-2">' . $e->getMessage() . '</p>
            </div>';
        } finally {
            // 임시 파일 삭제
            if (File::exists($tempViewFile)) {
                File::delete($tempViewFile);
            }
        }
    } catch (Exception $e) {
        $renderedContent = '<div class="p-4 bg-red-50 border border-red-200 rounded">
            <h3 class="text-red-800 font-bold">파일 처리 오류</h3>
            <p class="text-red-700 mt-2">' . $e->getMessage() . '</p>
        </div>';
    }

    // 화면 데이터 객체 생성 (뷰에서 사용하기 위해)
    $screen = (object) [
        'id' => $id,
        'title' => $title,
        'description' => $description,
        'type' => 'template',
        'content' => $renderedContent
    ];

    return view('700-page-sandbox.714-page-custom-screen-preview.000-index', [
        'screen' => $screen,
        'customContent' => $renderedContent
    ]);
})->name('sandbox.custom-screen-preview');
