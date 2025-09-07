<?php

namespace App\Http\ProjectPage\Index;

use App\Http\Controllers\Controller as BaseController;
use App\Models\ProjectPage;
use App\Models\Project;
use App\Exceptions\ApiException;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    /**
     * 프로젝트의 페이지 목록 조회
     */
    public function __invoke(Request $request, $projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            throw ApiException::notFound('프로젝트를 찾을 수 없습니다.');
        }

        $parentId = $request->query('parent_id');
        
        // parent_id가 'null' 문자열이나 빈 문자열인 경우 null로 처리
        if ($parentId === 'null' || $parentId === '') {
            $parentId = null;
        } else if ($parentId !== null) {
            // parent_id가 있는 경우 정수로 변환
            $parentId = (int) $parentId;
        }
        
        $query = ProjectPage::where('project_id', $projectId);
        
        if ($parentId === null) {
            $query->whereNull('parent_id');
        } else {
            $query->where('parent_id', $parentId);
        }
        
        $pages = $query->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => $pages,
            'debug' => [
                'parent_id_param' => $request->query('parent_id'),
                'processed_parent_id' => $parentId,
                'count' => $pages->count()
            ]
        ]);
    }
}