<?php

namespace App\Http\CoreApi\OrganizationBilling\ChangePlan;

use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Controller extends \App\Http\CoreApi\Controller
{
    public function changePlan(Request $request, Organization $organization): JsonResponse
    {
        try {
            $validated = $request->validate([
                'plan' => ['required', 'string', Rule::in(['basic', 'pro', 'enterprise'])],
                'licenses' => ['required', 'integer', 'min:1', 'max:10000'],
                'billing_cycle' => ['required', 'string', Rule::in(['monthly', 'yearly'])],
            ]);

            // 플랜 가격 정보
            $planPricing = [
                'basic' => ['monthly' => 10000, 'name' => 'Basic'],
                'pro' => ['monthly' => 20000, 'name' => 'Pro'],
                'enterprise' => ['monthly' => 50000, 'name' => 'Enterprise']
            ];

            $plan = $validated['plan'];
            $licenses = $validated['licenses'];
            $billingCycle = $validated['billing_cycle'];

            // 가격 계산
            $monthlyPrice = $planPricing[$plan]['monthly'];
            $licensePrice = $monthlyPrice * $licenses;

            if ($billingCycle === 'yearly') {
                $totalPrice = $licensePrice * 12 * 0.9; // 10% 할인
            } else {
                $totalPrice = $licensePrice;
            }

            DB::transaction(function () use ($organization, $plan, $licenses, $billingCycle, $totalPrice, $planPricing) {
                // 기존 구독 비활성화
                Subscription::where('organization_id', $organization->id)
                    ->where('status', 'active')
                    ->update(['status' => 'cancelled']);

                // 새 구독 생성
                $subscription = new Subscription();
                $subscription->organization_id = $organization->id;
                $subscription->plan_name = $planPricing[$plan]['name'];
                $subscription->plan_type = $plan;
                $subscription->license_count = $licenses;
                $subscription->billing_cycle = $billingCycle;
                $subscription->price = $totalPrice;
                $subscription->currency = 'KRW';
                $subscription->status = 'active';
                $subscription->starts_at = now();

                if ($billingCycle === 'monthly') {
                    $subscription->next_billing_at = now()->addMonth();
                } else {
                    $subscription->next_billing_at = now()->addYear();
                }

                $subscription->save();

                // Organization 라이센스 수 업데이트
                $organization->update([
                    'license_count' => $licenses
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => '플랜이 성공적으로 변경되었습니다.',
                'data' => [
                    'plan' => $planPricing[$plan]['name'],
                    'licenses' => $licenses,
                    'billing_cycle' => $billingCycle,
                    'total_price' => $totalPrice,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => '입력값이 올바르지 않습니다.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Plan change failed', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '플랜 변경 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.'
            ], 500);
        }
    }
}
