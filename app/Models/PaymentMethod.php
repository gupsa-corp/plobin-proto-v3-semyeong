<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    protected $fillable = [
        'organization_id',
        'billing_key',
        'method_type',
        'card_company',
        'card_number',
        'card_type',
        'expiry_month',
        'expiry_year',
        'is_default',
        'is_active',
        'toss_response',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'toss_response' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getDisplayName(): string
    {
        if ($this->method_type === 'card' && $this->card_company && $this->card_number) {
            return $this->card_company . ' **** **** **** ' . substr($this->card_number, -4);
        }
        
        return ucfirst($this->method_type);
    }

    public function getExpiryDate(): string
    {
        if ($this->expiry_month && $this->expiry_year) {
            return sprintf('%02d/%s', $this->expiry_month, substr($this->expiry_year, -2));
        }
        
        return '';
    }

    public function isExpired(): bool
    {
        if (!$this->expiry_month || !$this->expiry_year) {
            return false;
        }

        $expiryDate = \DateTime::createFromFormat('Y-m-d', $this->expiry_year . '-' . $this->expiry_month . '-01');
        $lastDayOfMonth = $expiryDate->format('Y-m-t');
        
        return now()->gt($lastDayOfMonth);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
