<?php

namespace App\Http\CoreApi\Page\Delete;

use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\OrganizationMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends \App\Http\Controller
{
    public function __invoke($id, $projectId, $pageId)
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

            // 하위 페이지가 있는지 확인
            $hasChildren = ProjectPage::where('parent_id', $pageId)->exists();
            if ($hasChildren) {
                return response()->json(['success' => false, 'error' => '하위 페이지가 있는 페이지는 삭제할 수 없습니다. 먼저 하위 페이지를 삭제해주세요.']);
            }

            // 페이지 삭제
            $page->delete();

            // 프로젝트의 첫 번째 페이지로 리다이렉트할 URL 생성
            $firstPage = ProjectPage::where('project_id', $projectId)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->first();

            $redirectUrl = $firstPage
                ? route('project.dashboard.page', [
                    'id' => $id,
                    'projectId' => $projectId,
                    'pageId' => $firstPage->id
                ])
                : route('project.dashboard', ['id' => $id, 'projectId' => $projectId]);

            return response()->json([
                'success' => true,
                'message' => '페이지가 삭제되었습니다.',
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Exception $e) {
            Log::error('페이지 삭제 오류: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => '페이지 삭제 중 오류가 발생했습니다.']);
        }
    }
}