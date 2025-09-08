<?php

namespace App\Enums;

enum PageAccessLevel: string
{
    case PUBLIC = 'public';
    case MEMBER = 'member';
    case CONTRIBUTOR = 'contributor';
    case MODERATOR = 'moderator';
    case ADMIN = 'admin';
    case OWNER = 'owner';
    case CUSTOM = 'custom';

    /**
     * 사용자에게 표시될 접근 레벨명
     */
    public function getDisplayName(): string
    {
        return match($this) {
            self::PUBLIC => '모든 사용자',
            self::MEMBER => '멤버 이상',
            self::CONTRIBUTOR => '기여자 이상',
            self::MODERATOR => '중간관리자 이상',
            self::ADMIN => '관리자 이상',
            self::OWNER => '소유자만',
            self::CUSTOM => '커스텀 역할',
        };
    }

    /**
     * 접근 레벨에 대한 설명
     */
    public function getDescription(): string
    {
        return match($this) {
            self::PUBLIC => '누구나 접근 가능',
            self::MEMBER => '프로젝트 멤버 이상만 접근 가능',
            self::CONTRIBUTOR => '기여자 권한 이상만 접근 가능',
            self::MODERATOR => '중간관리자 권한 이상만 접근 가능',
            self::ADMIN => '관리자 권한 이상만 접근 가능',
            self::OWNER => '프로젝트 소유자만 접근 가능',
            self::CUSTOM => '지정된 커스텀 역할만 접근 가능',
        };
    }

    /**
     * 접근 레벨별 색상 클래스
     */
    public function getColorClass(): string
    {
        return match($this) {
            self::PUBLIC => 'bg-green-100 text-green-800',
            self::MEMBER => 'bg-blue-100 text-blue-800',
            self::CONTRIBUTOR => 'bg-purple-100 text-purple-800',
            self::MODERATOR => 'bg-orange-100 text-orange-800',
            self::ADMIN => 'bg-red-100 text-red-800',
            self::OWNER => 'bg-black text-white',
            self::CUSTOM => 'bg-indigo-100 text-indigo-800',
        };
    }

    /**
     * 접근 레벨별 아이콘
     */
    public function getIcon(): string
    {
        return match($this) {
            self::PUBLIC => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3s-4.5 4.03-4.5 9 2.015 9 4.5 9z',
            self::MEMBER => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
            self::CONTRIBUTOR => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125',
            self::MODERATOR => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.333 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
            self::ADMIN => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            self::OWNER => 'M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z',
            self::CUSTOM => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        };
    }

    /**
     * 해당 접근 레벨에 접근할 수 있는 최소 역할을 반환
     */
    public function getRequiredRole(): ?ProjectRole
    {
        return match($this) {
            self::PUBLIC => null, // 누구나
            self::MEMBER => ProjectRole::MEMBER,
            self::CONTRIBUTOR => ProjectRole::CONTRIBUTOR,
            self::MODERATOR => ProjectRole::MODERATOR,
            self::ADMIN => ProjectRole::ADMIN,
            self::OWNER => ProjectRole::OWNER,
            self::CUSTOM => null, // 별도 처리 필요
        };
    }

    /**
     * 역할이 이 접근 레벨에 접근할 수 있는지 확인
     */
    public function canRoleAccess(ProjectRole $role): bool
    {
        if ($this === self::CUSTOM) {
            return false; // 커스텀은 별도 처리 필요
        }

        if ($this === self::PUBLIC) {
            return true;
        }

        $requiredRole = $this->getRequiredRole();
        return $requiredRole ? $role->includes($requiredRole) : false;
    }

    /**
     * 선택 가능한 모든 접근 레벨 반환 (커스텀 제외)
     */
    public static function getSelectableOptions(): array
    {
        return [
            self::PUBLIC,
            self::MEMBER,
            self::CONTRIBUTOR,
            self::MODERATOR,
            self::ADMIN,
            self::OWNER,
        ];
    }
}