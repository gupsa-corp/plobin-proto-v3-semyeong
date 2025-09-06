<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'first_name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'first_name.required' => '이름을 입력해주세요.',
        'first_name.min' => '이름은 최소 2자 이상이어야 합니다.',
        'last_name.required' => '성을 입력해주세요.',
        'last_name.min' => '성은 최소 2자 이상이어야 합니다.',
        'email.required' => '이메일을 입력해주세요.',
        'email.email' => '올바른 이메일 주소를 입력해주세요.',
        'email.unique' => '이미 사용중인 이메일입니다.',
        'password.required' => '비밀번호를 입력해주세요.',
        'password.min' => '비밀번호는 최소 6자 이상이어야 합니다.',
        'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('200-page-auth.202-page-auth-signup.300-livewire-form');
    }
}
