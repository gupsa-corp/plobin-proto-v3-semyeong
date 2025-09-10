<?php

namespace App\Http\Controllers\PlatformAdmin\PricingPlan\GetPricingStatistics;

use App\Models\PricingPlan;
use App\Models\Subscription;
use App\Models\BillingHistory;
use Illuminate\Http\JsonResponse;

class Controller
{
    /**
     * Get subscription and pricing statistics
     */
    public function __invoke(): JsonResponse
    {
        $stats = [
            'total_plans' => PricingPlan::count(),
            'active_plans' => PricingPlan::where('is_active', true)->count(),
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'monthly_revenue' => BillingHistory::where('status', 'DONE')
                ->whereMonth('approved_at', now()->month)
                ->sum('amount'),
            'plans_by_type' => PricingPlan::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'subscriptions_by_plan' => Subscription::selectRaw('plan_name, COUNT(*) as count')
                ->where('status', 'active')
                ->groupBy('plan_name')
                ->pluck('count', 'plan_name')
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
