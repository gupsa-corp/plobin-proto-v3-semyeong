<?php

namespace App\Http\Organization\CreateOrganization;

use App\Http\Controllers\ApiController;
use App\Models\Organization;

class Controller extends ApiController
{
    public function __invoke(Request $request)
    {
        $organization = Organization::create([
            'name' => $request->name,
            'url' => strtolower($request->url), // URL을 소문자로 저장
            'user_id' => auth()->id()
        ]);

        return $this->created([
            'id' => $organization->id,
            'name' => $organization->name,
            'url' => $organization->url
        ], '조직이 생성되었습니다.');
    }
}