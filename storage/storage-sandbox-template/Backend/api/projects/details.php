<?php
/**
 * @OA\Get(
 *     path="/api/projects/details",
 *     tags={"Projects"},
 *     summary="Get specific project with statistics",
 *     description="Retrieves a specific project with detailed statistics including task information",
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         description="Project ID to retrieve",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Project retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Project not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

require_once '../../Functions/GanttChart/release/ProjectsApiHandler.php';

use App\Functions\GanttChart\ProjectsApiHandler;

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

$projectId = $_GET['id'] ?? null;

if (!$projectId || !is_numeric($projectId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Project ID is required and must be a valid number'
    ]);
    exit;
}

try {
    $handler = new ProjectsApiHandler();
    $result = $handler->getProjectById((int)$projectId);
    
    http_response_code($result['success'] ? 200 : 404);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error: ' . $e->getMessage()
    ]);
}
?>