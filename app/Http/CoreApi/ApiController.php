<?php

namespace App\Http\CoreApi;

use App\Http\CoreApi\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function success(mixed $data = null, string $message = '성공', int $status = 200): JsonResponse
    {
        $response = ['success' => true, 'message' => $message];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    protected function created(mixed $data = null, string $message = '생성되었습니다.'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function updated(mixed $data = null, string $message = '업데이트되었습니다.'): JsonResponse
    {
        return $this->success($data, $message);
    }

    protected function deleted(string $message = '삭제되었습니다.'): JsonResponse
    {
        return $this->success(null, $message);
    }
}
