<?php

namespace App\Http\Controllers;

use App\Models\User;

class PlatformAdminController extends Controller
{
    public function users()
    {
        $users = User::getUsersWithRoles();
        
        return view('900-page-platform-admin.903-page-users.000-index', compact('users'));
    }
}