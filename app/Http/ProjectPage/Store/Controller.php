<?php

namespace App\Http\ProjectPage\Store;

use App\Http\Controllers\Controller as BaseController;
use App\Models\ProjectPage;
use App\Models\Project;
use App\Exceptions\ApiException;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    /**
     * 새 페이지 생성
     */
    public function __invoke(Request $request, $projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            throw ApiException::notFound('프로젝트를 찾을 수 없습니다.');
        }

        $validated = $request->validated();

        // 슬러그 생성 (중복 방지를 위해 유니크 ID 추가)
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug . '-' . uniqid();
        
        // 정렬 순서 설정 (부모가 같은 항목들 중 마지막 + 1)
        $parentId = $validated['parent_id'] ?? null;
        $lastOrder = ProjectPage::where('project_id', $projectId)
                               ->where('parent_id', $parentId)
                               ->max('sort_order') ?? 0;

        // config를 JSON으로 content에 저장 (설명도 포함)
        $content = json_encode([
            'type' => $validated['config']['type'] ?? 'default',
            'description' => $validated['description'] ?? '',
            'url' => $validated['config']['url'] ?? null,
            'icon' => $validated['icon'] ?? 'fas fa-file'
        ]);

        $page = ProjectPage::create([
            'project_id' => $projectId,
            'title' => $validated['name'],
            'slug' => $slug,
            'content' => $content,
            'status' => 'published',
            'user_id' => auth()->id() ?? 1,
            'parent_id' => $parentId,
            'sort_order' => $lastOrder + 1
        ]);

        // 응답에 필요한 데이터 추가
        $pageData = $page->toArray();
        $pageData['name'] = $page->title;
        $pageData['icon'] = $validated['icon'] ?? 'fas fa-file';
        $pageData['description'] = $validated['description'] ?? '';

        return response()->json([
            'success' => true,
            'data' => $pageData,
            'message' => '페이지가 생성되었습니다.'
        ], 201);
    }
}