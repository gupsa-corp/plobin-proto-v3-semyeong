<?php

namespace App\Livewire\Organization;

use Livewire\Component;
use App\Models\Organization;
use App\Models\OrganizationMember;
use Illuminate\Support\Facades\Auth;

class CreateOrganization extends Component
{
    public $name = '';
    public $description = '';
    public $showModal = false;

    protected $listeners = ['openCreateModal' => 'openModal'];

    protected $rules = [
        'name' => 'required|min:1|max:25',
        'description' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'name.required' => '조직명을 입력해주세요.',
        'name.min' => '조직명은 최소 1자 이상이어야 합니다.',
        'name.max' => '조직명은 최대 25자까지 입력 가능합니다.',
        'description.max' => '조직 설명은 최대 255자까지 입력 가능합니다.',
    ];

    public function openModal()
    {
        // 모달 열기 전에 인증 상태 확인
        if (!Auth::check()) {
            session()->flash('error', '조직을 생성하려면 로그인이 필요합니다.');
            return redirect('/login');
        }

        $this->showModal = true;
        $this->reset(['name', 'description']);
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'description']);
        $this->resetErrorBag();
    }

    public function createOrganization()
    {
        $this->validate();

        // 인증 상태 체크
        if (!Auth::check()) {
            session()->flash('error', '로그인이 필요합니다.');
            return redirect('/login');
        }

        // 조직 생성
        $organization = Organization::create([
            'name' => $this->name,
            'description' => $this->description,
            'user_id' => Auth::id(),
            'status' => 'active',
            'members_count' => 1,
        ]);

        // 생성자를 조직의 소유자로 추가
        OrganizationMember::create([
            'user_id' => Auth::id(),
            'organization_id' => $organization->id,
            'role_name' => 'owner',
            'invitation_status' => 'accepted',
            'joined_at' => now()
        ]);

        session()->flash('message', '조직이 성공적으로 생성되었습니다.');

        $this->closeModal();
        $this->dispatch('organizationCreated');
    }

    public function render()
    {
        return view('300-page-service.306-page-organizations-list.300-livewire-modal-create-organization');
    }
}
