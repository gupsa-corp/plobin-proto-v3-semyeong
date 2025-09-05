<?php

namespace App\Http\AuthUser\SignupPlobin;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class Controller extends ApiController
{
    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();

            // 이메일 중복 체크
            if (User::where('email', strtolower(trim($request->email)))->exists()) {
                return $this->unprocessableEntity([
                    'email' => ['이미 사용중인 이메일입니다.']
                ]);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
            ]);

            // 회원가입과 동시에 로그인 토큰 발급
            $token = $user->createToken('auth-token')->plainTextToken;

            DB::commit();

            return $this->created([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
                'token' => $token,
                'redirect_url' => '/dashboard'
            ], '회원가입이 완료되었습니다.');

        } catch (Exception $e) {
            DB::rollBack();
            
            return $this->internalServerError([], '회원가입 중 오류가 발생했습니다. 다시 시도해주세요.');
        }
    }
}
