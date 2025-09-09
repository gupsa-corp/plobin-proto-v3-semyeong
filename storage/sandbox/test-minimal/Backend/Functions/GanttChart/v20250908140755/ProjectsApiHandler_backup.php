<?php
/**
 * @OA\Tag(
 *     name="Projects",
 *     description="Project management operations including retrieval of project information, statistics, and timelines"
 * )
 */

namespace App\Functions\GanttChart;

require_once 'DatabaseConnection.php';

/**
 * Projects API Handler
 * 
 * Handles all project-related API operations including project retrieval,
 * statistics calculation, and timeline management.
 */
class ProjectsApiHandler
{
    private $db;
    
    public function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }
    
    /**
     * @OA\Get(
     *     path="/api/GanttChart?action=get_projects",
     *     tags={"Projects"},
     *     summary="Get all projects",
     *     description="Retrieves all projects with basic information ordered by creation date",
     *     @OA\Response(
     *         response=200,
     *         description="Projects retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Sample Project"),
     *                     @OA\Property(property="description", type="string", example="Project description"),
     *                     @OA\Property(property="start_date", type="string", format="date", example="2024-01-01"),
     *                     @OA\Property(property="end_date", type="string", format="date", example="2024-12-31"),
     *                     @OA\Property(property="status", type="string", example="active"),
     *                     @OA\Property(property="created_at", type="string", format="datetime")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Database error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve projects")
     *         )
     *     )
     * )
     * 
     * Get all projects with basic information
     */
    public function getAllProjects()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM projects ORDER BY created_at DESC");
            $stmt->execute();
            $projects = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'data' => $projects
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve projects: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get specific project with detailed statistics
     */
    public function getProjectById($projectId)
    {
        if (!$projectId) {
            return [
                'success' => false,
                'message' => 'Project ID is required'
            ];
        }
        
        try {
            $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
            $stmt->execute([$projectId]);
            $project = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$project) {
                return [
                    'success' => false,
                    'message' => 'Project not found'
                ];
            }
            
            // Get detailed task statistics
            $project['task_statistics'] = $this->getProjectTaskStatistics($projectId);
            
            return [
                'success' => true,
                'data' => $project
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve project: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get project task statistics
     */
    private function getProjectTaskStatistics($projectId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_tasks,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
                    AVG(progress) as average_progress,
                    COUNT(CASE WHEN priority = 'high' THEN 1 END) as high_priority_tasks,
                    COUNT(CASE WHEN priority = 'medium' THEN 1 END) as medium_priority_tasks,
                    COUNT(CASE WHEN priority = 'low' THEN 1 END) as low_priority_tasks
                FROM tasks 
                WHERE project_id = ?
            ");
            $stmt->execute([$projectId]);
            $stats = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $stats ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get project overview with timeline information
     */
    public function getProjectOverview($projectId)
    {
        if (!$projectId) {
            return [
                'success' => false,
                'message' => 'Project ID is required'
            ];
        }
        
        try {
            $project = $this->getProjectById($projectId);
            if (!$project['success']) {
                return $project;
            }
            
            $projectData = $project['data'];
            
            // Get timeline information
            $timelineInfo = $this->getProjectTimeline($projectId);
            $projectData['timeline_info'] = $timelineInfo;
            
            return [
                'success' => true,
                'data' => $projectData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrieve project overview: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get project timeline information
     */
    private function getProjectTimeline($projectId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    MIN(start_date) as earliest_task_start,
                    MAX(end_date) as latest_task_end,
                    COUNT(*) as total_tasks,
                    AVG(duration) as average_duration
                FROM tasks 
                WHERE project_id = ?
            ");
            $stmt->execute([$projectId]);
            $timeline = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $timeline ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }
}