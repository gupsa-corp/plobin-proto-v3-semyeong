<?php

namespace App\Http\CoreApi\Sandbox\ListScreens;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class Controller extends \App\Http\CoreApi\ApiController
{
    /**
     * 선택된 샌드박스의 화면(스크린) 목록을 반환
     */
    public function listScreens(Request $request)
    {
        $sandboxName = $request->get('sandbox_name');
        
        if (!$sandboxName) {
            return response()->json(['error' => '샌드박스 이름이 필요합니다.'], 400);
        }

        $sandboxPath = storage_path('storage-sandbox-' . $sandboxName);
        
        // 샌드박스 디렉토리가 존재하는지 확인
        if (!File::exists($sandboxPath)) {
            return response()->json(['error' => '샌드박스가 존재하지 않습니다.'], 404);
        }

        try {
            $screens = $this->findScreenFiles($sandboxPath);
            
            return response()->json([
                'sandbox_name' => $sandboxName,
                'screens' => $screens,
                'total_count' => count($screens)
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => '스크린 목록을 가져오는 중 오류가 발생했습니다.'], 500);
        }
    }

    /**
     * 샌드박스에서 화면 파일들을 찾아서 반환
     */
    private function findScreenFiles($sandboxPath)
    {
        $screens = [];
        $extensions = ['html', 'php']; // 화면으로 간주할 파일 확장자
        
        // 재귀적으로 모든 화면 파일 찾기
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sandboxPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                $filename = $file->getBasename();
                
                // 화면 파일인지 확인
                if (in_array($extension, $extensions)) {
                    $relativePath = str_replace($sandboxPath . '/', '', $file->getPathname());
                    $directory = dirname($relativePath);
                    
                    // 파일 내용에서 제목 추출 시도
                    $title = $this->extractTitle($file->getPathname());
                    if (!$title) {
                        $title = $this->generateTitleFromFilename($filename);
                    }
                    
                    $screens[] = [
                        'id' => md5($relativePath), // 파일 경로 기반 고유 ID
                        'filename' => $filename,
                        'title' => $title,
                        'path' => $relativePath,
                        'directory' => $directory === '.' ? '/' : $directory,
                        'type' => $extension,
                        'size' => $this->formatBytes($file->getSize()),
                        'modified_at' => date('Y-m-d H:i:s', $file->getMTime()),
                        'description' => $this->generateDescription($filename, $directory)
                    ];
                }
            }
        }

        // 경로 및 파일명으로 정렬
        usort($screens, function($a, $b) {
            $pathCompare = strcmp($a['directory'], $b['directory']);
            if ($pathCompare === 0) {
                return strcmp($a['filename'], $b['filename']);
            }
            return $pathCompare;
        });

        return $screens;
    }

    /**
     * HTML 파일에서 제목 추출
     */
    private function extractTitle($filePath)
    {
        try {
            $content = File::get($filePath);
            
            // HTML title 태그에서 제목 추출
            if (preg_match('/<title[^>]*>(.*?)<\/title>/i', $content, $matches)) {
                return trim(strip_tags($matches[1]));
            }
            
            // HTML h1 태그에서 제목 추출
            if (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $content, $matches)) {
                return trim(strip_tags($matches[1]));
            }
            
            // PHP 주석에서 제목 추출
            if (preg_match('/\/\*\*?\s*\*?\s*(.+?)\s*\*?\s*\*?\//s', $content, $matches)) {
                return trim($matches[1]);
            }
            
        } catch (\Exception $e) {
            // 파일을 읽을 수 없는 경우 무시
        }
        
        return null;
    }

    /**
     * 파일명에서 제목 생성
     */
    private function generateTitleFromFilename($filename)
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        // 특별한 파일명들 처리
        $specialNames = [
            'index' => '메인 페이지',
            'main' => '메인 화면',
            'home' => '홈 화면',
            'dashboard' => '대시보드',
            'form-publisher-save' => '폼 발행 저장',
            'form-publisher-preview' => '폼 발행 미리보기',
            'form-publisher-list' => '폼 발행 목록',
            'form-publisher-editor' => '폼 발행 편집기',
            'gantt' => '간트 차트'
        ];
        
        if (isset($specialNames[$name])) {
            return $specialNames[$name];
        }
        
        // 하이픈과 언더스코어를 공백으로 변경하고 첫 글자 대문자화
        $title = str_replace(['-', '_'], ' ', $name);
        return ucwords($title);
    }

    /**
     * 파일 설명 생성
     */
    private function generateDescription($filename, $directory)
    {
        $descriptions = [
            'index' => '기본 시작 페이지입니다',
            'form-publisher' => '폼 발행 관련 화면입니다',
            'gantt' => '간트 차트 화면입니다',
            'dashboard' => '대시보드 화면입니다'
        ];
        
        // 파일명 패턴으로 설명 생성
        foreach ($descriptions as $pattern => $desc) {
            if (strpos($filename, $pattern) !== false) {
                return $desc;
            }
        }
        
        // 디렉토리 기반 설명
        if ($directory !== '/' && $directory !== '.') {
            return "/{$directory} 디렉토리의 화면입니다";
        }
        
        return "사용자 화면 파일입니다";
    }

    /**
     * 파일 크기 포맷팅
     */
    private function formatBytes($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 1) . ' ' . $units[$unitIndex];
    }
}