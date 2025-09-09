<?php
declare(strict_types = 1);

namespace App\Functions\UserAuth;

class UserAuth
{
    public function __invoke($action)
    {
        switch ($action) {
            case 'login':
                return ['success' => true, 'token' => 'sample_token_' . time(), 'user' => ['id' => 1, 'username' => 'demo']];
            case 'register':
                return ['success' => true, 'user_id' => rand(1000, 9999), 'message' => 'User registered'];
            case 'validate_token':
                return ['success' => true, 'user_id' => 1, 'username' => 'demo', 'role' => 'user'];
            case 'logout':
                return ['success' => true, 'message' => 'Logged out'];
            case 'change_password':
                return ['success' => true, 'message' => 'Password changed'];
            default:
                return ['success' => false, 'message' => 'Unknown action'];
        }
    }
}
