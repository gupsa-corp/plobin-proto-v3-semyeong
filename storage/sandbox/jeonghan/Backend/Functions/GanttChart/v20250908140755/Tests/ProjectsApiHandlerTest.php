<?php
require_once '../ProjectsApiHandler.php';

use App\Functions\GanttChart\ProjectsApiHandler;

class ProjectsApiHandlerTest
{
    private $handler;
    
    public function __construct()
    {
        $this->handler = new ProjectsApiHandler();
    }
    
    public function runAllTests()
    {
        echo "=== Running ProjectsApiHandler Tests ===\n";
        
        $this->testGetAllProjects();
        $this->testGetProjectById();
        $this->testGetProjectOverview();
        $this->testGetProjectByIdWithInvalidId();
        
        echo "=== ProjectsApiHandler Tests Completed ===\n\n";
    }
    
    private function testGetAllProjects()
    {
        echo "Testing getAllProjects()...\n";
        
        $result = $this->handler->getAllProjects();
        
        if ($result['success']) {
            echo "✅ SUCCESS: Retrieved projects successfully\n";
            echo "   Found " . count($result['data']) . " projects\n";
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testGetProjectById()
    {
        echo "Testing getProjectById() with valid ID...\n";
        
        // First get all projects to get a valid ID
        $allProjects = $this->handler->getAllProjects();
        if ($allProjects['success'] && !empty($allProjects['data'])) {
            $projectId = $allProjects['data'][0]['id'];
            
            $result = $this->handler->getProjectById($projectId);
            
            if ($result['success']) {
                echo "✅ SUCCESS: Retrieved project with ID {$projectId} successfully\n";
                echo "   Project: " . $result['data']['name'] . "\n";
                if (isset($result['data']['task_statistics'])) {
                    echo "   Task Statistics included\n";
                }
            } else {
                echo "❌ FAILED: " . $result['message'] . "\n";
            }
        } else {
            echo "⚠️  SKIPPED: No projects found for testing\n";
        }
        echo "\n";
    }
    
    private function testGetProjectOverview()
    {
        echo "Testing getProjectOverview()...\n";
        
        // First get all projects to get a valid ID
        $allProjects = $this->handler->getAllProjects();
        if ($allProjects['success'] && !empty($allProjects['data'])) {
            $projectId = $allProjects['data'][0]['id'];
            
            $result = $this->handler->getProjectOverview($projectId);
            
            if ($result['success']) {
                echo "✅ SUCCESS: Retrieved project overview for ID {$projectId} successfully\n";
                echo "   Project: " . $result['data']['name'] . "\n";
                if (isset($result['data']['timeline_info'])) {
                    echo "   Timeline information included\n";
                }
            } else {
                echo "❌ FAILED: " . $result['message'] . "\n";
            }
        } else {
            echo "⚠️  SKIPPED: No projects found for testing\n";
        }
        echo "\n";
    }
    
    private function testGetProjectByIdWithInvalidId()
    {
        echo "Testing getProjectById() with invalid ID...\n";
        
        $result = $this->handler->getProjectById(99999);
        
        if (!$result['success'] && $result['message'] === 'Project not found') {
            echo "✅ SUCCESS: Properly handled invalid project ID\n";
        } else {
            echo "❌ FAILED: Should have returned 'Project not found' error\n";
        }
        echo "\n";
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $test = new ProjectsApiHandlerTest();
    $test->runAllTests();
}