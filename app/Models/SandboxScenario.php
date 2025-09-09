<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SandboxScenario extends Model
{
    protected $table = 'sandbox_scenarios';

    protected $fillable = [
        'group_id',
        'title',
        'description',
        'priority',
        'status',
        'assignee_id',
        'reporter_id',
        'estimated_hours',
        'actual_hours',
        'due_date',
        'tags',
        'progress_percentage',
        'sort_order',
        'created_by'
    ];

    protected $casts = [
        'due_date' => 'date',
        'tags' => 'array',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'progress_percentage' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $attributes = [
        'priority' => 'medium',
        'status' => 'todo',
        'progress_percentage' => 0,
        'sort_order' => 0,
    ];

    /**
     * 시나리오가 속한 그룹
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(SandboxScenarioGroup::class);
    }

    /**
     * 시나리오에 속한 서브 시나리오들
     */
    public function subScenarios(): HasMany
    {
        return $this->hasMany(SandboxSubScenario::class)->orderBy('sort_order');
    }

    /**
     * 시나리오 담당자
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * 시나리오 보고자
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * 시나리오 생성자
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 시나리오에 달린 댓글들
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(SandboxScenarioComment::class, 'commentable');
    }

    /**
     * 진행률 자동 계산 (서브 시나리오들의 평균)
     */
    public function calculateProgress(): int
    {
        $subScenarios = $this->subScenarios;

        if ($subScenarios->isEmpty()) {
            return 0;
        }

        return (int) $subScenarios->avg('progress_percentage');
    }

    /**
     * 진행률 업데이트
     */
    public function updateProgress(): void
    {
        $this->progress_percentage = $this->calculateProgress();
        $this->save();
    }
}
