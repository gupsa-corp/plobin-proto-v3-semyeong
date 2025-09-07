<?php

namespace App\Http\CoreApi\User\LogoutPlobin;

use App\Http\CoreApi\ApiRequest;

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
