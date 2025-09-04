<?php

if (!function_exists('findViewDirectory')) {
    /**
     * 현재 라우트에서 뷰 디렉토리명 찾기
     * 
     * @return string 뷰 디렉토리명 (예: '901-admin-dashboard')
     * @throws RuntimeException 뷰 디렉토리를 찾지 못한 경우
     */
    function findViewDirectory()
    {
        $request = request();
        $path = $request->path();
        
        // URL 경로 기반으로 뷰 디렉토리 매핑
        $pathMapping = [
            '/' => '101-landing-home',
            'login' => '201-auth-login', 
            'signup' => '202-auth-signup',
            'dashboard' => '301-service-dashboard',
            'admin' => '901-admin-dashboard',
        ];
        
        if (isset($pathMapping[$path])) {
            return $pathMapping[$path];
        }
        
        throw new RuntimeException("Unable to determine view directory for path: {$path}");
    }
}

if (!function_exists('getCommonPath')) {
    /**
     * 현재 뷰 디렉토리에서 공통 컴포넌트 경로 추론
     * 
     * 예시:
     * - 901-admin-dashboard -> 900-admin-common
     * - 201-auth-login -> 200-auth-common
     * - 301-service-dashboard -> 300-service-common
     * - 101-landing-home -> 100-landing-common
     * 
     * @return string 공통 컴포넌트 경로
     * @throws RuntimeException 패턴 매칭 실패 시
     */
    function getCommonPath()
    {
        $folder = findViewDirectory();
        
        // 패턴 매칭: 901-admin-dashboard -> [901, 9, 01, admin]
        if (preg_match('/^(\d)(\d{2})-([^-]+)/', $folder, $matches)) {
            return $matches[1] . '00-' . $matches[3] . '-common';
        }
        
        throw new RuntimeException("Unable to parse view directory pattern: {$folder}");
    }
}

if (!function_exists('getCurrentViewPath')) {
    /**
     * 현재 뷰 디렉토리에서 body 경로 추론
     * 
     * 예시:
     * - 901-admin-dashboard -> 901-admin-dashboard.body
     * - 201-auth-login -> 201-auth-login.body
     * 
     * @return string body 경로
     */
    function getCurrentViewPath()
    {
        return findViewDirectory() . '.body';
    }
}