<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\OrganizationPermission;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'url',
        'description',
        'user_id',
        'status',
        'members_count'
    ];

    protected $hidden = [
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_members')
                    ->withPivot(['permission_level', 'joined_at', 'invited_at', 'invitation_status'])
                    ->withTimestamps();
    }

    public function getMemberPermission(User $user): ?OrganizationPermission
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->permission : null;
    }

    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function addMember(User $user, OrganizationPermission $permission = OrganizationPermission::INVITED): OrganizationMember
    {
        return $this->members()->create([
            'user_id' => $user->id,
            'permission_level' => $permission->value,
            'invited_at' => now(),
        ]);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active');
    }

    public function billingHistories(): HasMany
    {
        return $this->hasMany(BillingHistory::class);
    }

    public function businessInfo(): HasOne
    {
        return $this->hasOne(BusinessInfo::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function defaultPaymentMethod(): HasOne
    {
        return $this->hasOne(PaymentMethod::class)->where('is_default', true)->where('is_active', true);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function getCurrentUsage(): array
    {
        return [
            'members' => $this->members_count ?? $this->members()->count(),
            'projects' => $this->projects()->count(),
            'storage' => 0, // TODO: 실제 스토리지 사용량 계산
        ];
    }
}
