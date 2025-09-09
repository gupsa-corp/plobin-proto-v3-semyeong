<?php
/**
 * @OA\Get(
 *     path="/api/info",
 *     tags={"API Information"},
 *     summary="Get comprehensive API information",
 *     description="Returns detailed information about all available API endpoints including parameters, responses, and examples",
 *     @OA\Response(
 *         response=200,
 *         description="API information retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
 *     )
 * )
 */

require_once '../../Functions/GanttChart/release/ApiInfoHandler.php';

use App\Functions\GanttChart\ApiInfoHandler;

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
    $handler = new ApiInfoHandler();
    $result = $handler->getApiInfo();
    
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