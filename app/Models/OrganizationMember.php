<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\OrganizationPermission;

class OrganizationMember extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'permission_level',
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

    public function getPermissionAttribute(): OrganizationPermission
    {
        return OrganizationPermission::from($this->permission_level);
    }

    public function setPermissionAttribute(OrganizationPermission $permission): void
    {
        $this->permission_level = $permission->value;
    }

    public function hasPermission(OrganizationPermission $requiredPermission): bool
    {
        return $this->permission_level >= $requiredPermission->value;
    }
}
