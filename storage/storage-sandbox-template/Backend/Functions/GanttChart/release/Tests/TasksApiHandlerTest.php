<?php
require_once '../TasksApiHandler.php';

use App\Functions\GanttChart\TasksApiHandler;

class TasksApiHandlerTest
{
    private $handler;
    
    public function __construct()
    {
        $this->handler = new TasksApiHandler();
    }
    
    public function runAllTests()
    {
        echo "=== Running TasksApiHandler Tests ===\n";
        
        $this->testGetProjectTasks();
        $this->testGetTaskById();
        $this->testGetTasksByStatus();
        $this->testGetTasksByPriority();
        $this->testGetTasksByStatusWithInvalidStatus();
        
        echo "=== TasksApiHandler Tests Completed ===\n\n";
    }
    
    private function testGetProjectTasks()
    {
        echo "Testing getProjectTasks()...\n";
        
        // Use project ID 1 for testing
        $result = $this->handler->getProjectTasks(1);
        
        if ($result['success']) {
            echo "✅ SUCCESS: Retrieved tasks for project 1 successfully\n";
            echo "   Found " . count($result['data']) . " tasks\n";
            if (!empty($result['data'])) {
                echo "   Sample task: " . $result['data'][0]['name'] . "\n";
                if (isset($result['data'][0]['is_overdue'])) {
                    echo "   Task metadata included (is_overdue, days_remaining)\n";
                }
            }
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testGetTaskById()
    {
        echo "Testing getTaskById()...\n";
        
        // First get tasks to get a valid task ID
        $tasks = $this->handler->getProjectTasks(1);
        if ($tasks['success'] && !empty($tasks['data'])) {
            $taskId = $tasks['data'][0]['id'];
            
            $result = $this->handler->getTaskById($taskId);
            
            if ($result['success']) {
                echo "✅ SUCCESS: Retrieved task with ID {$taskId} successfully\n";
                echo "   Task: " . $result['data']['name'] . "\n";
                if (isset($result['data']['dependencies']) || isset($result['data']['dependent_tasks'])) {
                    echo "   Dependency information included\n";
                }
            } else {
                echo "❌ FAILED: " . $result['message'] . "\n";
            }
        } else {
            echo "⚠️  SKIPPED: No tasks found for testing\n";
        }
        echo "\n";
    }
    
    private function testGetTasksByStatus()
    {
        echo "Testing getTasksByStatus() with 'pending' status...\n";
        
        $result = $this->handler->getTasksByStatus(1, 'pending');
        
        if ($result['success']) {
            echo "✅ SUCCESS: Retrieved pending tasks for project 1 successfully\n";
            echo "   Found " . count($result['data']) . " pending tasks\n";
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testGetTasksByPriority()
    {
        echo "Testing getTasksByPriority() with 'high' priority...\n";
        
        $result = $this->handler->getTasksByPriority(1, 'high');
        
        if ($result['success']) {
            echo "✅ SUCCESS: Retrieved high priority tasks for project 1 successfully\n";
            echo "   Found " . count($result['data']) . " high priority tasks\n";
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testGetTasksByStatusWithInvalidStatus()
    {
        echo "Testing getTasksByStatus() with invalid status...\n";
        
        $result = $this->handler->getTasksByStatus(1, 'invalid_status');
        
        if (!$result['success'] && strpos($result['message'], 'Invalid status') !== false) {
            echo "✅ SUCCESS: Properly handled invalid status\n";
        } else {
            echo "❌ FAILED: Should have returned invalid status error\n";
        }
        echo "\n";
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $test = new TasksApiHandlerTest();
    $test->runAllTests();
}