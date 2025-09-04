<?php

namespace App\Http\AuthUser\SignupPlobin;

use App\Http\Requests\ApiRequest;

class Request extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules(),
            'password' => $this->passwordRules(),
            'password_confirmation' => [
                'required',
                'string',
                'same:password'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '이름을 입력해주세요.',
            'name.regex' => '이름은 한글, 영문만 입력 가능합니다.',
            'email.required' => '이메일을 입력해주세요.',
            'email.email' => '올바른 이메일 형식을 입력해주세요.',
            'email.regex' => '유효한 이메일 형식을 입력해주세요.',
            'password.required' => '비밀번호를 입력해주세요.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.regex' => '비밀번호는 대소문자, 숫자, 특수문자를 포함해야 합니다.',
            'password_confirmation.required' => '비밀번호 확인을 입력해주세요.',
            'password_confirmation.same' => '비밀번호가 일치하지 않습니다.'
        ];
    }
}