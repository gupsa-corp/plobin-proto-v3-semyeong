<?php
/**
 * @OA\Get(
 *     path="/api/gantt/critical-path",
 *     tags={"Gantt Data"},
 *     summary="Get Gantt data with critical path analysis",
 *     description="Get Gantt chart data with critical path analysis and task priority identification",
 *     @OA\Parameter(
 *         name="project_id",
 *         in="query",
 *         required=true,
 *         description="Project ID for critical path analysis",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Gantt data with critical path retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing project ID",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

require_once '../../Functions/GanttChart/release/GanttDataApiHandler.php';

use App\Functions\GanttChart\GanttDataApiHandler;

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
    $handler = new GanttDataApiHandler();
    $result = $handler->getGanttDataWithCriticalPath((int)$projectId);
    
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