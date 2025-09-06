<?php

namespace App\Http\Organization\SearchMembers;

use App\Http\Controllers\ApiController;
use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationMember;
use App\Enums\OrganizationPermission;
use App\Exceptions\ApiException;

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
            throw ApiException::forbidden('멤버 관리 권한이 없습니다.');
        }

        $query = $request->get('query');
        $limit = $request->get('limit', 10);

        // 사용자 검색 (이메일 또는 이름으로)
        $users = User::where(function ($q) use ($query) {
            $q->where('email', 'LIKE', "%{$query}%")
              ->orWhere('name', 'LIKE', "%{$query}%")
              ->orWhere('nickname', 'LIKE', "%{$query}%");
        })
        ->limit($limit)
        ->get();

        // 각 사용자의 조직 내 상태 확인
        $results = $users->map(function ($user) use ($organizationId) {
            $membership = OrganizationMember::where('organization_id', $organizationId)
                ->where('user_id', $user->id)
                ->first();

            $status = 'available'; // 초대 가능
            $statusText = '초대 가능';
            $permission = null;

            if ($membership) {
                switch ($membership->invitation_status) {
                    case 'pending':
                        $status = 'pending';
                        $statusText = '초대 대기 중';
                        break;
                    case 'accepted':
                        $status = 'member';
                        $statusText = '이미 멤버';
                        $permission = OrganizationPermission::from($membership->permission_level);
                        break;
                    case 'declined':
                        $status = 'declined';
                        $statusText = '초대 거절';
                        break;
                }
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar_url ?? null,
                'status' => $status,
                'status_text' => $statusText,
                'permission' => $permission ? [
                    'level' => $permission->value,
                    'label' => $permission->getLabel(),
                    'badge_color' => $permission->getBadgeColor()
                ] : null,
                'joined_at' => $membership?->joined_at?->format('Y.m.d'),
                'invited_at' => $membership?->invited_at?->format('Y.m.d')
            ];
        });

        return $this->success([
            'users' => $results,
            'total' => $results->count(),
            'query' => $query
        ]);
    }
}