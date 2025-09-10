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
            $table->string('sandbox_name')->nullable()->after('sort_order'); // 샌드박스 타입 (none, template, 1, 2, etc.)
            $table->json('custom_screen_settings')->nullable()->after('sandbox_name'); // 커스텀 화면 설정
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_pages', function (Blueprint $table) {
            $table->dropColumn(['sandbox_name', 'custom_screen_settings']);
        });
    }
};
