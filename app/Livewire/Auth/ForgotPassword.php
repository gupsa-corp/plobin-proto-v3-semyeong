<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    protected $messages = [
        'email.required' => '이메일을 입력해주세요.',
        'email.email' => '올바른 이메일 주소를 입력해주세요.',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', '비밀번호 재설정 링크가 이메일로 전송되었습니다.');
        } else {
            $this->addError('email', '해당 이메일로 등록된 계정을 찾을 수 없습니다.');
        }
    }

    public function render()
    {
        return view('200-page-auth.203-page-auth-forgot-password.300-livewire-form');
    }
}