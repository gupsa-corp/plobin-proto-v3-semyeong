<?php

namespace App\Http\CoreApi\Page\SetSandbox;

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
            'sandbox' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'sandbox.max' => '샌드박스 이름은 255자를 초과할 수 없습니다.',
        ];
    }
}