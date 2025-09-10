<?php

namespace App\Http\Controllers\OrganizationBilling\GetBillingData;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function __invoke(Request $request, Organization $organization): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 구독 정보 조회
        $subscription = $organization->activeSubscription;

        // 현재 사용량 조회
        $usage = $organization->getCurrentUsage();

        // 결제 내역 조회 (최근 10건)
        $billingHistories = $organization->billingHistories()
            ->with('subscription')
            ->orderBy('approved_at', 'desc')
            ->orderBy('requested_at', 'desc')
            ->limit(10)
            ->get();

        // 결제 수단 조회
        $paymentMethods = $organization->paymentMethods()
            ->active()
            ->orderBy('is_default', 'desc')
            ->get();

        // 사업자 정보 조회
        $businessInfo = $organization->businessInfo;

        return response()->json([
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
            ],
            'subscription' => $subscription ? [
                'id' => $subscription->id,
                'plan_name' => $subscription->plan_name,
                'status' => $subscription->status,
                'monthly_price' => $subscription->monthly_price,
                'max_members' => $subscription->max_members,
                'max_projects' => $subscription->max_projects,
                'max_storage_gb' => $subscription->max_storage_gb,
                'current_period_start' => $subscription->current_period_start->format('Y-m-d'),
                'current_period_end' => $subscription->current_period_end->format('Y-m-d'),
                'next_billing_date' => $subscription->next_billing_date->format('Y-m-d'),
                'days_until_billing' => $subscription->getDaysUntilBilling(),
                'is_active' => $subscription->isActive(),
                'is_expired' => $subscription->isExpired(),
            ] : null,
            'usage' => [
                'members' => $usage['members'],
                'projects' => $usage['projects'],
                'storage' => $usage['storage'],
                'usage_percentages' => $subscription ? [
                    'members' => $subscription->getUsagePercentage('members', $usage['members']),
                    'projects' => $subscription->getUsagePercentage('projects', $usage['projects']),
                    'storage' => $subscription->getUsagePercentage('storage', $usage['storage']),
                ] : null,
            ],
            'billing_histories' => $billingHistories->map(function ($history) {
                return [
                    'id' => $history->id,
                    'payment_key' => $history->payment_key,
                    'order_id' => $history->order_id,
                    'description' => $history->description,
                    'amount' => $history->amount,
                    'formatted_amount' => $history->getFormattedAmount(),
                    'status' => $history->status,
                    'status_text' => $history->getStatusText(),
                    'status_color' => $history->getStatusBadgeColor(),
                    'method' => $history->method,
                    'requested_at' => $history->requested_at->format('Y.m.d'),
                    'approved_at' => $history->approved_at?->format('Y.m.d'),
                    'formatted_date' => $history->getFormattedDate(),
                    'receipt_url' => $history->receipt_url,
                    'card_number' => $history->card_number,
                    'card_company' => $history->card_company,
                    'is_paid' => $history->isPaid(),
                ];
            }),
            'payment_methods' => $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'display_name' => $method->getDisplayName(),
                    'method_type' => $method->method_type,
                    'card_company' => $method->card_company,
                    'card_number' => $method->card_number,
                    'expiry_date' => $method->getExpiryDate(),
                    'is_default' => $method->is_default,
                    'is_active' => $method->is_active,
                    'is_expired' => $method->isExpired(),
                ];
            }),
            'business_info' => $businessInfo ? [
                'id' => $businessInfo->id,
                'business_name' => $businessInfo->business_name,
                'business_registration_number' => $businessInfo->business_registration_number,
                'formatted_business_number' => $businessInfo->getFormattedBusinessNumber(),
                'representative_name' => $businessInfo->representative_name,
                'business_type' => $businessInfo->business_type,
                'business_item' => $businessInfo->business_item,
                'full_address' => $businessInfo->getFullAddress(),
                'phone' => $businessInfo->phone,
                'email' => $businessInfo->email,
                'has_complete_info' => $businessInfo->hasCompleteInfo(),
            ] : null,
            'has_active_subscription' => $organization->hasActiveSubscription(),
            'can_download_receipt' => $businessInfo && $businessInfo->hasCompleteInfo(),
        ]);
    }
}
