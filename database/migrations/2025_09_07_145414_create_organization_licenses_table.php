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
        Schema::create('organization_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('license_type', 50); // 'basic', 'pro', 'enterprise', 'addon' 등
            $table->string('license_name', 100); // 'Pro Plan', 'Advanced Storage', 'Priority Support' 등
            $table->integer('quantity')->default(1); // 라이센스 개수 (멤버 수, 스토리지 용량 등)
            $table->string('unit_type', 20)->default('seat'); // 'seat', 'gb', 'feature' 등 단위
            $table->decimal('unit_price', 10, 2); // 단위당 가격
            $table->decimal('total_price', 10, 2); // 총 가격
            $table->string('billing_cycle', 20)->default('monthly'); // 'monthly', 'yearly', 'one-time'
            $table->datetime('starts_at'); // 라이센스 시작일
            $table->datetime('expires_at')->nullable(); // 라이센스 만료일 (nullable for perpetual licenses)
            $table->boolean('auto_renew')->default(true); // 자동 갱신 여부
            $table->string('status', 20)->default('active'); // 'active', 'suspended', 'cancelled', 'expired'
            $table->datetime('purchased_at'); // 구매일시
            $table->datetime('cancelled_at')->nullable(); // 취소일시
            $table->text('cancellation_reason')->nullable(); // 취소 사유
            $table->json('metadata')->nullable(); // 추가 메타데이터 (특성, 제한사항 등)
            $table->timestamps();
            
            $table->index(['organization_id', 'status']);
            $table->index(['organization_id', 'license_type']);
            $table->index('expires_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_licenses');
    }
};
