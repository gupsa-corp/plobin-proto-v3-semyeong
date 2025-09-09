<?php
/**
 * @OA\Post(
 *     path="/api/progress/batch-update",
 *     tags={"Task Progress"},
 *     summary="Batch update task progress",
 *     description="Update multiple tasks progress in batch operation",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="updates",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="task_id", type="integer", example=1),
 *                     @OA\Property(property="progress", type="integer", minimum=0, maximum=100, example=50)
 *                 ),
 *                 description="Array of task updates"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Batch update completed",
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

$updates = $input['updates'] ?? null;

if (!$updates || !is_array($updates) || empty($updates)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Updates array is required and must not be empty'
    ]);
    exit;
}

// Validate each update
foreach ($updates as $index => $update) {
    if (!isset($update['task_id']) || !is_numeric($update['task_id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "Invalid task_id in update at index {$index}"
        ]);
        exit;
    }
    
    if (!isset($update['progress']) || !is_numeric($update['progress'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "Invalid progress in update at index {$index}"
        ]);
        exit;
    }
}

try {
    $handler = new TaskProgressApiHandler();
    $result = $handler->batchUpdateTaskProgress($updates);
    
    http_response_code(200);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error: ' . $e->getMessage()
    ]);
}
?>