<?php
/**
 * Universal Model - 모든 테이블을 처리하는 범용 모델
 */

namespace App\Models;

use CodeIgniter\Model;

class TemplateModel extends Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $skipValidation = true;
    
    /**
     * 테이블 설정
     */
    public function setTable($tableName)
    {
        $this->table = $tableName;
        return $this;
    }
    
    /**
     * 전체 데이터 조회
     */
    public function getAll()
    {
        return $this->findAll();
    }
    
    /**
     * ID로 조회
     */
    public function getById($id)
    {
        return $this->find($id);
    }
    
    /**
     * 조건 검색
     */
    public function search($where = [])
    {
        $builder = $this->builder();
        foreach ($where as $field => $value) {
            $builder->where($field, $value);
        }
        return $builder->get()->getResultArray();
    }
    
    /**
     * 데이터 생성
     */
    public function create($data)
    {
        return $this->insert($data);
    }
    
    /**
     * 데이터 수정
     */
    public function updateById($id, $data)
    {
        return $this->update($id, $data);
    }
    
    /**
     * 데이터 삭제
     */
    public function deleteById($id)
    {
        return $this->delete($id);
    }
    
    /**
     * 테이블별 모델 인스턴스 생성
     */
    public static function table($tableName)
    {
        $model = new self();
        $model->setTable($tableName);
        return $model;
    }
}