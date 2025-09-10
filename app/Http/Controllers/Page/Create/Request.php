<?php

namespace App\Http\Controllers\Page\Create;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function authorize()
    {
        return true; // 권한은 Controller에서 처리
    }

    public function rules()
    {
        return [
            'parent_id' => 'nullable|integer|exists:project_pages,id',
            'title' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'parent_id.exists' => '상위 페이지가 존재하지 않습니다.',
            'title.max' => '제목은 255자를 초과할 수 없습니다.',
        ];
    }
}
