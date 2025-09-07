<?php

namespace App\Http\ProjectPage\Destroy;

use App\Http\Controllers\Controller as BaseController;
use App\Models\ProjectPage;
use App\Exceptions\ApiException;

class Controller extends BaseController
{
    /**
     * 페이지 삭제
     */
    public function __invoke($projectId, $pageId)
    {
        $page = ProjectPage::where('project_id', $projectId)
                         ->find($pageId);

        if (!$page) {
            throw ApiException::notFound('페이지를 찾을 수 없습니다.');
        }

        $page->delete();

        return response()->json([
            'success' => true,
            'message' => '페이지가 삭제되었습니다.'
        ]);
    }
}