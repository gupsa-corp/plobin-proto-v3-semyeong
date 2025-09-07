<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OrganizationPermissionService;
use Database\Seeders\OrganizationPermissionMigrationSeeder;

class MigrateOrganizationPermissions extends Command
{
    protected $signature = 'permission:migrate-organization 
                            {--dry-run : Show what would be migrated without making changes}
                            {--force : Force the operation to run in production}';

    protected $description = 'Migrate OrganizationPermission enum to Spatie Laravel Permission system';

    public function handle(): int
    {
        if ($this->option('dry-run')) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            return $this->performDryRun();
        }

        if (app()->environment('production') && !$this->option('force')) {
            $this->error('⚠️  This command is potentially destructive.');
            $this->error('Use --force to run in production environment.');
            return 1;
        }

        $this->info('🚀 Starting OrganizationPermission migration...');

        try {
            // 1. 권한과 역할 시드 데이터 생성
            $this->info('📝 Creating roles and permissions...');
            $seeder = new OrganizationPermissionMigrationSeeder();
            $seeder->run();
            $this->info('✅ Roles and permissions created successfully');

            // 2. 기존 조직 멤버들의 권한을 새로운 시스템으로 마이그레이션
            $this->info('👥 Migrating organization members...');
            $this->migrateOrganizationMembers();
            $this->info('✅ Organization members migrated successfully');

            // 3. 마이그레이션 완료 보고서
            $this->displayMigrationReport();

            $this->info('🎉 Migration completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Migration failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    private function performDryRun(): int
    {
        $this->info('📊 Analyzing current organization members...');
        
        $members = \App\Models\OrganizationMember::with(['user', 'organization'])->get();
        $enumCounts = [];
        $roleMapping = [];

        foreach ($members as $member) {
            $enumValue = $member->permission_level;
            $roleName = OrganizationPermissionService::enumToRole($enumValue);
            
            if (!isset($enumCounts[$enumValue])) {
                $enumCounts[$enumValue] = 0;
            }
            $enumCounts[$enumValue]++;
            
            if ($roleName) {
                if (!isset($roleMapping[$roleName])) {
                    $roleMapping[$roleName] = 0;
                }
                $roleMapping[$roleName]++;
            }
        }

        $this->info('📈 Current permission distribution:');
        $this->table(
            ['Enum Value', 'Enum Label', 'Count', 'New Role Name'],
            collect($enumCounts)->map(function ($count, $enumValue) {
                $enumLabel = $this->getEnumLabel($enumValue);
                $roleName = OrganizationPermissionService::enumToRole($enumValue) ?? 'No role';
                return [$enumValue, $enumLabel, $count, $roleName];
            })->values()->toArray()
        );

        $this->info('🎯 Roles to be created/assigned:');
        $this->table(
            ['Role Name', 'Users Count'],
            collect($roleMapping)->map(function ($count, $roleName) {
                return [$roleName, $count];
            })->toArray()
        );

        $this->info('ℹ️  Run without --dry-run to perform the actual migration');
        return 0;
    }

    private function migrateOrganizationMembers(): void
    {
        $members = \App\Models\OrganizationMember::with('user')->get();
        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($members as $member) {
            try {
                $roleName = OrganizationPermissionService::enumToRole($member->permission_level);
                
                if ($roleName && $member->user) {
                    // 사용자에게 역할이 없으면 할당
                    if (!$member->user->hasRole($roleName)) {
                        $member->user->assignRole($roleName);
                        $migrated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    $skipped++;
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $errors++;
                $this->error("\n❌ Error migrating member {$member->id}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        
        $this->newLine(2);
        $this->info("📊 Migration Statistics:");
        $this->info("   - Migrated: {$migrated} users");
        $this->info("   - Skipped: {$skipped} users");
        $this->info("   - Errors: {$errors} users");
    }

    private function displayMigrationReport(): void
    {
        $this->info('📋 Migration Report:');
        
        // 역할 통계
        $roles = \Spatie\Permission\Models\Role::withCount('users')->get();
        $this->table(
            ['Role Name', 'Display Name', 'Users Count'],
            $roles->map(function ($role) {
                $displayInfo = OrganizationPermissionService::getRoleDisplayInfo($role->name);
                return [$role->name, $displayInfo['label'], $role->users_count];
            })->toArray()
        );

        // 권한 통계
        $permissionCount = \Spatie\Permission\Models\Permission::count();
        $this->info("📝 Total permissions created: {$permissionCount}");
        
        // 카테고리별 권한 통계
        $categories = \App\Models\PermissionCategory::withCount('permissions')->get();
        if ($categories->count() > 0) {
            $this->table(
                ['Category', 'Display Name', 'Permissions Count'],
                $categories->map(function ($category) {
                    return [$category->name, $category->display_name, $category->permissions_count ?? 0];
                })->toArray()
            );
        }
    }

    private function getEnumLabel(int $enumValue): string
    {
        return match($enumValue) {
            0 => 'INVITED',
            100 => 'USER',
            150 => 'USER_ADVANCED',
            200 => 'SERVICE_MANAGER',
            250 => 'SERVICE_MANAGER_SENIOR',
            300 => 'ORGANIZATION_ADMIN',
            350 => 'ORGANIZATION_ADMIN_SENIOR',
            400 => 'ORGANIZATION_OWNER',
            450 => 'ORGANIZATION_OWNER_FOUNDER',
            500 => 'PLATFORM_ADMIN',
            550 => 'PLATFORM_ADMIN_SUPER',
            default => 'UNKNOWN',
        };
    }
}