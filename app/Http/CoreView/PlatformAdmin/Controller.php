<?php

namespace App\Http\CoreView\PlatformAdmin;

use App\Http\CoreView\Controller as BaseController;
use App\Models\User;

class Controller extends BaseController
{
    public function users()
    {
        $users = User::getUsersWithRoles();

        return view('900-page-platform-admin.903-page-users.000-index', compact('users'));
    }
}
