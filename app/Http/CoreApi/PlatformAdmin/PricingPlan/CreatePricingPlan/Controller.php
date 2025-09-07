<?php

namespace App\Http\CoreApi\PlatformAdmin\PricingPlan\CreatePricingPlan;

use App\Models\PricingPlan;
use Illuminate\Http\JsonResponse;

class Controller
{
    /**
     * Store a newly created pricing plan
     */
    public function __invoke(Request $request): JsonResponse
    {
        $plan = PricingPlan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => '요금제가 성공적으로 생성되었습니다.',
            'data' => $plan
        ], 201);
    }
}