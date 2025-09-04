<?php

namespace App\Utils;

class ApiHelper
{
    public static function successResponse(mixed $data = null, string $message = '성공', int $status = 200): array
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return $response;
    }

    public static function errorResponse(string $message, ?array $errors = null, array $data = []): array
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $response;
    }

    public static function getPattern(string $type): string
    {
        return match($type) {
            'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'phone' => '/^01[016789]-?[0-9]{3,4}-?[0-9]{4}$/',
            'name' => '/^[가-힣a-zA-Z\s]+$/',
            'username' => '/^[a-zA-Z0-9_]{3,20}$/',
            'slug' => '/^[a-z0-9-]+$/',
            default => ''
        };
    }

    public static function getStatusMessage(int $code): string
    {
        return match($code) {
            200 => '성공',
            201 => '생성됨',
            204 => '내용 없음',
            400 => '잘못된 요청',
            401 => '인증 필요',
            403 => '접근 금지',
            404 => '찾을 수 없음',
            409 => '충돌',
            422 => '검증 실패',
            429 => '요청 횟수 초과',
            500 => '서버 오류',
            default => '알 수 없는 상태'
        };
    }

    public static function arrayToQuery(array $params): string
    {
        return http_build_query(array_filter($params));
    }

    public static function generateRandomString(int $length = 32, string $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'): string
    {
        return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 0, $length);
    }

    public static function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }
}
