<?php
namespace App\Functions\GanttChart;

class DatabaseConnection
{
    private static $instance = null;
    private $db;
    
    private function __construct()
    {
        $this->db = $this->createConnection();
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->db;
    }
    
    /**
     * Create database connection
     */
    private function createConnection()
    {
        // Functions/GanttChart/release/DatabaseConnection.php에서 Backend 루트로 이동
        $backendRoot = dirname(dirname(dirname(dirname(__FILE__))));
        $dbPath = $backendRoot . '/Databases/Release.sqlite';
        
        if (!file_exists($dbPath)) {
            throw new \Exception('Database file not found: ' . $dbPath);
        }
        
        try {
            $pdo = new \PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage() . ' (Path: ' . $dbPath . ')');
        }
    }
}