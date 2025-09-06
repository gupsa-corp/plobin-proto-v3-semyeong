<?php

namespace App\Enums;

enum OrganizationPermission: int
{
    // 없음 (조직에 초대된 상태) (~99)
    case INVITED = 0;
    
    // 사용자 (100~199)
    case USER = 100;
    case USER_ADVANCED = 150;
    
    // 서비스 매니저 (200~299)
    case SERVICE_MANAGER = 200;
    case SERVICE_MANAGER_SENIOR = 250;
    
    // 조직 관리자 (300~399)
    case ORGANIZATION_ADMIN = 300;
    case ORGANIZATION_ADMIN_SENIOR = 350;
    
    // 조직 관리자 (소유자) (400~499)
    case ORGANIZATION_OWNER = 400;
    case ORGANIZATION_OWNER_FOUNDER = 450;
    
    // 플랫폼 관리자 (500~599)
    case PLATFORM_ADMIN = 500;
    case PLATFORM_ADMIN_SUPER = 550;

    public function getLabel(): string
    {
        return match($this) {
            self::INVITED => '초대됨',
            self::USER => '사용자',
            self::USER_ADVANCED => '고급 사용자',
            self::SERVICE_MANAGER => '서비스 매니저',
            self::SERVICE_MANAGER_SENIOR => '선임 서비스 매니저',
            self::ORGANIZATION_ADMIN => '조직 관리자',
            self::ORGANIZATION_ADMIN_SENIOR => '선임 조직 관리자',
            self::ORGANIZATION_OWNER => '조직 소유자',
            self::ORGANIZATION_OWNER_FOUNDER => '조직 창립자',
            self::PLATFORM_ADMIN => '플랫폼 관리자',
            self::PLATFORM_ADMIN_SUPER => '최고 관리자',
        };
    }

    public function getShortLabel(): string
    {
        return match($this) {
            self::INVITED => '초대됨',
            self::USER => '사용자',
            self::USER_ADVANCED => '사용자+',
            self::SERVICE_MANAGER => '서비스 매니저',
            self::SERVICE_MANAGER_SENIOR => '서비스 매니저+',
            self::ORGANIZATION_ADMIN => '관리자',
            self::ORGANIZATION_ADMIN_SENIOR => '관리자+',
            self::ORGANIZATION_OWNER => '소유자',
            self::ORGANIZATION_OWNER_FOUNDER => '창립자',
            self::PLATFORM_ADMIN => '플랫폼 관리자',
            self::PLATFORM_ADMIN_SUPER => '최고 관리자',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::INVITED => '조직에 초대되었으나 아직 권한이 부여되지 않음',
            self::USER => '기본 사용자 권한, 프로젝트 참여 및 기본 기능 사용',
            self::USER_ADVANCED => '고급 사용자 권한, 추가 기능 접근 가능',
            self::SERVICE_MANAGER => '서비스 관리 권한, 프로젝트 관리 및 팀 리딩',
            self::SERVICE_MANAGER_SENIOR => '선임 서비스 매니저, 고급 프로젝트 관리 권한',
            self::ORGANIZATION_ADMIN => '조직 관리 권한, 멤버 관리 및 조직 설정',
            self::ORGANIZATION_ADMIN_SENIOR => '선임 조직 관리자, 고급 조직 관리 권한',
            self::ORGANIZATION_OWNER => '조직 소유자, 모든 조직 관리 권한',
            self::ORGANIZATION_OWNER_FOUNDER => '조직 창립자, 최고 조직 권한',
            self::PLATFORM_ADMIN => '플랫폼 관리자, 시스템 관리 권한',
            self::PLATFORM_ADMIN_SUPER => '최고 관리자, 모든 시스템 권한',
        };
    }

    public function getBadgeColor(): string
    {
        return match($this) {
            self::INVITED => 'yellow',
            self::USER, self::USER_ADVANCED => 'blue',
            self::SERVICE_MANAGER, self::SERVICE_MANAGER_SENIOR => 'green',
            self::ORGANIZATION_ADMIN, self::ORGANIZATION_ADMIN_SENIOR => 'purple',
            self::ORGANIZATION_OWNER, self::ORGANIZATION_OWNER_FOUNDER => 'red',
            self::PLATFORM_ADMIN, self::PLATFORM_ADMIN_SUPER => 'gray',
        };
    }

    public function getLevel(): int
    {
        return match($this) {
            self::INVITED => 0,
            self::USER, self::USER_ADVANCED => 1,
            self::SERVICE_MANAGER, self::SERVICE_MANAGER_SENIOR => 2,
            self::ORGANIZATION_ADMIN, self::ORGANIZATION_ADMIN_SENIOR => 3,
            self::ORGANIZATION_OWNER, self::ORGANIZATION_OWNER_FOUNDER => 4,
            self::PLATFORM_ADMIN, self::PLATFORM_ADMIN_SUPER => 5,
        };
    }

    public function getLevelName(): string
    {
        return match($this) {
            self::INVITED => '없음',
            self::USER, self::USER_ADVANCED => '사용자',
            self::SERVICE_MANAGER, self::SERVICE_MANAGER_SENIOR => '서비스 매니저',
            self::ORGANIZATION_ADMIN, self::ORGANIZATION_ADMIN_SENIOR => '조직 관리자',
            self::ORGANIZATION_OWNER, self::ORGANIZATION_OWNER_FOUNDER => '조직 소유자',
            self::PLATFORM_ADMIN, self::PLATFORM_ADMIN_SUPER => '플랫폼 관리자',
        };
    }

    public function hasPermission(OrganizationPermission $requiredPermission): bool
    {
        return $this->value >= $requiredPermission->value;
    }

    public function canManageMembers(): bool
    {
        return $this->value >= self::ORGANIZATION_ADMIN->value;
    }

    public function canManagePermissions(): bool
    {
        return $this->value >= self::ORGANIZATION_ADMIN->value;
    }

    public function canManageBilling(): bool
    {
        return $this->value >= self::ORGANIZATION_OWNER->value;
    }

    public function canManageProjects(): bool
    {
        return $this->value >= self::SERVICE_MANAGER->value;
    }

    public function canDeleteOrganization(): bool
    {
        return $this->value >= self::ORGANIZATION_OWNER->value;
    }

    public static function getAllByLevel(): array
    {
        return [
            0 => [self::INVITED],
            1 => [self::USER, self::USER_ADVANCED],
            2 => [self::SERVICE_MANAGER, self::SERVICE_MANAGER_SENIOR],
            3 => [self::ORGANIZATION_ADMIN, self::ORGANIZATION_ADMIN_SENIOR],
            4 => [self::ORGANIZATION_OWNER, self::ORGANIZATION_OWNER_FOUNDER],
            5 => [self::PLATFORM_ADMIN, self::PLATFORM_ADMIN_SUPER],
        ];
    }

    public static function getSelectOptions(): array
    {
        $options = [];
        foreach (self::cases() as $permission) {
            $options[$permission->value] = $permission->getLabel();
        }
        return $options;
    }
}