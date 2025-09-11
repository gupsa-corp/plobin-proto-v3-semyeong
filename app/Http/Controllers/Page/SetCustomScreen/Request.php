<?php

namespace App\Http\Controllers\Page\SetCustomScreen;

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
            'custom_screen' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'custom_screen.max' => '커스텀 화면 ID는 255자를 초과할 수 없습니다.',
        ];
    }
}
