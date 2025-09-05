<?php

return [
    // 경로 => [뷰, 라우트명(선택사항)]
    '/' => ['view' => '101-landing-home'],
    '/login' => ['view' => '201-auth-login', 'name' => 'login'],
    '/signup' => ['view' => '202-auth-signup', 'name' => 'register'],
    '/forgot-password' => ['view' => '203-auth-forgot-password', 'name' => 'password.request'],
    '/reset-password' => ['view' => '204-auth-reset-password', 'name' => 'password.reset'],
    '/dashboard' => ['view' => '301-service-dashboard', 'name' => 'dashboard'],
    '/organizations/{id}/dashboard' => ['view' => '302-service-organization-dashboard', 'name' => 'organization.dashboard'],
    '/admin' => ['view' => '901-admin-dashboard', 'name' => 'admin.dashboard'],
    '/admin/users' => ['view' => '902-admin-users', 'name' => 'admin.users'],
    '/admin/settings' => ['view' => '903-admin-settings', 'name' => 'admin.settings'],
];