<?php

namespace App\Livewire\FormPublisher;

use App\Models\SandboxForm;
use Livewire\Component;

class FormPreview extends Component
{
    public $formId;
    public $form;
    public $formData = [];
    
    public function mount($formId)
    {
        $this->formId = $formId;
        $this->form = SandboxForm::findOrFail($formId);
        
        // Initialize form data
        if ($this->form->form_fields) {
            foreach ($this->form->form_fields as $field) {
                $this->formData[$field['id'] ?? $field['name'] ?? ''] = '';
            }
        }
    }
    
    public function submitForm()
    {
        // Basic validation
        $this->validate($this->getValidationRules());
        
        // Here you can process the form submission
        // For now, we'll just show a success message
        session()->flash('form-submitted', $this->form->form_settings['successMessage'] ?? 'Form submitted successfully!');
        
        // Reset form data
        $this->reset('formData');
        if ($this->form->form_fields) {
            foreach ($this->form->form_fields as $field) {
                $this->formData[$field['id'] ?? $field['name'] ?? ''] = '';
            }
        }
        
        $this->dispatch('form-submitted');
    }
    
    private function getValidationRules()
    {
        $rules = [];
        
        if ($this->form->form_fields) {
            foreach ($this->form->form_fields as $field) {
                $fieldId = $field['id'] ?? $field['name'] ?? '';
                if ($fieldId) {
                    $fieldRules = [];
                    
                    // Check if field is required
                    if (isset($field['required']) && $field['required']) {
                        $fieldRules[] = 'required';
                    }
                    
                    // Add type-specific validation
                    switch ($field['type'] ?? '') {
                        case 'email':
                            $fieldRules[] = 'email';
                            break;
                        case 'number':
                            $fieldRules[] = 'numeric';
                            break;
                        case 'url':
                            $fieldRules[] = 'url';
                            break;
                    }
                    
                    if (!empty($fieldRules)) {
                        $rules["formData.{$fieldId}"] = implode('|', $fieldRules);
                    }
                }
            }
        }
        
        return $rules;
    }

    public function render()
    {
        return view('form-publisher.form-preview', [
            'form' => $this->form,
        ]);
    }
}
