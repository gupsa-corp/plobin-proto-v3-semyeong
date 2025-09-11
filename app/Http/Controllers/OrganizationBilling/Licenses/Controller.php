<?php

namespace App\Http\Controllers\OrganizationBilling\Licenses;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use App\Models\License;
use App\Models\LicenseUsage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class Controller extends BaseController
{
    /**
     * 조직의 라이센스 목록 조회
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 활성 라이센스 조회
        $licenses = $organization->licenses()
            ->with(['licenseType', 'usage'])
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        // 만료된 라이센스도 함께 조회 (최근 3개월)
        $expiredLicenses = $organization->licenses()
            ->with(['licenseType', 'usage'])
            ->where(function ($query) {
                $query->where('status', 'expired')
                      ->orWhere('expires_at', '<=', now());
            })
            ->where('created_at', '>=', now()->subMonths(3))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'active_licenses' => $licenses->map(function ($license) {
                    return [
                        'id' => $license->id,
                        'license_type' => [
                            'id' => $license->licenseType->id,
                            'name' => $license->licenseType->name,
                            'description' => $license->licenseType->description,
                            'category' => $license->licenseType->category,
                        ],
                        'quantity' => $license->quantity,
                        'used_quantity' => $license->getUsedQuantity(),
                        'remaining_quantity' => $license->getRemainingQuantity(),
                        'usage_percentage' => $license->getUsagePercentage(),
                        'monthly_price' => $license->monthly_price,
                        'formatted_price' => $license->getFormattedPrice(),
                        'purchased_at' => $license->purchased_at->format('Y.m.d'),
                        'expires_at' => $license->expires_at->format('Y.m.d'),
                        'days_remaining' => $license->getDaysRemaining(),
                        'status' => $license->status,
                        'is_active' => $license->isActive(),
                        'is_expiring_soon' => $license->isExpiringSoon(),
                    ];
                }),
                'expired_licenses' => $expiredLicenses->map(function ($license) {
                    return [
                        'id' => $license->id,
                        'license_type_name' => $license->licenseType->name,
                        'quantity' => $license->quantity,
                        'purchased_at' => $license->purchased_at->format('Y.m.d'),
                        'expires_at' => $license->expires_at->format('Y.m.d'),
                        'status' => $license->status,
                    ];
                }),
                'summary' => [
                    'total_active_licenses' => $licenses->count(),
                    'total_expired_licenses' => $expiredLicenses->count(),
                    'total_monthly_cost' => $licenses->sum('monthly_price'),
                    'total_licenses_quantity' => $licenses->sum('quantity'),
                    'total_used_quantity' => $licenses->sum(function ($license) {
                        return $license->getUsedQuantity();
                    }),
                ],
            ]
        ]);
    }

    /**
     * 라이센스 구매
     */
    public function purchase(Request $request, Organization $organization): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'license_type_id' => 'required|exists:license_types,id',
            'quantity' => 'required|integer|min:1|max:1000',
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 라이센스 타입 조회
        $licenseType = \App\Models\LicenseType::findOrFail($request->license_type_id);

        // 결제 금액 계산
        $monthlyPrice = $licenseType->monthly_price * $request->quantity;
        $totalPrice = $monthlyPrice * $request->duration_months;

        // 기본 결제 수단 확인
        $defaultPaymentMethod = $organization->paymentMethods()
            ->where('is_default', true)
            ->where('is_active', true)
            ->first();

        if (!$defaultPaymentMethod) {
            return response()->json([
                'success' => false,
                'message' => '기본 결제 수단을 먼저 등록해주세요.'
            ], 400);
        }

        try {
            // 라이센스 생성
            $license = $organization->licenses()->create([
                'license_type_id' => $request->license_type_id,
                'quantity' => $request->quantity,
                'monthly_price' => $monthlyPrice,
                'purchased_at' => now(),
                'expires_at' => now()->addMonths($request->duration_months),
                'status' => 'active',
            ]);

            // 결제 내역 생성 (시뮬레이션)
            $billingHistory = $organization->billingHistories()->create([
                'payment_key' => 'license_' . $license->id . '_' . time(),
                'order_id' => 'LICENSE_' . strtoupper(uniqid()),
                'description' => "{$licenseType->name} 라이센스 {$request->quantity}개 ({$request->duration_months}개월)",
                'amount' => $totalPrice,
                'status' => 'paid',
                'method' => 'card',
                'requested_at' => now(),
                'approved_at' => now(),
                'card_company' => $defaultPaymentMethod->card_company,
                'card_number' => $defaultPaymentMethod->card_number,
                'payment_gateway' => 'toss',
                'transaction_id' => 'txn_' . uniqid(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '라이센스가 성공적으로 구매되었습니다.',
                'data' => [
                    'license' => [
                        'id' => $license->id,
                        'license_type_name' => $licenseType->name,
                        'quantity' => $license->quantity,
                        'monthly_price' => $license->monthly_price,
                        'total_price' => $totalPrice,
                        'duration_months' => $request->duration_months,
                        'purchased_at' => $license->purchased_at->format('Y.m.d'),
                        'expires_at' => $license->expires_at->format('Y.m.d'),
                    ],
                    'payment' => [
                        'order_id' => $billingHistory->order_id,
                        'amount' => $billingHistory->amount,
                        'formatted_amount' => $billingHistory->getFormattedAmount(),
                        'method' => $billingHistory->method,
                        'approved_at' => $billingHistory->approved_at->format('Y.m.d H:i'),
                    ],
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('License purchase failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '라이센스 구매 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.'
            ], 500);
        }
    }

    /**
     * 라이센스 사용량 조회
     */
    public function usage(Request $request, Organization $organization): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // 활성 라이센스의 사용량 조회
        $licenses = $organization->licenses()
            ->with(['licenseType'])
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->get();

        $usageData = [];

        foreach ($licenses as $license) {
            // 해당 기간의 사용량 조회
            $usage = LicenseUsage::where('license_id', $license->id)
                ->whereBetween('usage_date', [$startDate, $endDate])
                ->selectRaw('DATE(usage_date) as date, SUM(quantity_used) as daily_usage')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $totalUsed = $usage->sum('daily_usage');

            $usageData[] = [
                'license' => [
                    'id' => $license->id,
                    'license_type_name' => $license->licenseType->name,
                    'license_type_category' => $license->licenseType->category,
                    'total_quantity' => $license->quantity,
                ],
                'usage_summary' => [
                    'total_used' => $totalUsed,
                    'remaining' => $license->quantity - $totalUsed,
                    'usage_percentage' => $license->quantity > 0 ? round(($totalUsed / $license->quantity) * 100, 2) : 0,
                ],
                'daily_usage' => $usage->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'usage' => $item->daily_usage,
                    ];
                }),
            ];
        }

        // 전체 사용량 요약
        $overallSummary = [
            'total_licenses' => $licenses->count(),
            'total_quantity' => $licenses->sum('quantity'),
            'total_used' => array_sum(array_column($usageData, 'usage_summary.total_used')),
            'total_monthly_cost' => $licenses->sum('monthly_price'),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'overall_summary' => $overallSummary,
                'licenses_usage' => $usageData,
            ]
        ]);
    }
}
