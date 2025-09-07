<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class SpatiePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // 캐시 초기화
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Starting permission system initialization...');

        // 1. 권한 카테고리 생성
        $this->call(PermissionCategorySeeder::class);

        // 2. 권한 생성
        $this->call(PermissionSeeder::class);

        // 3. 역할 생성 및 권한 할당
        $this->call(RoleSeeder::class);

        // 4. 동적 권한 규칙 생성
        $this->call(DynamicPermissionRuleSeeder::class);

        // 5. 권한 템플릿 생성
        $this->call(PermissionTemplateSeeder::class);

        $this->command->info('Spatie permission system initialized successfully!');
        $this->command->info('Includes support for:');
        $this->command->info('- Project and page management permissions');
        $this->command->info('- Public access permissions for guest users');
        $this->command->info('- Enhanced role hierarchy');
    }
}
