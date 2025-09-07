<?php
/**
 * @OA\Get(
 *     path="/api/progress/history",
 *     tags={"Task Progress"},
 *     summary="Get task progress history",
 *     description="Get complete progress change history for a specific task",
 *     @OA\Parameter(
 *         name="task_id",
 *         in="query",
 *         required=true,
 *         description="Task ID to get history for",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task progress history retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing task ID",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

require_once '../../Functions/GanttChart/release/TaskProgressApiHandler.php';

use App\Functions\GanttChart\TaskProgressApiHandler;

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

$taskId = $_GET['task_id'] ?? null;

if (!$taskId || !is_numeric($taskId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Task ID is required and must be a valid number'
    ]);
    exit;
}

try {
    $handler = new TaskProgressApiHandler();
    $result = $handler->getTaskProgressHistory((int)$taskId);
    
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