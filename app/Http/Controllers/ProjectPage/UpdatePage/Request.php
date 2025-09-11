<?php

namespace App\Http\Controllers\ProjectPage\UpdatePage;

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
            'title' => 'string|max:255',
            'content' => 'nullable|string',
            'status' => 'in:draft,published,archived',
            'parent_id' => 'nullable|exists:project_pages,id',
            'custom_screen_settings' => 'nullable|array',
            'custom_screen_settings.screen_id' => 'nullable|integer',
            'custom_screen_settings.screen_name' => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => '제목은 255자 이하로 입력해주세요.',
            'parent_id.exists' => '존재하지 않는 부모 페이지입니다.',
            'status.in' => '상태는 draft, published, archived 중 하나여야 합니다.'
        ];
    }
}
