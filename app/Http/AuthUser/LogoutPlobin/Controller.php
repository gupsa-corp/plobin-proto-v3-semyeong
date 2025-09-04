<?php

namespace App\Http\AuthUser\LogoutPlobin;

use App\Http\Controllers\ApiController;

class Controller extends ApiController
{
    public function __invoke(Request $request)
    {
        // 사용자는 AuthTokenMiddleware에서 이미 검증됨
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return $this->success(null, '로그아웃이 완료되었습니다.');
    }
}
