<?php

namespace App\Http\Controllers\RoleHierarchy\GetHierarchy;

use App\Services\RoleHierarchyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class Controller
{
    public function __construct(
        protected RoleHierarchyService $roleHierarchy
    ) {}

    /**
     * Get role hierarchy visualization
     */
    public function __invoke(): JsonResponse
    {
        try {
            $hierarchy = $this->roleHierarchy->getHierarchyVisualization();

            return response()->json([
                'success' => true,
                'data' => $hierarchy
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get role hierarchy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load role hierarchy'
            ], 500);
        }
    }
}
