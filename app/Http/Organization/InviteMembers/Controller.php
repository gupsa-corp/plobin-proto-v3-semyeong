<?php

namespace App\Http\Organization\InviteMembers;

use App\Http\Controllers\ApiController;
use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationMember;
use App\Enums\OrganizationPermission;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;

class Controller extends ApiController
{
    public function __invoke(Request $request, $organizationId)
    {
        // 조직 존재 여부 확인
        $organization = Organization::find($organizationId);
        if (!$organization) {
            throw ApiException::notFound('조직을 찾을 수 없습니다.');
        }

        // 현재 사용자의 권한 확인
        $currentUser = auth()->user();
        $currentMember = OrganizationMember::where('organization_id', $organizationId)
            ->where('user_id', $currentUser->id)
            ->first();

        if (!$currentMember || !OrganizationPermission::from($currentMember->permission_level)->canManageMembers()) {
            throw ApiException::forbidden('멤버 초대 권한이 없습니다.');
        }

        $invitations = $request->get('invitations');
        $results = [
            'successful' => [],
            'failed' => [],
            'already_exists' => []
        ];

        DB::beginTransaction();

        try {
            foreach ($invitations as $invitation) {
                $email = $invitation['email'];
                $permissionLevel = $invitation['permission_level'];
                $message = $invitation['message'] ?? null;

                // 권한 레벨 유효성 확인
                try {
                    $permission = OrganizationPermission::from($permissionLevel);
                } catch (\ValueError $e) {
                    $results['failed'][] = [
                        'email' => $email,
                        'reason' => '유효하지 않은 권한 레벨입니다.'
                    ];
                    continue;
                }

                // 권한 부여 가능 여부 확인
                $currentPermission = OrganizationPermission::from($currentMember->permission_level);
                if (!$this->canGrantPermission($currentPermission, $permission)) {
                    $results['failed'][] = [
                        'email' => $email,
                        'reason' => '해당 권한을 부여할 수 있는 권한이 없습니다.'
                    ];
                    continue;
                }

                // 사용자 찾기
                $user = User::where('email', $email)->first();
                
                if (!$user) {
                    $results['failed'][] = [
                        'email' => $email,
                        'reason' => '등록되지 않은 사용자입니다. 먼저 회원가입을 진행해주세요.'
                    ];
                    continue;
                }

                // 이미 조직 멤버인지 확인
                $existingMember = OrganizationMember::where('organization_id', $organizationId)
                    ->where('user_id', $user->id)
                    ->first();

                if ($existingMember) {
                    $statusText = match($existingMember->invitation_status) {
                        'pending' => '이미 초대된 사용자입니다',
                        'accepted' => '이미 조직의 멤버입니다',
                        'declined' => '초대를 거절한 사용자입니다',
                        default => '이미 관련 기록이 있는 사용자입니다'
                    };

                    $results['already_exists'][] = [
                        'email' => $email,
                        'name' => $user->name,
                        'status' => $existingMember->invitation_status,
                        'reason' => $statusText
                    ];
                    continue;
                }

                // 멤버 초대 생성
                OrganizationMember::create([
                    'organization_id' => $organizationId,
                    'user_id' => $user->id,
                    'permission_level' => $permissionLevel,
                    'invitation_status' => 'pending',
                    'invited_at' => now()
                ]);

                $results['successful'][] = [
                    'email' => $email,
                    'name' => $user->name,
                    'permission' => [
                        'level' => $permission->value,
                        'label' => $permission->getLabel()
                    ]
                ];
            }

            // 멤버 수 업데이트
            $organization->update([
                'members_count' => $organization->members()->count()
            ]);

            DB::commit();

            return $this->success([
                'total_processed' => count($invitations),
                'successful_count' => count($results['successful']),
                'failed_count' => count($results['failed']),
                'already_exists_count' => count($results['already_exists']),
                'results' => $results
            ], '초대 처리가 완료되었습니다.');

        } catch (\Exception $e) {
            DB::rollback();
            throw ApiException::internalError('초대 처리 중 오류가 발생했습니다.');
        }
    }

    /**
     * 권한 부여 가능 여부 확인
     */
    private function canGrantPermission(OrganizationPermission $currentPermission, OrganizationPermission $targetPermission): bool
    {
        // 자신보다 높은 권한을 부여할 수 없음
        if ($targetPermission->value >= $currentPermission->value) {
            return false;
        }

        // 플랫폼 관리자만이 플랫폼 관리자 권한을 부여할 수 있음
        if ($targetPermission->value >= OrganizationPermission::PLATFORM_ADMIN->value) {
            return $currentPermission->value >= OrganizationPermission::PLATFORM_ADMIN->value;
        }

        // 조직 소유자만이 조직 소유자 권한을 부여할 수 있음
        if ($targetPermission->value >= OrganizationPermission::ORGANIZATION_OWNER->value) {
            return $currentPermission->value >= OrganizationPermission::ORGANIZATION_OWNER->value;
        }

        // 조직 관리자는 조직 관리자까지만 부여 가능
        if ($currentPermission->value >= OrganizationPermission::ORGANIZATION_ADMIN->value) {
            return $targetPermission->value < OrganizationPermission::ORGANIZATION_OWNER->value;
        }

        return false;
    }
}