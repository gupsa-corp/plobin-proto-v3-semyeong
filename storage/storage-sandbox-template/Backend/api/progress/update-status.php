<?php
/**
 * @OA\Post(
 *     path="/api/progress/update-status",
 *     tags={"Task Progress"},
 *     summary="Update task status",
 *     description="Update task status directly with automatic progress adjustment",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="task_id", type="integer", example=1, description="Task ID to update"),
 *             @OA\Property(
 *                 property="status", 
 *                 type="string", 
 *                 enum={"pending", "in_progress", "completed", "cancelled", "on_hold"}, 
 *                 example="completed", 
 *                 description="New task status"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task status updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid parameters",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

require_once '../../Functions/GanttChart/release/TaskProgressApiHandler.php';

use App\Functions\GanttChart\TaskProgressApiHandler;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Only POST requests are supported.'
    ]);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON in request body'
    ]);
    exit;
}

$taskId = $input['task_id'] ?? null;
$status = $input['status'] ?? null;

if (!$taskId || !is_numeric($taskId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Task ID is required and must be a valid number'
    ]);
    exit;
}

if (!$status) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Status is required'
    ]);
    exit;
}

$validStatuses = ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold'];
if (!in_array($status, $validStatuses)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid status. Valid statuses: ' . implode(', ', $validStatuses)
    ]);
    exit;
}

try {
    $handler = new TaskProgressApiHandler();
    $result = $handler->updateTaskStatus((int)$taskId, $status);
    
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