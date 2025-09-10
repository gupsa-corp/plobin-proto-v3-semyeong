<?php

namespace App\Http\Controllers\Organization\Admin\Projects;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function __invoke(Request $request, $id)
    {
        $projects = Project::where('organization_id', $id)
            ->with(['user', 'organization'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 조직 선택 드롭다운을 위한 모든 조직 목록
        $organizations = Organization::select(['organizations.id', 'organizations.name'])
            ->join('organization_members', 'organizations.id', '=', 'organization_members.organization_id')
            ->where('organization_members.user_id', Auth::id())
            ->where('organization_members.invitation_status', 'accepted')
            ->orderBy('organizations.created_at', 'desc')
            ->get();

        return view('800-page-organization-admin.804-page-projects.000-index', compact('projects', 'id', 'organizations'));
    }
}