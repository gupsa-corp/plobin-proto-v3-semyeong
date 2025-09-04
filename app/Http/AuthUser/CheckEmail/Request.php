<?php

namespace App\Http\AuthUser\CheckEmail;

use App\Exceptions\ApiException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => $this->getEmailRules()
        ];
    }

    private function getEmailRules(): array
    {
        return [
            'required',
            'string',
            'email:rfc,dns',
            'max:255',
            'min:5',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => '이메일을 입력해주세요.',
            'email.string' => '이메일은 문자열이어야 합니다.',
            'email.email' => '올바른 이메일 형식을 입력해주세요.',
            'email.max' => '이메일은 최대 255자까지 입력 가능합니다.',
            'email.min' => '이메일은 최소 5자 이상이어야 합니다.',
            'email.regex' => '유효한 이메일 형식을 입력해주세요.'
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => '이메일'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->sanitizeEmail($this->input('email'))
        ]);
    }

    private function sanitizeEmail(?string $email): ?string
    {
        return $email ? trim($email) : null;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw ApiException::validation(
            '입력값 검증에 실패했습니다.',
            $validator->errors()->toArray()
        );
    }
}
