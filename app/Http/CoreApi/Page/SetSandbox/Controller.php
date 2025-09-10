<?php

namespace App\Http\CoreApi\Page\SetSandbox;

use App\Models\Page;
use Illuminate\Support\Facades\Log;

class Controller extends \App\Http\Controller
{
    public function __invoke($id, $projectId, $pageId, Request $request)
    {
        try {
            // 페이지 존재 여부 확인
            $page = Page::where('id', $pageId)
                ->whereHas('project', function($query) use ($projectId, $id) {
                    $query->where('id', $projectId)
                          ->whereHas('organization', function($q) use ($id) {
                              $q->where('id', $id);
                          });
                })->first();

            if (!$page) {
                return redirect()->back()->with('error', '페이지를 찾을 수 없습니다.');
            }

            // 샌드박스 설정 저장
            $sandboxName = $request->input('sandbox', '');
            if (empty($sandboxName)) {
                $sandboxName = null; // 빈 값을 null로 변환
            }

            $page->update([
                'sandbox_name' => $sandboxName
            ]);

            return redirect()->back()->with('success', '샌드박스 설정이 저장되었습니다.');
        } catch (\Exception $e) {
            Log::error('샌드박스 설정 저장 오류', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', '설정 저장 중 오류가 발생했습니다.');
        }
    }
}