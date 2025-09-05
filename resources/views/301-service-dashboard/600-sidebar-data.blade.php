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
                        <path d="M9 12H1v6a2 2 0 002 2h5v-8zM11 12v8h5a2 2 0 002-2v-6h-8zM10 1l-8 8v1h8V1zM11 10h8V9l-8-8v9z" fill="currentColor"/>
                      </svg>'
        ],
        [
            'title' => '설정',
            'url' => '/settings',
            'active' => request()->is('settings') || request()->is('settings/*'),
            'icon' => '<svg width="20" height="20" viewBox="0 0 20 20">
                        <path d="M8.5 2.5A1.5 1.5 0 0110 4h0a1.5 1.5 0 011.5 1.5v.5h3a1 1 0 011 1v8a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1h3v-.5A1.5 1.5 0 019.5 4h0A1.5 1.5 0 018.5 2.5z" fill="currentColor"/>
                      </svg>'
        ]
    ],
    'organization_config' => [
        'name' => '기본 조직',
        'logo' => '/images/logo.png',
        'description' => '기본 조직입니다.',
        'current_plan' => 'free',
        'members_count' => 1,
        'no_org_text' => '조직을 선택해주세요',
        'search_placeholder' => '조직 검색...',
        'no_org_message' => '조직이 없습니다',
        'no_org_submessage' => '새 조직을 만들어 시작해보세요',
        'create_org_button_text' => '조직 만들기'
    ]
];