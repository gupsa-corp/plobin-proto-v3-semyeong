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
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->string('original_name', 255); // 원본 파일명
            $table->string('stored_name', 255); // 저장된 파일명 (타임스탬프 포함)
            $table->string('file_path', 500); // 저장 경로
            $table->bigInteger('file_size'); // 파일 크기 (bytes)
            $table->string('mime_type', 100); // MIME 타입
            $table->timestamp('uploaded_at'); // 업로드 시간
            $table->unsignedBigInteger('user_id')->nullable(); // 업로드한 사용자 ID
            $table->timestamps(); // created_at, updated_at

            // 인덱스
            $table->index('user_id');
            $table->index('uploaded_at');
            $table->index('mime_type');
            $table->index('file_size');

            // 외래 키 제약조건
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_files');
    }
};
