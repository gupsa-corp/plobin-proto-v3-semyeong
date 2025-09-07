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
        Schema::create('dynamic_permission_rules', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type'); // 리소스 타입 (member_management, project_management)
            $table->string('action'); // 액션 (view, create, edit, delete, invite)
            $table->json('required_permissions')->nullable(); // 필요한 권한들 JSON 배열
            $table->json('required_roles')->nullable(); // 필요한 역할들 JSON 배열  
            $table->integer('minimum_role_level')->nullable(); // 최소 역할 레벨
            $table->text('custom_logic')->nullable(); // 커스텀 로직 (PHP 코드)
            $table->text('description')->nullable(); // 규칙 설명
            $table->boolean('is_active')->default(true); // 활성화 상태
            $table->timestamps();
            
            $table->unique(['resource_type', 'action']); // 리소스-액션 조합 유니크
            $table->index(['resource_type', 'is_active']); // 성능을 위한 인덱스
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_permission_rules');
    }
};
