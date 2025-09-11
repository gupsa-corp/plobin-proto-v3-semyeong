<?php

namespace App\Http\Controllers\Organization\Admin\Permissions\Management;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function __invoke(Request $request, $id)
    {
        // 조직 선택 드롭다운을 위한 모든 조직 목록
        $organizations = Organization::select(['organizations.id', 'organizations.name'])
            ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
            ->where('organization_members.user_id', Auth::id())
            ->where('organization_members.invitation_status', 'accepted')
            ->orderBy('organizations.created_at', 'desc')
            ->get();

        return view('800-page-organization-admin.807-page-permissions-management.000-index', compact('organizations'))->with('activeTab', 'management');
    }
}