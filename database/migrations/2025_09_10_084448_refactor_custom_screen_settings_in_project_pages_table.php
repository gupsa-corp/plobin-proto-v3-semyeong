<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 컬럼들이 이미 존재하므로 데이터 마이그레이션만 수행
        // 기존 JSON 데이터를 새로운 컬럼들로 마이그레이션
        DB::table('project_pages')
            ->whereNotNull('custom_screen_settings')
            ->orderBy('id')
            ->chunk(100, function ($pages) {
                foreach ($pages as $page) {
                    $settings = json_decode($page->custom_screen_settings, true);
                    
                    if (!$settings || !is_array($settings)) continue;
                    
                    $updateData = [
                        'custom_screen_id' => $settings['screen_id'] ?? null,
                        'custom_screen_type' => $settings['screen_type'] ?? 'template',
                        'custom_screen_enabled' => (bool)($settings['enabled'] ?? true),
                        'custom_screen_applied_at' => $settings['applied_at'] ?? null,
                        'template_path' => $settings['template_path'] ?? null,
                    ];
                    
                    // 나머지 설정값들은 custom_screen_config로 저장
                    $excludedKeys = ['screen_id', 'screen_type', 'enabled', 'applied_at', 'template_path'];
                    $config = array_diff_key($settings, array_flip($excludedKeys));
                    
                    if (!empty($config)) {
                        $updateData['custom_screen_config'] = json_encode($config);
                    }
                    
                    DB::table('project_pages')
                        ->where('id', $page->id)
                        ->update($updateData);
                }
            });

        // 3. 기존 JSON 컬럼 완전 제거
        Schema::table('project_pages', function (Blueprint $table) {
            $table->dropColumn('custom_screen_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_pages', function (Blueprint $table) {
            // 1. 기존 JSON 컬럼 복원
            $table->json('custom_screen_settings')->nullable()->after('sandbox_name');
        });

        // 2. 새로운 컬럼들을 기존 JSON 형태로 복원
        DB::table('project_pages')
            ->whereNotNull('custom_screen_id')
            ->orderBy('id')
            ->chunk(100, function ($pages) {
                foreach ($pages as $page) {
                    $settings = [
                        'screen_id' => $page->custom_screen_id,
                        'screen_type' => $page->custom_screen_type,
                        'enabled' => (bool)$page->custom_screen_enabled,
                        'applied_at' => $page->custom_screen_applied_at,
                        'template_path' => $page->template_path,
                    ];

                    if ($page->custom_screen_config) {
                        $config = json_decode($page->custom_screen_config, true);
                        if ($config) {
                            $settings = array_merge($settings, $config);
                        }
                    }

                    DB::table('project_pages')
                        ->where('id', $page->id)
                        ->update(['custom_screen_settings' => json_encode($settings)]);
                }
            });

        // 3. 새로운 컬럼들 제거
        Schema::table('project_pages', function (Blueprint $table) {
            $table->dropColumn([
                'custom_screen_id',
                'custom_screen_type', 
                'custom_screen_enabled',
                'custom_screen_applied_at',
                'custom_screen_config',
                'template_path'
            ]);
        });
    }
};
