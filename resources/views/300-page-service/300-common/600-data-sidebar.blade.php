<?php

return [
    'navigation_items' => [
        [
            'title' => '대시보드',
            'url' => '/dashboard',
            'active' => request()->is('dashboard') || request()->is('dashboard/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M3 4h4v4H3V4zM9 4h4v4H9V4zM3 10h4v4H3v-4zM9 10h4v4H9v-4z" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '조직 관리',
            'url' => '/organizations',
            'active' => request()->is('organizations') || request()->is('organizations/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" fill="currentColor"/>
                      </svg>'
        ],
    ],
];