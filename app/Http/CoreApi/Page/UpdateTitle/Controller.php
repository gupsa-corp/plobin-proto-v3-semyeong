<?php

namespace App\Http\CoreApi\Page\UpdateTitle;

use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\OrganizationMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends \App\Http\Controller
{
    public function __invoke($id, $projectId, $pageId, Request $request)
    {
        try {
            // 프로젝트 존재 확인
            $project = Project::where('id', $projectId)
                ->whereHas('organization', function($query) use ($id) {
                    $query->where('id', $id);
                })
                ->first();

            if (!$project) {
                return response()->json(['success' => false, 'error' => '프로젝트를 찾을 수 없습니다.']);
            }

            // 권한 확인 (프로젝트 소유자이거나 조직 멤버여야 함)
            $hasAccess = $project->user_id === Auth::id() ||
                OrganizationMember::where('organization_id', $id)
                    ->where('user_id', Auth::id())
                    ->where('invitation_status', 'accepted')
                    ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'error' => '권한이 없습니다.']);
            }

            // 페이지 존재 확인
            $page = ProjectPage::where('id', $pageId)
                ->where('project_id', $projectId)
                ->first();

            if (!$page) {
                return response()->json(['success' => false, 'error' => '페이지를 찾을 수 없습니다.']);
            }

            // 제목 업데이트
            $title = $request->input('title');
            if (empty($title)) {
                return response()->json(['success' => false, 'error' => '제목을 입력해주세요.']);
            }

            $page->title = $title;
            $page->save();

            return response()->json(['success' => true, 'message' => '페이지 제목이 변경되었습니다.']);

        } catch (\Exception $e) {
            Log::error('페이지 제목 업데이트 오류: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => '페이지 제목 변경 중 오류가 발생했습니다.']);
        }
    }
}