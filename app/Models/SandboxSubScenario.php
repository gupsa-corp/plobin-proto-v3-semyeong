<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SandboxSubScenario extends Model
{
    protected $table = 'sandbox_sub_scenarios';

    protected $fillable = [
        'scenario_id',
        'title',
        'description',
        'priority',
        'status',
        'assignee_id',
        'estimated_hours',
        'actual_hours',
        'progress_percentage',
        'sort_order'
    ];

    protected $casts = [
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
     * 서브 시나리오가 속한 메인 시나리오
     */
    public function scenario(): BelongsTo
    {
        return $this->belongsTo(SandboxScenario::class);
    }

    /**
     * 서브 시나리오 담당자
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * 서브 시나리오에 속한 단계들
     */
    public function steps(): HasMany
    {
        return $this->hasMany(SandboxScenarioStep::class)->orderBy('step_number');
    }

    /**
     * 서브 시나리오에 달린 댓글들
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(SandboxScenarioComment::class, 'commentable');
    }

    /**
     * 진행률 자동 계산 (단계들의 완료율 기반)
     */
    public function calculateProgress(): int
    {
        $steps = $this->steps;

        if ($steps->isEmpty()) {
            return 0;
        }

        $completedSteps = $steps->where('status', 'done')->count();
        return (int) (($completedSteps / $steps->count()) * 100);
    }

    /**
     * 진행률 업데이트 및 부모 시나리오 진행률 재계산
     */
    public function updateProgress(): void
    {
        $this->progress_percentage = $this->calculateProgress();
        $this->save();

        // 부모 시나리오의 진행률도 업데이트
        if ($this->scenario) {
            $this->scenario->updateProgress();
        }
    }

    /**
     * 다음 단계 번호 계산 (1-10 범위 내에서 사용되지 않은 번호)
     */
    public function getNextStepNumber(): int
    {
        $existingNumbers = $this->steps()->pluck('step_number')->toArray();
        $usedNumbers = array_flip($existingNumbers);

        for ($i = 1; $i <= 10; $i++) {
            if (!isset($usedNumbers[$i])) {
                return $i;
            }
        }

        // 모든 단계가 사용중이면 첫 번째 빈 번호 반환 (없으면 1)
        return 1;
    }
}
