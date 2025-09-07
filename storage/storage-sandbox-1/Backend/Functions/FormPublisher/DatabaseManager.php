<?php
/**
 * DatabaseManager - SQLite 데이터베이스 연결 및 쿼리 관리
 */

class DatabaseManager
{
    private $pdo;
    private $dbPath;
    
    public function __construct()
    {
        $this->dbPath = __DIR__ . '/../../db/ai.sqlite';
        $this->connect();
        $this->createTablesIfNotExist();
    }
    
    /**
     * SQLite 연결
     */
    private function connect()
    {
        try {
            $this->pdo = new PDO('sqlite:' . $this->dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * 테이블 생성 (없는 경우)
     */
    private function createTablesIfNotExist()
    {
        $migrationFile = __DIR__ . '/../../Migrations/create_sandbox_forms_table.sql';
        if (file_exists($migrationFile)) {
            $sql = file_get_contents($migrationFile);
            $this->pdo->exec($sql);
        }
    }
    
    /**
     * SELECT 쿼리 실행
     */
    public function select($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception('Select query failed: ' . $e->getMessage());
        }
    }
    
    /**
     * 단일 레코드 조회
     */
    public function selectOne($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception('Select one query failed: ' . $e->getMessage());
        }
    }
    
    /**
     * INSERT 쿼리 실행
     */
    public function insert($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Insert query failed: ' . $e->getMessage());
        }
    }
    
    /**
     * UPDATE 쿼리 실행
     */
    public function update($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Update query failed: ' . $e->getMessage());
        }
    }
    
    /**
     * DELETE 쿼리 실행
     */
    public function delete($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Delete query failed: ' . $e->getMessage());
        }
    }
    
    /**
     * 트랜잭션 시작
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * 커밋
     */
    public function commit()
    {
        return $this->pdo->commit();
    }
    
    /**
     * 롤백
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }
}