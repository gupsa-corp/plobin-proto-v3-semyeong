<?php
/**
 * @OA\Tag(
 *     name="Task Progress",
 *     description="Task progress tracking and history operations including progress updates, status management, and batch operations"
 * )
 */

namespace App\Functions\GanttChart;

require_once 'DatabaseConnection.php';

/**
 * Task Progress API Handler
 * 
 * Handles task progress operations including progress updates, status changes,
 * batch operations, and progress history tracking.
 */
class TaskProgressApiHandler
{
    private $db;
    
    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }
    
    /**
     * Update task progress with automatic status adjustment
     */
    public function updateTaskProgress($taskId, $progress)
    {
        if (!$taskId || $progress === null) {
            return [
                'success' => false,
                'message' => 'Task ID and progress are required'
            ];
        }
        
        if ($progress < 0 || $progress > 100) {
            return [
                'success' => false,
                'message' => 'Progress must be between 0 and 100'
            ];
        }
        
        try {
            // Check if task exists and get current data
            $currentTask = $this->getTaskCurrentData($taskId);
            if (!$currentTask) {
                return [
                    'success' => false,
                    'message' => 'Task not found'
                ];
            }
            
            // Calculate new status based on progress
            $newStatus = $this->calculateStatusFromProgress($progress);
            
            // Update progress and status
            $updateResult = $this->executeProgressUpdate($taskId, $progress, $newStatus);
            
            if ($updateResult['success']) {
                // Log progress history
                $this->logProgressHistory($taskId, $currentTask['progress'], $progress, $currentTask['status'], $newStatus);
                
                // Check if update affects dependent tasks
                $this->updateDependentTasksIfNeeded($taskId, $newStatus);
                
                return [
                    'success' => true,
                    'message' => 'Task progress updated successfully',
                    'data' => [
                        'task_id' => $taskId,
                        'previous_progress' => $currentTask['progress'],
                        'new_progress' => $progress,
                        'previous_status' => $currentTask['status'],
                        'new_status' => $newStatus,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                ];
            }
            
            return $updateResult;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update task progress: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update multiple tasks progress in batch
     */
    public function batchUpdateTaskProgress($updates)
    {
        if (!is_array($updates) || empty($updates)) {
            return [
                'success' => false,
                'message' => 'Updates array is required and must not be empty'
            ];
        }
        
        $results = [];
        $successCount = 0;
        $errorCount = 0;
        
        try {
            $this->db->beginTransaction();
            
            foreach ($updates as $update) {
                $taskId = $update['task_id'] ?? null;
                $progress = $update['progress'] ?? null;
                
                $result = $this->updateTaskProgress($taskId, $progress);
                $results[] = [
                    'task_id' => $taskId,
                    'success' => $result['success'],
                    'message' => $result['message']
                ];
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'message' => "Batch update completed. Success: {$successCount}, Errors: {$errorCount}",
                'data' => [
                    'total_processed' => count($updates),
                    'success_count' => $successCount,
                    'error_count' => $errorCount,
                    'detailed_results' => $results
                ]
            ];
        } catch (\Exception $e) {
            $this->db->rollback();
            
            return [
                'success' => false,
                'message' => 'Batch update failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update task status directly (without progress)
     */
    public function updateTaskStatus($taskId, $status)
    {
        if (!$taskId || !$status) {
            return [
                'success' => false,
                'message' => 'Task ID and status are required'
            ];
        }
        
        $validStatuses = ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold'];
        if (!in_array($status, $validStatuses)) {
            return [
                'success' => false,
                'message' => 'Invalid status. Valid statuses: ' . implode(', ', $validStatuses)
            ];
        }
        
        try {
            // Get current task data
            $currentTask = $this->getTaskCurrentData($taskId);
            if (!$currentTask) {
                return [
                    'success' => false,
                    'message' => 'Task not found'
                ];
            }
            
            // Adjust progress based on status
            $newProgress = $this->calculateProgressFromStatus($status, $currentTask['progress']);
            
            // Update both status and progress
            $stmt = $this->db->prepare("
                UPDATE tasks 
                SET status = ?, progress = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $stmt->execute([$status, $newProgress, $taskId]);
            
            // Log the change
            $this->logProgressHistory($taskId, $currentTask['progress'], $newProgress, $currentTask['status'], $status);
            
            return [
                'success' => true,
                'message' => 'Task status updated successfully',
                'data' => [
                    'task_id' => $taskId,
                    'previous_status' => $currentTask['status'],
                    'new_status' => $status,
                    'previous_progress' => $currentTask['progress'],
                    'new_progress' => $newProgress
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update task status: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get task progress history
     */
    public function getTaskProgressHistory($taskId)
    {
        if (!$taskId) {
            return [
                'success' => false,
                'message' => 'Task ID is required'
            ];
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM task_progress_history 
                WHERE task_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$taskId]);
            $history = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'data' => $history
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve task progress history: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get current task data
     */
    private function getTaskCurrentData($taskId)
    {
        try {
            $stmt = $this->db->prepare("SELECT id, progress, status FROM tasks WHERE id = ?");
            $stmt->execute([$taskId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Calculate status from progress value
     */
    private function calculateStatusFromProgress($progress)
    {
        if ($progress == 0) {
            return 'pending';
        } elseif ($progress > 0 && $progress < 100) {
            return 'in_progress';
        } elseif ($progress == 100) {
            return 'completed';
        }
        
        return 'pending';
    }
    
    /**
     * Calculate progress from status
     */
    private function calculateProgressFromStatus($status, $currentProgress)
    {
        switch ($status) {
            case 'pending':
                return 0;
            case 'completed':
                return 100;
            case 'in_progress':
                return max($currentProgress, 1); // Ensure at least 1% for in_progress
            default:
                return $currentProgress;
        }
    }
    
    /**
     * Execute the progress update
     */
    private function executeProgressUpdate($taskId, $progress, $status)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE tasks 
                SET progress = ?, status = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            $stmt->execute([$progress, $status, $taskId]);
            
            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to execute progress update: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Log progress history
     */
    private function logProgressHistory($taskId, $oldProgress, $newProgress, $oldStatus, $newStatus)
    {
        try {
            // Check if task_progress_history table exists, if not create it
            $this->createProgressHistoryTableIfNotExists();
            
            $stmt = $this->db->prepare("
                INSERT INTO task_progress_history 
                (task_id, old_progress, new_progress, old_status, new_status, created_at)
                VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ");
            $stmt->execute([$taskId, $oldProgress, $newProgress, $oldStatus, $newStatus]);
        } catch (\Exception $e) {
            // Log error but don't fail the main update
            error_log('Failed to log progress history: ' . $e->getMessage());
        }
    }
    
    /**
     * Create progress history table if it doesn't exist
     */
    private function createProgressHistoryTableIfNotExists()
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS task_progress_history (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    task_id INTEGER NOT NULL,
                    old_progress INTEGER,
                    new_progress INTEGER,
                    old_status VARCHAR(50),
                    new_status VARCHAR(50),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (task_id) REFERENCES tasks(id)
                )
            ");
        } catch (\Exception $e) {
            error_log('Failed to create task_progress_history table: ' . $e->getMessage());
        }
    }
    
    /**
     * Update dependent tasks if needed (when a task is completed)
     */
    private function updateDependentTasksIfNeeded($taskId, $newStatus)
    {
        if ($newStatus !== 'completed') {
            return;
        }
        
        try {
            // Find tasks that depend on this completed task
            $stmt = $this->db->prepare("
                SELECT DISTINCT t.id, t.status 
                FROM tasks t
                JOIN task_dependencies td ON t.id = td.task_id
                WHERE td.depends_on_task_id = ? AND t.status = 'pending'
            ");
            $stmt->execute([$taskId]);
            $dependentTasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Check if dependent tasks can now start
            foreach ($dependentTasks as $dependentTask) {
                $canStart = $this->checkIfTaskCanStart($dependentTask['id']);
                if ($canStart) {
                    // Auto-update status to ready if all dependencies are met
                    $stmt = $this->db->prepare("
                        UPDATE tasks 
                        SET status = 'ready', updated_at = CURRENT_TIMESTAMP
                        WHERE id = ?
                    ");
                    $stmt->execute([$dependentTask['id']]);
                }
            }
        } catch (\Exception $e) {
            error_log('Failed to update dependent tasks: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if a task can start (all dependencies completed)
     */
    private function checkIfTaskCanStart($taskId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total_deps,
                       COUNT(CASE WHEN t.status = 'completed' THEN 1 END) as completed_deps
                FROM task_dependencies td
                JOIN tasks t ON td.depends_on_task_id = t.id
                WHERE td.task_id = ?
            ");
            $stmt->execute([$taskId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result['total_deps'] == $result['completed_deps'];
        } catch (\Exception $e) {
            return false;
        }
    }
}