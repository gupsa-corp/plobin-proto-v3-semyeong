<?php

/**
 * API Name: TestUserController
 * Description: 사용자 관리를 위한 테스트 API 컨트롤러입니다
 * Created: 2025-09-07 11:42:21
 */

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TestUserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Hello World from TestUserController',
            'users' => [
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe']
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'User created successfully',
            'data' => $request->all()
        ], 201);
    }
}