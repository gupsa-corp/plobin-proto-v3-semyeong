<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SandboxScenarioStep extends Model
{
    protected $table = 'sandbox_scenario_steps';

    protected $fillable = [
        'sub_scenario_id',
        'step_number',
        'title',
        'description',
        'status',
        'assignee_id',
        'estimated_hours',
        'actual_hours',
        'dependencies',
        'attachments',
        'completed_at',
        'sort_order'
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'dependencies' => 'array',
        'attachments' => 'array',
        'completed_at' => 'datetime',
        'step_number' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $attributes = [
        'status' => 'todo',
        'sort_order' => 0,
    ];

    /**
     * 단계가 속한 서브 시나리오
     */
    public function subScenario(): BelongsTo
    {
        return $this->belongsTo(SandboxSubScenario::class);
    }

    /**
     * 단계 담당자
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * 단계에 달린 댓글들
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(SandboxScenarioComment::class, 'commentable');
    }

    /**
     * 단계 완료 처리
     */
    public function markAsCompleted(): void
    {
        $this->status = 'done';
        $this->completed_at = now();
        $this->save();

        // 부모 서브 시나리오의 진행률 업데이트
        if ($this->subScenario) {
            $this->subScenario->updateProgress();
        }
    }

    /**
     * 단계 재개 처리
     */
    public function markAsInProgress(): void
    {
        $this->status = 'in-progress';
        $this->completed_at = null;
        $this->save();
    }

    /**
     * 단계 번호 검증 (1-10 범위)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($step) {
            if ($step->step_number < 1 || $step->step_number > 10) {
                throw new \InvalidArgumentException('단계 번호는 1에서 10 사이여야 합니다.');
            }
        });

        static::updating(function ($step) {
            if ($step->step_number < 1 || $step->step_number > 10) {
                throw new \InvalidArgumentException('단계 번호는 1에서 10 사이여야 합니다.');
            }
        });
    }

    /**
     * 선행 단계들 완료 여부 확인
     */
    public function areDependenciesCompleted(): bool
    {
        if (empty($this->dependencies)) {
            return true;
        }

        $dependencyIds = $this->dependencies;
        $completedSteps = self::whereIn('id', $dependencyIds)
            ->where('status', 'done')
            ->count();

        return $completedSteps === count($dependencyIds);
    }

    /**
     * 이 단계를 선행 단계로 하는 다른 단계들
     */
    public function dependentSteps()
    {
        return self::whereJsonContains('dependencies', $this->id)->get();
    }
}
