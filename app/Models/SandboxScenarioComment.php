<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SandboxScenarioComment extends Model
{
    protected $table = 'sandbox_scenario_comments';

    protected $fillable = [
        'scenario_id',
        'sub_scenario_id',
        'step_id',
        'user_id',
        'content',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    /**
     * 댓글 작성자
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 댓글이 달린 대상 (다형성 관계)
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 댓글이 달린 시나리오 (nullable)
     */
    public function scenario(): BelongsTo
    {
        return $this->belongsTo(SandboxScenario::class);
    }

    /**
     * 댓글이 달린 서브 시나리오 (nullable)
     */
    public function subScenario(): BelongsTo
    {
        return $this->belongsTo(SandboxSubScenario::class);
    }

    /**
     * 댓글이 달린 단계 (nullable)
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(SandboxScenarioStep::class);
    }

    /**
     * 댓글 생성 시 유효성 검증
     * 한 댓글은 시나리오, 서브 시나리오, 단계 중 하나에만 연결될 수 있음
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $targets = [$comment->scenario_id, $comment->sub_scenario_id, $comment->step_id];
            $nonNullCount = count(array_filter($targets));

            if ($nonNullCount === 0) {
                throw new \InvalidArgumentException('댓글은 시나리오, 서브 시나리오, 또는 단계 중 하나에 연결되어야 합니다.');
            }

            if ($nonNullCount > 1) {
                throw new \InvalidArgumentException('댓글은 한 개의 대상에만 연결될 수 있습니다.');
            }
        });

        static::updating(function ($comment) {
            $targets = [$comment->scenario_id, $comment->sub_scenario_id, $comment->step_id];
            $nonNullCount = count(array_filter($targets));

            if ($nonNullCount === 0) {
                throw new \InvalidArgumentException('댓글은 시나리오, 서브 시나리오, 또는 단계 중 하나에 연결되어야 합니다.');
            }

            if ($nonNullCount > 1) {
                throw new \InvalidArgumentException('댓글은 한 개의 대상에만 연결될 수 있습니다.');
            }
        });
    }

    /**
     * 댓글이 연결된 대상의 타입 반환
     */
    public function getTargetType(): string
    {
        if ($this->scenario_id) {
            return 'scenario';
        } elseif ($this->sub_scenario_id) {
            return 'sub_scenario';
        } elseif ($this->step_id) {
            return 'step';
        }

        return 'unknown';
    }

    /**
     * 댓글이 연결된 대상의 ID 반환
     */
    public function getTargetId(): ?int
    {
        return $this->scenario_id ?? $this->sub_scenario_id ?? $this->step_id;
    }

    /**
     * 댓글이 연결된 대상 객체 반환
     */
    public function getTarget()
    {
        if ($this->scenario_id) {
            return $this->scenario;
        } elseif ($this->sub_scenario_id) {
            return $this->subScenario;
        } elseif ($this->step_id) {
            return $this->step;
        }

        return null;
    }
}
