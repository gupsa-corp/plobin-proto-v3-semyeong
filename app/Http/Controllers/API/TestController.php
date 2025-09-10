<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FunctionMetadataService;
use App\Services\FunctionTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    private FunctionMetadataService $metadataService;
    private FunctionTemplateService $templateService;
    private string $basePath;
    private string $currentStorage;

    public function __construct(
        FunctionMetadataService $metadataService,
        FunctionTemplateService $templateService
    ) {
        $this->metadataService = $metadataService;
        $this->templateService = $templateService;
        $this->currentStorage = Session::get('sandbox_storage', 'template');
        $this->basePath = storage_path("sandbox/storage-sandbox-{$this->currentStorage}");
    }

    /**
     * Get system status for testing
     */
    public function status(): JsonResponse
    {
        try {
            $functions = $this->metadataService->getFunctions();
            $statistics = $this->metadataService->getStatistics();
            $templates = $this->templateService->getTemplates();

            return response()->json([
                'success' => true,
                'data' => [
                    'storage' => $this->currentStorage,
                    'base_path' => $this->basePath,
                    'functions_count' => count($functions),
                    'templates_count' => count($templates),
                    'statistics' => $statistics,
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Execute a function (simulate execution)
     */
    public function executeFunction(Request $request): JsonResponse
    {
        $request->validate([
            'function_name' => 'required|string',
            'parameters' => 'sometimes|array',
            'version' => 'sometimes|string'
        ]);

        $functionName = $request->input('function_name');
        $parameters = $request->input('parameters', []);
        $version = $request->input('version', 'release');

        try {
            // Check if function exists
            $function = $this->metadataService->getFunction($functionName);
            if (!$function) {
                return response()->json([
                    'success' => false,
                    'error' => "Function '{$functionName}' not found"
                ], 404);
            }

            // Check if version exists
            $versions = $function['versions'] ?? ['release'];
            if (!in_array($version, $versions)) {
                return response()->json([
                    'success' => false,
                    'error' => "Version '{$version}' not found for function '{$functionName}'"
                ], 404);
            }

            // Get function file path
            $functionPath = $this->basePath . "/functions/{$functionName}/{$version}/Function.php";

            if (!File::exists($functionPath)) {
                return response()->json([
                    'success' => false,
                    'error' => "Function file not found at: {$functionPath}"
                ], 404);
            }

            // Read function content
            $content = File::get($functionPath);

            // Simulate execution (in real scenario, you'd load and execute the class)
            $executionResult = [
                'function_name' => $functionName,
                'version' => $version,
                'parameters' => $parameters,
                'executed_at' => now()->toISOString(),
                'status' => 'simulated',
                'content_preview' => substr($content, 0, 500) . '...'
            ];

            return response()->json([
                'success' => true,
                'data' => $executionResult
            ]);

        } catch (\Exception $e) {
            Log::error("Function execution error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Read/query files from the sandbox
     */
    public function queryFiles(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'sometimes|string',
            'type' => 'sometimes|in:functions,templates,metadata,all',
            'function_name' => 'sometimes|string',
            'pattern' => 'sometimes|string'
        ]);

        $path = $request->input('path');
        $type = $request->input('type', 'all');
        $functionName = $request->input('function_name');
        $pattern = $request->input('pattern');

        try {
            $results = [];

            switch ($type) {
                case 'functions':
                    $results = $this->queryFunctionFiles($functionName);
                    break;

                case 'templates':
                    $results = $this->queryTemplateFiles();
                    break;

                case 'metadata':
                    $results = $this->queryMetadataFiles();
                    break;

                default:
                    if ($path) {
                        $results = $this->queryPath($path, $pattern);
                    } else {
                        $results = [
                            'functions' => $this->queryFunctionFiles(),
                            'templates' => $this->queryTemplateFiles(),
                            'metadata' => $this->queryMetadataFiles()
                        ];
                    }
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'query' => [
                    'path' => $path,
                    'type' => $type,
                    'function_name' => $functionName,
                    'pattern' => $pattern
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Modify files in the sandbox
     */
    public function modifyFiles(Request $request): JsonResponse
    {
        $request->validate([
            'operation' => 'required|in:create,update,delete,copy,move',
            'target' => 'required|string',
            'content' => 'sometimes|string',
            'source' => 'sometimes|string'
        ]);

        $operation = $request->input('operation');
        $target = $request->input('target');
        $content = $request->input('content');
        $source = $request->input('source');

        try {
            $result = null;

            switch ($operation) {
                case 'create':
                    $result = $this->createFile($target, $content ?? '');
                    break;

                case 'update':
                    $result = $this->updateFile($target, $content ?? '');
                    break;

                case 'delete':
                    $result = $this->deleteFile($target);
                    break;

                case 'copy':
                    if (!$source) {
                        throw new \Exception('Source path required for copy operation');
                    }
                    $result = $this->copyFile($source, $target);
                    break;

                case 'move':
                    if (!$source) {
                        throw new \Exception('Source path required for move operation');
                    }
                    $result = $this->moveFile($source, $target);
                    break;
            }

            return response()->json([
                'success' => true,
                'data' => $result,
                'operation' => [
                    'type' => $operation,
                    'target' => $target,
                    'source' => $source,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("File modification error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed function information
     */
    public function getFunctionInfo(string $functionName): JsonResponse
    {
        try {
            $function = $this->metadataService->getFunction($functionName);

            if (!$function) {
                return response()->json([
                    'success' => false,
                    'error' => "Function '{$functionName}' not found"
                ], 404);
            }

            // Get function files for each version
            $versions = [];
            foreach ($function['versions'] as $version) {
                $functionPath = $this->basePath . "/functions/{$functionName}/{$version}/Function.php";
                $versions[$version] = [
                    'exists' => File::exists($functionPath),
                    'path' => $functionPath,
                    'size' => File::exists($functionPath) ? File::size($functionPath) : 0,
                    'modified' => File::exists($functionPath) ? File::lastModified($functionPath) : null
                ];
            }

            $result = array_merge($function, [
                'function_name' => $functionName,
                'versions_detail' => $versions,
                'dependencies' => $this->metadataService->getFunctionDependencies($functionName),
                'dependents' => $this->metadataService->getFunctionDependents($functionName)
            ]);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a test function using templates
     */
    public function createTestFunction(Request $request): JsonResponse
    {
        $request->validate([
            'function_name' => 'required|string|regex:/^[A-Za-z][A-Za-z0-9_]*$/',
            'template_id' => 'required|string',
            'parameters' => 'sometimes|array',
            'description' => 'sometimes|string'
        ]);

        try {
            $functionName = $request->input('function_name');
            $templateId = $request->input('template_id');
            $parameters = $request->input('parameters', []);
            $description = $request->input('description', 'Test function created via API');

            // Add required parameters
            $parameters['className'] = $functionName;
            $parameters['description'] = $description;

            $result = $this->templateService->createFunction($functionName, $templateId, $parameters);

            return response()->json([
                'success' => $result['success'],
                'data' => $result,
                'message' => $result['message']
            ], $result['success'] ? 201 : 400);

        } catch (\Exception $e) {
            Log::error("Test function creation error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Private helper methods
    private function queryFunctionFiles(?string $functionName = null): array
    {
        $functionsPath = $this->basePath . '/functions';
        $results = [];

        if ($functionName) {
            $functionPath = $functionsPath . '/' . $functionName;
            if (File::exists($functionPath)) {
                $results[$functionName] = $this->getFunctionFileInfo($functionPath, $functionName);
            }
        } else {
            if (File::exists($functionsPath)) {
                $directories = File::directories($functionsPath);
                foreach ($directories as $dir) {
                    $name = basename($dir);
                    $results[$name] = $this->getFunctionFileInfo($dir, $name);
                }
            }
        }

        return $results;
    }

    private function getFunctionFileInfo(string $path, string $functionName): array
    {
        $versions = [];
        $versionDirs = File::directories($path);

        foreach ($versionDirs as $versionDir) {
            $version = basename($versionDir);
            $functionFile = $versionDir . '/Function.php';

            $versions[$version] = [
                'exists' => File::exists($functionFile),
                'path' => $functionFile,
                'size' => File::exists($functionFile) ? File::size($functionFile) : 0,
                'modified' => File::exists($functionFile) ? date('Y-m-d H:i:s', File::lastModified($functionFile)) : null
            ];
        }

        return [
            'path' => $path,
            'versions' => $versions,
            'metadata' => $this->metadataService->getFunction($functionName)
        ];
    }

    private function queryTemplateFiles(): array
    {
        $templatesPath = $this->basePath . '/templates';
        $results = [];

        if (File::exists($templatesPath)) {
            $files = File::files($templatesPath);
            foreach ($files as $file) {
                $results[basename($file)] = [
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime())
                ];
            }
        }

        return $results;
    }

    private function queryMetadataFiles(): array
    {
        $metadataPath = $this->basePath . '/metadata';
        $results = [];

        if (File::exists($metadataPath)) {
            $files = File::files($metadataPath);
            foreach ($files as $file) {
                $results[basename($file)] = [
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    'content_preview' => $file->getExtension() === 'json' ?
                        json_decode(File::get($file->getPathname()), true) : null
                ];
            }
        }

        return $results;
    }

    private function queryPath(string $path, ?string $pattern = null): array
    {
        $fullPath = str_starts_with($path, '/') ? $path : $this->basePath . '/' . $path;
        $results = [];

        if (!File::exists($fullPath)) {
            throw new \Exception("Path does not exist: {$fullPath}");
        }

        if (File::isDirectory($fullPath)) {
            $items = File::allFiles($fullPath);
            foreach ($items as $item) {
                if (!$pattern || fnmatch($pattern, $item->getFilename())) {
                    $results[] = [
                        'path' => $item->getPathname(),
                        'name' => $item->getFilename(),
                        'size' => $item->getSize(),
                        'modified' => date('Y-m-d H:i:s', $item->getMTime())
                    ];
                }
            }
        } else {
            $results = [
                'path' => $fullPath,
                'size' => File::size($fullPath),
                'modified' => date('Y-m-d H:i:s', File::lastModified($fullPath)),
                'content' => File::get($fullPath)
            ];
        }

        return $results;
    }

    private function createFile(string $target, string $content): array
    {
        $fullPath = str_starts_with($target, '/') ? $target : $this->basePath . '/' . $target;
        $directory = dirname($fullPath);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put($fullPath, $content);

        return [
            'path' => $fullPath,
            'size' => strlen($content),
            'created' => now()->toISOString()
        ];
    }

    private function updateFile(string $target, string $content): array
    {
        $fullPath = str_starts_with($target, '/') ? $target : $this->basePath . '/' . $target;

        if (!File::exists($fullPath)) {
            throw new \Exception("File does not exist: {$fullPath}");
        }

        $oldSize = File::size($fullPath);
        File::put($fullPath, $content);

        return [
            'path' => $fullPath,
            'old_size' => $oldSize,
            'new_size' => strlen($content),
            'updated' => now()->toISOString()
        ];
    }

    private function deleteFile(string $target): array
    {
        $fullPath = str_starts_with($target, '/') ? $target : $this->basePath . '/' . $target;

        if (!File::exists($fullPath)) {
            throw new \Exception("File does not exist: {$fullPath}");
        }

        $size = File::size($fullPath);
        File::delete($fullPath);

        return [
            'path' => $fullPath,
            'size' => $size,
            'deleted' => now()->toISOString()
        ];
    }

    private function copyFile(string $source, string $target): array
    {
        $fullSource = str_starts_with($source, '/') ? $source : $this->basePath . '/' . $source;
        $fullTarget = str_starts_with($target, '/') ? $target : $this->basePath . '/' . $target;

        if (!File::exists($fullSource)) {
            throw new \Exception("Source file does not exist: {$fullSource}");
        }

        $directory = dirname($fullTarget);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::copy($fullSource, $fullTarget);

        return [
            'source' => $fullSource,
            'target' => $fullTarget,
            'size' => File::size($fullTarget),
            'copied' => now()->toISOString()
        ];
    }

    private function moveFile(string $source, string $target): array
    {
        $fullSource = str_starts_with($source, '/') ? $source : $this->basePath . '/' . $source;
        $fullTarget = str_starts_with($target, '/') ? $target : $this->basePath . '/' . $target;

        if (!File::exists($fullSource)) {
            throw new \Exception("Source file does not exist: {$fullSource}");
        }

        $directory = dirname($fullTarget);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::move($fullSource, $fullTarget);

        return [
            'source' => $fullSource,
            'target' => $fullTarget,
            'size' => File::size($fullTarget),
            'moved' => now()->toISOString()
        ];
    }
}
