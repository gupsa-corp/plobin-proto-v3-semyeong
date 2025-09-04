<?php

use Illuminate\Support\Facades\Route;

$routes = config('routes-web');

foreach ($routes as $path => $view) {
    Route::get($path, function () use ($view) {
        return view($view . '.index');
    });
}
