<?php

namespace App\Http\AuthUser\LogoutPlobin;

use App\Http\Controllers\ApiController;
use App\Http\Traits\{HasRateLimit, HasCache, HasSecurity};

class Controller extends ApiController
{
    use HasRateLimit, HasCache, HasSecurity;

    public function __invoke(Request $request)
    {
        $this->checkRateLimit($request, null, 10, 1);

        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return $this->success(null, '로그아웃이 완료되었습니다.');
    }
}
