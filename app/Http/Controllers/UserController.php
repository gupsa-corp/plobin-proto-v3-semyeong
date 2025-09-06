<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * 사용자 프로필 정보 업데이트
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
            ], [
                'name.required' => '이름은 필수 입력 항목입니다.',
                'name.string' => '이름은 문자열이어야 합니다.',
                'name.max' => '이름은 255자를 초과할 수 없습니다.',
                'phone.string' => '연락처는 문자열이어야 합니다.',
                'phone.max' => '연락처는 20자를 초과할 수 없습니다.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => '입력 데이터에 오류가 있습니다.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => '인증된 사용자를 찾을 수 없습니다.'
                ], 401);
            }

            // 사용자 정보 업데이트
            $user->name = $request->input('name');
            if ($request->has('phone')) {
                $user->phone = $request->input('phone');
            }
            
            $user->save();

            return response()->json([
                'success' => true,
                'message' => '프로필이 성공적으로 업데이트되었습니다.',
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '프로필 업데이트 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 사용자 비밀번호 변경
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                    'confirmed'
                ],
            ], [
                'current_password.required' => '현재 비밀번호는 필수 입력 항목입니다.',
                'new_password.required' => '새 비밀번호는 필수 입력 항목입니다.',
                'new_password.min' => '새 비밀번호는 최소 8자 이상이어야 합니다.',
                'new_password.regex' => '새 비밀번호는 영문, 숫자, 특수문자를 포함해야 합니다.',
                'new_password.confirmed' => '새 비밀번호 확인이 일치하지 않습니다.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => '입력 데이터에 오류가 있습니다.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => '인증된 사용자를 찾을 수 없습니다.'
                ], 401);
            }

            // 현재 비밀번호 확인
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => '현재 비밀번호가 올바르지 않습니다.'
                ], 422);
            }

            // 새 비밀번호가 현재 비밀번호와 같은지 확인
            if (Hash::check($request->input('new_password'), $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => '새 비밀번호는 현재 비밀번호와 달라야 합니다.'
                ], 422);
            }

            // 비밀번호 업데이트
            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            return response()->json([
                'success' => true,
                'message' => '비밀번호가 성공적으로 변경되었습니다.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '비밀번호 변경 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}