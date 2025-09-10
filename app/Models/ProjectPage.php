<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PageAccessLevel;
use App\Enums\ProjectRole;
use App\Services\ProjectLogService;

class ProjectPage extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'content',
        'status',
        'parent_id',
        'user_id',
        'sort_order',
        'access_level',
        'allowed_roles',
        'sandbox_name',
        'custom_screen_id',
        'custom_screen_type',
        'custom_screen_enabled',
        'custom_screen_applied_at',
        'custom_screen_config',
        'template_path'
    ];

    protected $casts = [
        'allowed_roles' => 'json',
        'custom_screen_config' => 'json',
        'custom_screen_enabled' => 'boolean',
        'custom_screen_applied_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function ($page) {
            ProjectLogService::logPageCreated($page->project_id, $page->id, $page->title);
        });

        static::updated(function ($page) {
            if ($page->wasChanged()) {
                $changes = $page->getChanges();
                // timestamps와 soft delete는 제외
                unset($changes['updated_at'], $changes['created_at'], $changes['deleted_at']);

                if (!empty($changes)) {
                    ProjectLogService::logPageUpdated($page->project_id, $page->id, $page->title, $changes);
                }
            }
        });

        static::deleted(function ($page) {
            ProjectLogService::logPageDeleted($page->project_id, $page->id, $page->title);
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
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }

    /**
     * 페이지의 실제 접근 레벨 반환 (페이지별 설정 또는 프로젝트 기본값)
     */
    public function getEffectiveAccessLevel(): PageAccessLevel
    {
        if ($this->access_level) {
            return PageAccessLevel::from($this->access_level);
        }

        return $this->project->getDefaultAccessLevelEnum();
    }

    /**
     * 페이지 접근 레벨을 Enum으로 반환
     */
    public function getAccessLevelEnum(): ?PageAccessLevel
    {
        return $this->access_level ? PageAccessLevel::from($this->access_level) : null;
    }

    /**
     * 접근 가능한 역할 목록 반환
     */
    public function getAllowedRoles(): array
    {
        return $this->allowed_roles ?? [];
    }

    /**
     * 사용자가 이 페이지에 접근할 수 있는지 확인
     */
    public function canUserAccess(User $user): bool
    {
        // 프로젝트 소유자는 항상 접근 가능
        if ($this->project->user_id === $user->id) {
            return true;
        }

        // 사용자의 프로젝트 내 역할 확인
        $userRole = $this->project->getUserRole($user);

        // 페이지의 접근 레벨 확인
        $pageAccessLevel = $this->getEffectiveAccessLevel();

        // 커스텀 역할인 경우
        if ($pageAccessLevel === PageAccessLevel::CUSTOM) {
            return $this->checkCustomRoleAccess($user);
        }

        // 표준 역할 기반 접근 확인
        return $pageAccessLevel->canRoleAccess($userRole);
    }

    /**
     * 커스텀 역할 접근 권한 확인
     */
    private function checkCustomRoleAccess(User $user): bool
    {
        $allowedRoles = $this->getAllowedRoles();

        if (empty($allowedRoles)) {
            return false;
        }

        // 사용자의 커스텀 역할 확인
        $userCustomRoles = $user->roles()
            ->where('name', 'like', 'project_' . $this->project_id . '_%')
            ->pluck('id')
            ->toArray();

        return !empty(array_intersect($userCustomRoles, $allowedRoles));
    }

    /**
     * 페이지에 접근 가능한 최소 역할 반환
     */
    public function getRequiredMinimumRole(): ?ProjectRole
    {
        $accessLevel = $this->getEffectiveAccessLevel();
        return $accessLevel->getRequiredRole();
    }

    /**
     * 페이지가 제한된 접근 권한을 가지고 있는지 확인
     */
    public function isRestricted(): bool
    {
        $accessLevel = $this->getEffectiveAccessLevel();
        return $accessLevel !== PageAccessLevel::PUBLIC;
    }
}
