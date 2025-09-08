<?php

namespace App\Functions\AIServer;

/**
 * CallbackHandler
 * 
 * Handles AI server callbacks for sandbox operations
 */
class CallbackHandler
{
    private string $logFile;
    private array $supportedActions;

    public function __construct()
    {
        $this->logFile = __DIR__ . '/../../logs/ai-callbacks.log';
        $this->supportedActions = [
            'process_complete',
            'process_started',
            'process_error',
            'status_update',
            'data_processed',
            'webhook_received'
        ];
        
        // Ensure log directory exists
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    /**
     * Process incoming callback data
     * 
     * @param array $callbackData The callback payload
     * @return array Response data
     */
    public function processCallback(array $callbackData): array
    {
        $callbackId = $this->generateCallbackId();
        
        try {
            // Validate action
            $action = $callbackData['action'] ?? '';
            if (!$this->isValidAction($action)) {
                return [
                    'success' => false,
                    'message' => 'Unsupported action: ' . $action,
                    'callback_id' => $callbackId
                ];
            }

            // Log the callback
            $this->logCallback($callbackId, $callbackData);

            // Process based on action type
            $result = $this->handleAction($action, $callbackData);

            return [
                'success' => true,
                'message' => 'Callback processed successfully',
                'data' => [
                    'processed' => true,
                    'callback_id' => $callbackId,
                    'action_result' => $result
                ]
            ];

        } catch (\Exception $e) {
            $this->logError($callbackId, $e);
            return [
                'success' => false,
                'message' => 'Failed to process callback',
                'callback_id' => $callbackId,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle specific action types
     * 
     * @param string $action Action type
     * @param array $data Callback data
     * @return array Action result
     */
    private function handleAction(string $action, array $data): array
    {
        switch ($action) {
            case 'process_complete':
                return $this->handleProcessComplete($data);
            
            case 'process_started':
                return $this->handleProcessStarted($data);
            
            case 'process_error':
                return $this->handleProcessError($data);
            
            case 'status_update':
                return $this->handleStatusUpdate($data);
            
            case 'data_processed':
                return $this->handleDataProcessed($data);
            
            case 'webhook_received':
                return $this->handleWebhookReceived($data);
            
            default:
                return ['status' => 'unknown_action'];
        }
    }

    /**
     * Handle process completion callback
     */
    private function handleProcessComplete(array $data): array
    {
        $processId = $data['data']['process_id'] ?? 'unknown';
        $result = $data['data']['result'] ?? [];
        
        // Store completion data (could be database, cache, etc.)
        $this->storeProcessResult($processId, $result);
        
        return [
            'status' => 'completed',
            'process_id' => $processId,
            'result_stored' => true
        ];
    }

    /**
     * Handle process start callback
     */
    private function handleProcessStarted(array $data): array
    {
        $processId = $data['data']['process_id'] ?? 'unknown';
        
        return [
            'status' => 'started',
            'process_id' => $processId,
            'acknowledged' => true
        ];
    }

    /**
     * Handle process error callback
     */
    private function handleProcessError(array $data): array
    {
        $processId = $data['data']['process_id'] ?? 'unknown';
        $error = $data['data']['error'] ?? 'Unknown error';
        
        // Log error details
        error_log("AI Process Error - Process ID: {$processId}, Error: {$error}");
        
        return [
            'status' => 'error_logged',
            'process_id' => $processId,
            'error_acknowledged' => true
        ];
    }

    /**
     * Handle status update callback
     */
    private function handleStatusUpdate(array $data): array
    {
        $processId = $data['data']['process_id'] ?? 'unknown';
        $status = $data['data']['status'] ?? 'unknown';
        $progress = $data['data']['progress'] ?? 0;
        
        return [
            'status' => 'status_updated',
            'process_id' => $processId,
            'current_status' => $status,
            'progress' => $progress
        ];
    }

    /**
     * Handle data processed callback
     */
    private function handleDataProcessed(array $data): array
    {
        $dataId = $data['data']['data_id'] ?? 'unknown';
        $records = $data['data']['records_processed'] ?? 0;
        
        return [
            'status' => 'data_acknowledged',
            'data_id' => $dataId,
            'records_processed' => $records
        ];
    }

    /**
     * Handle webhook received callback
     */
    private function handleWebhookReceived(array $data): array
    {
        $webhookId = $data['data']['webhook_id'] ?? 'unknown';
        $source = $data['data']['source'] ?? 'unknown';
        
        return [
            'status' => 'webhook_acknowledged',
            'webhook_id' => $webhookId,
            'source' => $source
        ];
    }

    /**
     * Store process result
     */
    private function storeProcessResult(string $processId, array $result): void
    {
        // This could be implemented to store in database, cache, file system, etc.
        $storage = [
            'process_id' => $processId,
            'result' => $result,
            'stored_at' => date('c'),
            'sandbox' => 'storage-sandbox-1'
        ];
        
        // For now, just log it (in real implementation, store in database)
        $this->logInfo("Process result stored: " . json_encode($storage));
    }

    /**
     * Check if action is supported
     */
    private function isValidAction(string $action): bool
    {
        return in_array($action, $this->supportedActions);
    }

    /**
     * Generate unique callback ID
     */
    private function generateCallbackId(): string
    {
        return 'cb_' . uniqid() . '_' . time();
    }

    /**
     * Log callback data
     */
    private function logCallback(string $callbackId, array $data): void
    {
        $logEntry = [
            'timestamp' => date('c'),
            'callback_id' => $callbackId,
            'action' => $data['action'] ?? 'unknown',
            'data' => $data,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        file_put_contents(
            $this->logFile,
            json_encode($logEntry) . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Log error
     */
    private function logError(string $callbackId, \Exception $e): void
    {
        $errorEntry = [
            'timestamp' => date('c'),
            'callback_id' => $callbackId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'level' => 'ERROR'
        ];
        
        file_put_contents(
            $this->logFile,
            json_encode($errorEntry) . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Log info message
     */
    private function logInfo(string $message): void
    {
        $infoEntry = [
            'timestamp' => date('c'),
            'message' => $message,
            'level' => 'INFO'
        ];
        
        file_put_contents(
            $this->logFile,
            json_encode($infoEntry) . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Get callback logs (for debugging/monitoring)
     */
    public function getRecentLogs(int $limit = 100): array
    {
        if (!file_exists($this->logFile)) {
            return [];
        }

        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES);
        $logs = [];

        foreach (array_slice($lines, -$limit) as $line) {
            $decoded = json_decode($line, true);
            if ($decoded) {
                $logs[] = $decoded;
            }
        }

        return array_reverse($logs);
    }
}