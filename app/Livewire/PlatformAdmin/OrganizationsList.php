<?php

namespace App\Livewire\PlatformAdmin;

use App\Models\Organization;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationsList extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = null;
    
    // 조직 생성 관련 속성
    public $showCreateModal = false;
    public $name = '';
    public $url = '';
    public $description = '';
    
    // 사용자 검색 관련 속성
    public $selectedUser = null;
    public $showUserSearch = true;
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($organizationId)
    {
        $this->confirmingDelete = $organizationId;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
    }

    public function deleteOrganization()
    {
        if ($this->confirmingDelete) {
            $organization = Organization::find($this->confirmingDelete);
            
            if ($organization) {
                $organizationName = $organization->name;
                $organization->delete();
                
                session()->flash('message', "'{$organizationName}' 조직이 삭제되었습니다.");
            }
            
            $this->confirmingDelete = null;
            $this->resetPage();
        }
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->resetForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function createOrganization()
    {
        $this->validate([
            'name' => 'required|string|max:25',
            'url' => 'nullable|string|max:50|unique:organizations,url',
            'description' => 'nullable|string|max:500',
            'selectedUser' => 'required',
        ], [
            'name.required' => '조직명은 필수 항목입니다.',
            'name.max' => '조직명은 25자 이하로 입력해주세요.',
            'url.max' => 'URL은 50자 이하로 입력해주세요.',
            'url.unique' => '이미 사용 중인 URL입니다.',
            'description.max' => '설명은 500자 이하로 입력해주세요.',
            'selectedUser.required' => '조직 소유자를 선택해주세요.',
        ]);

        $organization = Organization::create([
            'name' => $this->name,
            'url' => $this->url ?: null,
            'description' => $this->description ?: null,
            'user_id' => $this->selectedUser->id,
            'status' => 'active',
            'members_count' => 1,
        ]);

        session()->flash('message', "'{$organization->name}' 조직이 생성되었습니다. (소유자: {$this->selectedUser->display_name})");
        
        $this->closeCreateModal();
        $this->resetPage();
    }

    public function clearSelectedUser()
    {
        $this->selectedUser = null;
        $this->showUserSearch = true;
    }

    public function updatedSelectedUser($value)
    {
        // Alpine.js sends user data as array, convert to object for easier blade template usage
        if (is_array($value)) {
            $this->selectedUser = (object) $value;
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->url = '';
        $this->description = '';
        $this->selectedUser = null;
        $this->showUserSearch = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $organizations = Organization::query()
            ->with('owner') // 조직 소유자 정보 로드
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('url', 'like', '%' . $this->search . '%');
            })
            ->withCount('projects')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.platform-admin.organizations-list', [
            'organizations' => $organizations
        ]);
    }
}
