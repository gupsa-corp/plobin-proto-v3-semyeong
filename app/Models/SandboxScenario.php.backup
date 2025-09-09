<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SandboxScenario extends Model
{
    protected $table = 'sandbox_scenarios';

    protected $fillable = [
        'title', 'description', 'priority', 'status', 'sort_order'
    ];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function requirements(): HasMany
    {
        return $this->hasMany(SandboxScenarioRequirement::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function allRequirements(): HasMany
    {
        return $this->hasMany(SandboxScenarioRequirement::class)->orderBy('sort_order');
    }

    // 진행률 계산 메서드
    public function getProgressAttribute(): int
    {
        $total = $this->allRequirements()->count();
        $completed = $this->allRequirements()->where('completed', true)->count();
        
        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }
}
