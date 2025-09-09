<?php
namespace App\Functions\StringHelper;

class StringHelper
{
    public function __invoke($params)
    {
        $operation = $params['operation'] ?? 'main';
        
        switch ($operation) {
            case 'format':
                return $this->formatData($params);
            case 'convert':
                return $this->convertData($params);
            case 'calculate':
                return $this->calculateValue($params);
            case 'generate':
                return $this->generateOutput($params);
            default:
                return $this->mainOperation($params);
        }
    }

    /**
     * Main utility operation
     */
    private function mainOperation($params)
    {
        try {
            // TODO: Implement main utility logic
            return [
                'success' => true,
                'result' => 'Main operation completed',
                'params_received' => array_keys($params)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Main operation error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format data according to specified format
     */
    private function formatData($params)
    {
        $data = $params['data'] ?? null;
        $format = $params['format'] ?? 'json';
        
        try {
            switch ($format) {
                case 'json':
                    return [
                        'success' => true,
                        'formatted_data' => json_encode($data, JSON_PRETTY_PRINT)
                    ];
                case 'csv':
                    // TODO: Implement CSV formatting
                    return ['success' => true, 'formatted_data' => 'CSV format'];
                case 'xml':
                    // TODO: Implement XML formatting
                    return ['success' => true, 'formatted_data' => 'XML format'];
                default:
                    return ['success' => false, 'message' => 'Unsupported format'];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Formatting error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Convert data from one type to another
     */
    private function convertData($params)
    {
        $input = $params['input'] ?? null;
        $from = $params['from'] ?? 'auto';
        $to = $params['to'] ?? 'array';
        
        try {
            // TODO: Implement conversion logic
            return [
                'success' => true,
                'converted_data' => $input,
                'from' => $from,
                'to' => $to
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Conversion error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate values based on input
     */
    private function calculateValue($params)
    {
        $values = $params['values'] ?? [];
        $operation = $params['calc_operation'] ?? 'sum';
        
        if (!is_array($values)) {
            return ['success' => false, 'message' => 'Values must be an array'];
        }
        
        try {
            $result = 0;
            switch ($operation) {
                case 'sum':
                    $result = array_sum($values);
                    break;
                case 'average':
                    $result = count($values) > 0 ? array_sum($values) / count($values) : 0;
                    break;
                case 'max':
                    $result = max($values);
                    break;
                case 'min':
                    $result = min($values);
                    break;
                default:
                    return ['success' => false, 'message' => 'Unknown calculation operation'];
            }
            
            return [
                'success' => true,
                'result' => $result,
                'operation' => $operation,
                'input_count' => count($values)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Calculation error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate output based on parameters
     */
    private function generateOutput($params)
    {
        $type = $params['generate_type'] ?? 'uuid';
        
        try {
            switch ($type) {
                case 'uuid':
                    $result = uniqid('', true);
                    break;
                case 'timestamp':
                    $result = date('Y-m-d H:i:s');
                    break;
                case 'hash':
                    $input = $params['input'] ?? '';
                    $result = hash('sha256', $input);
                    break;
                case 'random_string':
                    $length = $params['length'] ?? 10;
                    $result = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
                    break;
                default:
                    return ['success' => false, 'message' => 'Unknown generation type'];
            }
            
            return [
                'success' => true,
                'generated' => $result,
                'type' => $type
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Generation error: ' . $e->getMessage()
            ];
        }
    }
}