<?php
/**
 * GanttChart API Handlers Test Suite
 */

// Include all test files
require_once 'ProjectsApiHandlerTest.php';
require_once 'TasksApiHandlerTest.php';
require_once 'TaskProgressApiHandlerTest.php';

echo "========================================\n";
echo "   GanttChart API Handler Tests Suite   \n";
echo "========================================\n\n";

try {
    // Run ProjectsApiHandler tests
    $projectsTest = new ProjectsApiHandlerTest();
    $projectsTest->runAllTests();

    // Run TasksApiHandler tests
    $tasksTest = new TasksApiHandlerTest();
    $tasksTest->runAllTests();

    // Run TaskProgressApiHandler tests
    $progressTest = new TaskProgressApiHandlerTest();
    $progressTest->runAllTests();

    echo "========================================\n";
    echo "      All Tests Completed Successfully  \n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}