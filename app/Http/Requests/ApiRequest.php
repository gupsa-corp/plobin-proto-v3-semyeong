<?php

namespace App\Http\Requests;

use App\Exceptions\ApiException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract class ApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw ApiException::validation(
            '입력값 검증에 실패했습니다.',
            $validator->errors()->toArray()
        );
    }

    /**
     * 공통 이메일 규칙
     */
    protected function emailRules(): array
    {
        return [
            'required',
            'string',
            'email:rfc',
            'max:255',
            'min:5'
        ];
    }

    /**
     * 공통 패스워드 규칙
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            'min:8',
            'max:100',
            'regex:/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[a-z\d@$!%*?&]/'
        ];
    }

    /**
     * 공통 이름 규칙
     */
    protected function nameRules(): array
    {
        return [
            'required',
            'string',
            'min:2',
            'max:50',
            'regex:/^[가-힣a-zA-Z\s]+$/'
        ];
    }

    /**
     * 공통 전화번호 규칙
     */
    protected function phoneRules(): array
    {
        return [
            'required',
            'string',
            'regex:/^01[016789]-?[0-9]{3,4}-?[0-9]{4}$/'
        ];
    }

    /**
     * 입력값 사전 처리
     */
    protected function prepareForValidation(): void
    {
        $this->sanitizeInputs();
    }

    /**
     * 입력값 정리
     */
    private function sanitizeInputs(): void
    {
        $inputs = [];
        
        foreach ($this->all() as $key => $value) {
            if (is_string($value)) {
                $inputs[$key] = trim($value);
            }
        }
        
        if (!empty($inputs)) {
            $this->merge($inputs);
        }
    }
}