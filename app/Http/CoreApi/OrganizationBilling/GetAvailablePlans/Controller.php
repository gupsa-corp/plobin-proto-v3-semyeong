<?php

namespace App\Http\CoreApi\OrganizationBilling\GetAvailablePlans;

use App\Models\PricingPlan;
use Illuminate\Http\JsonResponse;

class Controller
{
    /**
     * Get available pricing plans for organization billing
     */
    public function __invoke(): JsonResponse
    {
        $plans = PricingPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('monthly_price')
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'type' => $plan->type,
                    'monthly_price' => $plan->monthly_price,
                    'max_members' => $plan->max_members,
                    'max_projects' => $plan->max_projects,
                    'max_storage_gb' => $plan->max_storage_gb,
                    'max_sheets' => $plan->max_sheets,
                    'is_featured' => $plan->is_featured,
                    'features' => $plan->features,
                    'formatted_price' => $plan->monthly_price ? '₩' . number_format($plan->monthly_price) : '문의',
                    'formatted_members' => $plan->max_members ? $plan->max_members . '명' : '무제한',
                    'formatted_storage' => $plan->max_storage_gb ? $plan->max_storage_gb . 'GB' : '무제한',
                    'formatted_projects' => $plan->max_projects ? $plan->max_projects . '개' : '무제한'
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }
}