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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // 플랜명 (예: 'Starter', 'Pro', 'Enterprise')
            $table->string('slug', 50)->unique(); // URL 친화적 식별자 (예: 'starter', 'pro', 'enterprise')
            $table->text('description')->nullable(); // 플랜 설명
            
            // 플랜 타입: 'usage_based' (사용량 기반) 또는 'monthly' (월간 고정)
            $table->string('type', 20); 
            
            // 월간 고정 플랜 필드들
            $table->integer('monthly_price')->nullable(); // 월 가격 (원 단위)
            $table->integer('max_members')->nullable(); // 최대 멤버 수 (null이면 무제한)
            $table->integer('max_projects')->nullable(); // 최대 프로젝트 수 (null이면 무제한)
            $table->bigInteger('max_storage_gb')->nullable(); // 최대 스토리지 (GB, null이면 무제한)
            $table->integer('max_sheets')->nullable(); // 최대 시트 수 (monthly 플랜용, null이면 무제한)
            
            // 사용량 기반 플랜 필드들
            $table->integer('price_per_member')->nullable(); // 멤버당 가격 (원/월)
            $table->integer('price_per_project')->nullable(); // 프로젝트당 가격 (원/월) 
            $table->integer('price_per_gb')->nullable(); // GB당 가격 (원/월)
            $table->integer('price_per_sheet')->nullable(); // 시트당 가격 (원/월)
            
            // 무료 허용량 (사용량 기반 플랜용)
            $table->integer('free_members')->default(0); // 무료 멤버 수
            $table->integer('free_projects')->default(0); // 무료 프로젝트 수
            $table->bigInteger('free_storage_gb')->default(0); // 무료 스토리지 (GB)
            $table->integer('free_sheets')->default(0); // 무료 시트 수
            
            // 플랜 상태 및 메타데이터
            $table->boolean('is_active')->default(true); // 활성화 상태
            $table->boolean('is_featured')->default(false); // 추천 플랜 여부
            $table->integer('sort_order')->default(0); // 정렬 순서
            $table->json('features')->nullable(); // 플랜별 기능 목록 (JSON)
            
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
