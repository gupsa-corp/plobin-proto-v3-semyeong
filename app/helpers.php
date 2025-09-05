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
// LOCALE HELPER FUNCTIONS
// ========================================

if (!function_exists('getHtmlLang')) {
    function getHtmlLang()
    {
        return app()->getLocale();
    }
}

if (!function_exists('getHtmlLangAttribute')) {
    function getHtmlLangAttribute()
    {
        return 'lang="' . getHtmlLang() . '"';
    }
}
