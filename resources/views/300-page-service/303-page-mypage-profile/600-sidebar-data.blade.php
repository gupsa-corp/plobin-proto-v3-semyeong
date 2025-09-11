<?php

return [
    'navigation_items' => [
        [
            'title' => '프로필',
            'url' => '/mypage',
            'active' => request()->is('mypage') && !request()->is('mypage/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '권한',
            'url' => '/mypage/permissions',
            'active' => request()->is('mypage/permissions'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-4 4-4-4 4-4 .257-.257A6 6 0 1118 8zm-6-2a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '회원탈퇴',
            'url' => '/mypage/delete',
            'active' => request()->is('mypage/delete'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                      </svg>'
        ],
    ],
];
