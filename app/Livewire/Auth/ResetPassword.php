<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Livewire\Component;

class ResetPassword extends Component
{
    public $token = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRules::min(8)],
            'password_confirmation' => 'required',
        ];
    }

    protected $messages = [
        'email.required' => '이메일을 입력해주세요.',
        'email.email' => '올바른 이메일 주소를 입력해주세요.',
        'password.required' => '비밀번호를 입력해주세요.',
        'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
        'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
        'password_confirmation.required' => '비밀번호 확인을 입력해주세요.',
    ];

    public function mount($token = null, $email = null)
    {
        $this->token = $token ?: request('token');
        $this->email = $email ?: request('email');
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            ['email' => $this->email, 'password' => $this->password, 'password_confirmation' => $this->password_confirmation, 'token' => $this->token],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', '비밀번호가 성공적으로 재설정되었습니다.');
            return redirect()->route('login');
        } else {
            $this->addError('email', '비밀번호 재설정에 실패했습니다. 링크가 만료되었거나 유효하지 않습니다.');
        }
    }

    public function render()
    {
        return view('200-page-auth.204-page-auth-reset-password.300-livewire-form');
    }
}