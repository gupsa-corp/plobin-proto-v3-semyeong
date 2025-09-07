<?php

namespace App\Http\CoreApi\Permissions\GetPermissions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Controller
{
    /**
     * Get all permissions with categories
     */
    public function __invoke(): JsonResponse
    {
        try {
            $permissions = Permission::all()->groupBy('category');
            $roles = Role::with('permissions')->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'permissions' => $permissions,
                    'roles' => $roles
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load permissions data'
            ], 500);
        }
    }
}