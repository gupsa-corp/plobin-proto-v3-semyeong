<?php

namespace App\Enums;

enum ProjectRole: string
{
    case GUEST = 'guest';
    case MEMBER = 'member';
    case CONTRIBUTOR = 'contributor';
    case MODERATOR = 'moderator';
    case ADMIN = 'admin';
    case OWNER = 'owner';

    /**
     * 역할 간 포함 관계 확인 (상위 역할이 하위 역할을 포함하는지)
     */
    public function includes(ProjectRole $role): bool
    {
        $hierarchy = [
            self::GUEST->value => 0,
            self::MEMBER->value => 1,
            self::CONTRIBUTOR->value => 2,
            self::MODERATOR->value => 3,
            self::ADMIN->value => 4,
            self::OWNER->value => 5,
        ];

        return $hierarchy[$this->value] >= $hierarchy[$role->value];
    }

    /**
     * 사용자에게 표시될 역할명
     */
    public function getDisplayName(): string
    {
        return match($this) {
            self::GUEST => '게스트',
            self::MEMBER => '멤버',
            self::CONTRIBUTOR => '기여자',
            self::MODERATOR => '중간관리자',
            self::ADMIN => '관리자',
            self::OWNER => '소유자',
        };
    }

    /**
     * 역할에 대한 설명
     */
    public function getDescription(): string
    {
        return match($this) {
            self::GUEST => '제한적 접근 권한',
            self::MEMBER => '기본 프로젝트 멤버 권한',
            self::CONTRIBUTOR => '프로젝트 수정 및 기여 권한',
            self::MODERATOR => '중간 관리 및 조정 권한',
            self::ADMIN => '프로젝트 관리 권한',
            self::OWNER => '모든 권한 (프로젝트 소유자)',
        };
    }

    /**
     * 역할별 색상 클래스
     */
    public function getColorClass(): string
    {
        return match($this) {
            self::GUEST => 'bg-gray-100 text-gray-800',
            self::MEMBER => 'bg-blue-100 text-blue-800',
            self::CONTRIBUTOR => 'bg-purple-100 text-purple-800',
            self::MODERATOR => 'bg-orange-100 text-orange-800',
            self::ADMIN => 'bg-red-100 text-red-800',
            self::OWNER => 'bg-black text-white',
        };
    }

    /**
     * 역할별 아이콘
     */
    public function getIcon(): string
    {
        return match($this) {
            self::GUEST => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
            self::MEMBER => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
            self::CONTRIBUTOR => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125',
            self::MODERATOR => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.333 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
            self::ADMIN => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            self::OWNER => 'M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z',
        };
    }

    /**
     * 모든 역할을 계층 순서대로 반환
     */
    public static function getAllInOrder(): array
    {
        return [
            self::GUEST,
            self::MEMBER,
            self::CONTRIBUTOR,
            self::MODERATOR,
            self::ADMIN,
            self::OWNER,
        ];
    }
}