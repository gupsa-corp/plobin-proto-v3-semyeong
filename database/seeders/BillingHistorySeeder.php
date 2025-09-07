<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BillingHistory;
use App\Models\Subscription;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BillingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 결제 내역 삭제
        BillingHistory::truncate();

        // 조직 1의 Pro 플랜 구독에 대한 결제 내역 생성
        $organization = Organization::first();
        $subscription = Subscription::where('organization_id', $organization->id)
                                  ->where('plan_name', 'pro')
                                  ->first();

        if ($subscription) {
            // 3개월치 성공한 결제 내역 생성
            $paymentDates = [
                Carbon::create(2024, 3, 15),
                Carbon::create(2024, 2, 15),
                Carbon::create(2024, 1, 15),
            ];

            foreach ($paymentDates as $index => $date) {
                $paymentKey = 'payment_key_' . $date->format('Ymd') . '_' . Str::random(10);
                $orderId = 'ORDER_' . $date->format('Ymd') . '_' . sprintf('%03d', $index + 1);
                
                BillingHistory::create([
                    'organization_id' => $organization->id,
                    'subscription_id' => $subscription->id,
                    'payment_key' => $paymentKey,
                    'order_id' => $orderId,
                    'description' => 'Pro 플랜 월간 구독',
                    'amount' => 99000,
                    'vat' => 9000,
                    'status' => 'DONE',
                    'method' => '카드',
                    'requested_at' => $date,
                    'approved_at' => $date->copy()->addMinutes(2),
                    'toss_response' => [
                        'paymentKey' => $paymentKey,
                        'type' => 'BILLING',
                        'orderId' => $orderId,
                        'orderName' => 'Pro 플랜 월간 구독',
                        'mId' => 'tvivarepublic',
                        'currency' => 'KRW',
                        'method' => '카드',
                        'totalAmount' => 99000,
                        'balanceAmount' => 99000,
                        'status' => 'DONE',
                        'requestedAt' => $date->toISOString(),
                        'approvedAt' => $date->copy()->addMinutes(2)->toISOString(),
                        'useEscrow' => false,
                        'lastTransactionKey' => null,
                        'suppliedAmount' => 90000,
                        'vat' => 9000,
                        'cultureExpense' => false,
                        'taxFreeAmount' => 0,
                        'taxExemptionAmount' => 0,
                        'card' => [
                            'amount' => 99000,
                            'issuerCode' => '361',
                            'issuerName' => 'BC카드',
                            'acquirerCode' => '361',
                            'acquirerName' => 'BC카드',
                            'number' => '433012******1234',
                            'installmentPlanMonths' => 0,
                            'approveNo' => '00000000',
                            'useCardPoint' => false,
                            'cardType' => '체크',
                            'ownerType' => '개인',
                            'acquireStatus' => 'READY',
                            'receiptUrl' => "https://dashboard.tosspayments.com/receipt/redirection?transactionId={$paymentKey}"
                        ]
                    ],
                    'receipt_url' => "https://dashboard.tosspayments.com/receipt/redirection?transactionId={$paymentKey}",
                    'card_number' => '433012******1234',
                    'card_company' => 'BC카드',
                ]);
            }

            $this->command->info("✅ Pro 플랜 3개월 결제 내역 생성 완료");
        }

        // 다른 조직들의 결제 내역도 생성
        $this->createOtherOrganizationPayments();
    }

    /**
     * 다른 조직들의 결제 내역 생성
     */
    private function createOtherOrganizationPayments(): void
    {
        // 스타터 조직의 결제 내역
        $starterOrg = Organization::where('name', '스타터 조직')->first();
        $starterSubscription = Subscription::where('organization_id', $starterOrg->id)->first();

        if ($starterSubscription) {
            $paymentKey = 'payment_key_starter_' . Str::random(10);
            $orderId = 'ORDER_STARTER_' . date('Ymd') . '_001';
            
            BillingHistory::create([
                'organization_id' => $starterOrg->id,
                'subscription_id' => $starterSubscription->id,
                'payment_key' => $paymentKey,
                'order_id' => $orderId,
                'description' => 'Starter 플랜 월간 구독',
                'amount' => 29000,
                'vat' => 2636,
                'status' => 'DONE',
                'method' => '카드',
                'requested_at' => Carbon::now()->startOfMonth(),
                'approved_at' => Carbon::now()->startOfMonth()->addMinutes(1),
                'receipt_url' => "https://dashboard.tosspayments.com/receipt/redirection?transactionId={$paymentKey}",
                'card_number' => '433012******5678',
                'card_company' => 'VISA',
                'toss_response' => [
                    'paymentKey' => $paymentKey,
                    'orderId' => $orderId,
                    'status' => 'DONE',
                    'totalAmount' => 29000,
                    'method' => '카드'
                ]
            ]);
        }

        // 취소된 조직의 마지막 성공 결제와 환불 내역
        $cancelledOrg = Organization::where('name', '취소된 조직')->first();
        $cancelledSubscription = Subscription::where('organization_id', $cancelledOrg->id)->first();

        if ($cancelledSubscription) {
            // 마지막 성공 결제
            $paymentKey = 'payment_key_cancelled_' . Str::random(10);
            $orderId = 'ORDER_CANCELLED_' . date('Ymd') . '_001';
            
            BillingHistory::create([
                'organization_id' => $cancelledOrg->id,
                'subscription_id' => $cancelledSubscription->id,
                'payment_key' => $paymentKey,
                'order_id' => $orderId,
                'description' => 'Business 플랜 월간 구독 (취소 전 마지막 결제)',
                'amount' => 99000,
                'vat' => 9000,
                'status' => 'DONE',
                'method' => '카드',
                'requested_at' => Carbon::now()->subMonth()->startOfMonth(),
                'approved_at' => Carbon::now()->subMonth()->startOfMonth()->addMinutes(2),
                'receipt_url' => "https://dashboard.tosspayments.com/receipt/redirection?transactionId={$paymentKey}",
                'card_number' => '433012******9012',
                'card_company' => '삼성카드',
                'toss_response' => [
                    'paymentKey' => $paymentKey,
                    'orderId' => $orderId,
                    'status' => 'DONE',
                    'totalAmount' => 99000,
                    'method' => '카드'
                ]
            ]);

            // 부분 환불 내역
            $refundKey = 'refund_key_cancelled_' . Str::random(10);
            $refundOrderId = 'REFUND_CANCELLED_' . date('Ymd') . '_001';
            
            BillingHistory::create([
                'organization_id' => $cancelledOrg->id,
                'subscription_id' => $cancelledSubscription->id,
                'payment_key' => $refundKey,
                'order_id' => $refundOrderId,
                'description' => 'Business 플랜 중도 해지 환불',
                'amount' => -49500, // 부분 환불
                'vat' => -4500,
                'status' => 'PARTIAL_CANCELED',
                'method' => '카드',
                'requested_at' => Carbon::now()->subWeek(),
                'approved_at' => Carbon::now()->subWeek()->addHours(1),
                'receipt_url' => "https://dashboard.tosspayments.com/receipt/redirection?transactionId={$refundKey}",
                'card_number' => '433012******9012',
                'card_company' => '삼성카드',
                'toss_response' => [
                    'paymentKey' => $refundKey,
                    'orderId' => $refundOrderId,
                    'status' => 'PARTIAL_CANCELED',
                    'totalAmount' => -49500,
                    'method' => '카드'
                ]
            ]);
        }

        $this->command->info("✅ 추가 조직 결제 내역 생성 완료");
        $this->command->info("   - 스타터 조직: 1건 성공 결제");
        $this->command->info("   - 취소된 조직: 1건 성공 + 1건 부분환불");
    }
}