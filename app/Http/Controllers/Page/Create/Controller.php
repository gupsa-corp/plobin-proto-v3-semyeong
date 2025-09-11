<?php

namespace App\Http\Controllers\Page\Create;

use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends \App\Http\Controllers\Controller
{
    public function __invoke($id, $projectId, Request $request)
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

            // 요청에서 parent_id와 title 가져오기
            $parentId = $request->input('parent_id');
            $title = $request->input('title', '새 페이지');

            // 페이지 순서 계산 (같은 parent_id를 가진 페이지들 기준)
            $maxSortOrder = ProjectPage::where('project_id', $projectId)
                ->where(function($query) use ($parentId) {
                    if ($parentId) {
                        $query->where('parent_id', $parentId);
                    } else {
                        $query->whereNull('parent_id');
                    }
                })
                ->max('sort_order');
            
            $sortOrder = ($maxSortOrder ?? 0) + 1;

            // 새 페이지 생성
            $page = ProjectPage::create([
                'project_id' => $projectId,
                'title' => $title,
                'slug' => 'new-page-' . time(), // 고유한 slug 생성
                'content' => '',
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
                'user_id' => Auth::id() // 현재 사용자 ID 추가
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => route('project.dashboard.page', [
                    'id' => $id,
                    'projectId' => $projectId,
                    'pageId' => $page->id
                ])
            ]);

        } catch (\Exception $e) {
            Log::error('페이지 생성 오류: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => '페이지 생성 중 오류가 발생했습니다.']);
        }
    }
}
