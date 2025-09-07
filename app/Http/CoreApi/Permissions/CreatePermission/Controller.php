<?php

namespace App\Http\CoreApi\Permissions\CreatePermission;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class Controller
{
    /**
     * Create new permission
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web',
                'category' => $request->category,
                'description' => $request->description
            ]);
            
            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($permission)
                ->log('Permission created: ' . $permission->name);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully',
                'data' => $permission
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create permission: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create permission'
            ], 500);
        }
    }
}