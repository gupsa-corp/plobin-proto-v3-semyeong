<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class OrganizationLicense extends Model
{
    protected $fillable = [
        'organization_id',
        'license_type',
        'license_name',
        'quantity',
        'unit_type',
        'unit_price',
        'total_price',
        'billing_cycle',
        'starts_at',
        'expires_at',
        'auto_renew',
        'status',
        'purchased_at',
        'cancelled_at',
        'cancellation_reason',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'auto_renew' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'purchased_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false; // Perpetual license
        }
        
        return $this->expires_at->isPast();
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->expires_at) {
            return null; // Perpetual license
        }
        
        return Carbon::now()->diffInDays($this->expires_at, false);
    }

    public function getFormattedTotalPrice(): string
    {
        return '₩' . number_format($this->total_price);
    }

    public function getFormattedUnitPrice(): string
    {
        return '₩' . number_format($this->unit_price);
    }

    public function getNextBillingDate(): ?Carbon
    {
        if (!$this->auto_renew || $this->isCancelled() || !$this->expires_at) {
            return null;
        }

        return $this->expires_at;
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'active' => $this->isExpired() ? 'red' : 'green',
            'suspended' => 'yellow',
            'cancelled' => 'gray',
            'expired' => 'red',
            default => 'gray'
        };
    }

    public function getStatusText(): string
    {
        if ($this->status === 'active' && $this->isExpired()) {
            return '만료됨';
        }

        return match($this->status) {
            'active' => '활성',
            'suspended' => '일시정지',
            'cancelled' => '취소됨',
            'expired' => '만료됨',
            default => $this->status
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('expires_at')
                    ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('license_type', $type);
    }
}
