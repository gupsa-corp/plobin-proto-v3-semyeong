<?php

namespace App\Http\Organization\InviteMembers;

use App\Http\Requests\ApiRequest;

class Request extends ApiRequest
{
    public function rules(): array
    {
        return [
            'invitations' => 'required|array|min:1|max:50',
            'invitations.*.email' => 'required|email|distinct',
            'invitations.*.permission_level' => 'required|integer|in:0,100,150,200,250,300,350,400,450,500,550',
            'invitations.*.message' => 'sometimes|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'invitations.required' => '초대할 멤버 정보를 입력해주세요.',
            'invitations.array' => '초대 정보는 배열 형태여야 합니다.',
            'invitations.min' => '최소 1명 이상 초대해야 합니다.',
            'invitations.max' => '한 번에 최대 50명까지만 초대할 수 있습니다.',
            'invitations.*.email.required' => '이메일을 입력해주세요.',
            'invitations.*.email.email' => '올바른 이메일 형식을 입력해주세요.',
            'invitations.*.email.distinct' => '중복된 이메일이 있습니다.',
            'invitations.*.permission_level.required' => '권한 레벨을 선택해주세요.',
            'invitations.*.permission_level.integer' => '권한 레벨은 숫자여야 합니다.',
            'invitations.*.permission_level.in' => '유효하지 않은 권한 레벨입니다.',
            'invitations.*.message.string' => '메시지는 문자열이어야 합니다.',
            'invitations.*.message.max' => '메시지는 500자를 초과할 수 없습니다.'
        ];
    }
}