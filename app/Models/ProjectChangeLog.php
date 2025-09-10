<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChangeLog extends Model
{
    use HasFactory;

    // Action constants
    public const ACTION_PROJECT_CREATED = 'project_created';
    public const ACTION_SETTINGS_UPDATED = 'settings_updated';
    public const ACTION_PAGE_CREATED = 'page_created';
    public const ACTION_PAGE_UPDATED = 'page_updated';
    public const ACTION_PAGE_DELETED = 'page_deleted';
    public const ACTION_USER_ADDED = 'user_added';
    public const ACTION_USER_REMOVED = 'user_removed';
    public const ACTION_PERMISSION_CHANGED = 'permission_changed';
    public const ACTION_SANDBOX_CREATED = 'sandbox_created';
    public const ACTION_SANDBOX_UPDATED = 'sandbox_updated';
    public const ACTION_SANDBOX_DELETED = 'sandbox_deleted';

    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'metadata',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logChange($projectId, $action, $description = null, $metadata = null, $userId = null)
    {
        return self::create([
            'project_id' => $projectId,
            'user_id' => $userId ?: auth()->id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function getActionNameAttribute()
    {
        $actionNames = [
            'project_created' => '프로젝트 생성',
            'project_updated' => '프로젝트 정보 수정',
            'project_deleted' => '프로젝트 삭제',
            'page_created' => '페이지 생성',
            'page_updated' => '페이지 수정',
            'page_deleted' => '페이지 삭제',
            'user_added' => '사용자 추가',
            'user_removed' => '사용자 제거',
            'permission_changed' => '권한 변경',
            'settings_changed' => '설정 변경'
        ];

        return $actionNames[$this->action] ?? $this->action;
    }
}