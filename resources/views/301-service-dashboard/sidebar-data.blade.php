<?php
return [
    [
        'title' => '대시보드',
        'url' => '/dashboard',
        'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2zm0 0V9a2 2 0 012-2h4a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 002-2V3a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 00-2 2',
        'active' => request()->is('dashboard') || request()->is('dashboard/*')
    ],
    [
        'title' => '프로젝트',
        'url' => '/projects',
        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'active' => request()->is('projects') || request()->is('projects/*')
    ],
    [
        'title' => '작업',
        'url' => '/tasks',
        'icon' => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'active' => request()->is('tasks') || request()->is('tasks/*')
    ],
    [
        'title' => '팀',
        'url' => '/team',
        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
        'active' => request()->is('team') || request()->is('team/*')
    ]
];
