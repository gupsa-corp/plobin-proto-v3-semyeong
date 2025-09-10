<?php

namespace App\Http\Controllers\ProjectPage\UpdateOrder;

use App\Http\Controllers\Controller as BaseController;
use App\Models\ProjectPage;
use App\Exceptions\ApiException;

class Controller extends BaseController
{
    /**
     * 페이지 순서 변경
     */
    public function __invoke(Request $request, $projectId)
    {
        $validated = $request->validated();

        foreach ($validated['pages'] as $pageData) {
            ProjectPage::where('project_id', $projectId)
                     ->where('id', $pageData['id'])
                     ->update(['sort_order' => $pageData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => '페이지 순서가 변경되었습니다.'
        ]);
    }
}
