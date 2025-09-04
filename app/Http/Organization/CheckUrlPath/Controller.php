<?php

namespace App\Http\Organization\CheckUrlPath;

use App\Http\Controllers\ApiController;
use App\Models\Organization;

class Controller extends ApiController
{
    public function __invoke(string $urlPath)
    {
        // URL path 형식 검증
        if (!preg_match('/^[a-z]{3,12}$/', $urlPath)) {
            return $this->error('유효하지 않은 URL 명 형식입니다.', 400);
        }

        // 중복 여부 확인
        $exists = Organization::where('url', $urlPath)->exists();

        return $this->success([
            'url_path' => $urlPath,
            'available' => !$exists
        ], $exists ? '이미 사용 중인 URL 명입니다.' : '사용 가능한 URL 명입니다.');
    }
}