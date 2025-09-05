<?php
return [
    [
        'title' => '프로젝트',
        'description' => '진행 중인 프로젝트를 관리하세요',
        'url' => '/projects',
        'icon' => 'project',
        'active' => request()->is('projects') || request()->is('projects/*')
    ],
    [
        'title' => '분석',
        'description' => '데이터 분석을 확인하세요',
        'url' => '/analytics',
        'icon' => 'analytics',
        'active' => request()->is('analytics') || request()->is('analytics/*')
    ],
    [
        'title' => '설정',
        'description' => '조직 설정을 관리하세요',
        'url' => '/settings',
        'icon' => 'settings',
        'active' => request()->is('settings') || request()->is('settings/*')
    ]
];
