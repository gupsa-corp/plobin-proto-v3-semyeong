<?php

namespace App\Http\AuthUser\CheckEmail;

use App\Http\Controllers\Controller as BaseController;
use App\Http\AuthUser\CheckEmail\Request as CheckEmailRequest;
use App\Http\AuthUser\CheckEmail\Response as CheckEmailResponse;
use App\Models\User;

class Controller extends BaseController
{
    public function __invoke(CheckEmailRequest $request)
    {
        $exists = User::where('email', $request->email)->exists();

        return CheckEmailResponse::emailCheck($exists);
    }
}
