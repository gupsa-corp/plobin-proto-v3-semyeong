<?php

namespace App\Commons;

class CommonTests
{
    /**
     * API 응답 결과 검증 메서드
     */
    public static function validateResult($result, $expectedKeys, $testName)
    {
        if (!$result['success']) {
            return "{$testName} 실패: " . json_encode($result);
        }
        
        foreach ($expectedKeys as $key) {
            if (!isset($result[$key])) {
                return "{$testName} 실패: {$key} 키가 없음";
            }
        }
        
        return null; // 성공
    }
    
    /**
     * 테스트 결과 출력 메서드
     */
    public static function outputResult($errors, $successMessage)
    {
        if (!empty($errors)) {
            return implode("\n", $errors);
        }
        
        return $successMessage;
    }
}