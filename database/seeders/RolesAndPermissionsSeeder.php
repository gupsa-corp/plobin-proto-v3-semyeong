<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            ['name' => 'view users', 'category' => 'User Management', 'description' => 'View user list and details'],
            ['name' => 'create users', 'category' => 'User Management', 'description' => 'Create new users'],
            ['name' => 'edit users', 'category' => 'User Management', 'description' => 'Edit user information'],
            ['name' => 'delete users', 'category' => 'User Management', 'description' => 'Delete users'],
            ['name' => 'manage user roles', 'category' => 'User Management', 'description' => 'Assign and manage user roles'],

            // Organization Management
            ['name' => 'view organizations', 'category' => 'Organization Management', 'description' => 'View organization list and details'],
            ['name' => 'create organizations', 'category' => 'Organization Management', 'description' => 'Create new organizations'],
            ['name' => 'edit organizations', 'category' => 'Organization Management', 'description' => 'Edit organization information'],
            ['name' => 'delete organizations', 'category' => 'Organization Management', 'description' => 'Delete organizations'],
            ['name' => 'manage organization members', 'category' => 'Organization Management', 'description' => 'Manage organization membership'],

            // Project Management
            ['name' => 'view projects', 'category' => 'Project Management', 'description' => 'View project list and details'],
            ['name' => 'create projects', 'category' => 'Project Management', 'description' => 'Create new projects'],
            ['name' => 'edit projects', 'category' => 'Project Management', 'description' => 'Edit project information'],
            ['name' => 'delete projects', 'category' => 'Project Management', 'description' => 'Delete projects'],
            ['name' => 'manage project members', 'category' => 'Project Management', 'description' => 'Manage project team members'],

            // Permission Management
            ['name' => 'view permissions', 'category' => 'Permission Management', 'description' => 'View roles and permissions'],
            ['name' => 'create permissions', 'category' => 'Permission Management', 'description' => 'Create new permissions'],
            ['name' => 'edit permissions', 'category' => 'Permission Management', 'description' => 'Edit existing permissions'],
            ['name' => 'delete permissions', 'category' => 'Permission Management', 'description' => 'Delete permissions'],
            ['name' => 'assign permissions', 'category' => 'Permission Management', 'description' => 'Assign permissions to roles'],

            // System Settings
            ['name' => 'view system settings', 'category' => 'System Settings', 'description' => 'View system configuration'],
            ['name' => 'edit system settings', 'category' => 'System Settings', 'description' => 'Modify system settings'],
            ['name' => 'manage backups', 'category' => 'System Settings', 'description' => 'Create and manage system backups'],
            ['name' => 'view logs', 'category' => 'System Settings', 'description' => 'Access system logs'],
            ['name' => 'manage integrations', 'category' => 'System Settings', 'description' => 'Configure external integrations'],

            // Billing & Finance
            ['name' => 'view billing', 'category' => 'Billing & Finance', 'description' => 'View billing information and invoices'],
            ['name' => 'manage billing', 'category' => 'Billing & Finance', 'description' => 'Manage billing settings and payments'],
            ['name' => 'view financial reports', 'category' => 'Billing & Finance', 'description' => 'Access financial reports and analytics'],
            ['name' => 'manage subscriptions', 'category' => 'Billing & Finance', 'description' => 'Manage subscription plans and features'],

            // Analytics & Reports
            ['name' => 'view analytics', 'category' => 'Analytics & Reports', 'description' => 'View system analytics and metrics'],
            ['name' => 'export data', 'category' => 'Analytics & Reports', 'description' => 'Export system data and reports'],
            ['name' => 'view audit logs', 'category' => 'Analytics & Reports', 'description' => 'Access audit trail and activity logs'],
            ['name' => 'create custom reports', 'category' => 'Analytics & Reports', 'description' => 'Create and customize reports'],

            // Content Management
            ['name' => 'manage content', 'category' => 'Content Management', 'description' => 'Create and edit content'],
            ['name' => 'publish content', 'category' => 'Content Management', 'description' => 'Publish and unpublish content'],
            ['name' => 'moderate content', 'category' => 'Content Management', 'description' => 'Review and moderate user-generated content'],
            ['name' => 'manage media', 'category' => 'Content Management', 'description' => 'Upload and manage media files'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                [
                    'name' => $permissionData['name'],
                    'guard_name' => 'web'
                ],
                [
                    'category' => $permissionData['category'],
                    'description' => $permissionData['description']
                ]
            );
        }

        // Create roles and assign permissions
        $rolePermissions = [
            'platform_admin' => [
                'description' => 'Full system access with all permissions',
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            'organization_admin' => [
                'description' => 'Organization-level administration',
                'permissions' => [
                    'view users', 'create users', 'edit users',
                    'view organizations', 'edit organizations',
                    'view projects', 'create projects', 'edit projects', 'delete projects',
                    'manage project members', 'manage organization members',
                    'view billing', 'manage billing', 'manage subscriptions',
                    'view analytics', 'export data', 'view audit logs',
                    'manage content', 'publish content', 'manage media'
                ]
            ],
            'organization_member' => [
                'description' => 'Basic organization member access',
                'permissions' => [
                    'view organizations', 'view projects', 'manage content',
                    'view analytics'
                ]
            ],
            'project_manager' => [
                'description' => 'Project-level management',
                'permissions' => [
                    'view projects', 'edit projects', 'manage project members',
                    'manage content', 'publish content', 'view analytics'
                ]
            ],
            'editor' => [
                'description' => 'Content creation and editing',
                'permissions' => [
                    'manage content', 'publish content', 'manage media'
                ]
            ],
            'viewer' => [
                'description' => 'Read-only access',
                'permissions' => [
                    'view organizations', 'view projects', 'view analytics'
                ]
            ]
        ];

        foreach ($rolePermissions as $roleName => $roleData) {
            $role = Role::firstOrCreate(
                [
                    'name' => $roleName,
                    'guard_name' => 'web'
                ],
                [
                    'description' => $roleData['description']
                ]
            );

            // Clear existing permissions and assign new ones
            $role->syncPermissions([]);

            foreach ($roleData['permissions'] as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Created ' . Permission::count() . ' permissions');
        $this->command->info('Created ' . Role::count() . ' roles');
    }
}