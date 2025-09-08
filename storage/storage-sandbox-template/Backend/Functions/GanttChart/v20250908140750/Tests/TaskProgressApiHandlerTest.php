<?php
require_once '../TaskProgressApiHandler.php';

use App\Functions\GanttChart\TaskProgressApiHandler;

class TaskProgressApiHandlerTest
{
    private $handler;
    
    public function __construct()
    {
        $this->handler = new TaskProgressApiHandler();
    }
    
    public function runAllTests()
    {
        echo "=== Running TaskProgressApiHandler Tests ===\n";
        
        $this->testUpdateTaskProgress();
        $this->testUpdateTaskProgressWithInvalidValues();
        $this->testUpdateTaskStatus();
        $this->testBatchUpdateTaskProgress();
        $this->testGetTaskProgressHistory();
        
        echo "=== TaskProgressApiHandler Tests Completed ===\n\n";
    }
    
    private function testUpdateTaskProgress()
    {
        echo "Testing updateTaskProgress()...\n";
        
        // Update task 1 to 50% progress
        $result = $this->handler->updateTaskProgress(1, 50);
        
        if ($result['success']) {
            echo "✅ SUCCESS: Updated task progress successfully\n";
            echo "   Task ID: " . $result['data']['task_id'] . "\n";
            echo "   New Progress: " . $result['data']['new_progress'] . "%\n";
            echo "   New Status: " . $result['data']['new_status'] . "\n";
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testUpdateTaskProgressWithInvalidValues()
    {
        echo "Testing updateTaskProgress() with invalid progress value...\n";
        
        $result = $this->handler->updateTaskProgress(1, 150); // Invalid: > 100
        
        if (!$result['success'] && strpos($result['message'], 'Progress must be between 0 and 100') !== false) {
            echo "✅ SUCCESS: Properly handled invalid progress value\n";
        } else {
            echo "❌ FAILED: Should have returned progress range error\n";
        }
        echo "\n";
    }
    
    private function testUpdateTaskStatus()
    {
        echo "Testing updateTaskStatus()...\n";
        
        $result = $this->handler->updateTaskStatus(1, 'in_progress');
        
        if ($result['success']) {
            echo "✅ SUCCESS: Updated task status successfully\n";
            echo "   Task ID: " . $result['data']['task_id'] . "\n";
            echo "   New Status: " . $result['data']['new_status'] . "\n";
            echo "   Adjusted Progress: " . $result['data']['new_progress'] . "%\n";
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testBatchUpdateTaskProgress()
    {
        echo "Testing batchUpdateTaskProgress()...\n";
        
        $updates = [
            ['task_id' => 1, 'progress' => 75],
            ['task_id' => 2, 'progress' => 25],
            ['task_id' => 999, 'progress' => 50] // Invalid task ID
        ];
        
        $result = $this->handler->batchUpdateTaskProgress($updates);
        
        if ($result['success']) {
            echo "✅ SUCCESS: Batch update completed\n";
            echo "   Total processed: " . $result['data']['total_processed'] . "\n";
            echo "   Success count: " . $result['data']['success_count'] . "\n";
            echo "   Error count: " . $result['data']['error_count'] . "\n";
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testGetTaskProgressHistory()
    {
        echo "Testing getTaskProgressHistory()...\n";
        
        $result = $this->handler->getTaskProgressHistory(1);
        
        if ($result['success']) {
            echo "✅ SUCCESS: Retrieved task progress history\n";
            echo "   Found " . count($result['data']) . " history entries\n";
            if (!empty($result['data'])) {
                $latest = $result['data'][0];
                echo "   Latest entry: " . $latest['old_progress'] . "% → " . $latest['new_progress'] . "%\n";
            }
        } else {
            echo "❌ FAILED: " . $result['message'] . "\n";
        }
        echo "\n";
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $test = new TaskProgressApiHandlerTest();
    $test->runAllTests();
}