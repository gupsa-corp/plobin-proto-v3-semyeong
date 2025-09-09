<?php

namespace App\Http\CoreApi\Sandbox;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Models\SandboxCustomScreen;
use App\Services\CustomScreenRenderer;

class CustomScreenPreviewController
{
    public function show($id)
    {
        try {
            $screen = SandboxCustomScreen::find($id);

            if (!$screen) {
                abort(404, '커스텀 화면을 찾을 수 없습니다.');
            }

            if (!$screen->fileExists()) {
                abort(404, '커스텀 화면 파일을 찾을 수 없습니다.');
            }

            // CustomScreenRenderer를 사용하여 파일 기반 콘텐츠 렌더링
            $renderer = new CustomScreenRenderer();
            $customContent = $renderer->render($screen->getFullFilePath(), [
                'title' => $screen->title,
                'description' => $screen->description,
                'organizations' => collect([
                    ['id' => 1, 'name' => '샘플 조직 1', 'members' => 15],
                    ['id' => 2, 'name' => '샘플 조직 2', 'members' => 8],
                ]),
                'projects' => collect([
                    ['id' => 1, 'name' => '프로젝트 A', 'status' => '진행중'],
                    ['id' => 2, 'name' => '프로젝트 B', 'status' => '완료'],
                ]),
                'users' => collect([
                    ['id' => 1, 'name' => '홍길동', 'email' => 'hong@example.com'],
                    ['id' => 2, 'name' => '김영희', 'email' => 'kim@example.com'],
                ]),
                'activities' => collect([
                    ['action' => '새 프로젝트 생성', 'user' => '홍길동', 'timestamp' => '5분 전'],
                    ['action' => '사용자 추가', 'user' => '김영희', 'timestamp' => '1시간 전'],
                ])
            ]);

            // 미리보기 페이지를 표시 (헤더/푸터 없이)
            return view('700-page-sandbox.714-page-custom-screen-preview.000-index', [
                'screen' => $screen,
                'customContent' => $customContent
            ]);

        } catch (\Exception $e) {
            \Log::error('커스텀 화면 미리보기 오류', ['id' => $id, 'error' => $e->getMessage()]);
            abort(500, '미리보기를 불러올 수 없습니다: ' . $e->getMessage());
        }
    }

    private function generateComponentPath($screen)
    {
        // 화면 제목을 기반으로 컴포넌트 경로 생성
        $slug = $this->generateSlug($screen['title']);
        return "sandbox.custom-screens.{$slug}";
    }

    private function generateRoutePath($screen)
    {
        // 화면 제목을 기반으로 라우트 경로 생성
        $slug = $this->generateSlug($screen['title']);
        return "/sandbox/{$slug}";
    }

    private function generateSlug($title)
    {
        // 한글 제목을 URL 친화적인 슬러그로 변환
        $slugMap = [
            '조직 목록' => 'organizations-list',
            '사용자 목록' => 'users-list',
            '프로젝트 목록' => 'projects-list',
            '대시보드' => 'dashboard',
            '설정' => 'settings',
        ];

        return $slugMap[$title] ?? 'custom-screen-' . $screen['id'] ?? 'unknown';
    }

    private function hasGeneratedScreen($screen)
    {
        // 실제로 생성된 화면이 있는지 확인
        $slug = $this->generateSlug($screen['title']);
        $routeName = "sandbox.{$slug}";

        try {
            route($routeName);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
