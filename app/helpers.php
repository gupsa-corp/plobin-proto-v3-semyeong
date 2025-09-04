<?php

if (!function_exists('findViewDirectory')) {
    function findViewDirectory()
    {
        $request = request();
        $path = '/' . ltrim($request->path(), '/');
        
        $routes = config('routes-web');
        
        if (isset($routes[$path])) {
            return $routes[$path];
        }
        
        throw new RuntimeException("Unable to determine view directory for path: {$path}");
    }
}

if (!function_exists('getCommonPath')) {
    function getCommonPath()
    {
        $folder = findViewDirectory();
        
        if (preg_match('/^(\d)(\d{2})-([^-]+)/', $folder, $matches)) {
            return $matches[1] . '00-' . $matches[3] . '-common';
        }
        
        throw new RuntimeException("Unable to parse view directory pattern: {$folder}");
    }
}

if (!function_exists('getCurrentViewPath')) {
    function getCurrentViewPath()
    {
        return findViewDirectory() . '.body';
    }
}