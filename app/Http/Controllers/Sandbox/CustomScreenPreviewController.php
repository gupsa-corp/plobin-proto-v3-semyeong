<?php

namespace App\Http\Controllers\Sandbox;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class CustomScreenPreviewController
{
    public function show($id)
    {
        $currentStorage = Session::get('sandbox_storage', 'template');
        $dbPath = storage_path("sandbox/storage-sandbox-{$currentStorage}/database/sqlite.db");

        if (!File::exists($dbPath)) {
            abort(404, '커스텀 화면을 찾을 수 없습니다.');
        }

        try {
            $pdo = new \PDO("sqlite:$dbPath");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare('SELECT * FROM custom_screens WHERE id = ?');
            $stmt->execute([$id]);
            $screen = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$screen) {
                abort(404, '커스텀 화면을 찾을 수 없습니다.');
            }

            // 항상 미리보기 페이지를 표시 (헤더/푸터 없이)
            return view('700-page-sandbox.714-page-custom-screen-preview.000-index', compact('screen'));

        } catch (\Exception $e) {
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
