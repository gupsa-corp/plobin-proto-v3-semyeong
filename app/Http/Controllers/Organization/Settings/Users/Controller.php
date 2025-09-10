<?php

namespace App\Http\Controllers\Organization\Settings\Users;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function __invoke(Request $request, $id)
    {
        // 조직 정보 가져오기
        $organization = Organization::findOrFail($id);
        
        // 현재 사용자가 해당 조직의 멤버인지 확인
        $currentMember = OrganizationMember::where('organization_id', $id)
            ->where('user_id', Auth::id())
            ->where('invitation_status', 'accepted')
            ->first();
        
        if (!$currentMember) {
            abort(403, '이 조직에 접근할 권한이 없습니다.');
        }
        
        // 조직 멤버들 가져오기
        $members = OrganizationMember::where('organization_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // 조직 선택 드롭다운을 위한 모든 조직 목록
        $organizations = Organization::select(['organizations.id', 'organizations.name'])
            ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
            ->where('organization_members.user_id', Auth::id())
            ->where('organization_members.invitation_status', 'accepted')
            ->orderBy('organizations.created_at', 'desc')
            ->get();

        return view('800-page-organization-admin.809-page-settings-users.000-index', compact('organization', 'members', 'organizations', 'id'));
    }
}