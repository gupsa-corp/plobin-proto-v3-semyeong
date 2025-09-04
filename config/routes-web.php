<?php

return [
    // 경로 => [뷰, 라우트명(선택사항)]
    '/' => ['view' => '101-landing-home'],
    '/login' => ['view' => '201-auth-login', 'name' => 'login'],
    '/signup' => ['view' => '202-auth-signup', 'name' => 'register'],
    '/dashboard' => ['view' => '301-service-dashboard', 'name' => 'dashboard'],
    '/admin' => ['view' => '901-admin-dashboard', 'name' => 'admin.dashboard'],
    '/admin/users' => ['view' => '902-admin-users', 'name' => 'admin.users'],
    '/admin/settings' => ['view' => '903-admin-settings', 'name' => 'admin.settings'],
];