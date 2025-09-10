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
        // Clear existing role-permission assignments
        \DB::table('role_has_permissions')->delete();
        
        // 게스트 (비로그인 사용자)
        $guest = $this->createRoleDirectly('guest');
        $this->assignPermissionsDirectly($guest, [
            'view public projects',
            'view public pages',
            'view public organization info',
            'submit public forms',
            'view public analytics',
        ]);

        // 기본 사용자
        $user = $this->createRoleDirectly('user');
        $this->assignPermissionsDirectly($user, [
            'view projects',
            'view pages',
            'view organization settings',
        ]);

        // 고급 사용자
        $advancedUser = $this->createRoleDirectly('advanced_user');
        $this->assignPermissionsDirectly($advancedUser, [
            'view projects',
            'view pages',
            'view members',
            'view organization settings',
        ]);

        // 서비스 매니저
        $serviceManager = $this->createRoleDirectly('service_manager');
        $this->assignPermissionsDirectly($serviceManager, [
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
        $seniorServiceManager = $this->createRoleDirectly('senior_service_manager');
        $this->assignPermissionsDirectly($seniorServiceManager, [
            'view projects',
            'create projects', 
            'edit projects',
            'assign project members',
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'manage page versions',
            'manage project settings',
            'view members',
            'view organization settings',
        ]);

        // 조직 관리자
        $organizationAdmin = $this->createRoleDirectly('organization_admin');
        $this->assignPermissionsDirectly($organizationAdmin, [
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

        // 선임 조직 관리자 - same permissions as organization_admin
        $seniorOrgAdmin = $this->createRoleDirectly('senior_organization_admin');
        $this->assignPermissionsDirectly($seniorOrgAdmin, [
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

        // 조직 소유자
        $organizationOwner = $this->createRoleDirectly('organization_owner');
        $this->assignPermissionsDirectly($organizationOwner, [
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

        // 조직 창립자 - same as organization owner
        $organizationFounder = $this->createRoleDirectly('organization_founder');
        $this->assignPermissionsDirectly($organizationFounder, [
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

        // 플랫폼 관리자 - all permissions
        $platformAdmin = $this->createRoleDirectly('platform_admin');
        $this->assignAllPermissions($platformAdmin);

        // 최고 관리자 - all permissions
        $superAdmin = $this->createRoleDirectly('super_admin');
        $this->assignAllPermissions($superAdmin);

        $this->command->info('Roles and permissions assigned.');
    }
    
    /**
     * Create a role directly using database operations
     */
    private function createRoleDirectly(string $name): object
    {
        // Check if role already exists
        $existing = \DB::table('roles')->where('name', $name)->first();
        if ($existing) {
            $this->command->info("Role '{$name}' already exists (ID: {$existing->id})");
            return $existing;
        }
        
        // Create new role with explicit transaction
        \DB::beginTransaction();
        try {
            \DB::table('roles')->insert([
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Get the role by name (more reliable than using insertGetId result)
            $role = \DB::table('roles')->where('name', $name)->where('guard_name', 'web')->first();
            if (!$role) {
                throw new \Exception("Role creation failed - role not found after insert");
            }
            
            \DB::commit();
            $this->command->info("Created role '{$name}' (ID: {$role->id})");
            
            return $role;
            
        } catch (\Exception $e) {
            \DB::rollback();
            $this->command->error("Failed to create role '{$name}': " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Assign permissions directly using database operations
     */
    private function assignPermissionsDirectly($role, array $permissionNames): void
    {
        // Validate that the role actually exists in the database
        $roleExists = \DB::table('roles')->find($role->id);
        if (!$roleExists) {
            $this->command->error("Role {$role->name} (ID: {$role->id}) does not exist in database!");
            return;
        }
        
        $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();
        
        if (empty($permissionIds)) {
            $this->command->warn("No permissions found for role {$role->name}");
            return;
        }
        
        $inserts = array_map(function ($permissionId) use ($role) {
            return [
                'role_id' => $role->id,
                'permission_id' => $permissionId
            ];
        }, $permissionIds);
        
        try {
            \DB::table('role_has_permissions')->insert($inserts);
            $this->command->info("Assigned " . count($inserts) . " permissions to {$role->name}");
        } catch (\Exception $e) {
            $this->command->error("Failed to assign permissions to {$role->name}: " . $e->getMessage());
        }
    }
    
    /**
     * Assign all permissions to a role
     */
    private function assignAllPermissions($role): void
    {
        $permissionIds = Permission::pluck('id')->toArray();
        
        $inserts = array_map(function ($permissionId) use ($role) {
            return [
                'role_id' => $role->id,
                'permission_id' => $permissionId
            ];
        }, $permissionIds);
        
        if (!empty($inserts)) {
            \DB::table('role_has_permissions')->insert($inserts);
        }
    }
}
