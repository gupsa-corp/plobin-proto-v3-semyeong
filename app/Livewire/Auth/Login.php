<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'email.required' => '이메일을 입력해주세요.',
        'email.email' => '올바른 이메일 주소를 입력해주세요.',
        'password.required' => '비밀번호를 입력해주세요.',
        'password.min' => '비밀번호는 최소 6자 이상이어야 합니다.',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', '이메일 또는 비밀번호가 일치하지 않습니다.');
    }

    public function render()
    {
        return view('200-page-auth.201-page-auth-login.300-livewire-form');
    }
}
