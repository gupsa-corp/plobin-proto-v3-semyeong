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
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('permission_categories')->onDelete('cascade');
            $table->string('name'); // 그룹명 (view, manage, admin)
            $table->string('display_name'); // 표시명 (조회, 관리, 관리자)
            $table->text('description')->nullable(); // 그룹 설명
            $table->integer('sort_order')->default(0); // 정렬 순서
            $table->timestamps();
            
            $table->unique(['category_id', 'name']); // 카테고리 내에서 그룹명 유니크
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_groups');
    }
};
