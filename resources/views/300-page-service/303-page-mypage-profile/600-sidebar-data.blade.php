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
            'title' => '개인정보 수정',
            'url' => '/mypage/edit',
            'active' => request()->is('mypage/edit'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" fill="currentColor"/>
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
