<?php

return [
    'navigation_items' => [
        [
            'title' => '대시보드',
            'url' => '/organizations/' . request()->route('id') . '/dashboard',
            'active' => request()->is('organizations/*/dashboard'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M3 4h4v4H3V4zM9 4h4v4H9V4zM3 10h4v4H3v-4zM9 10h4v4H9v-4z" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '프로젝트',
            'url' => '/organizations/' . request()->route('id') . '/projects',
            'active' => request()->is('organizations/*/projects') || request()->is('organizations/*/projects/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" fill="currentColor"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm2.5 4a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100-2 1 1 0 000 2zm0 1a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z" clip-rule="evenodd" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '조직 관리자',
            'url' => '/organizations/' . request()->route('id') . '/admin',
            'active' => request()->is('organizations/*/admin') || request()->is('organizations/*/admin/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" fill="currentColor"/>
                      </svg>'
        ],
    ],
];
