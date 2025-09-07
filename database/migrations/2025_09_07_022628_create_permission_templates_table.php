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
        Schema::create('permission_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 템플릿 키 (basic_user, admin, service_manager)
            $table->string('display_name'); // 표시명 (기본 사용자, 관리자, 서비스 매니저)
            $table->text('description')->nullable(); // 템플릿 설명
            $table->json('permissions_config'); // 권한 설정 JSON
            $table->boolean('is_active')->default(true); // 활성화 상태
            $table->integer('sort_order')->default(0); // 정렬 순서
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_templates');
    }
};
