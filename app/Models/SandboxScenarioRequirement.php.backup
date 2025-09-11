<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SandboxScenarioRequirement extends Model
{
    protected $table = 'sandbox_scenario_requirements';

    protected $fillable = [
        'sandbox_scenario_id', 'parent_id', 'content', 'completed', 'sort_order'
    ];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function sandboxScenario(): BelongsTo
    {
        return $this->belongsTo(SandboxScenario::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SandboxScenarioRequirement::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(SandboxScenarioRequirement::class, 'parent_id')->orderBy('sort_order');
    }
}
