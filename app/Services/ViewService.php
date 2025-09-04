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
            $config = $routes[$path];
            
            // 새로운 배열 구조 지원
            if (is_array($config)) {
                return $config['view'];
            }
            
            // 이전 문자열 구조도 지원 (하위 호환성)
            if (is_string($config)) {
                return $config;
            }
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