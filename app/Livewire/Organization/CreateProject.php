<?php

namespace App\Livewire\Organization;

use Livewire\Component;
use App\Models\Project;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class CreateProject extends Component
{
    public $name = '';
    public $description = '';
    public $organizationId;
    public $organization;
    public $showModal = false;
    public $isCreating = false;

    protected $listeners = [
        'openCreateModal' => 'openModal'
    ];

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'description' => 'nullable|max:1000'
    ];

    protected $messages = [
        'name.required' => '프로젝트 이름을 입력해주세요.',
        'name.min' => '프로젝트 이름은 최소 2글자 이상이어야 합니다.',
        'name.max' => '프로젝트 이름은 255글자를 초과할 수 없습니다.',
        'description.max' => '설명은 1000글자를 초과할 수 없습니다.'
    ];

    public function mount($organizationId)
    {
        $this->organizationId = $organizationId;
        $this->organization = Organization::find($organizationId);
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->resetValidation();
    }

    public function create()
    {
        $this->validate();
        
        $this->isCreating = true;
        
        try {
            Project::create([
                'name' => $this->name,
                'description' => $this->description,
                'organization_id' => $this->organizationId,
                'user_id' => Auth::id()
            ]);

            $this->dispatch('projectCreated');
            $this->closeModal();
            session()->flash('success', '프로젝트가 성공적으로 생성되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '프로젝트 생성 중 오류가 발생했습니다.');
        }
        
        $this->isCreating = false;
    }

    public function render()
    {
        return view('300-page-service.307-page-organization-projects.301-livewire-create-project');
    }
}