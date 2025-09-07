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
        'priority',
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

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority');
    }

    public function getPriorityBadgeColor(): string
    {
        return match($this->priority) {
            1 => 'green',
            2 => 'orange',
            3 => 'blue',
            default => 'gray'
        };
    }

    public function getPriorityText(): string
    {
        return $this->priority . '순위';
    }

    public function updatePriority(int $newPriority): bool
    {
        $organization = $this->organization;
        
        // Get all payment methods for this organization ordered by priority
        $paymentMethods = $organization->paymentMethods()
            ->active()
            ->orderBy('priority')
            ->get();

        $oldPriority = $this->priority;
        
        // Update priorities
        if ($newPriority < $oldPriority) {
            // Moving up in priority (lower number = higher priority)
            foreach ($paymentMethods as $method) {
                if ($method->id === $this->id) {
                    $method->priority = $newPriority;
                } elseif ($method->priority >= $newPriority && $method->priority < $oldPriority) {
                    $method->priority += 1;
                }
                $method->save();
            }
        } elseif ($newPriority > $oldPriority) {
            // Moving down in priority (higher number = lower priority)
            foreach ($paymentMethods as $method) {
                if ($method->id === $this->id) {
                    $method->priority = $newPriority;
                } elseif ($method->priority <= $newPriority && $method->priority > $oldPriority) {
                    $method->priority -= 1;
                }
                $method->save();
            }
        }
        
        return $this->fresh()->priority === $newPriority;
    }

    public function canMoveUp(): bool
    {
        return $this->priority > 1;
    }

    public function canMoveDown(): bool
    {
        $maxPriority = $this->organization->paymentMethods()->active()->max('priority');
        return $this->priority < $maxPriority;
    }

    public function moveUp(): bool
    {
        if (!$this->canMoveUp()) {
            return false;
        }
        
        return $this->updatePriority($this->priority - 1);
    }

    public function moveDown(): bool
    {
        if (!$this->canMoveDown()) {
            return false;
        }
        
        return $this->updatePriority($this->priority + 1);
    }

    public function setAsDefault(): bool
    {
        // Remove default status from all other payment methods
        $this->organization->paymentMethods()
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);
        
        // Set this as default and priority 1
        $this->is_default = true;
        $this->save();
        
        return $this->updatePriority(1);
    }

    public static function reorderPriorities($organizationId, array $paymentMethodIds): bool
    {
        $priority = 1;
        foreach ($paymentMethodIds as $id) {
            PaymentMethod::where('id', $id)
                ->where('organization_id', $organizationId)
                ->update(['priority' => $priority]);
            $priority++;
        }
        
        return true;
    }
}
