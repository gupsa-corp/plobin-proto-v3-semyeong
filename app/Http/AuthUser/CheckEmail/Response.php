<?php

namespace App\Http\Auth\CheckEmail;

class Response
{
    public static function emailCheck(bool $exists)
    {
        return response()->json([
            'success' => true,
            'available' => !$exists,
            'message' => $exists ? '이미 사용중인 이메일입니다.' : '사용 가능한 이메일입니다.'
        ], 200);
    }

    public static function validationError(string $message, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }

    public static function error(string $message, string $error = null, int $status = 500)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, $status);
    }

    public static function fail(string $message, int $status = 400, array $data = [])
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }
}
