<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionCategory;

class PermissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'member_management',
                'display_name' => '회원 관리',
                'description' => '조직 구성원 관리 관련 권한',
                'sort_order' => 1,
            ],
            [
                'name' => 'project_management',
                'display_name' => '프로젝트 관리',
                'description' => '프로젝트 관련 관리 권한',
                'sort_order' => 2,
            ],
            [
                'name' => 'page_management',
                'display_name' => '페이지 관리',
                'description' => '프로젝트 내 페이지 관리 관련 권한',
                'sort_order' => 3,
            ],
            [
                'name' => 'billing_management',
                'display_name' => '결제 관리',
                'description' => '결제 및 구독 관련 관리 권한',
                'sort_order' => 4,
            ],
            [
                'name' => 'organization_settings',
                'display_name' => '조직 설정',
                'description' => '조직 전반적인 설정 관리 권한',
                'sort_order' => 5,
            ],
            [
                'name' => 'permission_management',
                'display_name' => '권한 관리',
                'description' => '권한 및 역할 관리 관련 권한',
                'sort_order' => 6,
            ],
            [
                'name' => 'public_access',
                'display_name' => '공개 접근',
                'description' => '로그인 없이 접근 가능한 기능',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            PermissionCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('Permission categories created.');
    }
}