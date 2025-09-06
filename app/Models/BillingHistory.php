<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingHistory extends Model
{
    protected $fillable = [
        'organization_id',
        'subscription_id',
        'payment_key',
        'order_id',
        'description',
        'amount',
        'vat',
        'status',
        'method',
        'requested_at',
        'approved_at',
        'toss_response',
        'receipt_url',
        'card_number',
        'card_company',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'toss_response' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'DONE';
    }

    public function isCanceled(): bool
    {
        return in_array($this->status, ['CANCELED', 'PARTIAL_CANCELED', 'ABORTED']);
    }

    public function isExpired(): bool
    {
        return $this->status === 'EXPIRED';
    }

    public function getFormattedAmount(): string
    {
        return number_format($this->amount) . '원';
    }

    public function getFormattedDate(): string
    {
        return $this->approved_at ? 
            $this->approved_at->format('Y.m.d') : 
            $this->requested_at->format('Y.m.d');
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'DONE' => 'green',
            'READY', 'IN_PROGRESS', 'WAITING_FOR_DEPOSIT' => 'yellow',
            'CANCELED', 'PARTIAL_CANCELED', 'ABORTED', 'EXPIRED' => 'red',
            default => 'gray'
        };
    }

    public function getStatusText(): string
    {
        return match($this->status) {
            'DONE' => '결제 완료',
            'READY' => '결제 준비',
            'IN_PROGRESS' => '결제 진행 중',
            'WAITING_FOR_DEPOSIT' => '입금 대기',
            'CANCELED' => '결제 취소',
            'PARTIAL_CANCELED' => '부분 취소',
            'ABORTED' => '결제 중단',
            'EXPIRED' => '결제 만료',
            default => $this->status
        };
    }
}
