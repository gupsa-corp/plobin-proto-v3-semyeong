<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SandboxScenarioGroup extends Model
{
    protected $table = 'sandbox_scenario_groups';

    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
        'sort_order',
        'created_by'
    ];

    protected $attributes = [
        'color' => '#3B82F6',
        'icon' => 'folder',
        'sort_order' => 0,
    ];

    /**
     * 그룹에 속한 시나리오들
     */
    public function scenarios(): HasMany
    {
        return $this->hasMany(SandboxScenario::class)->orderBy('sort_order');
    }

    /**
     * 그룹을 생성한 사용자
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
