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
        Schema::create('sandbox_copy_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // 어떤 프로젝트에서 일어난 복사인지
            $table->enum('source_type', ['template', 'sandbox']); // 복사 소스 타입
            $table->unsignedBigInteger('source_id'); // 소스 ID (템플릿 또는 샌드박스)
            $table->string('source_name', 100); // 소스 이름
            $table->string('target_name', 100); // 생성된 샌드박스 이름
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // 복사를 실행한 사용자
            $table->enum('status', ['success', 'failed', 'in_progress'])->default('in_progress'); // 복사 상태
            $table->text('error_message')->nullable(); // 실패 시 에러 메시지
            $table->timestamps();
            
            // 인덱스 추가
            $table->index(['project_id', 'created_by']);
            $table->index(['source_type', 'source_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandbox_copy_logs');
    }
};
