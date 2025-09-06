<?php

namespace App\Livewire\Organization;

use Livewire\Component;
use App\Models\Organization;
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

        // 조직 생성
        Organization::create([
            'name' => $this->name,
            'description' => $this->description,
            'user_id' => Auth::id(),
            'status' => 'active',
            'members_count' => 1,
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