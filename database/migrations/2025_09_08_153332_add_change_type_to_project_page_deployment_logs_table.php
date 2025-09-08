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
        Schema::table('project_page_deployment_logs', function (Blueprint $table) {
            $table->string('change_type')->default('deployment')->after('project_page_id'); // deployment, permission, content, name
            $table->json('change_data')->nullable()->after('reason'); // 변경된 데이터의 상세 정보
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_page_deployment_logs', function (Blueprint $table) {
            $table->dropColumn(['change_type', 'change_data']);
        });
    }
};
