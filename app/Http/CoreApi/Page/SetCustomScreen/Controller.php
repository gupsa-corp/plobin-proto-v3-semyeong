<?php

namespace App\Http\CoreApi\Page\SetCustomScreen;

use App\Models\ProjectPage;
use Illuminate\Support\Facades\Log;

class Controller extends \App\Http\Controller
{
    public function __invoke($id, $projectId, $pageId, Request $request)
    {
        try {
            $page = ProjectPage::where('id', $pageId)
                ->whereHas('project', function($query) use ($projectId, $id) {
                    $query->where('id', $projectId)
                          ->whereHas('organization', function($q) use ($id) {
                              $q->where('id', $id);
                          });
                })->first();

            if (!$page) {
                return redirect()->back()->with('error', '페이지를 찾을 수 없습니다.');
            }

            // 샌드박스가 설정되어 있는지 확인
            if (empty($page->sandbox_name)) {
                return redirect()->back()->with('error', '커스텀 화면을 사용하려면 먼저 샌드박스를 선택해야 합니다.');
            }

            $customScreenId = $request->input('custom_screen', '');

            if (!empty($customScreenId)) {
                $page->update([
                    'custom_screen_id' => $customScreenId,
                    'custom_screen_type' => 'template',
                    'custom_screen_enabled' => true,
                    'custom_screen_applied_at' => now(),
                    'template_path' => null, // 필요시 추가 설정
                ]);
            } else {
                // 커스텀 화면 비활성화
                $page->update([
                    'custom_screen_id' => null,
                    'custom_screen_type' => null,
                    'custom_screen_enabled' => false,
                    'custom_screen_applied_at' => null,
                    'template_path' => null,
                ]);
            }

            return redirect()->back()->with('success', '커스텀 화면 설정이 저장되었습니다.');
        } catch (\Exception $e) {
            Log::error('커스텀 화면 설정 저장 오류', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', '설정 저장 중 오류가 발생했습니다.');
        }
    }
}