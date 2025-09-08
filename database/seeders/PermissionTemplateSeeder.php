<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionTemplate;

class PermissionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'guest',
                'display_name' => '게스트 사용자',
                'description' => '로그인하지 않은 사용자 (공개 콘텐츠만 접근 가능)',
                'permissions_config' => [
                    'roles' => ['guest'],
                    'permissions' => [],
                ],
                'sort_order' => 0,
            ],
            [
                'name' => 'basic_user',
                'display_name' => '기본 사용자',
                'description' => '기본적인 프로젝트 및 페이지 조회 권한',
                'permissions_config' => [
                    'roles' => ['user'],
                    'permissions' => [],
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'content_creator',
                'display_name' => '콘텐츠 작성자',
                'description' => '페이지 작성 및 편집 권한',
                'permissions_config' => [
                    'roles' => ['advanced_user'],
                    'permissions' => [
                        'create pages',
                        'edit pages',
                    ],
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'service_manager',
                'display_name' => '서비스 매니저',
                'description' => '프로젝트 및 페이지 관리 권한',
                'permissions_config' => [
                    'roles' => ['service_manager'],
                    'permissions' => [],
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'senior_service_manager',
                'display_name' => '선임 서비스 매니저',
                'description' => '확장된 프로젝트 및 페이지 관리 권한',
                'permissions_config' => [
                    'roles' => ['senior_service_manager'],
                    'permissions' => [],
                ],
                'sort_order' => 4,
            ],
            [
                'name' => 'organization_admin',
                'display_name' => '조직 목록자',
                'description' => '조직 전반적인 관리 권한 (결제 제외)',
                'permissions_config' => [
                    'roles' => ['organization_admin'],
                    'permissions' => [],
                ],
                'sort_order' => 5,
            ],
            [
                'name' => 'organization_owner',
                'display_name' => '조직 소유자',
                'description' => '조직의 모든 관리 권한',
                'permissions_config' => [
                    'roles' => ['organization_owner'],
                    'permissions' => [],
                ],
                'sort_order' => 6,
            ],
            [
                'name' => 'platform_admin',
                'display_name' => '플랫폼 관리자',
                'description' => '시스템 전체 관리 권한',
                'permissions_config' => [
                    'roles' => ['platform_admin'],
                    'permissions' => [],
                ],
                'sort_order' => 7,
            ],
        ];

        foreach ($templates as $template) {
            PermissionTemplate::firstOrCreate(
                ['name' => $template['name']],
                $template
            );
        }

        $this->command->info('Permission templates created.');
    }
}
