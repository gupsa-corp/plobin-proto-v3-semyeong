<?php

use App\Services\ViewService;
use App\Services\ComponentService;

// ========================================
// VIEW SERVICE WRAPPER FUNCTIONS
// ========================================

if (!function_exists('findViewDirectory')) {
    function findViewDirectory()
    {
        return app(ViewService::class)->findViewDirectory();
    }
}

if (!function_exists('getCommonPath')) {
    function getCommonPath()
    {
        return app(ViewService::class)->getCommonPath();
    }
}

if (!function_exists('getCurrentViewPath')) {
    function getCurrentViewPath()
    {
        return app(ViewService::class)->getCurrentViewPath();
    }
}

// ========================================
// COMPONENT SERVICE WRAPPER FUNCTIONS
// ========================================

if (!function_exists('renderComponent')) {
    function renderComponent($configFile, $componentName, $overrides = [])
    {
        return app(ComponentService::class)->render($configFile, $componentName, $overrides);
    }
}

if (!function_exists('renderAuthLinks')) {
    function renderAuthLinks()
    {
        return app(ComponentService::class)->renderAuthLinks();
    }
}
