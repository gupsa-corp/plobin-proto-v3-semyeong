<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ProjectRole;

class ProjectMemberRole extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'role_name',
        'custom_permissions',
    ];

    protected $casts = [
        'custom_permissions' => 'json',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 역할을 Enum으로 반환
     */
    public function getRoleEnum(): ProjectRole
    {
        return ProjectRole::from($this->role_name);
    }

    /**
     * 역할명 설정
     */
    public function setRoleEnum(ProjectRole $role): void
    {
        $this->role_name = $role->value;
    }

    /**
     * 특정 권한을 가지고 있는지 확인
     */
    public function hasPermission(string $permission): bool
    {
        $customPermissions = $this->custom_permissions ?? [];
        return in_array($permission, $customPermissions);
    }

    /**
     * 커스텀 권한 추가
     */
    public function addPermission(string $permission): void
    {
        $permissions = $this->custom_permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->custom_permissions = $permissions;
        }
    }

    /**
     * 커스텀 권한 제거
     */
    public function removePermission(string $permission): void
    {
        $permissions = $this->custom_permissions ?? [];
        $this->custom_permissions = array_values(array_diff($permissions, [$permission]));
    }
}