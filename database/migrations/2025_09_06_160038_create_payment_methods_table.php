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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('billing_key', 200)->unique(); // Toss Payments 빌링키
            $table->string('method_type', 20); // 'card', 'account' 등
            $table->string('card_company', 50)->nullable(); // 카드사
            $table->string('card_number', 20)->nullable(); // 카드 번호 (마스킹된)
            $table->string('card_type', 20)->nullable(); // 'credit', 'debit', 'prepaid'
            $table->string('expiry_month', 2)->nullable(); // 만료 월
            $table->string('expiry_year', 4)->nullable(); // 만료 년
            $table->boolean('is_default')->default(false); // 기본 결제 수단 여부
            $table->boolean('is_active')->default(true); // 활성 상태
            $table->integer('priority')->default(1); // 결제 우선순위
            $table->json('toss_response')->nullable(); // Toss Payments 응답
            $table->timestamps();
            
            $table->index(['organization_id', 'is_default']);
            $table->index(['organization_id', 'priority']);
            $table->index('billing_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
