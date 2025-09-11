<?php

namespace App\Http\Controllers\ProjectPage\CreatePage;

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
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:project_pages,id',
            'status' => 'in:draft,published,archived'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '제목을 입력해주세요.',
            'title.max' => '제목은 255자 이하로 입력해주세요.',
            'parent_id.exists' => '존재하지 않는 부모 페이지입니다.',
            'status.in' => '상태는 draft, published, archived 중 하나여야 합니다.'
        ];
    }
}
