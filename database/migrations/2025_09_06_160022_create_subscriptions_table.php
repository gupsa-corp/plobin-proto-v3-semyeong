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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('plan_name', 50); // 'starter', 'pro', 'enterprise' 등
            $table->string('status', 20)->default('active'); // 'active', 'cancelled', 'expired', 'pending'
            $table->integer('monthly_price'); // 월 가격 (원 단위)
            $table->integer('max_members')->nullable(); // 최대 멤버 수 (null이면 무제한)
            $table->integer('max_projects')->nullable(); // 최대 프로젝트 수
            $table->bigInteger('max_storage_gb')->nullable(); // 최대 스토리지 (GB)
            $table->timestamp('current_period_start');
            $table->timestamp('current_period_end');
            $table->timestamp('next_billing_date');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            
            $table->index(['organization_id', 'status']);
            $table->index('next_billing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
