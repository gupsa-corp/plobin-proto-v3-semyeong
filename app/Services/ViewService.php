<?php

namespace App\Services;

use RuntimeException;

class ViewService
{
    /**
     * 현재 요청 경로에 따른 뷰 디렉터리 찾기
     */
    public function findViewDirectory(): string
    {
        $request = request();
        $path = '/' . ltrim($request->path(), '/');
        
        $routes = config('routes-web');
        
        if (isset($routes[$path])) {
            return $routes[$path];
        }
        
        throw new RuntimeException("Unable to determine view directory for path: {$path}");
    }

    /**
     * 공통 뷰 디렉터리 경로 생성
     */
    public function getCommonPath(): string
    {
        $folder = $this->findViewDirectory();
        
        if (preg_match('/^(\d)(\d{2})-([^-]+)/', $folder, $matches)) {
            return $matches[1] . '00-' . $matches[3] . '-common';
        }
        
        throw new RuntimeException("Unable to parse view directory pattern: {$folder}");
    }

    /**
     * 현재 뷰 경로 반환
     */
    public function getCurrentViewPath(): string
    {
        return $this->findViewDirectory() . '.body';
    }
}