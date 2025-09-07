<?php

namespace App\Http\CoreApi\Permissions\GetPermissionMatrix;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Controller
{
    /**
     * Get permission matrix for role-permission grid
     */
    public function __invoke(): JsonResponse
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all()->groupBy('category');
            
            // Build permission matrix
            $matrix = [];
            foreach ($roles as $role) {
                $rolePermissions = $role->permissions->pluck('name')->toArray();
                $matrix[$role->name] = [];
                
                foreach ($permissions as $category => $categoryPermissions) {
                    $matrix[$role->name][$category] = [];
                    foreach ($categoryPermissions as $permission) {
                        $matrix[$role->name][$category][$permission->name] = in_array($permission->name, $rolePermissions);
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'matrix' => $matrix,
                    'permissions' => $permissions,
                    'roles' => $roles->pluck('name')->toArray()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to build permission matrix: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to build permission matrix'
            ], 500);
        }
    }
}