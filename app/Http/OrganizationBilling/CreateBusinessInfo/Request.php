<?php

namespace App\Http\OrganizationBilling\CreateBusinessInfo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Request extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');
        $user = $this->user();
        return $organization && ($user ? $organization->hasMember($user) : true);
    }

    public function rules(): array
    {
        $organization = $this->route('organization');
        
        return [
            'business_name' => 'required|string|max:100',
            'business_registration_number' => [
                'required',
                'string',
                'regex:/^\d{3}-?\d{2}-?\d{5}$/',
                Rule::unique('business_infos')->ignore($organization?->businessInfo?->id),
            ],
            'representative_name' => 'required|string|max:50',
            'business_type' => 'nullable|string|max:50',
            'business_item' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'required|string|max:200',
            'detail_address' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'business_name.required' => '사업체명을 입력해주세요.',
            'business_registration_number.required' => '사업자등록번호를 입력해주세요.',
            'business_registration_number.regex' => '사업자등록번호 형식이 올바르지 않습니다. (예: 123-45-67890)',
            'business_registration_number.unique' => '이미 등록된 사업자등록번호입니다.',
            'representative_name.required' => '대표자명을 입력해주세요.',
            'address.required' => '주소를 입력해주세요.',
            'email.email' => '올바른 이메일 주소를 입력해주세요.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // 사업자등록번호에서 하이픈 제거
        if ($this->business_registration_number) {
            $this->merge([
                'business_registration_number' => preg_replace('/[^0-9]/', '', $this->business_registration_number)
            ]);
        }
    }
}