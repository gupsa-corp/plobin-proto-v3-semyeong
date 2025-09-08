<?php

namespace App\Http\CoreApi\OrganizationBilling\BusinessLookup;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function authorize(): bool
    {
        // 테스트 환경에서는 항상 허용
        if (app()->environment('local')) {
            return true;
        }

        // 조직 관리자나 소유자만 사업자 조회 가능
        return $this->user() && $this->user()->can('manage', $this->route('organization'));
    }

    public function rules(): array
    {
        return [
            'business_registration_number' => [
                'required',
                'string',
                'regex:/^[0-9\-]{10,12}$/', // 숫자와 하이픈, 10-12자리
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'business_registration_number.required' => '사업자등록번호를 입력해주세요.',
            'business_registration_number.string' => '사업자등록번호는 문자열이어야 합니다.',
            'business_registration_number.regex' => '올바른 사업자등록번호 형식을 입력해주세요. (예: 123-45-67890)',
        ];
    }

    public function prepareForValidation(): void
    {
        // 공백 제거
        if ($this->has('business_registration_number')) {
            $this->merge([
                'business_registration_number' => trim($this->business_registration_number),
            ]);
        }
    }
}
