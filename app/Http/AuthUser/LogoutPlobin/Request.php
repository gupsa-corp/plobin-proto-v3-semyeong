<?php

namespace App\Http\AuthUser\LogoutPlobin;

use App\Http\Requests\ApiRequest;

class Request extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
        ];
    }
}
