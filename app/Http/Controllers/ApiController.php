<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class ApiController extends Controller
{
    /**
     * API 성공 응답
     */
    protected function success(mixed $data = null, string $message = '성공', int $status = 200): JsonResponse
    {
        $response = ['success' => true, 'message' => $message];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return response()->json($response, $status);
    }

    /**
     * API 생성 성공 응답
     */
    protected function created(mixed $data = null, string $message = '생성되었습니다.'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
     * API 업데이트 성공 응답
     */
    protected function updated(mixed $data = null, string $message = '업데이트되었습니다.'): JsonResponse
    {
        return $this->success($data, $message);
    }

    /**
     * API 삭제 성공 응답
     */
    protected function deleted(string $message = '삭제되었습니다.'): JsonResponse
    {
        return $this->success(null, $message);
    }

}