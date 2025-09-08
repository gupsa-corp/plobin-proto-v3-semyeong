<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProjectLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 프로젝트 관계
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * 사용자 관계
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 로그 액션 타입들
     */
    const ACTION_PROJECT_CREATED = 'project_created';
    const ACTION_PROJECT_UPDATED = 'project_updated';
    const ACTION_PROJECT_DELETED = 'project_deleted';
    const ACTION_PAGE_CREATED = 'page_created';
    const ACTION_PAGE_UPDATED = 'page_updated';
    const ACTION_PAGE_DELETED = 'page_deleted';
    const ACTION_SETTINGS_UPDATED = 'settings_updated';
    const ACTION_USER_ADDED = 'user_added';
    const ACTION_USER_REMOVED = 'user_removed';
    const ACTION_PERMISSION_CHANGED = 'permission_changed';
    const ACTION_SANDBOX_CREATED = 'sandbox_created';
    const ACTION_SANDBOX_UPDATED = 'sandbox_updated';
    const ACTION_SANDBOX_DELETED = 'sandbox_deleted';

    /**
     * 액션 타입에 따른 한국어 설명
     */
    public function getActionDescriptionAttribute()
    {
        $actions = [
            self::ACTION_PROJECT_CREATED => '프로젝트가 생성되었습니다',
            self::ACTION_PROJECT_UPDATED => '프로젝트가 수정되었습니다',
            self::ACTION_PROJECT_DELETED => '프로젝트가 삭제되었습니다',
            self::ACTION_PAGE_CREATED => '페이지가 생성되었습니다',
            self::ACTION_PAGE_UPDATED => '페이지가 수정되었습니다',
            self::ACTION_PAGE_DELETED => '페이지가 삭제되었습니다',
            self::ACTION_SETTINGS_UPDATED => '설정이 변경되었습니다',
            self::ACTION_USER_ADDED => '사용자가 추가되었습니다',
            self::ACTION_USER_REMOVED => '사용자가 제거되었습니다',
            self::ACTION_PERMISSION_CHANGED => '권한이 변경되었습니다',
            self::ACTION_SANDBOX_CREATED => '샌드박스가 생성되었습니다',
            self::ACTION_SANDBOX_UPDATED => '샌드박스가 수정되었습니다',
            self::ACTION_SANDBOX_DELETED => '샌드박스가 삭제되었습니다',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * 상대 시간 표시
     */
    public function getRelativeTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * 로그 생성 정적 메서드
     */
    public static function createLog(
        int $projectId, 
        int $userId, 
        string $action, 
        string $description = null, 
        string $entityType = null, 
        int $entityId = null, 
        array $metadata = []
    ) {
        return self::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}