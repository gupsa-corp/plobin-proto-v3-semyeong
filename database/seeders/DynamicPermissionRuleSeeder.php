<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DynamicPermissionRule;

class DynamicPermissionRuleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $rules = [
            // 회원 관리 규칙
            [
                'resource_type' => 'member_management',
                'action' => 'view',
                'required_permissions' => ['view members'],
                'description' => '회원 목록 조회 권한',
            ],
            [
                'resource_type' => 'member_management', 
                'action' => 'invite',
                'required_permissions' => ['invite members'],
                'description' => '회원 초대 권한',
            ],
            [
                'resource_type' => 'member_management',
                'action' => 'edit',
                'required_permissions' => ['edit members'],
                'description' => '회원 정보 수정 권한',
            ],
            [
                'resource_type' => 'member_management',
                'action' => 'delete',
                'required_permissions' => ['delete members'],
                'description' => '회원 삭제 권한',
            ],
            [
                'resource_type' => 'member_management',
                'action' => 'change_permission',
                'required_permissions' => ['manage member permissions'],
                'description' => '회원 권한 변경',
            ],

            // 프로젝트 관리 규칙
            [
                'resource_type' => 'project_management',
                'action' => 'view',
                'required_permissions' => ['view projects'],
                'description' => '프로젝트 조회 권한',
            ],
            [
                'resource_type' => 'project_management',
                'action' => 'create',
                'required_permissions' => ['create projects'],
                'description' => '프로젝트 생성 권한',
            ],
            [
                'resource_type' => 'project_management',
                'action' => 'edit',
                'required_permissions' => ['edit projects'],
                'description' => '프로젝트 수정 권한',
            ],
            [
                'resource_type' => 'project_management',
                'action' => 'delete',
                'required_permissions' => ['delete projects'],
                'description' => '프로젝트 삭제 권한',
            ],
            [
                'resource_type' => 'project_management',
                'action' => 'assign_members',
                'required_permissions' => ['assign project members'],
                'description' => '프로젝트 멤버 배정 권한',
            ],
            [
                'resource_type' => 'project_management',
                'action' => 'archive',
                'required_permissions' => ['archive projects'],
                'description' => '프로젝트 아카이브 권한',
            ],
            [
                'resource_type' => 'project_management',
                'action' => 'restore',
                'required_permissions' => ['restore projects'],
                'description' => '프로젝트 복원 권한',
            ],

            // 페이지 관리 규칙
            [
                'resource_type' => 'page_management',
                'action' => 'view',
                'required_permissions' => ['view pages'],
                'description' => '페이지 조회 권한',
            ],
            [
                'resource_type' => 'page_management',
                'action' => 'create',
                'required_permissions' => ['create pages'],
                'description' => '페이지 생성 권한',
            ],
            [
                'resource_type' => 'page_management',
                'action' => 'edit',
                'required_permissions' => ['edit pages'],
                'description' => '페이지 수정 권한',
            ],
            [
                'resource_type' => 'page_management',
                'action' => 'delete',
                'required_permissions' => ['delete pages'],
                'description' => '페이지 삭제 권한',
            ],
            [
                'resource_type' => 'page_management',
                'action' => 'publish',
                'required_permissions' => ['publish pages'],
                'description' => '페이지 발행 권한',
            ],
            [
                'resource_type' => 'page_management',
                'action' => 'unpublish',
                'required_permissions' => ['unpublish pages'],
                'description' => '페이지 발행 취소 권한',
            ],
            [
                'resource_type' => 'page_management',
                'action' => 'manage_versions',
                'required_permissions' => ['manage page versions'],
                'description' => '페이지 버전 관리 권한',
            ],
            [
                'resource_type' => 'page_management',
                'action' => 'view_analytics',
                'required_permissions' => ['view page analytics'],
                'description' => '페이지 분석 조회 권한',
            ],

            // 결제 관리 규칙
            [
                'resource_type' => 'billing_management',
                'action' => 'view',
                'required_permissions' => ['view billing'],
                'description' => '결제 정보 조회 권한',
            ],
            [
                'resource_type' => 'billing_management',
                'action' => 'edit',
                'required_permissions' => ['edit billing'],
                'description' => '결제 정보 수정 권한',
            ],
            [
                'resource_type' => 'billing_management',
                'action' => 'download_receipts',
                'required_permissions' => ['download receipts'],
                'description' => '영수증 다운로드 권한',
            ],
            [
                'resource_type' => 'billing_management',
                'action' => 'change_plan',
                'required_permissions' => ['change subscription plan'],
                'description' => '구독 플랜 변경 권한',
            ],

            // 조직 설정 규칙
            [
                'resource_type' => 'organization_settings',
                'action' => 'view',
                'required_permissions' => ['view organization settings'],
                'description' => '조직 설정 조회 권한',
            ],
            [
                'resource_type' => 'organization_settings',
                'action' => 'edit',
                'required_permissions' => ['edit organization settings'],
                'description' => '조직 설정 수정 권한',
            ],
            [
                'resource_type' => 'organization_settings',
                'action' => 'delete',
                'required_permissions' => ['delete organization'],
                'description' => '조직 삭제 권한',
            ],

            // 공개 접근 규칙 (로그인 불필요)
            [
                'resource_type' => 'public_access',
                'action' => 'view_projects',
                'required_permissions' => ['view public projects'],
                'description' => '공개 프로젝트 조회 권한',
                'custom_logic' => json_encode([
                    'allow_guest' => true,
                    'check_public_status' => true
                ])
            ],
            [
                'resource_type' => 'public_access',
                'action' => 'view_pages',
                'required_permissions' => ['view public pages'],
                'description' => '공개 페이지 조회 권한',
                'custom_logic' => json_encode([
                    'allow_guest' => true,
                    'check_public_status' => true
                ])
            ],
            [
                'resource_type' => 'public_access',
                'action' => 'view_organization',
                'required_permissions' => ['view public organization info'],
                'description' => '공개 조직 정보 조회 권한',
                'custom_logic' => json_encode([
                    'allow_guest' => true,
                    'check_public_status' => true
                ])
            ],
            [
                'resource_type' => 'public_access',
                'action' => 'submit_forms',
                'required_permissions' => ['submit public forms'],
                'description' => '공개 폼 제출 권한',
                'custom_logic' => json_encode([
                    'allow_guest' => true,
                    'check_form_status' => true
                ])
            ],
            [
                'resource_type' => 'public_access',
                'action' => 'view_analytics',
                'required_permissions' => ['view public analytics'],
                'description' => '공개 분석 데이터 조회 권한',
                'custom_logic' => json_encode([
                    'allow_guest' => true,
                    'check_analytics_public' => true
                ])
            ],
        ];

        foreach ($rules as $rule) {
            DynamicPermissionRule::firstOrCreate(
                [
                    'resource_type' => $rule['resource_type'],
                    'action' => $rule['action']
                ],
                $rule
            );
        }

        $this->command->info('Dynamic permission rules created.');
    }
}