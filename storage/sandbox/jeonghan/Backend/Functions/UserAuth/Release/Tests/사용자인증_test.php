<?php

namespace App\Functions\UserAuth\Tests;

require_once dirname(__DIR__, 5) . '/vendor/autoload.php';

use App\Functions\UserAuth\UserAuth;
use App\Commons\CommonTests;

class UserAuthTest
{
    public function API_가_정상적으로_호출된다()
    {
        $userAuth = new UserAuth();
        $errors = [];
        
        // 로그인 테스트
        $loginResult = $userAuth->__invoke('login');
        $error = CommonTests::결과검증($loginResult, ['token'], '로그인');
        if ($error) $errors[] = $error;
        
        // 회원가입 테스트
        $registerResult = $userAuth->__invoke('register');
        $error = CommonTests::결과검증($registerResult, ['user_id'], '회원가입');
        if ($error) $errors[] = $error;
        
        // 토큰 검증 테스트
        $validateResult = $userAuth->__invoke('validate_token');
        $error = CommonTests::결과검증($validateResult, ['user_id'], '토큰 검증');
        if ($error) $errors[] = $error;
        
        // 로그아웃 테스트
        $logoutResult = $userAuth->__invoke('logout');
        $error = CommonTests::결과검증($logoutResult, [], '로그아웃');
        if ($error) $errors[] = $error;
        
        // 비밀번호 변경 테스트
        $changePasswordResult = $userAuth->__invoke('change_password');
        $error = CommonTests::결과검증($changePasswordResult, [], '비밀번호 변경');
        if ($error) $errors[] = $error;
        
        return CommonTests::최종결과($errors, "모든 API 호출이 정상적으로 작동함");
    }
}