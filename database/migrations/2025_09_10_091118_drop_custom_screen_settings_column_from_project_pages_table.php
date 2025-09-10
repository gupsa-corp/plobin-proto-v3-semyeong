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
        Schema::table('project_pages', function (Blueprint $table) {
            // 호환성 무시하고 기존 custom_screen_settings 컬럼 완전 제거
            $table->dropColumn('custom_screen_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_pages', function (Blueprint $table) {
            // 롤백시 기존 컬럼 복원
            $table->json('custom_screen_settings')->nullable()->after('sandbox_name');
        });
    }
};
