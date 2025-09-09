<?php
namespace App\Functions\GanttChart;
#
require_once 'ProjectsApiHandler.php';
require_once 'TasksApiHandler.php';
require_once 'GanttDataApiHandler.php';
require_once 'TaskProgressApiHandler.php';
require_once 'ApiInfoHandler.php';

class GanttChart
{
    private $projectsHandler;
    private $tasksHandler;
    private $ganttDataHandler;
    private $taskProgressHandler;
    private $apiInfoHandler;
    
    public function __construct()
    {
        $this->projectsHandler = new ProjectsApiHandler();
        $this->tasksHandler = new TasksApiHandler();
        $this->ganttDataHandler = new GanttDataApiHandler();
        $this->taskProgressHandler = new TaskProgressApiHandler();
        $this->apiInfoHandler = new ApiInfoHandler();
    }
    
    public function __invoke($params)
    {
        $action = $params['action'] ?? 'get_projects';
        
        switch ($action) {
            // Project-related actions
            case 'get_projects':
                return $this->projectsHandler->getAllProjects();
                
            case 'get_project':
                return $this->projectsHandler->getProjectById($params['project_id'] ?? null);
                
            case 'get_project_overview':
                return $this->projectsHandler->getProjectOverview($params['project_id'] ?? null);
                
            // Task-related actions
            case 'get_tasks':
                return $this->tasksHandler->getProjectTasks($params['project_id'] ?? null);
                
            case 'get_task':
                return $this->tasksHandler->getTaskById($params['task_id'] ?? null);
                
            case 'get_tasks_by_status':
                return $this->tasksHandler->getTasksByStatus($params['project_id'] ?? null, $params['status'] ?? null);
                
            case 'get_tasks_by_priority':
                return $this->tasksHandler->getTasksByPriority($params['project_id'] ?? null, $params['priority'] ?? null);
                
            // Gantt data actions
            case 'get_gantt_data':
                return $this->ganttDataHandler->getCompleteGanttData($params['project_id'] ?? null);
                
            case 'get_optimized_gantt_data':
                return $this->ganttDataHandler->getOptimizedGanttData($params['project_id'] ?? null);
                
            case 'get_gantt_critical_path':
                return $this->ganttDataHandler->getGanttDataWithCriticalPath($params['project_id'] ?? null);
                
            // Task progress actions
            case 'update_task_progress':
                return $this->taskProgressHandler->updateTaskProgress($params['task_id'] ?? null, $params['progress'] ?? null);
                
            case 'batch_update_progress':
                return $this->taskProgressHandler->batchUpdateTaskProgress($params['updates'] ?? []);
                
            case 'update_task_status':
                return $this->taskProgressHandler->updateTaskStatus($params['task_id'] ?? null, $params['status'] ?? null);
                
            case 'get_task_progress_history':
                return $this->taskProgressHandler->getTaskProgressHistory($params['task_id'] ?? null);
                
            // API Information actions
            case 'info':
                return $this->apiInfoHandler->getApiInfo();
                
            case 'functions':
                return $this->apiInfoHandler->getAllFunctions();
                
            default:
                return [
                    'success' => false, 
                    'message' => 'Unknown action. Available actions: get_projects, get_project, get_project_overview, get_tasks, get_task, get_tasks_by_status, get_tasks_by_priority, get_gantt_data, get_optimized_gantt_data, get_gantt_critical_path, update_task_progress, batch_update_progress, update_task_status, get_task_progress_history, info, functions'
                ];
        }
    }
}