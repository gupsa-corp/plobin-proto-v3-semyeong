<?php

namespace App\Http\AuthUser\Me;

use App\Http\Controllers\ApiController;

class Controller extends ApiController
{
    public function __invoke()
    {
        $user = auth()->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
        ]);
    }
}