<?php
/**
 * @OA\Post(
 *     path="/api/sandbox/storage-sandbox-1/callbacks/ai-server",
 *     tags={"AI Server"},
 *     summary="AI Server Callback Endpoint",
 *     description="Handle callbacks from AI server for sandbox operations",
 *     @OA\RequestBody(
 *         required=true,
 *         description="AI server callback payload",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="action",
 *                 type="string",
 *                 description="Action type",
 *                 example="process_complete"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 description="Callback data"
 *             ),
 *             @OA\Property(
 *                 property="timestamp",
 *                 type="string",
 *                 format="date-time",
 *                 description="Callback timestamp"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Callback processed successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         type="object",
 *                         @OA\Property(
 *                             property="processed",
 *                             type="boolean",
 *                             example=true
 *                         ),
 *                         @OA\Property(
 *                             property="callback_id",
 *                             type="string",
 *                             example="cb_12345"
 *                         )
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid callback data",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=405,
 *         description="Method not allowed",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

require_once '../../Functions/AIServer/CallbackHandler.php';

use App\Functions\AIServer\CallbackHandler;

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Only POST requests are supported.'
    ]);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON payload'
    ]);
    exit;
}

// Validate required fields
if (!isset($data['action']) || empty($data['action'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Action field is required'
    ]);
    exit;
}

try {
    $handler = new CallbackHandler();
    $result = $handler->processCallback($data);
    
    http_response_code($result['success'] ? 200 : 400);
    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    error_log("AI Server Callback Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error',
        'error_id' => uniqid('err_')
    ]);
}
?>