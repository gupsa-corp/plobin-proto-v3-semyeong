<?php

/**
 * 통합 컴포넌트 설정
 * 모든 UI 컴포넌트를 하나의 파일에서 관리
 */

return [
    
    // ============================
    // 기본 엘리먼트들 (Basic Elements)
    // ============================
    
    'text' => [
        'type' => 'text',
        'tag' => 'span',
        'classes' => 'text-gray-900',
        'attributes' => []
    ],

    'heading_1' => [
        'type' => 'text',
        'tag' => 'h1',
        'classes' => 'text-3xl font-bold text-gray-900 mb-4',
        'attributes' => []
    ],

    'heading_2' => [
        'type' => 'text',
        'tag' => 'h2',
        'classes' => 'text-2xl font-semibold text-gray-800 mb-3',
        'attributes' => []
    ],

    'heading_3' => [
        'type' => 'text',
        'tag' => 'h3',
        'classes' => 'text-xl font-medium text-gray-800 mb-2',
        'attributes' => []
    ],

    'paragraph' => [
        'type' => 'text',
        'tag' => 'p',
        'classes' => 'text-gray-700 mb-4 leading-relaxed',
        'attributes' => []
    ],

    'link' => [
        'type' => 'link',
        'tag' => 'a',
        'classes' => 'text-primary-600 hover:text-primary-800 underline transition-colors',
        'attributes' => []
    ],

    'divider' => [
        'type' => 'single',
        'tag' => 'hr',
        'classes' => 'border-gray-200 my-6',
        'attributes' => []
    ],

    'container' => [
        'type' => 'wrapper',
        'tag' => 'div',
        'classes' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
        'attributes' => []
    ],

    'page_header' => [
        'type' => 'wrapper',
        'tag' => 'header',
        'classes' => 'mb-6',
        'attributes' => []
    ],

    'card_header' => [
        'type' => 'wrapper',
        'tag' => 'div',
        'classes' => 'border-b border-gray-700 pb-3 mb-4',
        'attributes' => []
    ],

    // ============================
    // 버튼들 (Buttons)
    // ============================
    
    'primary_button' => [
        'type' => 'button',
        'tag' => 'button',
        'classes' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500',
        'attributes' => [
            'type' => 'button'
        ]
    ],

    'secondary_button' => [
        'type' => 'button',
        'tag' => 'button',
        'classes' => 'inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500',
        'attributes' => [
            'type' => 'button'
        ]
    ],

    'warning_button' => [
        'type' => 'button',
        'tag' => 'button',
        'classes' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500',
        'attributes' => [
            'type' => 'button'
        ]
    ],

    'success_button' => [
        'type' => 'button',
        'tag' => 'button',
        'classes' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
        'attributes' => [
            'type' => 'button'
        ]
    ],

    'danger_button' => [
        'type' => 'button',
        'tag' => 'button',
        'classes' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
        'attributes' => [
            'type' => 'button'
        ]
    ],

    // ============================
    // 폼 엘리먼트들 (Form Elements)
    // ============================
    
    'text_input' => [
        'type' => 'input',
        'tag' => 'input',
        'classes' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500',
        'attributes' => [
            'type' => 'text'
        ]
    ],

    'email_input' => [
        'type' => 'input',
        'tag' => 'input',
        'classes' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500',
        'attributes' => [
            'type' => 'email'
        ]
    ],

    'password_input' => [
        'type' => 'input',
        'tag' => 'input',
        'classes' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500',
        'attributes' => [
            'type' => 'password'
        ]
    ],

    'textarea' => [
        'type' => 'input',
        'tag' => 'textarea',
        'classes' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500',
        'attributes' => [
            'rows' => '4'
        ]
    ],

    'label' => [
        'type' => 'label',
        'tag' => 'label',
        'classes' => 'block text-sm font-medium text-gray-700 mb-1',
        'attributes' => []
    ],

    'admin_settings_form' => [
        'type' => 'form',
        'tag' => 'form',
        'classes' => 'space-y-4',
        'attributes' => [
            'method' => 'POST'
        ]
    ],

    'security_settings_form' => [
        'type' => 'form',
        'tag' => 'form',
        'classes' => 'space-y-4',
        'attributes' => [
            'method' => 'POST'
        ]
    ],

    // ============================
    // 네비게이션 (Navigation)
    // ============================
    
    'auth_links' => [
        'type' => 'nav_links',
        'wrapper' => [
            'tag' => 'div',
            'classes' => 'flex space-x-4'
        ],
        'items' => [
            [
                'tag' => 'a',
                'text' => '로그인',
                'url' => '/login',
                'classes' => 'text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium'
            ],
            [
                'tag' => 'a',
                'text' => '회원가입',
                'url' => '/signup',
                'classes' => 'bg-primary-600 text-white hover:bg-primary-700 px-3 py-2 rounded-md text-sm font-medium'
            ]
        ]
    ],

    'main_navigation' => [
        'type' => 'nav_menu',
        'wrapper' => [
            'tag' => 'nav',
            'classes' => 'bg-gray-800'
        ],
        'items' => [
            [
                'tag' => 'a',
                'text' => '홈',
                'url' => '/',
                'classes' => 'text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium'
            ],
            [
                'tag' => 'a', 
                'text' => '대시보드',
                'url' => '/dashboard',
                'classes' => 'text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium'
            ]
        ]
    ],

    // ============================
    // 레이아웃 (Layout)
    // ============================
    
    'page_container' => [
        'type' => 'wrapper',
        'tag' => 'div',
        'classes' => 'min-h-screen bg-gray-50',
        'attributes' => []
    ],
    
    'content_wrapper' => [
        'type' => 'wrapper',
        'tag' => 'div',
        'classes' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8',
        'attributes' => []
    ],
    
    'card' => [
        'type' => 'wrapper',
        'tag' => 'div',
        'classes' => 'bg-white overflow-hidden shadow rounded-lg',
        'attributes' => []
    ],

    'card_body' => [
        'type' => 'wrapper',
        'tag' => 'div',
        'classes' => 'px-4 py-5 sm:p-6',
        'attributes' => []
    ],

    // ============================
    // 테이블 (Tables)
    // ============================
    
    'admin_user_table' => [
        'type' => 'table',
        'wrapper' => [
            'tag' => 'div',
            'classes' => 'overflow-x-auto'
        ],
        'table' => [
            'tag' => 'table',
            'classes' => 'min-w-full divide-y divide-gray-700',
            'thead' => [
                'tag' => 'thead',
                'classes' => 'bg-gray-800',
                'tr' => [
                    'tag' => 'tr',
                    'cells' => [
                        ['tag' => 'th', 'classes' => 'px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider', 'text' => 'ID'],
                        ['tag' => 'th', 'classes' => 'px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider', 'text' => '사용자명'],
                        ['tag' => 'th', 'classes' => 'px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider', 'text' => '이메일'],
                        ['tag' => 'th', 'classes' => 'px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider', 'text' => '상태'],
                        ['tag' => 'th', 'classes' => 'px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider', 'text' => '생성일'],
                        ['tag' => 'th', 'classes' => 'px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider', 'text' => '작업']
                    ]
                ]
            ],
            'tbody' => [
                'tag' => 'tbody',
                'classes' => 'bg-gray-900 divide-y divide-gray-700',
                'rows' => [
                    [
                        'tag' => 'tr',
                        'classes' => 'hover:bg-gray-800',
                        'cells' => [
                            ['tag' => 'td', 'classes' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-300', 'text' => '1'],
                            ['tag' => 'td', 'classes' => 'px-6 py-4 whitespace-nowrap text-sm text-white font-medium', 'text' => '관리자'],
                            ['tag' => 'td', 'classes' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-300', 'text' => 'admin@example.com'],
                            ['tag' => 'td', 'classes' => 'px-6 py-4 whitespace-nowrap', 'content' => '<span class="px-2 py-1 text-xs font-semibold bg-green-600 text-white rounded-full">활성</span>'],
                            ['tag' => 'td', 'classes' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-300', 'text' => '2024-01-01'],
                            ['tag' => 'td', 'classes' => 'px-6 py-4 whitespace-nowrap text-sm font-medium', 'content' => '<button class="text-blue-400 hover:text-blue-300 mr-3">편집</button><button class="text-red-400 hover:text-red-300">삭제</button>']
                        ]
                    ]
                ]
            ]
        ]
    ],

    // ============================
    // 사이드바 (Sidebar)
    // ============================
    
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
    ],
    
    'admin_sidebar' => [
        'type' => 'sidebar',
        'wrapper' => [
            'tag' => 'nav',
            'classes' => 'bg-gray-900 w-64 min-h-screen flex flex-col'
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
            'classes' => 'flex items-center justify-center h-20 shadow-md',
            'content' => [
                'tag' => 'h1',
                'classes' => 'text-3xl font-bold text-white',
                'text' => '관리자'
            ]
        ],
        'menu' => [
            'tag' => 'div',
            'classes' => 'flex-1 px-4 py-6 overflow-y-auto',
            'items' => [
                [
                    'title' => '메인',
                    'items' => [
                        [
                            'type' => 'menu_item',
                            'tag' => 'a',
                            'text' => '대시보드',
                            'url' => '/admin',
                            'icon' => 'dashboard',
                            'classes' => 'flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors'
                        ],
                        [
                            'type' => 'menu_item',
                            'tag' => 'a',
                            'text' => '사용자 관리',
                            'url' => '/admin/users',
                            'icon' => 'users',
                            'classes' => 'flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors'
                        ]
                    ]
                ],
                [
                    'title' => '시스템',
                    'items' => [
                        [
                            'type' => 'menu_item',
                            'tag' => 'a',
                            'text' => '설정',
                            'url' => '/admin/settings',
                            'icon' => 'settings',
                            'classes' => 'flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors'
                        ]
                    ]
                ]
            ]
        ]
    ]
];