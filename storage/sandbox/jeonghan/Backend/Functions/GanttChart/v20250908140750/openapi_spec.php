<?php
/**
 * OpenAPI Specification for GanttChart API
 */

/**
 * @OA\Info(
 *     title="GanttChart API",
 *     version="1.0.0",
 *     description="Comprehensive project and task management system with Gantt chart visualization, task progress tracking, and project analytics.",
 *     @OA\Contact(
 *         name="API Support",
 *         email="support@example.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:9004/projects/gogo/Backend/api",
 *     description="Local development server"
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="Production server"
 * )
 * 
 * @OA\Tag(
 *     name="API Information",
 *     description="API information and documentation endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Projects",
 *     description="Project management operations"
 * )
 * 
 * @OA\Tag(
 *     name="Tasks", 
 *     description="Task management operations"
 * )
 * 
 * @OA\Tag(
 *     name="Gantt Data",
 *     description="Gantt chart data operations"
 * )
 * 
 * @OA\Tag(
 *     name="Task Progress",
 *     description="Task progress tracking operations"
 * )
 */

/**
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true, description="Operation success status"),
 *     @OA\Property(property="message", type="string", example="Operation completed successfully", description="Success or error message"),
 *     @OA\Property(property="data", description="Response data when success=true")
 * )
 */

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false, description="Operation success status"),
 *     @OA\Property(property="message", type="string", example="Error message description", description="Error description")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Project",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1, description="Project ID"),
 *     @OA\Property(property="name", type="string", example="Sample Project", description="Project name"),
 *     @OA\Property(property="description", type="string", example="Project description", description="Project description"),
 *     @OA\Property(property="start_date", type="string", format="date", example="2024-01-01", description="Project start date"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2024-12-31", description="Project end date"),
 *     @OA\Property(property="status", type="string", example="active", description="Project status"),
 *     @OA\Property(property="created_at", type="string", format="datetime", description="Creation timestamp")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1, description="Task ID"),
 *     @OA\Property(property="name", type="string", example="Task Name", description="Task name"),
 *     @OA\Property(property="description", type="string", example="Task description", description="Task description"),
 *     @OA\Property(property="start_date", type="string", format="date", example="2024-01-01", description="Task start date"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2024-01-15", description="Task end date"),
 *     @OA\Property(property="duration", type="integer", example=14, description="Task duration in days"),
 *     @OA\Property(property="progress", type="integer", minimum=0, maximum=100, example=75, description="Task progress percentage"),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "cancelled", "on_hold"}, example="in_progress", description="Task status"),
 *     @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "critical"}, example="high", description="Task priority"),
 *     @OA\Property(property="assigned_to", type="string", example="John Doe", description="Assigned team member"),
 *     @OA\Property(property="dependencies", type="array", @OA\Items(type="integer"), example={2, 3}, description="Task dependency IDs"),
 *     @OA\Property(property="is_overdue", type="boolean", example=false, description="Whether task is overdue"),
 *     @OA\Property(property="days_remaining", type="number", format="float", example=5.5, description="Days remaining to completion")
 * )
 */

/**
 * @OA\Get(
 *     path="/GanttChart",
 *     tags={"API Information"},
 *     summary="Get API information",
 *     description="Returns comprehensive information about all available API endpoints",
 *     @OA\Parameter(
 *         name="action",
 *         in="query",
 *         required=true,
 *         description="API action to perform",
 *         @OA\Schema(type="string", enum={"info"})
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="API information retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/GanttChart",
 *     tags={"API Information"},
 *     summary="Get functions list",
 *     description="Returns a simplified list of all available API functions",
 *     @OA\Parameter(
 *         name="action",
 *         in="query",
 *         required=true,
 *         description="API action to perform",
 *         @OA\Schema(type="string", enum={"functions"})
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Functions list retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/GanttChart",
 *     tags={"Projects"},
 *     summary="Get all projects",
 *     description="Retrieves all projects with basic information ordered by creation date",
 *     @OA\Parameter(
 *         name="action",
 *         in="query",
 *         required=true,
 *         description="API action to perform",
 *         @OA\Schema(type="string", enum={"get_projects"})
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Projects retrieved successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         type="array",
 *                         @OA\Items(ref="#/components/schemas/Project")
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Database error",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/GanttChart",
 *     tags={"Projects"},
 *     summary="Get specific project",
 *     description="Retrieves a specific project with detailed statistics",
 *     @OA\Parameter(
 *         name="action",
 *         in="query",
 *         required=true,
 *         description="API action to perform",
 *         @OA\Schema(type="string", enum={"get_project"})
 *     ),
 *     @OA\Parameter(
 *         name="project_id",
 *         in="query",
 *         required=true,
 *         description="Project ID to retrieve",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Project retrieved successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         allOf={
 *                             @OA\Schema(ref="#/components/schemas/Project"),
 *                             @OA\Schema(
 *                                 @OA\Property(
 *                                     property="task_statistics",
 *                                     type="object",
 *                                     @OA\Property(property="total_tasks", type="integer", example=10),
 *                                     @OA\Property(property="completed_tasks", type="integer", example=5),
 *                                     @OA\Property(property="in_progress_tasks", type="integer", example=3),
 *                                     @OA\Property(property="average_progress", type="number", format="float", example=65.5)
 *                                 )
 *                             )
 *                         }
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Project not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/GanttChart",
 *     tags={"Tasks"},
 *     summary="Get project tasks",
 *     description="Get all tasks for a specific project",
 *     @OA\Parameter(
 *         name="action",
 *         in="query",
 *         required=true,
 *         description="API action to perform",
 *         @OA\Schema(type="string", enum={"get_tasks"})
 *     ),
 *     @OA\Parameter(
 *         name="project_id",
 *         in="query",
 *         required=true,
 *         description="Project ID to get tasks for",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tasks retrieved successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         type="array",
 *                         @OA\Items(ref="#/components/schemas/Task")
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing project ID",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/GanttChart",
 *     tags={"Task Progress"},
 *     summary="Update task progress",
 *     description="Update task progress with automatic status adjustment",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="action", type="string", enum={"update_task_progress"}, example="update_task_progress"),
 *             @OA\Property(property="task_id", type="integer", example=1, description="Task ID to update"),
 *             @OA\Property(property="progress", type="integer", minimum=0, maximum=100, example=50, description="Progress percentage")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task progress updated successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         type="object",
 *                         @OA\Property(property="task_id", type="integer", example=1),
 *                         @OA\Property(property="previous_progress", type="integer", example=25),
 *                         @OA\Property(property="new_progress", type="integer", example=50),
 *                         @OA\Property(property="previous_status", type="string", example="pending"),
 *                         @OA\Property(property="new_status", type="string", example="in_progress"),
 *                         @OA\Property(property="updated_at", type="string", format="datetime")
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid parameters",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

class OpenApiSpec
{
    // This class exists only to hold OpenAPI annotations
}
?>