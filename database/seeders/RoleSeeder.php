<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // 게스트 (비로그인 사용자)
        $guest = Role::firstOrCreate(['name' => 'guest']);
        $guest->syncPermissions([
            'view public projects',
            'view public pages',
            'view public organization info',
            'submit public forms',
            'view public analytics',
        ]);

        // 기본 사용자
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions([
            'view projects',
            'view pages',
            'view organization settings',
        ]);

        // 고급 사용자
        $advancedUser = Role::firstOrCreate(['name' => 'advanced_user']);
        $advancedUser->syncPermissions([
            'view projects',
            'view pages',
            'view members',
            'view organization settings',
        ]);

        // 서비스 매니저
        $serviceManager = Role::firstOrCreate(['name' => 'service_manager']);
        $serviceManager->syncPermissions([
            'view projects',
            'create projects',
            'edit projects',
            'assign project members',
            'view pages',
            'create pages',
            'edit pages',
            'view members',
            'view organization settings',
        ]);

        // 선임 서비스 매니저
        $seniorServiceManager = Role::firstOrCreate(['name' => 'senior_service_manager']);
        $seniorServiceManager->syncPermissions(array_merge(
            $serviceManager->permissions->pluck('name')->toArray(),
            [
                'delete pages',
                'manage page versions',
                'manage project settings',
            ]
        ));

        // 조직 관리자
        $organizationAdmin = Role::firstOrCreate(['name' => 'organization_admin']);
        $organizationAdmin->syncPermissions([
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'assign project members',
            'manage project settings',
            'archive projects',
            'restore projects',
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'publish pages',
            'unpublish pages',
            'manage page versions',
            'view page analytics',
            'view members',
            'invite members',
            'edit members',
            'manage member permissions',
            'view billing',
            'download receipts',
            'view organization settings',
            'edit organization settings',
            'view permissions',
            'edit permissions',
            'assign roles',
            'access admin panel',
        ]);

        // 선임 조직 관리자
        $seniorOrgAdmin = Role::firstOrCreate(['name' => 'senior_organization_admin']);
        $seniorOrgAdmin->syncPermissions($organizationAdmin->permissions->pluck('name')->toArray());

        // 조직 소유자
        $organizationOwner = Role::firstOrCreate(['name' => 'organization_owner']);
        $organizationOwner->syncPermissions([
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'assign project members',
            'manage project settings',
            'archive projects',
            'restore projects',
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'publish pages',
            'unpublish pages',
            'manage page versions',
            'view page analytics',
            'view members',
            'invite members',
            'edit members',
            'delete members',
            'manage member permissions',
            'view billing',
            'edit billing',
            'download receipts',
            'change subscription plan',
            'view organization settings',
            'edit organization settings',
            'delete organization',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'assign roles',
            'access admin panel',
        ]);

        // 조직 창립자
        $organizationFounder = Role::firstOrCreate(['name' => 'organization_founder']);
        $organizationFounder->syncPermissions($organizationOwner->permissions->pluck('name')->toArray());

        // 플랫폼 관리자
        $platformAdmin = Role::firstOrCreate(['name' => 'platform_admin']);
        $platformAdmin->syncPermissions(Permission::all());

        // 최고 관리자
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        $this->command->info('Roles and permissions assigned.');
    }
}
