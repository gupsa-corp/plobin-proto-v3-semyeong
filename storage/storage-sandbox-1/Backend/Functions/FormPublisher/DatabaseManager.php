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
        // 정확한 절대 경로 생성
        // __FILE__ = .../Backend/Functions/FormPublisher/DatabaseManager.php
        // 4단계 위로 올라가서 db 폴더로 이동
        $currentFile = __FILE__;  // DatabaseManager.php 파일 경로
        $formPublisherDir = dirname($currentFile);  // FormPublisher 폴더
        $functionsDir = dirname($formPublisherDir);  // Functions 폴더
        $backendDir = dirname($functionsDir);  // Backend 폴더
        $sandboxDir = dirname($backendDir);  // storage-sandbox-1 폴더

        $this->dbPath = $sandboxDir . 'storage/storage-sandbox-1/Backend/Databases/Release.sqlite';

        // 디버그용 경로 확인
        if (!file_exists($this->dbPath)) {
            throw new Exception("Database file not found at: {$this->dbPath}");
        }

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
        $sandboxDir = dirname(dirname(dirname(__FILE__)));  // Backend/Functions/FormPublisher에서 3단계 위로
        $migrationFile = $sandboxDir . '/Backend/Migrations/create_sandbox_forms_table.sql';

        if (file_exists($migrationFile)) {
            $sql = file_get_contents($migrationFile);
            $this->pdo->exec($sql);
        } else {
            // 마이그레이션 파일이 없으면 직접 테이블 생성
            $sql = "
            CREATE TABLE IF NOT EXISTS sandbox_forms (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                form_json TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE INDEX IF NOT EXISTS idx_sandbox_forms_title ON sandbox_forms(title);
            CREATE INDEX IF NOT EXISTS idx_sandbox_forms_created_at ON sandbox_forms(created_at);
            ";
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
