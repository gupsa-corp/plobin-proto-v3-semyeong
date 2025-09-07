<?php

namespace App\Http\CoreApi\PlatformAdmin\Permissions\CreatePermission;

use App\Http\CoreApi\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * 새 권한을 생성합니다.
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name',
                'category' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'guard_name' => 'required|string|in:web,platform'
            ]);

            $permissionData = [
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name', 'web'),
                'category' => $request->input('category'),
                'description' => $request->input('description'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // 권한 생성
            $permission = Permission::create($permissionData);

            return response()->json([
                'success' => true,
                'message' => '새 권한이 성공적으로 생성되었습니다.',
                'data' => [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'category' => $permission->category,
                    'description' => $permission->description,
                    'guard_name' => $permission->guard_name,
                    'created_at' => $permission->created_at->toISOString()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => '입력 데이터가 유효하지 않습니다.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Permission creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '권한 생성 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}