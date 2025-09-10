<?php

namespace App\Http\Controllers\PlatformAdmin\PricingPlan\GetPricingPlans;

use App\Models\PricingPlan;
use Illuminate\Http\JsonResponse;

class Controller
{
    /**
     * Display a listing of pricing plans
     */
    public function __invoke(): JsonResponse
    {
        $plans = PricingPlan::orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }
}
