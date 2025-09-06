<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditProfile extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $organization = '';

    protected $rules = [
        'name' => 'required|min:2',
        'phone' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => '이름을 입력해주세요.',
        'name.min' => '이름은 최소 2자 이상이어야 합니다.',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->first_name . ' ' . $user->last_name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->organization = $user->organization ?? '샘플 조직';
    }

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();
        $nameParts = explode(' ', $this->name, 2);
        
        $user->update([
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'phone' => $this->phone,
        ]);

        session()->flash('message', '개인정보가 성공적으로 수정되었습니다.');
    }

    public function render()
    {
        return view('300-page-service.304-page-mypage-edit.300-livewire-form-profile');
    }
}