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
        Schema::create('permission_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 영문 키 (member_management, project_management)
            $table->string('display_name'); // 한국어 표시명 (회원 관리, 프로젝트 관리)
            $table->text('description')->nullable(); // 카테고리 설명
            $table->integer('sort_order')->default(0); // 정렬 순서
            $table->boolean('is_active')->default(true); // 활성화 상태
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_categories');
    }
};
