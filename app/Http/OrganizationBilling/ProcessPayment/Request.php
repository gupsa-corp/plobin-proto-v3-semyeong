<?php

namespace App\Http\OrganizationBilling\ProcessPayment;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function authorize(): bool
    {
        // 요청한 사용자가 해당 조직의 멤버인지 확인
        $organization = $this->route('organization');
        return $organization && $organization->hasMember($this->user());
    }

    public function rules(): array
    {
        return [
            'payment_key' => 'required|string|max:200',
            'order_id' => 'required|string|max:100',
            'amount' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_key.required' => '결제 키가 필요합니다.',
            'order_id.required' => '주문 ID가 필요합니다.',
            'amount.required' => '결제 금액이 필요합니다.',
            'amount.integer' => '결제 금액은 숫자여야 합니다.',
            'amount.min' => '결제 금액은 1원 이상이어야 합니다.',
        ];
    }
}