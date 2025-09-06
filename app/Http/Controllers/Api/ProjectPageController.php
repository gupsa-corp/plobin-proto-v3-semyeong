<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectPage;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectPageController extends Controller
{
    /**
     * 프로젝트의 페이지 목록 조회
     */
    public function index(Request $request, $projectId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $pages = ProjectPage::where('project_id', $projectId)
                               ->tabs()
                               ->get();

            return response()->json([
                'success' => true,
                'data' => $pages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '페이지 목록을 불러올 수 없습니다.'
            ], 500);
        }
    }

    /**
     * 새 페이지 생성
     */
    public function store(Request $request, $projectId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'config' => 'nullable|array',
                'parent_id' => 'nullable|integer|exists:project_pages,id'
            ]);

            $project = Project::findOrFail($projectId);

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '페이지 생성에 실패했습니다: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 페이지 상세 조회
     */
    public function show($projectId, $pageId)
    {
        try {
            $page = ProjectPage::where('project_id', $projectId)
                             ->findOrFail($pageId);

            return response()->json([
                'success' => true,
                'data' => $page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '페이지를 찾을 수 없습니다.'
            ], 404);
        }
    }

    /**
     * 페이지 수정
     */
    public function update(Request $request, $projectId, $pageId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'nullable|string|max:255',
                'description' => 'nullable|string'
            ]);

            $page = ProjectPage::where('project_id', $projectId)
                             ->findOrFail($pageId);

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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '페이지 수정에 실패했습니다.'
            ], 500);
        }
    }

    /**
     * 페이지 삭제
     */
    public function destroy($projectId, $pageId)
    {
        try {
            $page = ProjectPage::where('project_id', $projectId)
                             ->findOrFail($pageId);

            $page->delete();

            return response()->json([
                'success' => true,
                'message' => '페이지가 삭제되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '페이지 삭제에 실패했습니다.'
            ], 500);
        }
    }

    /**
     * 페이지 순서 변경
     */
    public function updateOrder(Request $request, $projectId)
    {
        try {
            $validated = $request->validate([
                'pages' => 'required|array',
                'pages.*.id' => 'required|integer',
                'pages.*.sort_order' => 'required|integer'
            ]);

            foreach ($validated['pages'] as $pageData) {
                ProjectPage::where('project_id', $projectId)
                         ->where('id', $pageData['id'])
                         ->update(['sort_order' => $pageData['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => '페이지 순서가 변경되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '페이지 순서 변경에 실패했습니다.'
            ], 500);
        }
    }
}
