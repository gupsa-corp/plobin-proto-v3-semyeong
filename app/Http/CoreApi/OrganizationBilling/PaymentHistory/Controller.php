<?php

namespace App\Http\CoreApi\OrganizationBilling\PaymentHistory;

use App\Http\CoreApi\Controller as BaseController;
use App\Models\Organization;
use App\Models\BillingHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Controller extends BaseController
{
    /**
     * 결제 내역 목록 조회
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 페이지네이션 매개변수
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');

        $query = $organization->billingHistories()
            ->with('subscription')
            ->orderBy('approved_at', 'desc')
            ->orderBy('requested_at', 'desc');

        // 날짜 필터
        if ($startDate) {
            $query->whereDate('requested_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('requested_at', '<=', $endDate);
        }

        // 상태 필터
        if ($status) {
            $query->where('status', $status);
        }

        $billingHistories = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $billingHistories->items(),
            'pagination' => [
                'current_page' => $billingHistories->currentPage(),
                'last_page' => $billingHistories->lastPage(),
                'per_page' => $billingHistories->perPage(),
                'total' => $billingHistories->total(),
                'has_more' => $billingHistories->hasMorePages(),
            ],
            'payments' => $billingHistories->map(function ($history) {
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
                    'requested_at' => $history->requested_at->format('Y.m.d H:i'),
                    'approved_at' => $history->approved_at?->format('Y.m.d H:i'),
                    'formatted_date' => $history->getFormattedDate(),
                    'receipt_url' => $history->receipt_url,
                    'card_number' => $history->card_number,
                    'card_company' => $history->card_company,
                    'is_paid' => $history->isPaid(),
                    'plan_name' => $history->subscription?->plan_name,
                    'subscription_period' => $history->subscription ? [
                        'start' => $history->subscription->current_period_start->format('Y.m.d'),
                        'end' => $history->subscription->current_period_end->format('Y.m.d'),
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * 특정 결제 내역 상세 조회
     */
    public function show(Request $request, Organization $organization, $paymentId): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $billingHistory = $organization->billingHistories()
            ->with('subscription')
            ->findOrFail($paymentId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $billingHistory->id,
                'payment_key' => $billingHistory->payment_key,
                'order_id' => $billingHistory->order_id,
                'description' => $billingHistory->description,
                'amount' => $billingHistory->amount,
                'formatted_amount' => $billingHistory->getFormattedAmount(),
                'status' => $billingHistory->status,
                'status_text' => $billingHistory->getStatusText(),
                'status_color' => $billingHistory->getStatusBadgeColor(),
                'method' => $billingHistory->method,
                'requested_at' => $billingHistory->requested_at->format('Y.m.d H:i:s'),
                'approved_at' => $billingHistory->approved_at?->format('Y.m.d H:i:s'),
                'formatted_date' => $billingHistory->getFormattedDate(),
                'receipt_url' => $billingHistory->receipt_url,
                'card_number' => $billingHistory->card_number,
                'card_company' => $billingHistory->card_company,
                'is_paid' => $billingHistory->isPaid(),
                'plan_name' => $billingHistory->subscription?->plan_name,
                'subscription_period' => $billingHistory->subscription ? [
                    'start' => $billingHistory->subscription->current_period_start->format('Y.m.d'),
                    'end' => $billingHistory->subscription->current_period_end->format('Y.m.d'),
                ] : null,
                'transaction_details' => [
                    'payment_gateway' => $billingHistory->payment_gateway ?? 'toss',
                    'transaction_id' => $billingHistory->transaction_id,
                    'gateway_response' => $billingHistory->gateway_response,
                ],
                'billing_info' => [
                    'business_name' => $organization->businessInfo?->business_name,
                    'business_registration_number' => $organization->businessInfo?->business_registration_number,
                    'representative_name' => $organization->businessInfo?->representative_name,
                ],
            ]
        ]);
    }
}