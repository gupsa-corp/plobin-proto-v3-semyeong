<?php

namespace App\Http\Organization\CreateOrganization;

use App\Http\Controllers\ApiController;
use App\Models\Organization;
use Illuminate\Http\Request;

class Controller extends ApiController
{
    public function __invoke(Request $request)
    {
        // 유효성 검사
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:25',
            'url_path' => 'required|string|min:3|max:12|regex:/^[a-z]+$/|unique:organizations,url'
        ], [
            'name.required' => '조직 이름을 입력해주세요.',
            'name.min' => '조직 이름은 1자 이상이어야 합니다.',
            'name.max' => '조직 이름은 25자 이하여야 합니다.',
            'url_path.required' => 'URL 명을 입력해주세요.',
            'url_path.min' => 'URL 명은 3자 이상이어야 합니다.',
            'url_path.max' => 'URL 명은 12자 이하여야 합니다.',
            'url_path.regex' => 'URL 명은 영문 소문자만 가능합니다.',
            'url_path.unique' => '이미 사용 중인 URL 명입니다.'
        ]);

        $organization = Organization::create([
            'name' => $validated['name'],
            'url' => $validated['url_path'], // url_path를 url 컬럼에 저장
            'user_id' => auth()->id()
        ]);

        return $this->created([
            'id' => $organization->id,
            'name' => $organization->name,
            'url' => $organization->url
        ], '조직이 생성되었습니다.');
    }
}