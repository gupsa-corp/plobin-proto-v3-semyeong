<?php

namespace App\Http\Controllers\PlatformAdmin\PricingPlan\CreatePricingPlan;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:50|unique:pricing_plans,slug',
            'description' => 'nullable|string',
            'type' => 'required|in:usage_based,monthly',
            'monthly_price' => 'nullable|integer|min:0',
            'max_members' => 'nullable|integer|min:0',
            'max_projects' => 'nullable|integer|min:0',
            'max_storage_gb' => 'nullable|integer|min:0',
            'max_sheets' => 'nullable|integer|min:0',
            'price_per_member' => 'nullable|integer|min:0',
            'price_per_project' => 'nullable|integer|min:0',
            'price_per_gb' => 'nullable|integer|min:0',
            'price_per_sheet' => 'nullable|integer|min:0',
            'free_members' => 'integer|min:0',
            'free_projects' => 'integer|min:0',
            'free_storage_gb' => 'integer|min:0',
            'free_sheets' => 'integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'features' => 'nullable|array'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '플랜 이름은 필수입니다.',
            'slug.required' => '슬러그는 필수입니다.',
            'slug.unique' => '이미 존재하는 슬러그입니다.',
            'type.required' => '플랜 타입은 필수입니다.',
            'type.in' => '플랜 타입은 usage_based 또는 monthly여야 합니다.'
        ];
    }
}
