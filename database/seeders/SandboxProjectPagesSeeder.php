<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectPage;
use App\Models\Project;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SandboxProjectPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 첫 번째 사용자 찾기 (없으면 생성)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => '테스트 사용자',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // 첫 번째 조직 찾기 (없으면 생성)
        $organization = Organization::first();
        if (!$organization) {
            $organization = Organization::create([
                'name' => '테스트 조직',
                'created_by' => $user->id,
            ]);
        }

        // 첫 번째 프로젝트 찾기 (없으면 생성)
        $project = Project::first();
        if (!$project) {
            $project = Project::create([
                'name' => '샘플 프로젝트',
                'description' => '샌드박스 테스트용 프로젝트입니다',
                'organization_id' => $organization->id,
                'user_id' => $user->id,
            ]);
        }

        // 샌드박스 페이지들 생성
        $pages = [
            [
                'title' => '📊 대시보드',
                'slug' => 'dashboard',
                'content' => '실시간 프로젝트 통계와 최근 활동을 확인할 수 있는 대시보드입니다.',
                'sandbox_type' => 'storage-sandbox-template',
                'custom_screen_settings' => json_encode([
                    'screen_type' => 'dashboard',
                    'template_path' => 'frontend/001-screen-dashboard/000-content.blade.php',
                    'live_component' => 'sandbox.custom-screens.live.dashboard-component',
                    'live_url' => '/sandbox/live-dashboard'
                ]),
                'parent_id' => null,
            ],
            [
                'title' => '📋 프로젝트 관리',
                'slug' => 'project-management',
                'content' => '프로젝트를 다양한 방식으로 관리할 수 있는 화면들입니다.',
                'parent_id' => null,
            ],
            [
                'title' => '📝 프로젝트 목록',
                'slug' => 'project-list',
                'content' => '프로젝트 목록을 확인하고 관리할 수 있습니다.',
                'sandbox_type' => 'storage-sandbox-template',
                'custom_screen_settings' => json_encode([
                    'screen_type' => 'project list',
                    'template_path' => 'frontend/002-screen-project-list/000-content.blade.php',
                    'live_component' => 'sandbox.custom-screens.live.project-list-component',
                    'live_url' => '/sandbox/live-project-list'
                ]),
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '🗂️ 테이블 뷰',
                'slug' => 'table-view',
                'content' => '프로젝트 데이터를 테이블 형태로 보고 관리할 수 있습니다.',
                'sandbox_type' => 'storage-sandbox-template',
                'custom_screen_settings' => json_encode([
                    'screen_type' => 'table view',
                    'template_path' => 'frontend/003-screen-table-view/000-content.blade.php',
                    'live_component' => 'sandbox.custom-screens.live.table-view-component',
                    'live_url' => '/sandbox/live-table-view'
                ]),
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '📋 칸반 보드',
                'slug' => 'kanban-board',
                'content' => '프로젝트를 칸반 보드 형태로 관리할 수 있습니다.',
                'sandbox_type' => 'storage-sandbox-template',
                'custom_screen_settings' => json_encode([
                    'screen_type' => 'kanban board',
                    'template_path' => 'frontend/004-screen-kanban-board/000-content.blade.php',
                    'live_component' => 'sandbox.custom-screens.live.kanban-board-component',
                    'live_url' => '/sandbox/live-kanban-board'
                ]),
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '📈 간트 차트',
                'slug' => 'gantt-chart',
                'content' => '프로젝트 일정을 간트 차트로 시각화하여 관리할 수 있습니다.',
                'sandbox_type' => 'storage-sandbox-template',
                'custom_screen_settings' => json_encode([
                    'screen_type' => 'gantt chart',
                    'template_path' => 'frontend/005-screen-gantt-chart/000-content.blade.php',
                    'live_component' => 'sandbox.custom-screens.live.gantt-chart-component',
                    'live_url' => '/sandbox/live-gantt-chart'
                ]),
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '📅 달력 뷰',
                'slug' => 'calendar-view',
                'content' => '프로젝트 일정을 달력 형태로 확인할 수 있습니다.',
                'sandbox_type' => 'storage-sandbox-template',
                'custom_screen_settings' => json_encode([
                    'screen_type' => 'calendar view',
                    'template_path' => 'frontend/006-screen-calendar-view/000-content.blade.php',
                    'live_component' => 'sandbox.custom-screens.live.calendar-view-component',
                    'live_url' => '/sandbox/live-calendar-view'
                ]),
                'parent_title' => '📋 프로젝트 관리',
            ],
        ];

        // 부모 페이지 생성 및 ID 저장
        $parentPages = [];
        
        foreach ($pages as $pageData) {
            if (!isset($pageData['parent_title'])) {
                // 최상위 페이지 생성
                $page = ProjectPage::create([
                    'project_id' => $project->id,
                    'title' => $pageData['title'],
                    'slug' => $pageData['slug'],
                    'content' => $pageData['content'],
                    'sandbox_type' => $pageData['sandbox_type'] ?? null,
                    'custom_screen_settings' => $pageData['custom_screen_settings'] ?? null,
                    'parent_id' => null,
                    'user_id' => $user->id,
                ]);
                
                $parentPages[$pageData['title']] = $page->id;
                
                $this->command->info("생성됨: {$pageData['title']}");
            }
        }

        // 하위 페이지 생성
        foreach ($pages as $pageData) {
            if (isset($pageData['parent_title'])) {
                $parentId = $parentPages[$pageData['parent_title']] ?? null;
                
                if ($parentId) {
                    $page = ProjectPage::create([
                        'project_id' => $project->id,
                        'title' => $pageData['title'],
                        'slug' => $pageData['slug'],
                        'content' => $pageData['content'],
                        'sandbox_type' => $pageData['sandbox_type'] ?? null,
                        'custom_screen_settings' => $pageData['custom_screen_settings'] ?? null,
                        'parent_id' => $parentId,
                        'user_id' => $user->id,
                    ]);
                    
                    $this->command->info("생성됨: {$pageData['parent_title']} > {$pageData['title']}");
                }
            }
        }

        $this->command->info('샌드박스 프로젝트 페이지 시딩이 완료되었습니다!');
        $this->command->info("프로젝트 URL: /organizations/{$organization->id}/projects/{$project->id}");
    }
}