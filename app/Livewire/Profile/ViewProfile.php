<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewProfile extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();

        // 사용자가 로그인하지 않은 경우 로그인 페이지로 리디렉션
        if (!$this->user) {
            return redirect('/login');
        }

        // 관계된 데이터를 미리 로드
        $this->user->load(['organizations', 'roles']);
        
        // 플래시 메시지 확인하여 모달 표시
        if (session('show_password_modal')) {
            $this->dispatch('showPasswordModal');
            session()->forget('show_password_modal');
        }
    }

    public function render()
    {
        return view('livewire.profile.400-view-profile');
    }
}
