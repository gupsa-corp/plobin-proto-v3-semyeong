<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\PageAccessLevel;
use App\Enums\ProjectRole;
use App\Services\ProjectChangeLogService;

class ProjectPage extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'content',
        'parent_id',
        'user_id',
        'sandbox_folder',
        'sandbox_custom_screen_folder',
    ];

    protected $casts = [
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function ($page) {
            ProjectChangeLogService::logPageCreated($page->project_id, $page->id, $page->title);
        });

        static::updated(function ($page) {
            if ($page->wasChanged()) {
                $changes = $page->getChanges();
                // timestamps는 제외
                unset($changes['updated_at'], $changes['created_at']);

                if (!empty($changes)) {
                    ProjectChangeLogService::logPageUpdated($page->project_id, $page->id, $page->title, $changes);
                }
            }
        });

        static::deleted(function ($page) {
            ProjectChangeLogService::logPageDeleted($page->project_id, $page->id, $page->title);
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectPage::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProjectPage::class, 'parent_id');
    }

    public function deploymentLogs(): HasMany
    {
        return $this->hasMany(ProjectPageDeploymentLog::class);
    }

    // 탭용 페이지들 (parent_id가 null인 것들)
    public function scopeTabs($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Sandbox 폴더 관련 헬퍼 메서드들
     */

    /**
     * Sandbox 폴더 경로 반환
     */
    public function getSandboxFolder(): ?string
    {
        return $this->sandbox_folder;
    }

    /**
     * Sandbox 커스텀 스크린 폴더 경로 반환
     */
    public function getSandboxCustomScreenFolder(): ?string
    {
        return $this->sandbox_custom_screen_folder;
    }

    /**
     * Sandbox 설정이 있는지 확인
     */
    public function hasSandboxSettings(): bool
    {
        return !empty($this->sandbox_folder) || !empty($this->sandbox_custom_screen_folder);
    }

    /**
     * 모든 sandbox 설정을 배열로 반환
     */
    public function getSandboxSettings(): array
    {
        return [
            'folder' => $this->getSandboxFolder(),
            'custom_screen_folder' => $this->getSandboxCustomScreenFolder(),
        ];
    }
}
