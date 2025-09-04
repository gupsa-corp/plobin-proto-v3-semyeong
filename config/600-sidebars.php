<?php

/**
 * 사이드바 컴포넌트 설정
 */

return [
    'user_sidebar' => [
        'type' => 'sidebar',
        'wrapper' => [
            'tag' => 'nav',
            'classes' => 'bg-white shadow-lg w-64 min-h-screen flex flex-col border-r border-gray-200'
        ],
        'overlay' => [
            'tag' => 'div',
            'classes' => 'fixed inset-0 z-30 bg-gray-600 opacity-0 hidden transition-opacity lg:hidden',
            'attributes' => [
                'id' => 'sidebar-overlay'
            ]
        ],
        'header' => [
            'tag' => 'div',
            'classes' => 'flex items-center justify-center h-20 shadow-sm border-b border-gray-200',
            'content' => [
                'tag' => 'h1',
                'classes' => 'text-2xl font-bold text-gray-900',
                'text' => 'Plobin'
            ]
        ],
        'menu' => [
            'tag' => 'div',
            'classes' => 'flex-1 px-4 py-6 overflow-y-auto',
            'items' => [
                [
                    'title' => '메뉴',
                    'items' => [
                        [
                            'type' => 'menu_item',
                            'tag' => 'a',
                            'text' => '대시보드',
                            'url' => '/dashboard',
                            'icon' => 'dashboard',
                            'classes' => 'flex items-center px-4 py-2 mt-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors'
                        ],
                        [
                            'type' => 'menu_item',
                            'tag' => 'a',
                            'text' => '프로젝트',
                            'url' => '/projects',
                            'icon' => 'folder',
                            'classes' => 'flex items-center px-4 py-2 mt-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors'
                        ],
                        [
                            'type' => 'menu_item',
                            'tag' => 'a',
                            'text' => '작업',
                            'url' => '/tasks',
                            'icon' => 'check-square',
                            'classes' => 'flex items-center px-4 py-2 mt-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors'
                        ],
                        [
                            'type' => 'menu_item',
                            'tag' => 'a',
                            'text' => '팀',
                            'url' => '/team',
                            'icon' => 'users',
                            'classes' => 'flex items-center px-4 py-2 mt-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors'
                        ]
                    ]
                ]
            ]
        ]
    ]
];