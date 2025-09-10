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
        $user = \DB::table('users')->first();
        if (!$user) {
            \DB::table('users')->insert([
                'name' => '테스트 사용자',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user = \DB::table('users')->where('email', 'test@example.com')->first();
            
            if (!$user) {
                throw new \Exception('Failed to create test user');
            }
        }

        // 첫 번째 조직 찾기 (없으면 생성)
        $organization = \DB::table('organizations')->first();
        if (!$organization) {
            \DB::table('organizations')->insert([
                'name' => '테스트 조직',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $organization = \DB::table('organizations')->where('name', '테스트 조직')->first();
            
            if (!$organization) {
                throw new \Exception('Failed to create test organization');
            }
        }

        // 첫 번째 프로젝트 찾기 (없으면 생성)
        $project = \DB::table('projects')->first();
        if (!$project) {
            \DB::table('projects')->insert([
                'name' => '샘플 프로젝트',
                'description' => '샌드박스 테스트용 프로젝트입니다',
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $project = \DB::table('projects')->where('name', '샘플 프로젝트')->first();
            
            if (!$project) {
                throw new \Exception('Failed to create test project');
            }
        }

        // 샌드박스 페이지들 생성 (simplified without custom screen settings)
        $pages = [
            [
                'title' => '📊 대시보드',
                'slug' => 'dashboard',
                'content' => '실시간 프로젝트 통계와 최근 활동을 확인할 수 있는 대시보드입니다.',
                'sandbox_name' => 'storage-sandbox-template',
                'custom_screen_type' => 'dashboard',
                'template_path' => 'frontend/001-screen-dashboard/000-content.blade.php',
                'custom_screen_enabled' => true,
                'parent_id' => null,
            ],
            [
                'title' => '📋 프로젝트 관리',
                'slug' => 'project-management',
                'content' => '프로젝트를 다양한 방식으로 관리할 수 있는 화면들입니다.',
                'custom_screen_type' => 'template',
                'custom_screen_enabled' => false,
                'parent_id' => null,
            ],
            [
                'title' => '📝 프로젝트 목록',
                'slug' => 'project-list',
                'content' => '프로젝트 목록을 확인하고 관리할 수 있습니다.',
                'sandbox_name' => 'storage-sandbox-template',
                'custom_screen_type' => 'project list',
                'template_path' => 'frontend/002-screen-project-list/000-content.blade.php',
                'custom_screen_enabled' => true,
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '🗂️ 테이블 뷰',
                'slug' => 'table-view',
                'content' => '프로젝트 데이터를 테이블 형태로 보고 관리할 수 있습니다.',
                'sandbox_name' => 'storage-sandbox-template',
                'custom_screen_type' => 'table view',
                'template_path' => 'frontend/003-screen-table-view/000-content.blade.php',
                'custom_screen_enabled' => true,
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '📋 칸반 보드',
                'slug' => 'kanban-board',
                'content' => '프로젝트를 칸반 보드 형태로 관리할 수 있습니다.',
                'sandbox_name' => 'storage-sandbox-template',
                'custom_screen_type' => 'kanban board',
                'template_path' => 'frontend/004-screen-kanban-board/000-content.blade.php',
                'custom_screen_enabled' => true,
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '📈 간트 차트',
                'slug' => 'gantt-chart',
                'content' => '프로젝트 일정을 간트 차트로 시각화하여 관리할 수 있습니다.',
                'sandbox_name' => 'storage-sandbox-template',
                'custom_screen_type' => 'gantt chart',
                'template_path' => 'frontend/005-screen-gantt-chart/000-content.blade.php',
                'custom_screen_enabled' => true,
                'parent_title' => '📋 프로젝트 관리',
            ],
            [
                'title' => '📅 달력 뷰',
                'slug' => 'calendar-view',
                'content' => '프로젝트 일정을 달력 형태로 확인할 수 있습니다.',
                'sandbox_name' => 'storage-sandbox-template',
                'custom_screen_type' => 'calendar view',
                'template_path' => 'frontend/006-screen-calendar-view/000-content.blade.php',
                'custom_screen_enabled' => true,
                'parent_title' => '📋 프로젝트 관리',
            ],
        ];

        // 부모 페이지 생성 및 ID 저장
        $parentPages = [];

        foreach ($pages as $pageData) {
            if (!isset($pageData['parent_title'])) {
                // 최상위 페이지 생성 - use direct DB insertion
                $pageId = \DB::table('project_pages')->insertGetId([
                    'project_id' => $project->id,
                    'title' => $pageData['title'],
                    'slug' => $pageData['slug'],
                    'content' => $pageData['content'],
                    'sandbox_name' => $pageData['sandbox_name'] ?? null,
                    'custom_screen_type' => $pageData['custom_screen_type'] ?? 'template',
                    'template_path' => $pageData['template_path'] ?? null,
                    'custom_screen_enabled' => $pageData['custom_screen_enabled'] ?? true,
                    'parent_id' => null,
                    'user_id' => $user->id,
                    'status' => 'published',
                    'access_level' => 'public',
                    'sort_order' => 0,
                    'screen_layout' => 'default',
                    'screen_theme' => 'light',
                    'screen_fullwidth' => false,
                    'screen_sidebar_position' => 'right',
                    'screen_header_visible' => true,
                    'screen_footer_visible' => true,
                    'screen_responsive' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Get the actual page ID (handling potential insertGetId issues)
                $actualPage = \DB::table('project_pages')->where('title', $pageData['title'])->where('project_id', $project->id)->first();
                if ($actualPage) {
                    $parentPages[$pageData['title']] = $actualPage->id;
                    $this->command->info("생성됨: {$pageData['title']}");
                }
            }
        }

        // 하위 페이지 생성
        foreach ($pages as $pageData) {
            if (isset($pageData['parent_title'])) {
                $parentId = $parentPages[$pageData['parent_title']] ?? null;

                if ($parentId) {
                    \DB::table('project_pages')->insert([
                        'project_id' => $project->id,
                        'title' => $pageData['title'],
                        'slug' => $pageData['slug'],
                        'content' => $pageData['content'],
                        'sandbox_name' => $pageData['sandbox_name'] ?? null,
                        'custom_screen_type' => $pageData['custom_screen_type'] ?? 'template',
                        'template_path' => $pageData['template_path'] ?? null,
                        'custom_screen_enabled' => $pageData['custom_screen_enabled'] ?? true,
                        'parent_id' => $parentId,
                        'user_id' => $user->id,
                        'status' => 'published',
                        'access_level' => 'public',
                        'sort_order' => 0,
                        'screen_layout' => 'default',
                        'screen_theme' => 'light',
                        'screen_fullwidth' => false,
                        'screen_sidebar_position' => 'right',
                        'screen_header_visible' => true,
                        'screen_footer_visible' => true,
                        'screen_responsive' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $this->command->info("생성됨: {$pageData['parent_title']} > {$pageData['title']}");
                }
            }
        }

        $this->command->info('샌드박스 프로젝트 페이지 시딩이 완료되었습니다!');
        $this->command->info("프로젝트 URL: /organizations/{$organization->id}/projects/{$project->id}");
    }
}