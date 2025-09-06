<?php

namespace App\Livewire\Organization;

use Livewire\Component;

class OrganizationManager extends Component
{
    public $name = '';
    public $description = '';
    public $showModal = false;
    public $loading = false;

    protected $rules = [
        'name' => 'required|string|min:1|max:255',
        'description' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'name.required' => '조직명을 입력해주세요.',
        'name.min' => '조직명은 최소 1자 이상이어야 합니다.',
        'name.max' => '조직명은 최대 255자까지 입력 가능합니다.',
        'description.max' => '조직 설명은 최대 500자까지 입력 가능합니다.',
    ];

    protected $listeners = ['openOrganizationModal'];

    public function openOrganizationModal()
    {
        $this->showModal = true;
        $this->reset(['name', 'description', 'loading']);
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'description', 'loading']);
        $this->resetErrorBag();
    }

    public function createOrganization()
    {
        $this->validate();

        $this->loading = true;

        // TODO: 실제 조직 생성 API 호출 구현
        // 현재는 임시로 지연 시뮬레이션
        sleep(1);

        session()->flash('message', '조직이 성공적으로 생성되었습니다.');
        
        $this->loading = false;
        $this->closeModal();
        $this->dispatch('organizationCreated');
    }

    public function render()
    {
        return view('300-page-service.306-page-organizations-list.300-livewire-modal-organization-manager');
    }
}