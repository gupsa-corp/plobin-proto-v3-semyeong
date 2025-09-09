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
            // 템플릿 ID인지 확인 (template_xxx 형식)
            if (str_starts_with($id, 'template_')) {
                return $this->showTemplateScreen($id);
            }

            // DB 기반 커스텀 화면 처리
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

    private function showTemplateScreen($id)
    {
        // template_xxx에서 실제 ID 추출
        $screenId = str_replace('template_', '', $id);
        
        // 템플릿 파일 경로 찾기
        $templatePath = storage_path('sandbox/storage-sandbox-template/frontend');
        $folders = File::directories($templatePath);
        
        $templateFolder = null;
        foreach ($folders as $folder) {
            $folderName = basename($folder);
            $parts = explode('-', $folderName, 3);
            if (($parts[0] ?? '') === $screenId) {
                $templateFolder = $folder;
                break;
            }
        }
        
        if (!$templateFolder) {
            abort(404, '템플릿 화면을 찾을 수 없습니다.');
        }
        
        $contentFile = $templateFolder . '/000-content.blade.php';
        if (!File::exists($contentFile)) {
            abort(404, '템플릿 파일이 존재하지 않습니다.');
        }
        
        // 템플릿 파일 내용 직접 렌더링
        $content = File::get($contentFile);
        
        // 샘플 데이터 설정
        $sampleData = [
            'title' => str_replace('-', ' ', basename($templateFolder)),
            'users' => [
                ['id' => 1, 'name' => '홍길동', 'email' => 'hong@example.com', 'status' => 'active'],
                ['id' => 2, 'name' => '김철수', 'email' => 'kim@example.com', 'status' => 'inactive'],
                ['id' => 3, 'name' => '이영희', 'email' => 'lee@example.com', 'status' => 'active']
            ],
            'projects' => [
                ['id' => 1, 'name' => '프로젝트 A', 'status' => 'active', 'progress' => 75],
                ['id' => 2, 'name' => '프로젝트 B', 'status' => 'pending', 'progress' => 30],
                ['id' => 3, 'name' => '프로젝트 C', 'status' => 'completed', 'progress' => 100]
            ]
        ];
        
        // 미리보기 페이지를 표시 (템플릿 전용)
        return view('700-page-sandbox.715-page-template-preview.000-index', [
            'templateContent' => $content,
            'templateData' => $sampleData,
            'templateId' => $id,
            'templateFolder' => basename($templateFolder)
        ]);
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
