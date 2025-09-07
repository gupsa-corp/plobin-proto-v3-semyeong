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
        Schema::create('sandbox_sql_executions', function (Blueprint $table) {
            $table->id();
            $table->string('sandbox_name')->comment('샌드박스 이름 (storage-sandbox-1)');
            $table->longText('sql_query')->comment('실행된 SQL 쿼리');
            $table->enum('query_type', ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'CREATE', 'DROP', 'ALTER', 'OTHER'])->comment('쿼리 타입');
            $table->enum('status', ['success', 'error'])->comment('실행 상태');
            $table->longText('result')->nullable()->comment('실행 결과 (JSON)');
            $table->text('error_message')->nullable()->comment('에러 메시지');
            $table->integer('affected_rows')->nullable()->comment('영향받은 행 수');
            $table->integer('execution_time_ms')->nullable()->comment('실행 시간 (밀리초)');
            $table->string('user_session_id')->comment('사용자 세션 ID');
            $table->timestamps();
            
            $table->index(['sandbox_name', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('query_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandbox_sql_executions');
    }
};
