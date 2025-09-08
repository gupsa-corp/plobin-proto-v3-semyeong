<?php
/**
 * @OA\Get(
 *     path="/api/tasks/by-status",
 *     tags={"Tasks"},
 *     summary="Get tasks filtered by status",
 *     description="Get tasks for a project filtered by specific status",
 *     @OA\Parameter(
 *         name="project_id",
 *         in="query",
 *         required=true,
 *         description="Project ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         required=true,
 *         description="Task status to filter by",
 *         @OA\Schema(
 *             type="string",
 *             enum={"pending", "in_progress", "completed", "cancelled", "on_hold"},
 *             example="pending"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Filtered tasks retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid parameters",
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
$status = $_GET['status'] ?? null;

if (!$projectId || !is_numeric($projectId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Project ID is required and must be a valid number'
    ]);
    exit;
}

if (!$status) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Status parameter is required'
    ]);
    exit;
}

try {
    $handler = new TasksApiHandler();
    $result = $handler->getTasksByStatus((int)$projectId, $status);
    
    http_response_code($result['success'] ? 200 : 400);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error: ' . $e->getMessage()
    ]);
}
?>