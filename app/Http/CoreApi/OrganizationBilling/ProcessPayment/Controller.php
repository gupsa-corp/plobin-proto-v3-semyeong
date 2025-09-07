<?php

namespace App\Http\CoreApi\OrganizationBilling\ProcessPayment;

use App\Http\CoreApi\Controller as BaseController;
use App\Models\Organization;
use App\Models\BillingHistory;
use App\Services\TossPaymentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    public function __construct(
        private TossPaymentsService $tossService
    ) {}

    public function __invoke(Request $request, Organization $organization): JsonResponse
    {
        $paymentKey = $request->input('payment_key');
        $orderId = $request->input('order_id');
        $amount = $request->input('amount');

        try {
            DB::beginTransaction();

            // Toss Payments API로 결제 승인
            $paymentResult = $this->tossService->confirmPayment($paymentKey, $orderId, $amount);

            // 결제 내역 저장 또는 업데이트
            $billingHistory = BillingHistory::updateOrCreate(
                [
                    'payment_key' => $paymentKey,
                    'order_id' => $orderId,
                ],
                [
                    'organization_id' => $organization->id,
                    'subscription_id' => $organization->activeSubscription?->id,
                    'description' => $paymentResult['orderName'],
                    'amount' => $paymentResult['totalAmount'],
                    'vat' => $paymentResult['vat'] ?? round($paymentResult['totalAmount'] / 11),
                    'status' => $paymentResult['status'],
                    'method' => $paymentResult['method']['type'] ?? 'card',
                    'requested_at' => now(),
                    'approved_at' => $paymentResult['approvedAt'] ? new \DateTime($paymentResult['approvedAt']) : null,
                    'toss_response' => $paymentResult,
                    'receipt_url' => $paymentResult['receipt']['url'] ?? null,
                    'card_number' => $paymentResult['card']['number'] ?? null,
                    'card_company' => $paymentResult['card']['company'] ?? null,
                ]
            );

            // 결제 성공 시 구독 정보 업데이트 로직 (필요한 경우)
            if ($paymentResult['status'] === 'DONE' && $organization->activeSubscription) {
                $subscription = $organization->activeSubscription;

                // 다음 결제일 연장 등의 로직
                if (str_contains($orderId, 'subscription_')) {
                    $subscription->update([
                        'next_billing_date' => $subscription->next_billing_date->addMonth(),
                        'current_period_end' => $subscription->current_period_end->addMonth(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '결제가 성공적으로 완료되었습니다.',
                'data' => [
                    'payment_key' => $paymentKey,
                    'order_id' => $orderId,
                    'status' => $paymentResult['status'],
                    'amount' => $paymentResult['totalAmount'],
                    'approved_at' => $paymentResult['approvedAt'],
                    'receipt_url' => $paymentResult['receipt']['url'] ?? null,
                    'billing_history_id' => $billingHistory->id,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment processing failed', [
                'organization_id' => $organization->id,
                'payment_key' => $paymentKey,
                'order_id' => $orderId,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            // 실패한 결제 내역도 저장
            BillingHistory::updateOrCreate(
                [
                    'payment_key' => $paymentKey,
                    'order_id' => $orderId,
                ],
                [
                    'organization_id' => $organization->id,
                    'subscription_id' => $organization->activeSubscription?->id,
                    'description' => '결제 처리 실패',
                    'amount' => $amount,
                    'status' => 'ABORTED',
                    'method' => 'card',
                    'requested_at' => now(),
                    'toss_response' => ['error' => $e->getMessage()],
                ]
            );

            return response()->json([
                'success' => false,
                'message' => '결제 처리 중 오류가 발생했습니다.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
