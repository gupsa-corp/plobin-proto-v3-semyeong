<?php
/**
 * Simple OpenAPI Spec Generator for GanttChart API
 */

require_once 'vendor/autoload.php';

// Create the OpenAPI specification manually
$swagger = [
    'openapi' => '3.0.0',
    'info' => [
        'title' => 'GanttChart API',
        'version' => '1.0.0',
        'description' => 'Comprehensive project and task management system with Gantt chart visualization, task progress tracking, and project analytics.',
        'contact' => [
            'name' => 'API Support',
            'email' => 'support@example.com'
        ]
    ],
    'servers' => [
        [
            'url' => 'http://localhost:9004/projects/gogo/Backend/api',
            'description' => 'Local development server'
        ],
        [
            'url' => '/api',
            'description' => 'Production server'
        ]
    ],
    'tags' => [
        ['name' => 'API Information', 'description' => 'API information and documentation endpoints'],
        ['name' => 'Projects', 'description' => 'Project management operations'],
        ['name' => 'Tasks', 'description' => 'Task management operations'],
        ['name' => 'Gantt Data', 'description' => 'Gantt chart data operations'],
        ['name' => 'Task Progress', 'description' => 'Task progress tracking operations']
    ],
    'paths' => [
        '/GanttChart' => [
            'get' => [
                'tags' => ['API Information'],
                'summary' => 'API endpoints',
                'description' => 'Multiple endpoints based on action parameter',
                'parameters' => [
                    [
                        'name' => 'action',
                        'in' => 'query',
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                            'enum' => [
                                'info', 'functions',
                                'get_projects', 'get_project', 'get_project_overview',
                                'get_tasks', 'get_task', 'get_tasks_by_status', 'get_tasks_by_priority',
                                'get_gantt_data', 'get_optimized_gantt_data', 'get_gantt_critical_path'
                            ]
                        ],
                        'description' => 'API action to perform'
                    ],
                    [
                        'name' => 'project_id',
                        'in' => 'query',
                        'required' => false,
                        'schema' => ['type' => 'integer'],
                        'description' => 'Project ID (required for project/task specific actions)'
                    ],
                    [
                        'name' => 'task_id',
                        'in' => 'query',
                        'required' => false,
                        'schema' => ['type' => 'integer'],
                        'description' => 'Task ID (required for task specific actions)'
                    ],
                    [
                        'name' => 'status',
                        'in' => 'query',
                        'required' => false,
                        'schema' => [
                            'type' => 'string',
                            'enum' => ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold']
                        ],
                        'description' => 'Task status filter'
                    ],
                    [
                        'name' => 'priority',
                        'in' => 'query',
                        'required' => false,
                        'schema' => [
                            'type' => 'string',
                            'enum' => ['low', 'medium', 'high', 'critical']
                        ],
                        'description' => 'Task priority filter'
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Successful response',
                        'content' => [
                            'application/json' => [
                                'schema' => ['$ref' => '#/components/schemas/ApiResponse']
                            ]
                        ]
                    ],
                    '400' => [
                        'description' => 'Bad request - Invalid parameters',
                        'content' => [
                            'application/json' => [
                                'schema' => ['$ref' => '#/components/schemas/ErrorResponse']
                            ]
                        ]
                    ],
                    '404' => [
                        'description' => 'Resource not found',
                        'content' => [
                            'application/json' => [
                                'schema' => ['$ref' => '#/components/schemas/ErrorResponse']
                            ]
                        ]
                    ],
                    '500' => [
                        'description' => 'Internal server error',
                        'content' => [
                            'application/json' => [
                                'schema' => ['$ref' => '#/components/schemas/ErrorResponse']
                            ]
                        ]
                    ]
                ]
            ],
            'post' => [
                'tags' => ['Task Progress'],
                'summary' => 'Task progress operations',
                'description' => 'Update task progress, batch updates, or status changes',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'action' => [
                                        'type' => 'string',
                                        'enum' => ['update_task_progress', 'batch_update_progress', 'update_task_status'],
                                        'description' => 'Progress action to perform'
                                    ],
                                    'task_id' => [
                                        'type' => 'integer',
                                        'description' => 'Task ID (for single task updates)'
                                    ],
                                    'progress' => [
                                        'type' => 'integer',
                                        'minimum' => 0,
                                        'maximum' => 100,
                                        'description' => 'Progress percentage'
                                    ],
                                    'status' => [
                                        'type' => 'string',
                                        'enum' => ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold'],
                                        'description' => 'Task status'
                                    ],
                                    'updates' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'task_id' => ['type' => 'integer'],
                                                'progress' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 100]
                                            ]
                                        ],
                                        'description' => 'Array of task updates (for batch operations)'
                                    ]
                                ],
                                'required' => ['action']
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Progress updated successfully',
                        'content' => [
                            'application/json' => [
                                'schema' => ['$ref' => '#/components/schemas/ApiResponse']
                            ]
                        ]
                    ],
                    '400' => [
                        'description' => 'Invalid parameters',
                        'content' => [
                            'application/json' => [
                                'schema' => ['$ref' => '#/components/schemas/ErrorResponse']
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'components' => [
        'schemas' => [
            'ApiResponse' => [
                'type' => 'object',
                'properties' => [
                    'success' => ['type' => 'boolean', 'example' => true, 'description' => 'Operation success status'],
                    'message' => ['type' => 'string', 'example' => 'Operation completed successfully', 'description' => 'Success or error message'],
                    'data' => ['description' => 'Response data when success=true']
                ],
                'required' => ['success']
            ],
            'ErrorResponse' => [
                'type' => 'object',
                'properties' => [
                    'success' => ['type' => 'boolean', 'example' => false, 'description' => 'Operation success status'],
                    'message' => ['type' => 'string', 'example' => 'Error message description', 'description' => 'Error description']
                ],
                'required' => ['success', 'message']
            ],
            'Project' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer', 'example' => 1, 'description' => 'Project ID'],
                    'name' => ['type' => 'string', 'example' => 'Sample Project', 'description' => 'Project name'],
                    'description' => ['type' => 'string', 'example' => 'Project description', 'description' => 'Project description'],
                    'start_date' => ['type' => 'string', 'format' => 'date', 'example' => '2024-01-01', 'description' => 'Project start date'],
                    'end_date' => ['type' => 'string', 'format' => 'date', 'example' => '2024-12-31', 'description' => 'Project end date'],
                    'status' => ['type' => 'string', 'example' => 'active', 'description' => 'Project status'],
                    'created_at' => ['type' => 'string', 'format' => 'datetime', 'description' => 'Creation timestamp'],
                    'task_statistics' => [
                        'type' => 'object',
                        'properties' => [
                            'total_tasks' => ['type' => 'integer', 'example' => 10],
                            'completed_tasks' => ['type' => 'integer', 'example' => 5],
                            'in_progress_tasks' => ['type' => 'integer', 'example' => 3],
                            'pending_tasks' => ['type' => 'integer', 'example' => 2],
                            'average_progress' => ['type' => 'number', 'format' => 'float', 'example' => 65.5]
                        ]
                    ]
                ]
            ],
            'Task' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer', 'example' => 1, 'description' => 'Task ID'],
                    'name' => ['type' => 'string', 'example' => 'Task Name', 'description' => 'Task name'],
                    'description' => ['type' => 'string', 'example' => 'Task description', 'description' => 'Task description'],
                    'start_date' => ['type' => 'string', 'format' => 'date', 'example' => '2024-01-01', 'description' => 'Task start date'],
                    'end_date' => ['type' => 'string', 'format' => 'date', 'example' => '2024-01-15', 'description' => 'Task end date'],
                    'duration' => ['type' => 'integer', 'example' => 14, 'description' => 'Task duration in days'],
                    'progress' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 100, 'example' => 75, 'description' => 'Task progress percentage'],
                    'status' => [
                        'type' => 'string', 
                        'enum' => ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold'], 
                        'example' => 'in_progress', 
                        'description' => 'Task status'
                    ],
                    'priority' => [
                        'type' => 'string', 
                        'enum' => ['low', 'medium', 'high', 'critical'], 
                        'example' => 'high', 
                        'description' => 'Task priority'
                    ],
                    'assigned_to' => ['type' => 'string', 'example' => 'John Doe', 'description' => 'Assigned team member'],
                    'dependencies' => ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [2, 3], 'description' => 'Task dependency IDs'],
                    'is_overdue' => ['type' => 'boolean', 'example' => false, 'description' => 'Whether task is overdue'],
                    'days_remaining' => ['type' => 'number', 'format' => 'float', 'example' => 5.5, 'description' => 'Days remaining to completion']
                ]
            ]
        ]
    ]
];

// Generate files
$jsonFile = __DIR__ . '/Functions/GanttChart/swagger.json';
$yamlFile = __DIR__ . '/Functions/GanttChart/swagger.yaml';

// Write JSON
file_put_contents($jsonFile, json_encode($swagger, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Write YAML (simple format)
$yamlContent = "# GanttChart API OpenAPI 3.0 Specification\n";
$yamlContent .= "# Generated automatically - Use swagger.json for complete specification\n\n";
$yamlContent .= "openapi: 3.0.0\n";
$yamlContent .= "info:\n";
$yamlContent .= "  title: GanttChart API\n";
$yamlContent .= "  version: 1.0.0\n";
$yamlContent .= "  description: 'Comprehensive project and task management system'\n";
file_put_contents($yamlFile, $yamlContent);

echo "🚀 OpenAPI specification generated successfully!\n";
echo "✅ JSON: {$jsonFile}\n";
echo "✅ YAML: {$yamlFile}\n";

$pathCount = count($swagger['paths']);
$tagCount = count($swagger['tags']);
$schemaCount = count($swagger['components']['schemas']);

echo "\n📊 API Statistics:\n";
echo "   • Paths: {$pathCount}\n";
echo "   • Tags: {$tagCount}\n";
echo "   • Schemas: {$schemaCount}\n";
echo "   • OpenAPI Version: {$swagger['openapi']}\n";
echo "   • API Version: {$swagger['info']['version']}\n";

echo "\n🔗 Access your API documentation:\n";
echo "   • JSON: http://localhost:9004/projects/gogo/Backend/Functions/GanttChart/swagger.json\n";
echo "   • YAML: http://localhost:9004/projects/gogo/Backend/Functions/GanttChart/swagger.yaml\n";
echo "   • Swagger UI: Setup Swagger UI to view the documentation\n";

echo "\n🎉 OpenAPI specification generation completed!\n";
?>