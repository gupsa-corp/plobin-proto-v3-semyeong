<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $permissions = [
            // 회원 관리
            'view members',
            'invite members',
            'edit members',
            'delete members',
            'manage member permissions',

            // 프로젝트 관리
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'assign project members',
            'manage project settings',
            'archive projects',
            'restore projects',

            // 페이지 관리
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'publish pages',
            'unpublish pages',
            'manage page versions',
            'view page analytics',

            // 결제 관리
            'view billing',
            'edit billing',
            'download receipts',
            'change subscription plan',

            // 조직 설정
            'view organization settings',
            'edit organization settings',
            'delete organization',

            // 권한 관리
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'assign roles',

            // 시스템 관리
            'access admin panel',
            'manage system settings',

            // 공개 접근 (로그인 불필요)
            'view public projects',
            'view public pages',
            'view public organization info',
            'submit public forms',
            'view public analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Permissions created.');
    }
}