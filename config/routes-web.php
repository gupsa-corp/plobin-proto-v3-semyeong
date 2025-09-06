<?php

return [
    // 경로 => [뷰, 라우트명(선택사항)]
    '/' => ['view' => '100-page-landing.101-page-landing-home.000-index'],
    '/login' => ['view' => '200-page-auth.201-page-auth-login.000-index', 'name' => 'login'],
    '/signup' => ['view' => '200-page-auth.202-page-auth-signup.000-index', 'name' => 'register'],
    '/forgot-password' => ['view' => '200-page-auth.203-page-auth-forgot-password.000-index', 'name' => 'password.request'],
    '/reset-password' => ['view' => '200-page-auth.204-page-auth-reset-password.000-index', 'name' => 'password.reset'],
    '/dashboard' => ['view' => '300-page-service.301-page-dashboard.000-index', 'name' => 'dashboard'],
    '/organizations' => ['view' => '300-page-service.306-page-organizations-list.000-index', 'name' => 'organizations.index'],
    '/mypage' => ['view' => '300-page-service.303-page-mypage-profile.000-index', 'name' => 'profile'],
    '/mypage/edit' => ['view' => '300-page-service.304-page-mypage-edit.000-index', 'name' => 'profile.edit'],
    '/mypage/delete' => ['view' => '300-page-service.305-page-mypage-delete.000-index', 'name' => 'account.delete'],
    '/organizations/{id}/dashboard' => ['view' => '300-page-service.302-page-organization-dashboard.000-index', 'name' => 'organization.dashboard'],
    '/admin' => ['view' => '900-page-admin.901-admin-dashboard', 'name' => 'admin.dashboard'],
    '/admin/users' => ['view' => '900-page-admin.902-admin-users', 'name' => 'admin.users'],
    '/admin/settings' => ['view' => '900-page-admin.903-admin-settings', 'name' => 'admin.settings'],
];
