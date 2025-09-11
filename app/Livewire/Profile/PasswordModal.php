<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\On;

class PasswordModal extends Component
{
    public $password = '';
    public $showModal = false;
    public $errorMessage = '';
    
    #[On('showPasswordModal')]
    public function openModal()
    {
        $this->showModal = true;
        $this->password = '';
        $this->errorMessage = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->password = '';
        $this->errorMessage = '';
    }

    public function verifyPassword()
    {
        $this->errorMessage = '';

        if (empty($this->password)) {
            $this->errorMessage = '비밀번호를 입력해주세요.';
            return;
        }

        if (!Hash::check($this->password, Auth::user()->password)) {
            $this->errorMessage = '비밀번호가 일치하지 않습니다.';
            return;
        }

        // 비밀번호 확인 성공 - 세션에 표시하고 리다이렉트
        session(['password_verified' => true]);
        $this->closeModal();
        return redirect('/mypage/edit');
    }

    public function render()
    {
        return view('livewire.profile.300-password-modal');
    }
}