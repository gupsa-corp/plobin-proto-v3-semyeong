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
        
        // Convert Form-Creator format to Form-Publisher format
        $this->convertFormCreatorData();
        
        // Initialize form data
        if ($this->form->form_fields) {
            foreach ($this->form->form_fields as $field) {
                $this->formData[$field['id'] ?? $field['name'] ?? ''] = '';
            }
        }
    }
    
    private function convertFormCreatorData()
    {
        // Check if form_fields contains Form-Creator format (components with properties)
        if ($this->form->form_fields && is_array($this->form->form_fields)) {
            $firstField = $this->form->form_fields[0] ?? null;
            
            // If the first field has 'properties' key, it's Form-Creator format
            if ($firstField && isset($firstField['properties'])) {
                $convertedFields = [];
                
                foreach ($this->form->form_fields as $component) {
                    $field = $this->convertComponentToField($component);
                    if ($field) {
                        $convertedFields[] = $field;
                    }
                }
                
                // Update the form with converted data
                $this->form->form_fields = $convertedFields;
            }
        }
    }
    
    private function convertComponentToField($component)
    {
        $props = $component['properties'] ?? [];
        $field = [
            'id' => $component['id'],
            'type' => $this->mapComponentType($component['type']),
            'label' => $props['label'] ?? 'Untitled Field',
            'name' => $props['name'] ?? $component['id'],
            'required' => $props['required'] ?? false,
            'disabled' => $props['disabled'] ?? false,
            'hidden' => $props['hidden'] ?? false,
            'placeholder' => $props['placeholder'] ?? '',
            'description' => $props['description'] ?? ''
        ];
        
        switch ($component['type']) {
            case 'dropdown':
            case 'select':
                $field['type'] = 'select';
                $field['options'] = $this->parseOptions($props['options'] ?? '');
                break;
            case 'radiogroup':
                $field['type'] = 'radio';
                $field['options'] = $this->parseOptions($props['options'] ?? '');
                break;
            case 'checkbox':
                $field['type'] = 'checkbox';
                break;
            case 'textarea':
                $field['type'] = 'textarea';
                $field['rows'] = intval($props['rows'] ?? 4);
                break;
            case 'input':
                $field['type'] = $props['inputType'] ?? 'text';
                break;
            case 'button':
                $field['type'] = 'button';
                $field['text'] = $props['text'] ?? $props['label'] ?? 'Button';
                $field['buttonType'] = $props['type'] ?? 'button';
                break;
            case 'header':
                $field['type'] = 'header';
                $field['level'] = $props['level'] ?? 'h2';
                $field['text'] = $props['text'] ?? $props['label'] ?? 'Header Text';
                break;
            case 'tagpicker':
                $field['type'] = 'tagpicker';
                $field['options'] = $this->parseOptions($props['options'] ?? '');
                break;
            default:
                $field['type'] = 'text';
        }
        
        return $field;
    }
    
    private function mapComponentType($creatorType)
    {
        $typeMap = [
            'input' => 'text',
            'textarea' => 'textarea',
            'dropdown' => 'select',
            'select' => 'select',
            'checkbox' => 'checkbox',
            'radiogroup' => 'radio',
            'button' => 'button',
            'header' => 'header',
            'file' => 'file',
            'date' => 'date',
            'tagpicker' => 'tagpicker'
        ];
        
        return $typeMap[$creatorType] ?? 'text';
    }
    
    private function parseOptions($options)
    {
        if (is_array($options)) {
            return $options;
        }
        if (is_string($options)) {
            return array_filter(array_map('trim', explode("\n", $options)));
        }
        return [];
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
