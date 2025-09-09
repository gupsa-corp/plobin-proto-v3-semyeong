# GanttChart API System

## 📁 File Structure

```
GanttChart/
├── release/
│   ├── Function.php                    # Main API entry point
│   ├── DatabaseConnection.php          # Singleton database connection
│   ├── ProjectsApiHandler.php          # Project management operations
│   ├── TasksApiHandler.php            # Task management operations
│   ├── GanttDataApiHandler.php        # Gantt chart data operations
│   ├── TaskProgressApiHandler.php     # Progress tracking operations
│   ├── ApiInfoHandler.php             # API information endpoints
│   └── Tests/
│       ├── RunTests.php               # Main test suite runner
│       ├── ProjectsApiHandlerTest.php # Projects API tests
│       ├── TasksApiHandlerTest.php    # Tasks API tests
│       └── TaskProgressApiHandlerTest.php # Progress API tests
├── API_DOCUMENTATION.md               # Complete API documentation
└── README.md                          # This file
```

## 🚀 Quick Start

### Test the Info Endpoint

```bash
# Get comprehensive API information
curl "http://localhost:9004/projects/gogo/Backend/api/GanttChart/info?action=info"

# Get simplified functions list
curl "http://localhost:9004/projects/gogo/Backend/api/GanttChart/info?action=functions"
```

### Run Tests

```bash
cd release/Tests
php RunTests.php
```

## 📊 API Overview

### Total Endpoints: 15

| Category | Endpoints | Description |
|----------|-----------|-------------|
| **API Information** | 2 | API metadata and documentation |
| **Projects** | 3 | Project management operations |
| **Tasks** | 4 | Task management and querying |
| **Gantt Data** | 3 | Chart visualization data |
| **Task Progress** | 4 | Progress tracking and history |

## 🎯 Core API Handlers

### 1. ProjectsApiHandler.php
**Purpose:** Project management operations  
**OpenAPI Tag:** `Projects`

**Functions:**
- `getAllProjects()` - Get all projects with basic information
- `getProjectById($projectId)` - Get specific project with statistics
- `getProjectOverview($projectId)` - Get comprehensive project overview

**Features:**
- Project statistics calculation
- Timeline information
- Task count and progress aggregation

### 2. TasksApiHandler.php
**Purpose:** Task management and querying  
**OpenAPI Tag:** `Tasks`

**Functions:**
- `getProjectTasks($projectId)` - Get all tasks for a project
- `getTaskById($taskId)` - Get specific task with dependencies
- `getTasksByStatus($projectId, $status)` - Filter tasks by status
- `getTasksByPriority($projectId, $priority)` - Filter tasks by priority

**Features:**
- Dependency relationship mapping
- Task metadata (overdue status, days remaining)
- Status and priority filtering
- Comprehensive task information

### 3. GanttDataApiHandler.php
**Purpose:** Gantt chart data and visualization  
**OpenAPI Tag:** `Gantt Data`

**Functions:**
- `getCompleteGanttData($projectId)` - Full Gantt chart data
- `getOptimizedGanttData($projectId)` - Performance-optimized data
- `getGanttDataWithCriticalPath($projectId)` - Critical path analysis

**Features:**
- Timeline calculation
- Milestone identification
- Resource allocation analysis
- Critical path detection
- Working days calculation

### 4. TaskProgressApiHandler.php
**Purpose:** Task progress tracking and history  
**OpenAPI Tag:** `Task Progress`

**Functions:**
- `updateTaskProgress($taskId, $progress)` - Update task progress
- `batchUpdateTaskProgress($updates)` - Batch progress updates
- `updateTaskStatus($taskId, $status)` - Direct status updates
- `getTaskProgressHistory($taskId)` - Progress change history

**Features:**
- Automatic status adjustment based on progress
- Progress history logging
- Batch operation support
- Dependent task cascade updates

### 5. ApiInfoHandler.php
**Purpose:** API information and documentation  
**OpenAPI Tag:** `API Information`

**Functions:**
- `getApiInfo()` - Comprehensive API information
- `getAllFunctions()` - Simplified functions list

**Features:**
- Complete endpoint documentation
- Parameter specifications
- Response examples
- Error code definitions

## 🔧 Technical Implementation

### Database Connection
- **Pattern:** Singleton pattern for connection management
- **Type:** SQLite with PDO
- **Features:** Automatic error handling, prepared statements

### Error Handling
- **Structure:** Standardized response format
- **Logging:** Exception details with context
- **Validation:** Input parameter validation

### Response Format
```json
{
  "success": boolean,
  "message": string (optional),
  "data": mixed (when success=true)
}
```

## 📋 Available Actions

### API Information
- `info` - Complete API information
- `functions` - Functions list

### Project Operations
- `get_projects` - All projects
- `get_project` - Specific project with statistics
- `get_project_overview` - Project with timeline info

### Task Operations
- `get_tasks` - Project tasks
- `get_task` - Specific task with dependencies
- `get_tasks_by_status` - Filter by status
- `get_tasks_by_priority` - Filter by priority

### Gantt Chart Data
- `get_gantt_data` - Complete Gantt data
- `get_optimized_gantt_data` - Performance optimized
- `get_gantt_critical_path` - Critical path analysis

### Progress Tracking
- `update_task_progress` - Single task progress
- `batch_update_progress` - Multiple tasks
- `update_task_status` - Direct status change
- `get_task_progress_history` - Change history

## 🧪 Testing

All handlers include comprehensive test coverage:

```bash
✅ ProjectsApiHandler: 4 test cases
✅ TasksApiHandler: 5 test cases  
✅ TaskProgressApiHandler: 5 test cases
✅ All handlers: Database integration tests
```

**Test Results:** All tests passing ✅

## 📖 Documentation

- **API_DOCUMENTATION.md** - Complete API reference with examples
- **OpenAPI Annotations** - Embedded in all handler classes
- **Inline Comments** - Method-level documentation
- **README.md** - This overview document

## 🚦 Usage Examples

### Basic Usage
```php
$gantt = new GanttChart();
$result = $gantt(['action' => 'get_projects']);
```

### JavaScript/AJAX
```javascript
fetch('/api/GanttChart?action=info')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Batch Operations
```json
{
  "action": "batch_update_progress",
  "updates": [
    {"task_id": 1, "progress": 50},
    {"task_id": 2, "progress": 75}
  ]
}
```

## 🔍 Key Features

- ✅ **Modular Architecture** - Separated by functionality
- ✅ **OpenAPI Compliant** - Full annotation support
- ✅ **Comprehensive Testing** - All handlers tested
- ✅ **Error Handling** - Standardized error responses
- ✅ **Performance Optimized** - Multiple data retrieval options
- ✅ **Dependency Management** - Task relationship tracking
- ✅ **Progress History** - Complete audit trail
- ✅ **Critical Path Analysis** - Project optimization insights
- ✅ **Resource Allocation** - Team workload analysis

## 🔄 Version History

- **v1.0.0** - Initial release with full API functionality
  - 15 API endpoints across 5 handler classes
  - Complete OpenAPI documentation
  - Comprehensive test coverage
  - Critical path analysis
  - Batch operations support