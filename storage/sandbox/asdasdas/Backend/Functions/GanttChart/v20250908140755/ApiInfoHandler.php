<?php
/**
 * @OA\Tag(
 *     name="API Information",
 *     description="API information and documentation endpoints"
 * )
 */

namespace App\Functions\GanttChart;

/**
 * API Information Handler
 * 
 * Provides comprehensive information about all available API endpoints,
 * their parameters, responses, and usage examples.
 */
class ApiInfoHandler
{
    /**
     * @OA\Get(
     *     path="/api/GanttChart/info",
     *     tags={"API Information"},
     *     summary="Get comprehensive API information",
     *     description="Returns detailed information about all available API endpoints including parameters, responses, and examples",
     *     @OA\Response(
     *         response=200,
     *         description="API information retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="version", type="string", example="1.0.0"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="base_url", type="string"),
     *                 @OA\Property(
     *                     property="endpoints",
     *                     type="object",
     *                     @OA\Property(property="projects", type="array", @OA\Items(type="object")),
     *                     @OA\Property(property="tasks", type="array", @OA\Items(type="object")),
     *                     @OA\Property(property="gantt_data", type="array", @OA\Items(type="object")),
     *                     @OA\Property(property="task_progress", type="array", @OA\Items(type="object"))
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getApiInfo()
    {
        return [
            'success' => true,
            'data' => [
                'version' => '1.0.0',
                'description' => 'GanttChart API - Comprehensive project and task management system',
                'base_url' => '/api/GanttChart',
                'last_updated' => date('Y-m-d H:i:s'),
                'total_endpoints' => 13,
                'categories' => [
                    'projects' => 'Project management operations',
                    'tasks' => 'Task management and querying',
                    'gantt_data' => 'Gantt chart data and visualization',
                    'task_progress' => 'Task progress tracking and history'
                ],
                'endpoints' => [
                    'projects' => [
                        [
                            'action' => 'get_projects',
                            'method' => 'GET',
                            'description' => 'Get all projects with basic information',
                            'handler' => 'ProjectsApiHandler::getAllProjects',
                            'parameters' => [],
                            'example' => "{'action': 'get_projects'}",
                            'response_type' => 'array of projects'
                        ],
                        [
                            'action' => 'get_project',
                            'method' => 'GET',
                            'description' => 'Get specific project with detailed statistics',
                            'handler' => 'ProjectsApiHandler::getProjectById',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID to retrieve']
                            ],
                            'example' => "{'action': 'get_project', 'project_id': 1}",
                            'response_type' => 'project object with statistics'
                        ],
                        [
                            'action' => 'get_project_overview',
                            'method' => 'GET',
                            'description' => 'Get project overview with timeline information',
                            'handler' => 'ProjectsApiHandler::getProjectOverview',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID to retrieve overview for']
                            ],
                            'example' => "{'action': 'get_project_overview', 'project_id': 1}",
                            'response_type' => 'project object with timeline info'
                        ]
                    ],
                    'tasks' => [
                        [
                            'action' => 'get_tasks',
                            'method' => 'GET',
                            'description' => 'Get all tasks for a specific project',
                            'handler' => 'TasksApiHandler::getProjectTasks',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID to get tasks for']
                            ],
                            'example' => "{'action': 'get_tasks', 'project_id': 1}",
                            'response_type' => 'array of tasks with metadata'
                        ],
                        [
                            'action' => 'get_task',
                            'method' => 'GET',
                            'description' => 'Get specific task with detailed information',
                            'handler' => 'TasksApiHandler::getTaskById',
                            'parameters' => [
                                ['name' => 'task_id', 'type' => 'integer', 'required' => true, 'description' => 'Task ID to retrieve']
                            ],
                            'example' => "{'action': 'get_task', 'task_id': 1}",
                            'response_type' => 'task object with dependencies'
                        ],
                        [
                            'action' => 'get_tasks_by_status',
                            'method' => 'GET',
                            'description' => 'Get tasks filtered by status',
                            'handler' => 'TasksApiHandler::getTasksByStatus',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID'],
                                ['name' => 'status', 'type' => 'string', 'required' => true, 'description' => 'Task status (pending, in_progress, completed, cancelled, on_hold)']
                            ],
                            'example' => "{'action': 'get_tasks_by_status', 'project_id': 1, 'status': 'pending'}",
                            'response_type' => 'array of filtered tasks'
                        ],
                        [
                            'action' => 'get_tasks_by_priority',
                            'method' => 'GET',
                            'description' => 'Get tasks filtered by priority',
                            'handler' => 'TasksApiHandler::getTasksByPriority',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID'],
                                ['name' => 'priority', 'type' => 'string', 'required' => true, 'description' => 'Task priority (low, medium, high, critical)']
                            ],
                            'example' => "{'action': 'get_tasks_by_priority', 'project_id': 1, 'priority': 'high'}",
                            'response_type' => 'array of filtered tasks'
                        ]
                    ],
                    'gantt_data' => [
                        [
                            'action' => 'get_gantt_data',
                            'method' => 'GET',
                            'description' => 'Get complete Gantt chart data for a project',
                            'handler' => 'GanttDataApiHandler::getCompleteGanttData',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID for Gantt chart']
                            ],
                            'example' => "{'action': 'get_gantt_data', 'project_id': 1}",
                            'response_type' => 'complete gantt data with timeline, milestones, resources'
                        ],
                        [
                            'action' => 'get_optimized_gantt_data',
                            'method' => 'GET',
                            'description' => 'Get optimized Gantt chart data (lighter payload)',
                            'handler' => 'GanttDataApiHandler::getOptimizedGanttData',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID for optimized Gantt chart']
                            ],
                            'example' => "{'action': 'get_optimized_gantt_data', 'project_id': 1}",
                            'response_type' => 'optimized gantt data with essential fields only'
                        ],
                        [
                            'action' => 'get_gantt_critical_path',
                            'method' => 'GET',
                            'description' => 'Get Gantt data with critical path analysis',
                            'handler' => 'GanttDataApiHandler::getGanttDataWithCriticalPath',
                            'parameters' => [
                                ['name' => 'project_id', 'type' => 'integer', 'required' => true, 'description' => 'Project ID for critical path analysis']
                            ],
                            'example' => "{'action': 'get_gantt_critical_path', 'project_id': 1}",
                            'response_type' => 'gantt data with critical path information'
                        ]
                    ],
                    'task_progress' => [
                        [
                            'action' => 'update_task_progress',
                            'method' => 'POST',
                            'description' => 'Update task progress with automatic status adjustment',
                            'handler' => 'TaskProgressApiHandler::updateTaskProgress',
                            'parameters' => [
                                ['name' => 'task_id', 'type' => 'integer', 'required' => true, 'description' => 'Task ID to update'],
                                ['name' => 'progress', 'type' => 'integer', 'required' => true, 'description' => 'Progress percentage (0-100)']
                            ],
                            'example' => "{'action': 'update_task_progress', 'task_id': 1, 'progress': 50}",
                            'response_type' => 'update confirmation with new status'
                        ],
                        [
                            'action' => 'batch_update_progress',
                            'method' => 'POST',
                            'description' => 'Update multiple tasks progress in batch',
                            'handler' => 'TaskProgressApiHandler::batchUpdateTaskProgress',
                            'parameters' => [
                                ['name' => 'updates', 'type' => 'array', 'required' => true, 'description' => 'Array of task updates [{task_id: int, progress: int}]']
                            ],
                            'example' => "{'action': 'batch_update_progress', 'updates': [{'task_id': 1, 'progress': 50}, {'task_id': 2, 'progress': 75}]}",
                            'response_type' => 'batch update results with success/error counts'
                        ],
                        [
                            'action' => 'update_task_status',
                            'method' => 'POST',
                            'description' => 'Update task status directly',
                            'handler' => 'TaskProgressApiHandler::updateTaskStatus',
                            'parameters' => [
                                ['name' => 'task_id', 'type' => 'integer', 'required' => true, 'description' => 'Task ID to update'],
                                ['name' => 'status', 'type' => 'string', 'required' => true, 'description' => 'New task status (pending, in_progress, completed, cancelled, on_hold)']
                            ],
                            'example' => "{'action': 'update_task_status', 'task_id': 1, 'status': 'completed'}",
                            'response_type' => 'status update confirmation with adjusted progress'
                        ],
                        [
                            'action' => 'get_task_progress_history',
                            'method' => 'GET',
                            'description' => 'Get task progress history',
                            'handler' => 'TaskProgressApiHandler::getTaskProgressHistory',
                            'parameters' => [
                                ['name' => 'task_id', 'type' => 'integer', 'required' => true, 'description' => 'Task ID to get history for']
                            ],
                            'example' => "{'action': 'get_task_progress_history', 'task_id': 1}",
                            'response_type' => 'array of progress history entries'
                        ]
                    ]
                ],
                'common_response_structure' => [
                    'success' => 'boolean - Operation success status',
                    'message' => 'string - Error message or success description (optional)',
                    'data' => 'mixed - Response data (when success=true)'
                ],
                'error_codes' => [
                    'MISSING_PARAMETER' => 'Required parameter not provided',
                    'INVALID_PARAMETER' => 'Parameter value is invalid',
                    'NOT_FOUND' => 'Requested resource not found',
                    'DATABASE_ERROR' => 'Database operation failed',
                    'VALIDATION_ERROR' => 'Input validation failed'
                ],
                'authentication' => [
                    'required' => false,
                    'description' => 'Currently no authentication required for API access'
                ],
                'rate_limiting' => [
                    'enabled' => false,
                    'description' => 'No rate limiting currently implemented'
                ]
            ]
        ];
    }

    /**
     * @OA\Get(
     *     path="/api/GanttChart/functions",
     *     tags={"API Information"},
     *     summary="Get list of all available functions",
     *     description="Returns a simplified list of all available API functions and their basic information",
     *     @OA\Response(
     *         response=200,
     *         description="Functions list retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_functions", type="integer"),
     *                 @OA\Property(property="functions", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     )
     * )
     */
    public function getAllFunctions()
    {
        $apiInfo = $this->getApiInfo();
        $endpoints = $apiInfo['data']['endpoints'];
        
        $functions = [];
        foreach ($endpoints as $category => $categoryEndpoints) {
            foreach ($categoryEndpoints as $endpoint) {
                $functions[] = [
                    'action' => $endpoint['action'],
                    'category' => $category,
                    'method' => $endpoint['method'],
                    'description' => $endpoint['description'],
                    'handler' => $endpoint['handler'],
                    'required_params' => array_filter($endpoint['parameters'], function($param) {
                        return $param['required'] ?? false;
                    })
                ];
            }
        }

        return [
            'success' => true,
            'data' => [
                'total_functions' => count($functions),
                'functions' => $functions
            ]
        ];
    }
}