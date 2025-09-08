<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewTestController extends Controller
{
    public function index()
    {
        return view('test.index');
    }
}