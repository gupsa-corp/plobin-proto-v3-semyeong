<?php
/**
 * FormRenderer - JSON을 HTML 폼으로 변환
 */

require_once 'ValidationHelper.php';

class FormRenderer
{
    /**
     * JSON을 HTML 폼으로 렌더링
     */
    public static function renderForm($formJson, $formId = null)
    {
        // JSON 검증
        $validation = ValidationHelper::validateJson($formJson);
        if (!$validation['valid']) {
            return '<div class="alert alert-error">Invalid JSON: ' . $validation['error'] . '</div>';
        }
        
        $formData = json_decode($formJson, true);
        
        // 폼 구조 검증
        $structureValidation = ValidationHelper::validateFormStructure($formData);
        if (!$structureValidation['valid']) {
            return '<div class="alert alert-error">Invalid form structure:<br>' . 
                   implode('<br>', $structureValidation['errors']) . '</div>';
        }
        
        return self::buildFormHtml($formData, $formId);
    }
    
    /**
     * HTML 폼 생성
     */
    private static function buildFormHtml($formData, $formId = null)
    {
        $html = '<div class="form-container">';
        
        // 폼 제목 및 설명
        if (!empty($formData['title'])) {
            $html .= '<h2 class="form-title">' . htmlspecialchars($formData['title']) . '</h2>';
        }
        
        if (!empty($formData['description'])) {
            $html .= '<p class="form-description">' . htmlspecialchars($formData['description']) . '</p>';
        }
        
        // 폼 시작
        $action = $formId ? "preview/{$formId}" : '#';
        $html .= '<form method="POST" action="' . $action . '" class="dynamic-form">';
        
        // 필드 렌더링
        foreach ($formData['fields'] as $field) {
            $html .= self::renderField($field);
        }
        
        // 제출 버튼
        $html .= '<div class="form-group">';
        $html .= '<button type="submit" class="btn btn-primary">제출</button>';
        $html .= '<button type="reset" class="btn btn-secondary">초기화</button>';
        $html .= '</div>';
        
        $html .= '</form>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 개별 필드 렌더링
     */
    private static function renderField($field)
    {
        $type = $field['type'];
        $name = htmlspecialchars($field['name']);
        $label = htmlspecialchars($field['label']);
        $required = isset($field['required']) && $field['required'] ? 'required' : '';
        $placeholder = isset($field['placeholder']) ? htmlspecialchars($field['placeholder']) : '';
        $value = isset($field['value']) ? htmlspecialchars($field['value']) : '';
        
        $html = '<div class="form-group">';
        $html .= '<label for="' . $name . '">' . $label;
        if ($required) {
            $html .= ' <span class="required">*</span>';
        }
        $html .= '</label>';
        
        switch ($type) {
            case 'textarea':
                $rows = $field['rows'] ?? 4;
                $html .= '<textarea id="' . $name . '" name="' . $name . '" rows="' . $rows . '" ';
                $html .= 'placeholder="' . $placeholder . '" ' . $required . '>' . $value . '</textarea>';
                break;
                
            case 'select':
                $html .= '<select id="' . $name . '" name="' . $name . '" ' . $required . '>';
                if ($placeholder) {
                    $html .= '<option value="">' . $placeholder . '</option>';
                }
                if (isset($field['options'])) {
                    foreach ($field['options'] as $option) {
                        $optionValue = htmlspecialchars($option['value']);
                        $optionText = htmlspecialchars($option['text']);
                        $selected = ($optionValue == $value) ? 'selected' : '';
                        $html .= '<option value="' . $optionValue . '" ' . $selected . '>' . $optionText . '</option>';
                    }
                }
                $html .= '</select>';
                break;
                
            case 'radio':
                if (isset($field['options'])) {
                    foreach ($field['options'] as $index => $option) {
                        $optionValue = htmlspecialchars($option['value']);
                        $optionText = htmlspecialchars($option['text']);
                        $checked = ($optionValue == $value) ? 'checked' : '';
                        $radioId = $name . '_' . $index;
                        
                        $html .= '<div class="radio-option">';
                        $html .= '<input type="radio" id="' . $radioId . '" name="' . $name . '" ';
                        $html .= 'value="' . $optionValue . '" ' . $checked . ' ' . $required . '>';
                        $html .= '<label for="' . $radioId . '">' . $optionText . '</label>';
                        $html .= '</div>';
                    }
                }
                break;
                
            case 'checkbox':
                $checked = $value ? 'checked' : '';
                $html .= '<div class="checkbox-option">';
                $html .= '<input type="checkbox" id="' . $name . '" name="' . $name . '" ';
                $html .= 'value="1" ' . $checked . ' ' . $required . '>';
                $html .= '<label for="' . $name . '">' . ($field['checkboxLabel'] ?? 'Check this') . '</label>';
                $html .= '</div>';
                break;
                
            default:
                // text, email, password, number, date, tel, url
                $html .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" ';
                $html .= 'value="' . $value . '" placeholder="' . $placeholder . '" ' . $required . '>';
                break;
        }
        
        // 도움말 텍스트
        if (isset($field['help'])) {
            $html .= '<small class="form-help">' . htmlspecialchars($field['help']) . '</small>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 폼 제출 결과 렌더링
     */
    public static function renderSubmissionResult($submissionData, $formData)
    {
        $html = '<div class="submission-result">';
        $html .= '<h3>폼 제출 결과</h3>';
        $html .= '<div class="result-data">';
        
        foreach ($formData['fields'] as $field) {
            $fieldName = $field['name'];
            $fieldLabel = $field['label'];
            $fieldValue = isset($submissionData[$fieldName]) ? $submissionData[$fieldName] : '';
            
            $html .= '<div class="result-item">';
            $html .= '<strong>' . htmlspecialchars($fieldLabel) . ':</strong> ';
            $html .= '<span>' . htmlspecialchars($fieldValue) . '</span>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '<div class="result-actions">';
        $html .= '<button onclick="window.history.back()" class="btn btn-secondary">다시 입력</button>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 에러 메시지 렌더링
     */
    public static function renderErrors($errors)
    {
        if (empty($errors)) {
            return '';
        }
        
        $html = '<div class="alert alert-error">';
        $html .= '<h4>입력 오류</h4>';
        $html .= '<ul>';
        
        foreach ($errors as $fieldName => $error) {
            $html .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
}