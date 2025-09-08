<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ProjectRole;

class OrganizationMember extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'role',
        'joined_at',
        'invited_at',
        'invitation_status'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'invited_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 역할을 ProjectRole Enum으로 반환
     */
    public function getRoleEnum(): ProjectRole
    {
        if ($this->role) {
            return ProjectRole::from($this->role);
        }

        // 기본값은 게스트
        return ProjectRole::GUEST;
    }

    /**
     * 특정 역할 이상인지 확인
     */
    public function hasRoleOrHigher(ProjectRole $role): bool
    {
        $currentRole = $this->getRoleEnum();
        return $currentRole->includes($role);
    }

    /**
     * 역할명 설정
     */
    public function setRoleEnum(ProjectRole $role): void
    {
        $this->role = $role->value;
    }

    /**
     * 역할 표시명 반환
     */
    public function getRoleDisplayName(): string
    {
        return $this->getRoleEnum()->getDisplayName();
    }
}
