<?php
/**
 * ValidationHelper - JSON 검증 및 유효성 검사
 */

class ValidationHelper
{
    /**
     * JSON 문법 검증
     */
    public static function validateJson($jsonString)
    {
        if (empty($jsonString)) {
            return ['valid' => false, 'error' => 'JSON string is empty'];
        }
        
        json_decode($jsonString);
        $jsonError = json_last_error();
        
        if ($jsonError !== JSON_ERROR_NONE) {
            return [
                'valid' => false, 
                'error' => self::getJsonErrorMessage($jsonError)
            ];
        }
        
        return ['valid' => true, 'error' => null];
    }
    
    /**
     * 폼 구조 검증
     */
    public static function validateFormStructure($formData)
    {
        $errors = [];
        
        // 기본 필드 검증
        if (!isset($formData['title']) || empty($formData['title'])) {
            $errors[] = 'Title is required';
        }
        
        if (!isset($formData['fields']) || !is_array($formData['fields'])) {
            $errors[] = 'Fields array is required';
        } else {
            // 각 필드 검증
            foreach ($formData['fields'] as $index => $field) {
                $fieldErrors = self::validateField($field, $index);
                $errors = array_merge($errors, $fieldErrors);
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * 개별 필드 검증
     */
    private static function validateField($field, $index)
    {
        $errors = [];
        $fieldPrefix = "Field #{$index}";
        
        // 필수 속성 검증
        if (!isset($field['type']) || empty($field['type'])) {
            $errors[] = "{$fieldPrefix}: type is required";
        }
        
        if (!isset($field['name']) || empty($field['name'])) {
            $errors[] = "{$fieldPrefix}: name is required";
        }
        
        if (!isset($field['label']) || empty($field['label'])) {
            $errors[] = "{$fieldPrefix}: label is required";
        }
        
        // 지원되는 필드 타입 검증
        $supportedTypes = ['text', 'email', 'password', 'textarea', 'select', 'radio', 'checkbox', 'number', 'date', 'tel', 'url'];
        if (isset($field['type']) && !in_array($field['type'], $supportedTypes)) {
            $errors[] = "{$fieldPrefix}: unsupported field type '{$field['type']}'";
        }
        
        return $errors;
    }
    
    /**
     * 폼 제출 데이터 검증
     */
    public static function validateSubmissionData($submissionData, $formStructure)
    {
        $errors = [];
        $fields = $formStructure['fields'] ?? [];
        
        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $isRequired = $field['required'] ?? false;
            $fieldValue = $submissionData[$fieldName] ?? null;
            
            // 필수 필드 검증
            if ($isRequired && empty($fieldValue)) {
                $errors[$fieldName] = "{$field['label']} is required";
                continue;
            }
            
            // 타입별 검증
            if (!empty($fieldValue)) {
                $typeValidation = self::validateFieldType($fieldValue, $field['type'], $field['label']);
                if (!$typeValidation['valid']) {
                    $errors[$fieldName] = $typeValidation['error'];
                }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * 필드 타입별 데이터 검증
     */
    private static function validateFieldType($value, $type, $label)
    {
        switch ($type) {
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return ['valid' => false, 'error' => "{$label} must be a valid email"];
                }
                break;
                
            case 'number':
                if (!is_numeric($value)) {
                    return ['valid' => false, 'error' => "{$label} must be a number"];
                }
                break;
                
            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    return ['valid' => false, 'error' => "{$label} must be a valid URL"];
                }
                break;
                
            case 'tel':
                if (!preg_match('/^[\d\s\-\+\(\)]+$/', $value)) {
                    return ['valid' => false, 'error' => "{$label} must be a valid phone number"];
                }
                break;
        }
        
        return ['valid' => true, 'error' => null];
    }
    
    /**
     * JSON 에러 메시지 반환
     */
    private static function getJsonErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Invalid or malformed JSON';
            case JSON_ERROR_CTRL_CHAR:
                return 'Control character error';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters';
            default:
                return 'Unknown JSON error';
        }
    }
    
    /**
     * 입력 데이터 정리
     */
    public static function sanitizeInput($input)
    {
        if (is_string($input)) {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
        
        return $input;
    }
}