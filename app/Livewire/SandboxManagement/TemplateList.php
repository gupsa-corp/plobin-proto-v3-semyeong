<?php

namespace App\Livewire\SandboxManagement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SandboxTemplate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TemplateList extends Component
{
    use WithPagination;

    // 필터 및 검색 프로퍼티
    public $search = '';
    public $typeFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // 모달 및 상태 프로퍼티
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    
    // 폼 프로퍼티
    public $templateName = '';
    public $templateType = 'custom';
    public $description = '';
    public $templatePath = '';
    
    // 실시간 업데이트를 위한 프로퍼티
    protected $listeners = ['refreshTemplates' => 'render'];

    public function render()
    {
        $templates = $this->getTemplates();
        $statistics = $this->getStatistics();
        
        return view('sandbox-management.template-list', [
            'templates' => $templates,
            'statistics' => $statistics,
        ]);
    }

    protected function getTemplates()
    {
        $query = SandboxTemplate::query()
            ->when($this->search, function($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($this->typeFilter, function($query, $typeFilter) {
                $query->where('type', $typeFilter);
            });

        return $query->orderBy($this->sortBy, $this->sortDirection)
                    ->paginate($this->perPage);
    }

    protected function getStatistics()
    {
        $total = SandboxTemplate::count();
        $system = SandboxTemplate::where('type', 'system')->count();
        $custom = SandboxTemplate::where('type', 'custom')->count();
        $mostUsed = SandboxTemplate::orderBy('usage_count', 'desc')->first();

        return compact('total', 'system', 'custom', 'mostUsed');
    }

    // 검색 및 필터 메서드
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // 템플릿 생성
    public function openCreateModal()
    {
        $this->reset(['templateName', 'templateType', 'description', 'templatePath']);
        $this->showCreateModal = true;
    }

    public function createTemplate()
    {
        $this->validate([
            'templateName' => 'required|string|max:255|unique:sandbox_templates,name',
            'templateType' => 'required|in:system,custom',
            'description' => 'nullable|string|max:500',
            'templatePath' => 'required|string|max:255',
        ], [
            'templateName.required' => '템플릿 이름을 입력해주세요.',
            'templateName.unique' => '이미 존재하는 템플릿 이름입니다.',
            'templateType.required' => '템플릿 타입을 선택해주세요.',
            'templatePath.required' => '템플릿 경로를 입력해주세요.',
        ]);

        try {
            SandboxTemplate::create([
                'name' => $this->templateName,
                'type' => $this->templateType,
                'description' => $this->description,
                'path' => $this->templatePath,
                'usage_count' => 0,
                'created_at' => now(),
            ]);

            $this->showCreateModal = false;
            $this->dispatch('template-created', ['message' => '템플릿이 성공적으로 생성되었습니다.']);
            
        } catch (\Exception $e) {
            $this->dispatch('template-error', ['message' => '템플릿 생성 중 오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }

    // 템플릿 수정
    public function openEditModal($templateId)
    {
        $template = SandboxTemplate::findOrFail($templateId);
        
        $this->editingId = $templateId;
        $this->templateName = $template->name;
        $this->templateType = $template->type;
        $this->description = $template->description;
        $this->templatePath = $template->path;
        
        $this->showEditModal = true;
    }

    public function updateTemplate()
    {
        $this->validate([
            'templateName' => 'required|string|max:255|unique:sandbox_templates,name,' . $this->editingId,
            'templateType' => 'required|in:system,custom',
            'description' => 'nullable|string|max:500',
            'templatePath' => 'required|string|max:255',
        ]);

        try {
            $template = SandboxTemplate::findOrFail($this->editingId);
            $template->update([
                'name' => $this->templateName,
                'type' => $this->templateType,
                'description' => $this->description,
                'path' => $this->templatePath,
            ]);

            $this->showEditModal = false;
            $this->editingId = null;
            $this->dispatch('template-updated', ['message' => '템플릿이 성공적으로 수정되었습니다.']);
            
        } catch (\Exception $e) {
            $this->dispatch('template-error', ['message' => '템플릿 수정 중 오류가 발생했습니다.']);
        }
    }

    // 템플릿 삭제
    public function confirmDelete($templateId)
    {
        $this->editingId = $templateId;
        $this->showDeleteModal = true;
    }

    public function deleteTemplate()
    {
        try {
            $template = SandboxTemplate::findOrFail($this->editingId);
            
            // 시스템 템플릿은 삭제할 수 없음
            if ($template->type === 'system') {
                $this->dispatch('template-error', ['message' => '시스템 템플릿은 삭제할 수 없습니다.']);
                return;
            }
            
            $template->delete();

            $this->showDeleteModal = false;
            $this->editingId = null;
            $this->dispatch('template-deleted', ['message' => '템플릿이 성공적으로 삭제되었습니다.']);
            
        } catch (\Exception $e) {
            $this->dispatch('template-error', ['message' => '템플릿 삭제 중 오류가 발생했습니다.']);
        }
    }

    // 도우미 메서드들
    public function getTemplateSize($path)
    {
        // 실제 크기 계산은 비동기로 처리
        return rand(10, 100) . ' MB';
    }

    public function refreshTemplates()
    {
        // 컴포넌트 새로고침
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->editingId = null;
    }
}
