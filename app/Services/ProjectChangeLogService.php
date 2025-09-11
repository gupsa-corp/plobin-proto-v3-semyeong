<?php

namespace App\Services;

use App\Models\ProjectChangeLog;
use Illuminate\Support\Facades\Auth;

class ProjectChangeLogService
{
    /**
     * 프로젝트 로그 기록
     */
    public static function log(
        int $projectId,
        string $action,
        string $description = null,
        string $entityType = null,
        int $entityId = null,
        array $metadata = [],
        int $userId = null
    ) {
        $userId = $userId ?: (Auth::id() ?? 1);

        return ProjectChangeLog::create([
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

    /**
     * 프로젝트 생성 로그
     */
    public static function logProjectCreated(int $projectId, string $projectName, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_PROJECT_CREATED,
            description: "프로젝트 '{$projectName}'가 생성되었습니다.",
            entityType: 'project',
            entityId: $projectId,
            metadata: ['project_name' => $projectName],
            userId: $userId
        );
    }

    /**
     * 프로젝트 설정 변경 로그
     */
    public static function logProjectUpdated(int $projectId, array $changes, int $userId = null)
    {
        $changedFields = array_keys($changes);
        $description = "프로젝트 설정이 변경되었습니다: " . implode(', ', $changedFields);

        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_SETTINGS_UPDATED,
            description: $description,
            entityType: 'project',
            entityId: $projectId,
            metadata: ['changes' => $changes],
            userId: $userId
        );
    }

    /**
     * 페이지 생성 로그
     */
    public static function logPageCreated(int $projectId, int $pageId, string $pageTitle, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_PAGE_CREATED,
            description: "페이지 '{$pageTitle}'가 생성되었습니다.",
            entityType: 'page',
            entityId: $pageId,
            metadata: ['page_title' => $pageTitle],
            userId: $userId
        );
    }

    /**
     * 페이지 수정 로그
     */
    public static function logPageUpdated(int $projectId, int $pageId, string $pageTitle, array $changes = [], int $userId = null)
    {
        $description = "페이지 '{$pageTitle}'가 수정되었습니다.";
        if (!empty($changes)) {
            $description .= " 변경된 항목: " . implode(', ', array_keys($changes));
        }

        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_PAGE_UPDATED,
            description: $description,
            entityType: 'page',
            entityId: $pageId,
            metadata: [
                'page_title' => $pageTitle,
                'changes' => $changes
            ],
            userId: $userId
        );
    }

    /**
     * 페이지 삭제 로그
     */
    public static function logPageDeleted(int $projectId, int $pageId, string $pageTitle, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_PAGE_DELETED,
            description: "페이지 '{$pageTitle}'가 삭제되었습니다.",
            entityType: 'page',
            entityId: $pageId,
            metadata: ['page_title' => $pageTitle],
            userId: $userId
        );
    }

    /**
     * 사용자 추가 로그
     */
    public static function logUserAdded(int $projectId, int $addedUserId, string $userName, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_USER_ADDED,
            description: "사용자 '{$userName}'가 프로젝트에 추가되었습니다.",
            entityType: 'user',
            entityId: $addedUserId,
            metadata: ['user_name' => $userName, 'added_user_id' => $addedUserId],
            userId: $userId
        );
    }

    /**
     * 사용자 제거 로그
     */
    public static function logUserRemoved(int $projectId, int $removedUserId, string $userName, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_USER_REMOVED,
            description: "사용자 '{$userName}'가 프로젝트에서 제거되었습니다.",
            entityType: 'user',
            entityId: $removedUserId,
            metadata: ['user_name' => $userName, 'removed_user_id' => $removedUserId],
            userId: $userId
        );
    }

    /**
     * 권한 변경 로그
     */
    public static function logPermissionChanged(int $projectId, int $targetUserId, string $userName, string $oldPermission, string $newPermission, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_PERMISSION_CHANGED,
            description: "사용자 '{$userName}'의 권한이 '{$oldPermission}'에서 '{$newPermission}'로 변경되었습니다.",
            entityType: 'user',
            entityId: $targetUserId,
            metadata: [
                'user_name' => $userName,
                'old_permission' => $oldPermission,
                'new_permission' => $newPermission,
                'target_user_id' => $targetUserId
            ],
            userId: $userId
        );
    }

    /**
     * 샌드박스 생성 로그
     */
    public static function logSandboxCreated(int $projectId, int $sandboxId, string $sandboxName, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_SANDBOX_CREATED,
            description: "샌드박스 '{$sandboxName}'가 생성되었습니다.",
            entityType: 'sandbox',
            entityId: $sandboxId,
            metadata: ['sandbox_folder' => $sandboxName],
            userId: $userId
        );
    }

    /**
     * 샌드박스 수정 로그
     */
    public static function logSandboxUpdated(int $projectId, int $sandboxId, string $sandboxName, array $changes = [], int $userId = null)
    {
        $description = "샌드박스 '{$sandboxName}'가 수정되었습니다.";
        if (!empty($changes)) {
            $description .= " 변경된 항목: " . implode(', ', array_keys($changes));
        }

        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_SANDBOX_UPDATED,
            description: $description,
            entityType: 'sandbox',
            entityId: $sandboxId,
            metadata: [
                'sandbox_folder' => $sandboxName,
                'changes' => $changes
            ],
            userId: $userId
        );
    }

    /**
     * 샌드박스 삭제 로그
     */
    public static function logSandboxDeleted(int $projectId, int $sandboxId, string $sandboxName, int $userId = null)
    {
        return self::log(
            projectId: $projectId,
            action: ProjectChangeLog::ACTION_SANDBOX_DELETED,
            description: "샌드박스 '{$sandboxName}'가 삭제되었습니다.",
            entityType: 'sandbox',
            entityId: $sandboxId,
            metadata: ['sandbox_folder' => $sandboxName],
            userId: $userId
        );
    }
}
