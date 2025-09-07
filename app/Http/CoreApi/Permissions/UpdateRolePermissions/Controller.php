<?php

namespace App\Http\CoreApi\Permissions\UpdateRolePermissions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class Controller
{
    /**
     * Bulk update role permissions
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $role = Role::where('name', $request->role_name)->firstOrFail();
            $oldPermissions = $role->permissions->pluck('name')->toArray();
            
            // Sync permissions
            $role->syncPermissions($request->permissions);
            
            $newPermissions = $role->fresh()->permissions->pluck('name')->toArray();
            
            // Log activity
            $added = array_diff($newPermissions, $oldPermissions);
            $removed = array_diff($oldPermissions, $newPermissions);
            
            if (!empty($added) || !empty($removed)) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($role)
                    ->withProperties([
                        'added_permissions' => $added,
                        'removed_permissions' => $removed
                    ])
                    ->log("Role permissions updated for: {$role->name}");
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Role permissions updated successfully',
                'data' => [
                    'added' => $added,
                    'removed' => $removed
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update role permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role permissions'
            ], 500);
        }
    }
}