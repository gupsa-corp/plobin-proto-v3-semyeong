<?php

namespace App\Http\CoreApi\User\Me;

use App\Http\CoreApi\ApiController;

class Controller extends ApiController
{
    public function __invoke(\Illuminate\Http\Request $request)
    {
        $user = $request->user() ?: auth()->user();

        if (!$user) {
            throw \App\Exceptions\ApiException::unauthorized('인증된 사용자를 찾을 수 없습니다.');
        }

        return $this->success([
            'id' => $user->id,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'created_at' => $user->created_at,
        ]);
    }
}
