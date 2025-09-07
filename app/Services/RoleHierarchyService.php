<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleHierarchyService
{
    /**
     * Define role hierarchy levels
     * Higher number = higher authority
     */
    protected array $roleHierarchy = [
        'platform_admin' => 1000,
        'organization_admin' => 800,
        'project_manager' => 600,
        'editor' => 400,
        'organization_member' => 200,
        'viewer' => 100,
    ];

    /**
     * Get role hierarchy level
     */
    public function getRoleLevel(string $roleName): int
    {
        return $this->roleHierarchy[$roleName] ?? 0;
    }

    /**
     * Check if role A has higher authority than role B
     */
    public function hasHigherAuthority(string $roleA, string $roleB): bool
    {
        return $this->getRoleLevel($roleA) > $this->getRoleLevel($roleB);
    }

    /**
     * Get all roles that are subordinate to given role
     */
    public function getSubordinateRoles(string $roleName): array
    {
        $currentLevel = $this->getRoleLevel($roleName);
        
        return array_keys(array_filter($this->roleHierarchy, function($level) use ($currentLevel) {
            return $level < $currentLevel;
        }));
    }

    /**
     * Get all roles that have authority over given role
     */
    public function getSuperiorRoles(string $roleName): array
    {
        $currentLevel = $this->getRoleLevel($roleName);
        
        return array_keys(array_filter($this->roleHierarchy, function($level) use ($currentLevel) {
            return $level > $currentLevel;
        }));
    }

    /**
     * Check if user can manage another user based on role hierarchy
     */
    public function canManageUser(User $manager, User $target): bool
    {
        $managerRoles = $manager->getRoleNames()->toArray();
        $targetRoles = $target->getRoleNames()->toArray();
        
        // Platform admin can manage everyone
        if (in_array('platform_admin', $managerRoles)) {
            return true;
        }
        
        // Get highest role level for each user
        $managerLevel = $this->getHighestRoleLevel($managerRoles);
        $targetLevel = $this->getHighestRoleLevel($targetRoles);
        
        return $managerLevel > $targetLevel;
    }

    /**
     * Get highest role level from array of role names
     */
    protected function getHighestRoleLevel(array $roleNames): int
    {
        $maxLevel = 0;
        
        foreach ($roleNames as $roleName) {
            $level = $this->getRoleLevel($roleName);
            if ($level > $maxLevel) {
                $maxLevel = $level;
            }
        }
        
        return $maxLevel;
    }

    /**
     * Get roles that a user can assign to others
     */
    public function getAssignableRoles(User $user): array
    {
        $userRoles = $user->getRoleNames()->toArray();
        
        // Platform admin can assign any role
        if (in_array('platform_admin', $userRoles)) {
            return array_keys($this->roleHierarchy);
        }
        
        $userLevel = $this->getHighestRoleLevel($userRoles);
        
        // Can only assign roles with lower authority
        return array_keys(array_filter($this->roleHierarchy, function($level) use ($userLevel) {
            return $level < $userLevel;
        }));
    }

    /**
     * Validate role assignment
     */
    public function validateRoleAssignment(User $assigner, string $targetRole, ?User $target = null): array
    {
        $errors = [];
        $assignerRoles = $assigner->getRoleNames()->toArray();
        $assignerLevel = $this->getHighestRoleLevel($assignerRoles);
        $targetRoleLevel = $this->getRoleLevel($targetRole);
        
        // Check if assigner has authority to assign this role
        if ($targetRoleLevel >= $assignerLevel && !in_array('platform_admin', $assignerRoles)) {
            $errors[] = 'You do not have authority to assign this role';
        }
        
        // Additional validation if target user is specified
        if ($target) {
            $targetCurrentRoles = $target->getRoleNames()->toArray();
            $targetCurrentLevel = $this->getHighestRoleLevel($targetCurrentRoles);
            
            // Check if assigner can manage target user
            if ($targetCurrentLevel >= $assignerLevel && !in_array('platform_admin', $assignerRoles)) {
                $errors[] = 'You do not have authority to modify this user\'s roles';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'assigner_level' => $assignerLevel,
            'target_role_level' => $targetRoleLevel
        ];
    }

    /**
     * Get role hierarchy visualization data
     */
    public function getHierarchyVisualization(): array
    {
        $roles = collect($this->roleHierarchy)
            ->sortByDesc(function($level) { return $level; })
            ->map(function($level, $name) {
                $role = Role::where('name', $name)->with('permissions')->first();
                return [
                    'name' => $name,
                    'display_name' => ucwords(str_replace('_', ' ', $name)),
                    'level' => $level,
                    'description' => $role?->description,
                    'permissions_count' => $role?->permissions->count() ?? 0,
                    'users_count' => $role?->users->count() ?? 0
                ];
            });
        
        return $roles->values()->toArray();
    }

    /**
     * Suggest role based on permissions
     */
    public function suggestRoleForPermissions(array $permissionNames): ?string
    {
        $roles = Role::with('permissions')->get();
        $bestMatch = null;
        $bestScore = 0;
        
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            $intersection = array_intersect($permissionNames, $rolePermissions);
            $score = count($intersection) / count($permissionNames);
            
            if ($score > $bestScore && $score >= 0.7) { // 70% match threshold
                $bestScore = $score;
                $bestMatch = $role->name;
            }
        }
        
        return $bestMatch;
    }

    /**
     * Get inheritance chain for role
     */
    public function getRoleInheritanceChain(string $roleName): array
    {
        $chain = [$roleName];
        $currentLevel = $this->getRoleLevel($roleName);
        
        // Add all superior roles in hierarchy order
        $superiorRoles = array_filter($this->roleHierarchy, function($level) use ($currentLevel) {
            return $level > $currentLevel;
        });
        
        arsort($superiorRoles);
        $chain = array_merge($chain, array_keys($superiorRoles));
        
        return $chain;
    }

    /**
     * Check for circular dependencies in role assignments
     */
    public function hasCircularDependency(string $role, array $inheritedRoles): bool
    {
        return in_array($role, $inheritedRoles);
    }

    /**
     * Get effective permissions for role considering hierarchy
     */
    public function getEffectivePermissions(string $roleName): array
    {
        $role = Role::where('name', $roleName)->with('permissions')->first();
        if (!$role) {
            return [];
        }
        
        $permissions = $role->permissions->pluck('name')->toArray();
        
        // Add inherited permissions from superior roles if needed
        // This is a basic implementation - you might want more complex inheritance rules
        
        return array_unique($permissions);
    }

    /**
     * Generate role assignment audit trail
     */
    public function generateAuditTrail(User $assigner, User $target, string $oldRole, string $newRole): array
    {
        return [
            'action' => 'role_assignment',
            'assigner_id' => $assigner->id,
            'assigner_name' => $assigner->name,
            'assigner_roles' => $assigner->getRoleNames()->toArray(),
            'target_id' => $target->id,
            'target_name' => $target->name,
            'old_role' => $oldRole,
            'new_role' => $newRole,
            'old_role_level' => $this->getRoleLevel($oldRole),
            'new_role_level' => $this->getRoleLevel($newRole),
            'authority_check' => $this->validateRoleAssignment($assigner, $newRole, $target),
            'timestamp' => now()->toISOString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];
    }
}