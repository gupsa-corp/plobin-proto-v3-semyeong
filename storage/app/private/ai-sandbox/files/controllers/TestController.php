<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Hello from TestController!',
            'timestamp' => now()
        ]);
    }
}