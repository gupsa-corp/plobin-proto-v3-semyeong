<?php
return [
    'navigation_items' => [
        [
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                      </svg>',
            'title' => '회원 관리',
            'url' => '/organizations/1/admin/members',
            'active' => request()->is('organizations/*/admin/members'),
            'description' => '조직 구성원 관리'
        ],
        [
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>',
            'title' => '권한 관리',
            'url' => '/organizations/1/admin/permissions',
            'active' => request()->is('organizations/*/admin/permissions'),
            'description' => '역할 및 권한 설정'
        ],
        [
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                      </svg>',
            'title' => '결제 관리',
            'url' => '/organizations/1/admin/billing',
            'active' => request()->is('organizations/*/admin/billing'),
            'description' => '요금제 및 결제 관리'
        ],
        [
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                      </svg>',
            'title' => '프로젝트 관리',
            'url' => '/organizations/1/admin/projects',
            'active' => request()->is('organizations/*/admin/projects'),
            'description' => '조직 프로젝트 관리'
        ]
    ]
];
?>