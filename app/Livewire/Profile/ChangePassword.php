<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ChangePassword extends Component
{
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
        'new_password_confirmation' => 'required',
    ];

    protected $messages = [
        'current_password.required' => '현재 비밀번호를 입력해주세요.',
        'new_password.required' => '새 비밀번호를 입력해주세요.',
        'new_password.min' => '새 비밀번호는 최소 8자 이상이어야 합니다.',
        'new_password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
        'new_password_confirmation.required' => '비밀번호 확인을 입력해주세요.',
    ];

    public function changePassword()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', '현재 비밀번호가 일치하지 않습니다.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('password_message', '비밀번호가 성공적으로 변경되었습니다.');
    }

    public function render()
    {
        return view('300-page-service.304-page-mypage-edit.300-livewire-form-password');
    }
}