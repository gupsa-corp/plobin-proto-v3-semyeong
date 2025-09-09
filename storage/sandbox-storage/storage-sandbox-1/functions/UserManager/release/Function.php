<?php
namespace App\Functions\UserManager;

require_once 'DatabaseConnection.php';

class UserManager
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
    }

    public function __invoke($params)
    {
        $action = $params['action'] ?? 'list';
        
        switch ($action) {
            
            case 'list':
                return $this->list($params);
            
            case 'create':
                return $this->create($params);
            
            case 'read':
                return $this->read($params);
            
            case 'update':
                return $this->update($params);
            
            case 'delete':
                return $this->delete($params);
            
            case 'search':
                return $this->search($params);
            
            
            default:
                return [
                    'success' => false,
                    'message' => 'Unknown action. Available actions: list, create, read, update, delete, search'
                ];
        }
    }

    
    /**
     *  operation
     */
    private function ($params)
    {
        try {
            // TODO: Implement  logic
            return [
                'success' => true,
                'data' => [],
                'message' => ' operation completed'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error in : ' . $e->getMessage()
            ];
        }
    }

    
}