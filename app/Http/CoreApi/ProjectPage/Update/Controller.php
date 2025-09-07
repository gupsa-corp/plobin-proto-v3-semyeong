<?php

namespace App\Http\CoreApi\ProjectPage\Update;

use App\Http\CoreApi\Controller as BaseController;
use App\Models\ProjectPage;
use App\Exceptions\ApiException;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    /**
     * 페이지 수정
     */
    public function __invoke(Request $request, $projectId, $pageId)
    {
        $page = ProjectPage::where('project_id', $projectId)
                         ->find($pageId);

        if (!$page) {
            throw ApiException::notFound('페이지를 찾을 수 없습니다.');
        }

        $validated = $request->validated();

        // 이름이 변경되면 슬러그도 업데이트
        $updateData = [
            'title' => $validated['name'],
            'content' => $validated['description'] ?? $page->content
        ];

        if ($page->title !== $validated['name']) {
            $updateData['slug'] = Str::slug($validated['name']);
        }

        $page->update($updateData);

        // 응답에 프론트엔드 호환 형식 추가
        $pageData = $page->toArray();
        $pageData['name'] = $page->title;
        $pageData['icon'] = $validated['icon'] ?? 'fas fa-file';
        $pageData['description'] = $page->content;

        return response()->json([
            'success' => true,
            'data' => $pageData,
            'message' => '페이지가 수정되었습니다.'
        ]);
    }
}
