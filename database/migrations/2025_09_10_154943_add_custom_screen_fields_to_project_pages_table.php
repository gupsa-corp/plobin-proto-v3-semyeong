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
            $table->boolean('custom_screen_enabled')->default(false)->after('sandbox_custom_screen_folder');
            $table->timestamp('custom_screen_applied_at')->nullable()->after('custom_screen_enabled');
            $table->string('template_path')->nullable()->after('custom_screen_applied_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_pages', function (Blueprint $table) {
            $table->dropColumn(['custom_screen_enabled', 'custom_screen_applied_at', 'template_path']);
        });
    }
};
