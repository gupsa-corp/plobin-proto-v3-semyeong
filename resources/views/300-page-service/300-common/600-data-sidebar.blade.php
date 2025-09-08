<?php

return [
    'navigation_items' => [
        [
            'title' => '조직 대시보드',
            'url' => function() {
                // 현재 URL에서 조직 ID 추출
                if (preg_match('/\/organizations\/(\d+)/', request()->getRequestUri(), $matches)) {
                    return '/organizations/' . $matches[1] . '/dashboard';
                }
                // 조직 ID를 찾을 수 없으면 조직 목록으로 리다이렉트
                return '/organizations';
            },
            'active' => request()->is('organizations/*/dashboard') || request()->is('organizations/*/dashboard/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M3 4h4v4H3V4zM9 4h4v4H9V4zM3 10h4v4H3v-4zM9 10h4v4H9v-4z" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '조직 목록',
            'url' => '/organizations',
            'active' => request()->is('organizations') && !request()->is('organizations/*/'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '조직 관리',
            'url' => function() {
                // 현재 URL에서 조직 ID 추출
                if (preg_match('/\/organizations\/(\d+)/', request()->getRequestUri(), $matches)) {
                    return '/organizations/' . $matches[1] . '/admin';
                }
                // 조직 ID를 찾을 수 없으면 조직 목록으로 리다이렉트
                return '/organizations';
            },
            'active' => request()->is('organizations/*/admin') || request()->is('organizations/*/admin/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" fill="currentColor"/>
                      </svg>'
        ],
    ],
];
