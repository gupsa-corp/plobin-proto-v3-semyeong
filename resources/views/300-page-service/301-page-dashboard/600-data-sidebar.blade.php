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
    ],
];
