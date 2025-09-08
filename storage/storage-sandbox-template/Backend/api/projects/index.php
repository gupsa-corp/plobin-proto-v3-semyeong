<?php
/**
 * @OA\Get(
 *     path="/api/projects",
 *     tags={"Projects"},
 *     summary="Get all projects",
 *     description="Retrieves all projects with basic information ordered by creation date",
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

try {
    $handler = new ProjectsApiHandler();
    $result = $handler->getAllProjects();
    
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