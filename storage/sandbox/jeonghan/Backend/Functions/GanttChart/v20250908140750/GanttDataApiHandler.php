<?php
/**
 * @OA\Tag(
 *     name="Gantt Data",
 *     description="Gantt chart data and visualization operations including timeline calculation, critical path analysis, and resource allocation"
 * )
 */

namespace App\Functions\GanttChart;

require_once 'DatabaseConnection.php';
require_once 'ProjectsApiHandler.php';
require_once 'TasksApiHandler.php';

/**
 * Gantt Data API Handler
 * 
 * Handles Gantt chart data operations including complete data retrieval,
 * optimized data for performance, critical path analysis, and resource allocation.
 */
class GanttDataApiHandler
{
    private $db;
    private $projectsHandler;
    private $tasksHandler;
    
    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
        $this->projectsHandler = new ProjectsApiHandler();
        $this->tasksHandler = new TasksApiHandler();
    }
    
    /**
     * Get complete Gantt chart data for a project
     */
    public function getCompleteGanttData($projectId)
    {
        if (!$projectId) {
            return [
                'success' => false,
                'message' => 'Project ID is required'
            ];
        }
        
        try {
            // Get project information
            $projectResult = $this->projectsHandler->getProjectById($projectId);
            if (!$projectResult['success']) {
                return $projectResult;
            }
            
            // Get tasks with dependencies
            $tasksResult = $this->tasksHandler->getProjectTasks($projectId);
            if (!$tasksResult['success']) {
                return $tasksResult;
            }
            
            $project = $projectResult['data'];
            $tasks = $tasksResult['data'];
            
            // Generate formatted Gantt data
            $ganttData = $this->formatGanttChartData($project, $tasks);
            
            return [
                'success' => true,
                'data' => $ganttData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve Gantt data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get optimized Gantt data for visualization (lighter payload)
     */
    public function getOptimizedGanttData($projectId)
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
                    id, name, start_date, end_date, duration, 
                    progress, status, priority, parent_task_id
                FROM tasks 
                WHERE project_id = ?
                ORDER BY start_date, id
            ");
            $stmt->execute([$projectId]);
            $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Get basic project info
            $stmt = $this->db->prepare("
                SELECT id, name, start_date, end_date, status 
                FROM projects 
                WHERE id = ?
            ");
            $stmt->execute([$projectId]);
            $project = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$project) {
                return [
                    'success' => false,
                    'message' => 'Project not found'
                ];
            }
            
            $ganttData = [
                'project' => $project,
                'tasks' => $tasks,
                'timeline' => $this->calculateProjectTimeline($project, $tasks)
            ];
            
            return [
                'success' => true,
                'data' => $ganttData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve optimized Gantt data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get Gantt data with critical path analysis
     */
    public function getGanttDataWithCriticalPath($projectId)
    {
        if (!$projectId) {
            return [
                'success' => false,
                'message' => 'Project ID is required'
            ];
        }
        
        try {
            $ganttResult = $this->getCompleteGanttData($projectId);
            if (!$ganttResult['success']) {
                return $ganttResult;
            }
            
            $ganttData = $ganttResult['data'];
            
            // Calculate critical path
            $criticalPath = $this->calculateCriticalPath($ganttData['tasks']);
            $ganttData['critical_path'] = $criticalPath;
            
            // Mark critical tasks
            foreach ($ganttData['tasks'] as &$task) {
                $task['is_critical'] = in_array($task['id'], $criticalPath);
            }
            
            return [
                'success' => true,
                'data' => $ganttData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve Gantt data with critical path: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Format complete Gantt chart data
     */
    private function formatGanttChartData($project, $tasks)
    {
        $timeline = $this->calculateProjectTimeline($project, $tasks);
        
        return [
            'project' => [
                'id' => $project['id'],
                'name' => $project['name'],
                'description' => $project['description'],
                'start_date' => $project['start_date'],
                'end_date' => $project['end_date'],
                'actual_start_date' => $timeline['actual_start_date'],
                'actual_end_date' => $timeline['actual_end_date'],
                'status' => $project['status'],
                'task_statistics' => $project['task_statistics'],
                'completion_percentage' => $this->calculateProjectCompletionPercentage($tasks)
            ],
            'tasks' => array_map(function($task) {
                return [
                    'id' => $task['id'],
                    'name' => $task['name'],
                    'description' => $task['description'],
                    'start_date' => $task['start_date'],
                    'end_date' => $task['end_date'],
                    'duration' => $task['duration'],
                    'progress' => $task['progress'],
                    'status' => $task['status'],
                    'priority' => $task['priority'],
                    'assigned_to' => $task['assigned_to'],
                    'parent_task_id' => $task['parent_task_id'],
                    'dependencies' => $task['dependencies'],
                    'is_overdue' => $task['is_overdue'],
                    'days_remaining' => $task['days_remaining']
                ];
            }, $tasks),
            'timeline' => $timeline,
            'milestones' => $this->getProjectMilestones($tasks),
            'resource_allocation' => $this->getResourceAllocation($tasks)
        ];
    }
    
    /**
     * Calculate project timeline information
     */
    private function calculateProjectTimeline($project, $tasks)
    {
        $projectStart = $project['start_date'];
        $projectEnd = $project['end_date'];
        
        if (empty($tasks)) {
            $actualStart = $projectStart;
            $actualEnd = $projectEnd;
        } else {
            $actualStart = min(array_column($tasks, 'start_date'));
            $actualEnd = max(array_column($tasks, 'end_date'));
        }
        
        $timelineStart = min($projectStart, $actualStart);
        $timelineEnd = max($projectEnd ?: $actualEnd, $actualEnd);
        
        return [
            'project_start_date' => $projectStart,
            'project_end_date' => $projectEnd,
            'actual_start_date' => $actualStart,
            'actual_end_date' => $actualEnd,
            'timeline_start_date' => $timelineStart,
            'timeline_end_date' => $timelineEnd,
            'total_days' => (strtotime($timelineEnd) - strtotime($timelineStart)) / 86400,
            'working_days' => $this->calculateWorkingDays($timelineStart, $timelineEnd)
        ];
    }
    
    /**
     * Calculate working days between two dates (excluding weekends)
     */
    private function calculateWorkingDays($startDate, $endDate)
    {
        $start = strtotime($startDate);
        $end = strtotime($endDate);
        $workingDays = 0;
        
        while ($start <= $end) {
            $dayOfWeek = date('N', $start);
            if ($dayOfWeek < 6) { // Monday = 1, Sunday = 7
                $workingDays++;
            }
            $start = strtotime('+1 day', $start);
        }
        
        return $workingDays;
    }
    
    /**
     * Calculate project completion percentage
     */
    private function calculateProjectCompletionPercentage($tasks)
    {
        if (empty($tasks)) {
            return 0;
        }
        
        $totalProgress = array_sum(array_column($tasks, 'progress'));
        return round($totalProgress / count($tasks), 2);
    }
    
    /**
     * Get project milestones (tasks with 0 duration)
     */
    private function getProjectMilestones($tasks)
    {
        return array_filter($tasks, function($task) {
            return $task['duration'] == 0 || $task['priority'] === 'critical';
        });
    }
    
    /**
     * Get resource allocation information
     */
    private function getResourceAllocation($tasks)
    {
        $resources = [];
        
        foreach ($tasks as $task) {
            if ($task['assigned_to']) {
                if (!isset($resources[$task['assigned_to']])) {
                    $resources[$task['assigned_to']] = [
                        'assigned_to' => $task['assigned_to'],
                        'task_count' => 0,
                        'total_duration' => 0,
                        'completed_tasks' => 0,
                        'average_progress' => 0
                    ];
                }
                
                $resources[$task['assigned_to']]['task_count']++;
                $resources[$task['assigned_to']]['total_duration'] += $task['duration'];
                if ($task['status'] === 'completed') {
                    $resources[$task['assigned_to']]['completed_tasks']++;
                }
            }
        }
        
        // Calculate average progress for each resource
        foreach ($resources as &$resource) {
            $assignedTasks = array_filter($tasks, function($task) use ($resource) {
                return $task['assigned_to'] === $resource['assigned_to'];
            });
            
            if (!empty($assignedTasks)) {
                $totalProgress = array_sum(array_column($assignedTasks, 'progress'));
                $resource['average_progress'] = round($totalProgress / count($assignedTasks), 2);
            }
        }
        
        return array_values($resources);
    }
    
    /**
     * Calculate critical path (simplified implementation)
     */
    private function calculateCriticalPath($tasks)
    {
        // This is a simplified critical path calculation
        // A full implementation would require more complex graph algorithms
        
        $criticalTasks = array_filter($tasks, function($task) {
            return $task['priority'] === 'high' || 
                   $task['is_overdue'] || 
                   !empty($task['dependencies']);
        });
        
        return array_column($criticalTasks, 'id');
    }
}