<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $fillable = [
        'name',
        'slug', 
        'description',
        'type',
        'monthly_price',
        'max_members',
        'max_projects',
        'max_storage_gb',
        'max_sheets',
        'price_per_member',
        'price_per_project',
        'price_per_gb',
        'price_per_sheet',
        'free_members',
        'free_projects',
        'free_storage_gb',
        'free_sheets',
        'is_active',
        'is_featured',
        'sort_order',
        'features'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean'
    ];

    // 스코프: 활성화된 플랜만
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // 스코프: 타입별
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // 월간 고정 플랜인지 확인
    public function isMonthlyPlan()
    {
        return $this->type === 'monthly';
    }

    // 사용량 기반 플랜인지 확인  
    public function isUsageBasedPlan()
    {
        return $this->type === 'usage_based';
    }

    // 특정 사용량에 대한 월간 비용 계산 (사용량 기반 플랜용)
    public function calculateMonthlyCost($members = 0, $projects = 0, $storage_gb = 0, $sheets = 0)
    {
        if (!$this->isUsageBasedPlan()) {
            return $this->monthly_price ?? 0;
        }

        $cost = 0;
        
        // 무료 허용량을 초과한 부분만 계산
        $cost += max(0, $members - $this->free_members) * ($this->price_per_member ?? 0);
        $cost += max(0, $projects - $this->free_projects) * ($this->price_per_project ?? 0);
        $cost += max(0, $storage_gb - $this->free_storage_gb) * ($this->price_per_gb ?? 0);
        $cost += max(0, $sheets - $this->free_sheets) * ($this->price_per_sheet ?? 0);

        return $cost;
    }

    // 구독 관계
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_name', 'slug');
    }
}
