<?php

namespace App\Http\Controllers\OrganizationBilling\DownloadReceipt;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'billing_history_id' => 'required|integer|exists:billing_histories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'billing_history_id.required' => '결제 내역 ID가 필요합니다.',
            'billing_history_id.exists' => '존재하지 않는 결제 내역입니다.',
        ];
    }
}
