<?php

namespace App\Http\Sandbox\ApiCreate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Controller extends \App\Http\Controllers\Controller
{
    public function execute($id)
    {
        // 구현필요
        return response()->json(['message' => '구현필요']);
    }

    public function store(Request $request)
    {
        // 구현필요
        return response()->json(['message' => '구현필요']);
    }
}