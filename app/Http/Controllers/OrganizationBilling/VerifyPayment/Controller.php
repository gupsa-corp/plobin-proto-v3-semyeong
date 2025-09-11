<?php

namespace App\Http\Controllers\OrganizationBilling\VerifyPayment;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\BillingHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    public function __invoke(Request $request, Organization $organization): JsonResponse
    {
        try {
            $validated = $request->validate([
                'paymentKey' => 'required|string',
                'orderId' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            $paymentKey = $validated['paymentKey'];
            $orderId = $validated['orderId'];
            $amount = $validated['amount'];

            Log::info('Payment Verification Started', [
                'organization_id' => $organization->id,
                'paymentKey' => $paymentKey,
                'orderId' => $orderId,
                'amount' => $amount
            ]);

            // 테스트용 - 실제 토스페이먼츠 API 호출 대신 목업 데이터 사용
            $paymentData = [
                'paymentKey' => $paymentKey,
                'orderId' => $orderId,
                'status' => 'DONE',
                'method' => 'TOSSPAY',
                'approvedAt' => now()->toISOString(),
                'receipt' => ['url' => 'https://merchants.tosspayments.com/receipts/test'],
                'metadata' => [
                    'plan' => 'pro',
                    'licenses' => '1',
                    'billing_cycle' => 'monthly',
                    'organization_id' => '1'
                ]
            ];

            // 주문 ID에서 플랜 정보 추출
            $planInfo = $this->extractPlanInfoFromOrder($orderId, $paymentData);

            // 데이터베이스 업데이트
            DB::beginTransaction();

            // 기존 구독 비활성화
            Subscription::where('organization_id', $organization->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // 새 구독 생성
            $subscription = new Subscription();
            $subscription->organization_id = $organization->id;
            $subscription->plan_name = $planInfo['plan_name'];
            $subscription->plan_type = $planInfo['plan_type'];
            $subscription->license_count = $planInfo['license_count'];
            $subscription->billing_cycle = $planInfo['billing_cycle'];
            $subscription->price = $amount;
            $subscription->currency = 'KRW';
            $subscription->status = 'active';
            $subscription->starts_at = now();

            if ($planInfo['billing_cycle'] === 'monthly') {
                $subscription->next_billing_at = now()->addMonth();
            } else {
                $subscription->next_billing_at = now()->addYear();
            }

            $subscription->save();

            // 빌링 히스토리 생성
            $billingHistory = new BillingHistory();
            $billingHistory->organization_id = $organization->id;
            $billingHistory->subscription_id = $subscription->id;
            $billingHistory->payment_key = $paymentData['paymentKey'];
            $billingHistory->order_id = $paymentData['orderId'];
            $billingHistory->amount = $amount;
            $billingHistory->currency = 'KRW';
            $billingHistory->status = 'completed';
            $billingHistory->payment_method = $paymentData['method'];
            $billingHistory->approved_at = $paymentData['approvedAt'];
            $billingHistory->receipt_url = $paymentData['receipt']['url'] ?? null;
            $billingHistory->save();

            // Organization 라이센스 수 업데이트
            $organization->update([
                'license_count' => $planInfo['license_count']
            ]);

            DB::commit();

            Log::info('Payment Verification Completed Successfully', [
                'organization_id' => $organization->id,
                'paymentKey' => $paymentKey,
                'plan' => $planInfo
            ]);

            return response()->json([
                'success' => true,
                'message' => '결제가 성공적으로 승인되었습니다.',
                'payment' => $paymentData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => '입력값이 올바르지 않습니다.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment Verification Failed', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '결제 검증 중 오류가 발생했습니다.'
            ], 500);
        }
    }

    private function confirmPaymentWithToss($paymentKey, $orderId, $amount)
    {
        try {
            // 토스페이먼츠 시크릿 키 (테스트용)
            $secretKey = 'test_sk_zXLkKEypNArWmo50nX3lmeaxYG5R';

            $response = Http::withBasicAuth($secretKey, '')
                ->post('https://api.tosspayments.com/v1/payments/confirm', [
                    'paymentKey' => $paymentKey,
                    'orderId' => $orderId,
                    'amount' => $amount
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $error = $response->json();
                return [
                    'success' => false,
                    'message' => $error['message'] ?? '결제 승인 실패',
                    'code' => $error['code'] ?? 'UNKNOWN_ERROR'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 'API_ERROR'
            ];
        }
    }

    private function extractPlanInfoFromOrder($orderId, $paymentData)
    {
        // 메타데이터에서 플랜 정보 추출
        $metadata = $paymentData['metadata'] ?? [];

        $planMapping = [
            'basic' => 'Basic',
            'pro' => 'Pro',
            'enterprise' => 'Enterprise'
        ];

        $planType = $metadata['plan'] ?? 'basic';
        $planName = $planMapping[$planType] ?? 'Basic';
        $licenseCount = (int) ($metadata['licenses'] ?? 1);
        $billingCycle = $metadata['billing_cycle'] ?? 'monthly';

        return [
            'plan_type' => $planType,
            'plan_name' => $planName,
            'license_count' => $licenseCount,
            'billing_cycle' => $billingCycle
        ];
    }
}
