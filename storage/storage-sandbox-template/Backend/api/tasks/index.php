<?php
/**
 * @OA\Get(
 *     path="/api/tasks",
 *     tags={"Tasks"},
 *     summary="Get project tasks",
 *     description="Get all tasks for a specific project",
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

require_once '../../Functions/GanttChart/release/TasksApiHandler.php';

use App\Functions\GanttChart\TasksApiHandler;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Only GET requests are supported.'
    ]);
    exit;
}

$projectId = $_GET['project_id'] ?? null;

if (!$projectId || !is_numeric($projectId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Project ID is required and must be a valid number'
    ]);
    exit;
}

try {
    $handler = new TasksApiHandler();
    $result = $handler->getProjectTasks((int)$projectId);
    
    http_response_code($result['success'] ? 200 : 500);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error: ' . $e->getMessage()
    ]);
}
?>