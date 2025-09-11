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
        Schema::create('project_page_deployment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_page_id')->constrained()->onDelete('cascade');
            $table->string('change_type')->default('deployment'); // deployment, permission, content, name
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('from_status');
            $table->string('to_status');
            $table->text('reason')->nullable();
            $table->json('change_data')->nullable(); // 변경된 데이터의 상세 정보
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_page_deployment_logs');
    }
};
