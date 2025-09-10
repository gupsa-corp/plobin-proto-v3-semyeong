<?php

namespace App\Http\Controllers\Page\SetCustomScreen;

use App\Models\ProjectPage;
use App\Services\SandboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Controller extends \App\Http\Controllers\Controller
{
    protected SandboxService $sandboxService;

    public function __construct(SandboxService $sandboxService)
    {
        $this->sandboxService = $sandboxService;
    }

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

            // 프로젝트 레벨에서 샌드박스가 설정되어 있는지 확인
            if (!$this->sandboxService->hasProjectSandbox($page->project)) {
                return redirect()->back()->with('error', '커스텀 화면을 사용하려면 먼저 프로젝트 설정에서 샌드박스를 선택해야 합니다.');
            }

            $customScreenFolder = $request->input('custom_screen', '');

            if ($this->sandboxService->setCustomScreen($page, $customScreenFolder)) {
                return redirect()->back()->with('success', '커스텀 화면 설정이 저장되었습니다.');
            } else {
                return redirect()->back()->with('error', '설정 저장 중 오류가 발생했습니다.');
            }

        } catch (\Exception $e) {
            Log::error('커스텀 화면 설정 저장 오류', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', '설정 저장 중 오류가 발생했습니다.');
        }
    }
}
