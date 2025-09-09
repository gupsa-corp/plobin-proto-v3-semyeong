<?php
/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="Task management and querying operations including task retrieval, filtering, and dependency management"
 * )
 */

namespace App\Functions\GanttChart;

require_once 'DatabaseConnection.php';

/**
 * Tasks API Handler
 * 
 * Handles all task-related API operations including task retrieval,
 * filtering by status/priority, and dependency management.
 */
class TasksApiHandler
{
    private $db;
    
    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }
    
    /**
     * Get all tasks for a specific project
     */
    public function getProjectTasks($projectId)
    {
        if (!$projectId) {
            return [
                'success' => false,
                'message' => 'Project ID is required'
            ];
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    t.*,
                    GROUP_CONCAT(td.depends_on_task_id) as dependencies
                FROM tasks t
                LEFT JOIN task_dependencies td ON t.id = td.task_id
                WHERE t.project_id = ?
                GROUP BY t.id
                ORDER BY t.start_date, t.id
            ");
            $stmt->execute([$projectId]);
            $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Convert dependencies to array and add additional metadata
            foreach ($tasks as &$task) {
                $task['dependencies'] = $task['dependencies'] ? 
                    array_map('intval', explode(',', $task['dependencies'])) : 
                    [];
                $task['is_overdue'] = $this->isTaskOverdue($task);
                $task['days_remaining'] = $this->getDaysRemaining($task);
            }
            
            return [
                'success' => true,
                'data' => $tasks
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve tasks: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get specific task with detailed information including dependencies
     */
    public function getTaskById($taskId)
    {
        if (!$taskId) {
            return [
                'success' => false,
                'message' => 'Task ID is required'
            ];
        }
        
        try {
            $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute([$taskId]);
            $task = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$task) {
                return [
                    'success' => false,
                    'message' => 'Task not found'
                ];
            }
            
            // Get detailed dependency information
            $task['dependencies'] = $this->getTaskDependencies($taskId);
            $task['dependent_tasks'] = $this->getDependentTasks($taskId);
            $task['is_overdue'] = $this->isTaskOverdue($task);
            $task['days_remaining'] = $this->getDaysRemaining($task);
            
            return [
                'success' => true,
                'data' => $task
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve task: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get tasks filtered by status
     */
    public function getTasksByStatus($projectId, $status)
    {
        if (!$projectId || !$status) {
            return [
                'success' => false,
                'message' => 'Project ID and status are required'
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
            $stmt = $this->db->prepare("
                SELECT * FROM tasks 
                WHERE project_id = ? AND status = ?
                ORDER BY priority DESC, start_date
            ");
            $stmt->execute([$projectId, $status]);
            $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Add metadata to each task
            foreach ($tasks as &$task) {
                $task['is_overdue'] = $this->isTaskOverdue($task);
                $task['days_remaining'] = $this->getDaysRemaining($task);
            }
            
            return [
                'success' => true,
                'data' => $tasks
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve tasks by status: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get tasks filtered by priority
     */
    public function getTasksByPriority($projectId, $priority)
    {
        if (!$projectId || !$priority) {
            return [
                'success' => false,
                'message' => 'Project ID and priority are required'
            ];
        }
        
        $validPriorities = ['low', 'medium', 'high', 'critical'];
        if (!in_array($priority, $validPriorities)) {
            return [
                'success' => false,
                'message' => 'Invalid priority. Valid priorities: ' . implode(', ', $validPriorities)
            ];
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM tasks 
                WHERE project_id = ? AND priority = ?
                ORDER BY start_date
            ");
            $stmt->execute([$projectId, $priority]);
            $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Add metadata to each task
            foreach ($tasks as &$task) {
                $task['is_overdue'] = $this->isTaskOverdue($task);
                $task['days_remaining'] = $this->getDaysRemaining($task);
            }
            
            return [
                'success' => true,
                'data' => $tasks
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve tasks by priority: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get task dependencies with detailed information
     */
    private function getTaskDependencies($taskId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    td.depends_on_task_id,
                    t.name as depends_on_task_name,
                    t.status as depends_on_task_status,
                    t.progress as depends_on_task_progress,
                    td.dependency_type
                FROM task_dependencies td
                JOIN tasks t ON td.depends_on_task_id = t.id
                WHERE td.task_id = ?
            ");
            $stmt->execute([$taskId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get tasks that depend on this task
     */
    private function getDependentTasks($taskId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    t.id,
                    t.name,
                    t.status,
                    t.progress,
                    td.dependency_type
                FROM task_dependencies td
                JOIN tasks t ON td.task_id = t.id
                WHERE td.depends_on_task_id = ?
            ");
            $stmt->execute([$taskId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Check if task is overdue
     */
    private function isTaskOverdue($task)
    {
        if ($task['status'] === 'completed') {
            return false;
        }
        
        $endDate = strtotime($task['end_date']);
        $today = strtotime(date('Y-m-d'));
        
        return $endDate < $today;
    }
    
    /**
     * Calculate days remaining for task
     */
    private function getDaysRemaining($task)
    {
        if ($task['status'] === 'completed') {
            return 0;
        }
        
        $endDate = strtotime($task['end_date']);
        $today = strtotime(date('Y-m-d'));
        
        return max(0, ($endDate - $today) / 86400);
    }
}