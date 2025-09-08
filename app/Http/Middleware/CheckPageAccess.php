<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectPage;
use App\Models\Project;
use App\Services\AccessControlService;

class CheckPageAccess
{
    protected $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 인증된 사용자만 처리
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // 프로젝트 ID 추출
        $projectId = $request->route('projectId');
        if (!$projectId) {
            return $next($request);
        }

        $project = Project::find($projectId);
        if (!$project) {
            abort(404, '프로젝트를 찾을 수 없습니다.');
        }

        // 프로젝트 접근 권한 확인
        if (!$this->accessControlService->canUserAccessProject($user, $project)) {
            return $this->handleAccessDenied($request, '프로젝트 접근 권한이 없습니다.');
        }

        // 페이지별 접근 권한 확인 (페이지 ID가 있는 경우)
        $pageId = $request->route('pageId');
        if ($pageId) {
            $page = ProjectPage::find($pageId);
            if (!$page) {
                abort(404, '페이지를 찾을 수 없습니다.');
            }

            if (!$this->accessControlService->canUserAccessPage($user, $page)) {
                $requiredRole = $page->getRequiredMinimumRole();
                $message = $requiredRole 
                    ? "이 페이지는 {$requiredRole->getDisplayName()} 권한 이상이 필요합니다."
                    : '이 페이지에 접근할 권한이 없습니다.';
                
                return $this->handleAccessDenied($request, $message);
            }
        }

        return $next($request);
    }

    /**
     * 접근 거부 처리
     */
    private function handleAccessDenied(Request $request, string $message)
    {
        // AJAX 요청인 경우
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error' => $message,
                'code' => 403
            ], 403);
        }

        // 일반 요청인 경우
        return response()->view('errors.403-page-access', [
            'message' => $message,
            'project_id' => $request->route('id'),
            'project_name' => $request->route('id') ? Project::find($request->route('id'))->name ?? '알 수 없음' : '알 수 없음'
        ], 403);
    }
}