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
        Schema::create('query_logs', function (Blueprint $table) {
            $table->id();
            $table->text('query'); // 실행된 SQL 쿼리
            $table->json('bindings')->nullable(); // 바인딩 파라미터들
            $table->decimal('execution_time', 8, 3); // 실행 시간 (밀리초)
            $table->string('connection')->nullable(); // 데이터베이스 커넥션명
            $table->string('file')->nullable(); // 실행된 파일 경로
            $table->integer('line')->nullable(); // 실행된 라인 번호
            $table->text('stack_trace')->nullable(); // 스택 트레이스
            $table->string('user_id')->nullable(); // 실행한 사용자 ID
            $table->string('session_id')->nullable(); // 세션 ID
            $table->string('ip_address', 45)->nullable(); // IP 주소
            $table->string('request_method', 10)->nullable(); // HTTP 메소드
            $table->text('request_url')->nullable(); // 요청 URL
            $table->timestamps();
            
            $table->index(['created_at']);
            $table->index(['execution_time']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('query_logs');
    }
};
