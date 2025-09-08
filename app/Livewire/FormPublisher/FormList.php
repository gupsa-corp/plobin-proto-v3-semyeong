<?php

namespace App\Livewire\FormPublisher;

use App\Models\SandboxForm;
use Livewire\Component;
use Livewire\WithPagination;

class FormList extends Component
{
    use WithPagination;

    public $search = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteForm($formId)
    {
        SandboxForm::findOrFail($formId)->delete();
        
        $this->dispatch('form-deleted');
    }

    public function duplicateForm($formId)
    {
        $originalForm = SandboxForm::findOrFail($formId);
        
        $newForm = $originalForm->replicate();
        $newForm->title = $originalForm->title . ' (복사본)';
        $newForm->save();
        
        $this->dispatch('form-duplicated');
    }

    public function render()
    {
        $forms = SandboxForm::query()
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('form-publisher.form-list', [
            'forms' => $forms
        ]);
    }
}
