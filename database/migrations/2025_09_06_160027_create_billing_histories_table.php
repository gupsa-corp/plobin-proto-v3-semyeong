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
        Schema::create('billing_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->string('payment_key', 200)->unique(); // Toss Payments paymentKey
            $table->string('order_id', 100)->unique(); // 주문 ID
            $table->string('description', 500); // 결제 설명
            $table->integer('amount'); // 결제 금액 (원 단위)
            $table->integer('vat')->nullable(); // 부가세
            $table->string('status', 30); // 'READY', 'IN_PROGRESS', 'WAITING_FOR_DEPOSIT', 'DONE', 'CANCELED', 'PARTIAL_CANCELED', 'ABORTED', 'EXPIRED'
            $table->string('method', 50)->nullable(); // 결제 수단 ('카드', '가상계좌', '간편결제' 등)
            $table->timestamp('requested_at'); // 결제 요청 시각
            $table->timestamp('approved_at')->nullable(); // 결제 승인 시각
            $table->json('toss_response')->nullable(); // Toss Payments API 응답 전체
            $table->string('receipt_url', 500)->nullable(); // 영수증 URL
            $table->string('card_number', 20)->nullable(); // 카드 번호 (마스킹된)
            $table->string('card_company', 50)->nullable(); // 카드사
            $table->timestamps();
            
            $table->index(['organization_id', 'status']);
            $table->index('payment_key');
            $table->index('order_id');
            $table->index('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_histories');
    }
};
