<?php

namespace App\Http\Controllers\RoleHierarchy\AssignRole;

use App\Models\User;
use App\Services\RoleHierarchyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class Controller
{
    public function __construct(
        protected RoleHierarchyService $roleHierarchy
    ) {}

    /**
     * Assign role with hierarchy validation
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $assigner = $request->user() ?? auth()->user();
            $targetUser = User::findOrFail($request->target_user_id);
            $roleName = $request->role_name;

            // Validate assignment
            $validation = $this->roleHierarchy->validateRoleAssignment($assigner, $roleName, $targetUser);

            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role assignment not allowed',
                    'errors' => $validation['errors']
                ], 403);
            }

            // Get old roles for audit
            $oldRoles = $targetUser->getRoleNames()->toArray();

            // Assign new role (replace all existing roles)
            $targetUser->syncRoles([$roleName]);

            // Generate audit trail
            $auditTrail = $this->roleHierarchy->generateAuditTrail(
                $assigner,
                $targetUser,
                implode(', ', $oldRoles),
                $roleName
            );

            // Log activity
            activity()
                ->causedBy($assigner)
                ->performedOn($targetUser)
                ->withProperties($auditTrail)
                ->log("Role assigned: {$roleName}");

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully',
                'data' => [
                    'old_roles' => $oldRoles,
                    'new_role' => $roleName,
                    'audit_trail' => $auditTrail
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Role assignment failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Role assignment failed'
            ], 500);
        }
    }
}
