<?php

namespace App\Http\Sandbox\PreviewBlade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Controller
{
    public function show($id)
    {
        // 구현필요
        return response()->json(['message' => '구현필요']);
    }
}