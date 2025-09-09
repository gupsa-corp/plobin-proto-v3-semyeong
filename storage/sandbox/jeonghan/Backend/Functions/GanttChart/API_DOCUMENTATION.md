# GanttChart API Documentation

## Overview

The GanttChart API provides comprehensive project and task management functionality with support for Gantt chart visualization, task progress tracking, and project analytics.

**Version:** 1.0.0  
**Base URL:** `/api/GanttChart`  
**Last Updated:** 2025-09-02

## Table of Contents

1. [API Information](#api-information)
2. [Projects API](#projects-api)
3. [Tasks API](#tasks-api)
4. [Gantt Data API](#gantt-data-api)
5. [Task Progress API](#task-progress-api)
6. [Common Response Structure](#common-response-structure)
7. [Error Codes](#error-codes)
8. [Usage Examples](#usage-examples)

## API Information

### Get API Information

**Endpoint:** `GET /api/GanttChart?action=info`  
**Description:** Returns comprehensive information about all available API endpoints

**Response:**
```json
{
  "success": true,
  "data": {
    "version": "1.0.0",
    "description": "GanttChart API - Comprehensive project and task management system",
    "total_endpoints": 13,
    "categories": {...},
    "endpoints": {...}
  }
}
```

### Get Functions List

**Endpoint:** `GET /api/GanttChart?action=functions`  
**Description:** Returns a simplified list of all available API functions

**Response:**
```json
{
  "success": true,
  "data": {
    "total_functions": 13,
    "functions": [...]
  }
}
```

---

## Projects API

### Get All Projects

**Endpoint:** `GET /api/GanttChart?action=get_projects`  
**Handler:** `ProjectsApiHandler::getAllProjects`  
**Description:** Retrieves all projects with basic information ordered by creation date

**Parameters:** None

**Example Request:**
```json
{"action": "get_projects"}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Sample Project",
      "description": "Project description",
      "start_date": "2024-01-01",
      "end_date": "2024-12-31",
      "status": "active",
      "created_at": "2024-01-01 10:00:00"
    }
  ]
}
```

### Get Specific Project

**Endpoint:** `GET /api/GanttChart?action=get_project`  
**Handler:** `ProjectsApiHandler::getProjectById`  
**Description:** Retrieves a specific project with detailed statistics including task information

**Parameters:**
- `project_id` (integer, required): Project ID to retrieve

**Example Request:**
```json
{"action": "get_project", "project_id": 1}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Sample Project",
    "description": "Project description",
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "status": "active",
    "task_statistics": {
      "total_tasks": 10,
      "completed_tasks": 5,
      "in_progress_tasks": 3,
      "pending_tasks": 2,
      "average_progress": 65.5,
      "high_priority_tasks": 2,
      "medium_priority_tasks": 5,
      "low_priority_tasks": 3
    }
  }
}
```

### Get Project Overview

**Endpoint:** `GET /api/GanttChart?action=get_project_overview`  
**Handler:** `ProjectsApiHandler::getProjectOverview`  
**Description:** Retrieves comprehensive project overview including timeline information and statistics

**Parameters:**
- `project_id` (integer, required): Project ID to get overview for

**Example Request:**
```json
{"action": "get_project_overview", "project_id": 1}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Sample Project",
    "description": "Project description",
    "timeline_info": {
      "earliest_task_start": "2024-01-01",
      "latest_task_end": "2024-12-31",
      "total_tasks": 10,
      "average_duration": 15.5
    },
    "task_statistics": {...}
  }
}
```

---

## Tasks API

### Get Project Tasks

**Endpoint:** `GET /api/GanttChart?action=get_tasks`  
**Handler:** `TasksApiHandler::getProjectTasks`  
**Description:** Get all tasks for a specific project

**Parameters:**
- `project_id` (integer, required): Project ID to get tasks for

**Example Request:**
```json
{"action": "get_tasks", "project_id": 1}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Task Name",
      "description": "Task description",
      "start_date": "2024-01-01",
      "end_date": "2024-01-15",
      "progress": 75,
      "status": "in_progress",
      "priority": "high",
      "assigned_to": "John Doe",
      "dependencies": [2, 3],
      "is_overdue": false,
      "days_remaining": 5
    }
  ]
}
```

### Get Specific Task

**Endpoint:** `GET /api/GanttChart?action=get_task`  
**Handler:** `TasksApiHandler::getTaskById`  
**Description:** Get specific task with detailed information

**Parameters:**
- `task_id` (integer, required): Task ID to retrieve

**Example Request:**
```json
{"action": "get_task", "task_id": 1}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Task Name",
    "description": "Task description",
    "dependencies": [
      {
        "depends_on_task_id": 2,
        "depends_on_task_name": "Prerequisite Task",
        "depends_on_task_status": "completed",
        "depends_on_task_progress": 100,
        "dependency_type": "finish_to_start"
      }
    ],
    "dependent_tasks": [
      {
        "id": 3,
        "name": "Dependent Task",
        "status": "pending",
        "progress": 0,
        "dependency_type": "finish_to_start"
      }
    ],
    "is_overdue": false,
    "days_remaining": 5
  }
}
```

### Get Tasks by Status

**Endpoint:** `GET /api/GanttChart?action=get_tasks_by_status`  
**Handler:** `TasksApiHandler::getTasksByStatus`  
**Description:** Get tasks filtered by status

**Parameters:**
- `project_id` (integer, required): Project ID
- `status` (string, required): Task status (pending, in_progress, completed, cancelled, on_hold)

**Example Request:**
```json
{"action": "get_tasks_by_status", "project_id": 1, "status": "pending"}
```

### Get Tasks by Priority

**Endpoint:** `GET /api/GanttChart?action=get_tasks_by_priority`  
**Handler:** `TasksApiHandler::getTasksByPriority`  
**Description:** Get tasks filtered by priority

**Parameters:**
- `project_id` (integer, required): Project ID
- `priority` (string, required): Task priority (low, medium, high, critical)

**Example Request:**
```json
{"action": "get_tasks_by_priority", "project_id": 1, "priority": "high"}
```

---

## Gantt Data API

### Get Complete Gantt Data

**Endpoint:** `GET /api/GanttChart?action=get_gantt_data`  
**Handler:** `GanttDataApiHandler::getCompleteGanttData`  
**Description:** Get complete Gantt chart data for a project

**Parameters:**
- `project_id` (integer, required): Project ID for Gantt chart

**Example Request:**
```json
{"action": "get_gantt_data", "project_id": 1}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "project": {
      "id": 1,
      "name": "Sample Project",
      "start_date": "2024-01-01",
      "end_date": "2024-12-31",
      "completion_percentage": 65.5
    },
    "tasks": [...],
    "timeline": {
      "timeline_start_date": "2024-01-01",
      "timeline_end_date": "2024-12-31",
      "total_days": 365,
      "working_days": 260
    },
    "milestones": [...],
    "resource_allocation": [
      {
        "assigned_to": "John Doe",
        "task_count": 5,
        "total_duration": 75,
        "completed_tasks": 3,
        "average_progress": 70.5
      }
    ]
  }
}
```

### Get Optimized Gantt Data

**Endpoint:** `GET /api/GanttChart?action=get_optimized_gantt_data`  
**Handler:** `GanttDataApiHandler::getOptimizedGanttData`  
**Description:** Get optimized Gantt chart data (lighter payload)

**Parameters:**
- `project_id` (integer, required): Project ID for optimized Gantt chart

**Example Request:**
```json
{"action": "get_optimized_gantt_data", "project_id": 1}
```

### Get Gantt Critical Path

**Endpoint:** `GET /api/GanttChart?action=get_gantt_critical_path`  
**Handler:** `GanttDataApiHandler::getGanttDataWithCriticalPath`  
**Description:** Get Gantt data with critical path analysis

**Parameters:**
- `project_id` (integer, required): Project ID for critical path analysis

**Example Request:**
```json
{"action": "get_gantt_critical_path", "project_id": 1}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "project": {...},
    "tasks": [
      {
        "id": 1,
        "name": "Task Name",
        "is_critical": true,
        ...
      }
    ],
    "critical_path": [1, 3, 5, 7]
  }
}
```

---

## Task Progress API

### Update Task Progress

**Endpoint:** `POST /api/GanttChart?action=update_task_progress`  
**Handler:** `TaskProgressApiHandler::updateTaskProgress`  
**Description:** Update task progress with automatic status adjustment

**Parameters:**
- `task_id` (integer, required): Task ID to update
- `progress` (integer, required): Progress percentage (0-100)

**Example Request:**
```json
{"action": "update_task_progress", "task_id": 1, "progress": 50}
```

**Response:**
```json
{
  "success": true,
  "message": "Task progress updated successfully",
  "data": {
    "task_id": 1,
    "previous_progress": 25,
    "new_progress": 50,
    "previous_status": "pending",
    "new_status": "in_progress",
    "updated_at": "2024-01-01 14:30:00"
  }
}
```

### Batch Update Progress

**Endpoint:** `POST /api/GanttChart?action=batch_update_progress`  
**Handler:** `TaskProgressApiHandler::batchUpdateTaskProgress`  
**Description:** Update multiple tasks progress in batch

**Parameters:**
- `updates` (array, required): Array of task updates [{"task_id": int, "progress": int}]

**Example Request:**
```json
{
  "action": "batch_update_progress",
  "updates": [
    {"task_id": 1, "progress": 50},
    {"task_id": 2, "progress": 75}
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Batch update completed. Success: 2, Errors: 0",
  "data": {
    "total_processed": 2,
    "success_count": 2,
    "error_count": 0,
    "detailed_results": [...]
  }
}
```

### Update Task Status

**Endpoint:** `POST /api/GanttChart?action=update_task_status`  
**Handler:** `TaskProgressApiHandler::updateTaskStatus`  
**Description:** Update task status directly

**Parameters:**
- `task_id` (integer, required): Task ID to update
- `status` (string, required): New task status (pending, in_progress, completed, cancelled, on_hold)

**Example Request:**
```json
{"action": "update_task_status", "task_id": 1, "status": "completed"}
```

**Response:**
```json
{
  "success": true,
  "message": "Task status updated successfully",
  "data": {
    "task_id": 1,
    "previous_status": "in_progress",
    "new_status": "completed",
    "previous_progress": 75,
    "new_progress": 100
  }
}
```

### Get Task Progress History

**Endpoint:** `GET /api/GanttChart?action=get_task_progress_history`  
**Handler:** `TaskProgressApiHandler::getTaskProgressHistory`  
**Description:** Get task progress history

**Parameters:**
- `task_id` (integer, required): Task ID to get history for

**Example Request:**
```json
{"action": "get_task_progress_history", "task_id": 1}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "task_id": 1,
      "old_progress": 25,
      "new_progress": 50,
      "old_status": "pending",
      "new_status": "in_progress",
      "created_at": "2024-01-01 14:30:00"
    }
  ]
}
```

---

## Common Response Structure

All API responses follow this standard structure:

```json
{
  "success": boolean,     // Operation success status
  "message": string,      // Error message or success description (optional)
  "data": mixed          // Response data (when success=true)
}
```

**Success Response Example:**
```json
{
  "success": true,
  "data": {...}
}
```

**Error Response Example:**
```json
{
  "success": false,
  "message": "Project not found"
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| `MISSING_PARAMETER` | Required parameter not provided |
| `INVALID_PARAMETER` | Parameter value is invalid |
| `NOT_FOUND` | Requested resource not found |
| `DATABASE_ERROR` | Database operation failed |
| `VALIDATION_ERROR` | Input validation failed |

---

## Usage Examples

### Basic Project Management Workflow

```javascript
// 1. Get all projects
const projects = await fetch('/api/GanttChart?action=get_projects');

// 2. Get specific project with statistics
const project = await fetch('/api/GanttChart?action=get_project&project_id=1');

// 3. Get project tasks
const tasks = await fetch('/api/GanttChart?action=get_tasks&project_id=1');

// 4. Update task progress
const updateResult = await fetch('/api/GanttChart', {
  method: 'POST',
  body: JSON.stringify({
    action: 'update_task_progress',
    task_id: 1,
    progress: 75
  })
});
```

### Gantt Chart Visualization

```javascript
// Get complete Gantt chart data
const ganttData = await fetch('/api/GanttChart?action=get_gantt_data&project_id=1');

// For performance-critical scenarios, use optimized data
const optimizedData = await fetch('/api/GanttChart?action=get_optimized_gantt_data&project_id=1');

// For critical path analysis
const criticalPathData = await fetch('/api/GanttChart?action=get_gantt_critical_path&project_id=1');
```

### Batch Operations

```javascript
// Update multiple tasks at once
const batchUpdate = await fetch('/api/GanttChart', {
  method: 'POST',
  body: JSON.stringify({
    action: 'batch_update_progress',
    updates: [
      {task_id: 1, progress: 50},
      {task_id: 2, progress: 75},
      {task_id: 3, progress: 100}
    ]
  })
});
```

### Task Filtering

```javascript
// Get tasks by status
const pendingTasks = await fetch('/api/GanttChart?action=get_tasks_by_status&project_id=1&status=pending');

// Get high priority tasks
const highPriorityTasks = await fetch('/api/GanttChart?action=get_tasks_by_priority&project_id=1&priority=high');
```

---

## Authentication & Security

**Authentication:** Currently no authentication required for API access  
**Rate Limiting:** No rate limiting currently implemented  
**CORS:** Configure as needed for your application

---

## Support & Contact

For API support, bug reports, or feature requests, please contact the development team or create an issue in the project repository.

**Version History:**
- v1.0.0: Initial release with comprehensive project and task management features