<?php
/**
 * OpenAPI Spec Generator for GanttChart API
 * 
 * This script scans all GanttChart API handler files and generates
 * a complete OpenAPI 3.0 specification from the annotations.
 */

require_once 'vendor/autoload.php';

use OpenApi\Generator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="GanttChart API",
 *     description="Comprehensive project and task management system with Gantt chart visualization, task progress tracking, and project analytics.",
 *     version="1.0.0",
 *     contact={
 *         "name": "API Support",
 *         "email": "support@example.com"
 *     }
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:9004/projects/gogo/Backend/api",
 *     description="Local development server"
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="Production server"
 * )
 * 
 * @OA\ExternalDocumentation(
 *     description="Complete API Documentation",
 *     url="/Functions/GanttChart/API_DOCUMENTATION.md"
 * )
 */

// Define the paths to scan for annotations
$scanPaths = [
    __DIR__ . '/Functions/GanttChart/release/openapi_spec.php',
];

// Additional OpenAPI definitions
$additionalAnnotations = [
    /**
     * @OA\Schema(
     *     schema="Project",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Sample Project"),
     *     @OA\Property(property="description", type="string", example="Project description"),
     *     @OA\Property(property="start_date", type="string", format="date", example="2024-01-01"),
     *     @OA\Property(property="end_date", type="string", format="date", example="2024-12-31"),
     *     @OA\Property(property="status", type="string", example="active"),
     *     @OA\Property(property="created_at", type="string", format="datetime")
     * )
     */
    
    /**
     * @OA\Schema(
     *     schema="Task",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Task Name"),
     *     @OA\Property(property="description", type="string", example="Task description"),
     *     @OA\Property(property="start_date", type="string", format="date", example="2024-01-01"),
     *     @OA\Property(property="end_date", type="string", format="date", example="2024-01-15"),
     *     @OA\Property(property="duration", type="integer", example=14),
     *     @OA\Property(property="progress", type="integer", minimum=0, maximum=100, example=75),
     *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "cancelled", "on_hold"}, example="in_progress"),
     *     @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "critical"}, example="high"),
     *     @OA\Property(property="assigned_to", type="string", example="John Doe"),
     *     @OA\Property(property="parent_task_id", type="integer", nullable=true, example=null),
     *     @OA\Property(property="dependencies", type="array", @OA\Items(type="integer"), example={2, 3}),
     *     @OA\Property(property="is_overdue", type="boolean", example=false),
     *     @OA\Property(property="days_remaining", type="number", format="float", example=5.5)
     * )
     */
    
    /**
     * @OA\Schema(
     *     schema="ApiResponse",
     *     type="object",
     *     @OA\Property(property="success", type="boolean", example=true),
     *     @OA\Property(property="message", type="string", example="Operation completed successfully"),
     *     @OA\Property(property="data", type="object", description="Response data when success=true")
     * )
     */
    
    /**
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     type="object",
     *     @OA\Property(property="success", type="boolean", example=false),
     *     @OA\Property(property="message", type="string", example="Error message description")
     * )
     */
];

echo "🚀 Generating OpenAPI specification for GanttChart API...\n";

try {
    // Generate OpenAPI specification
    $openapi = Generator::scan($scanPaths);
    
    // Write to JSON file
    $jsonFile = __DIR__ . '/Functions/GanttChart/swagger.json';
    file_put_contents($jsonFile, $openapi->toJson());
    
    // Write to YAML file (optional)
    $yamlFile = __DIR__ . '/Functions/GanttChart/swagger.yaml';
    file_put_contents($yamlFile, $openapi->toYaml());
    
    echo "✅ OpenAPI JSON specification generated: {$jsonFile}\n";
    echo "✅ OpenAPI YAML specification generated: {$yamlFile}\n";
    
    // Display some statistics
    $spec = json_decode($openapi->toJson(), true);
    $pathCount = count($spec['paths'] ?? []);
    $tagCount = count($spec['tags'] ?? []);
    
    echo "\n📊 API Statistics:\n";
    echo "   • Endpoints: {$pathCount}\n";
    echo "   • Tags: {$tagCount}\n";
    echo "   • OpenAPI Version: " . ($spec['openapi'] ?? 'N/A') . "\n";
    echo "   • API Version: " . ($spec['info']['version'] ?? 'N/A') . "\n";
    
    echo "\n🔗 Access your API documentation:\n";
    echo "   • JSON: http://localhost:9004/projects/gogo/Backend/Functions/GanttChart/swagger.json\n";
    echo "   • YAML: http://localhost:9004/projects/gogo/Backend/Functions/GanttChart/swagger.yaml\n";
    
} catch (Exception $e) {
    echo "❌ Error generating OpenAPI specification: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n🎉 OpenAPI specification generation completed!\n";
?>