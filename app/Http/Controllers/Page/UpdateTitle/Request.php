<?php

namespace App\Http\Controllers\Page\UpdateTitle;

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
            'title' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '제목을 입력해주세요.',
            'title.max' => '제목은 255자를 초과할 수 없습니다.',
        ];
    }
}
