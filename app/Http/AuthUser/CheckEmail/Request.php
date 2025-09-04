<?php

namespace App\Http\AuthUser\CheckEmail;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => '이메일을 입력해주세요.',
            'email.email' => '올바른 이메일 형식을 입력해주세요.',
            'email.max' => '이메일은 최대 255자까지 입력 가능합니다.'
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => '이메일'
        ];
    }
}
