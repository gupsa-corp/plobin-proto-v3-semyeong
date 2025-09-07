<?php
/**
 * FormManager - 폼 관리 메인 클래스
 */

require_once 'DatabaseManager.php';
require_once 'ValidationHelper.php';
require_once 'FormRenderer.php';

class FormManager
{
    private $db;
    
    public function __construct()
    {
        $this->db = new DatabaseManager();
    }
    
    /**
     * 폼 저장
     */
    public function saveForm($title, $description, $formJson, $id = null)
    {
        try {
            // 입력 데이터 정리
            $title = ValidationHelper::sanitizeInput($title);
            $description = ValidationHelper::sanitizeInput($description);
            
            // JSON 검증
            $jsonValidation = ValidationHelper::validateJson($formJson);
            if (!$jsonValidation['valid']) {
                return ['success' => false, 'error' => 'JSON 오류: ' . $jsonValidation['error']];
            }
            
            // 폼 구조 검증
            $formData = json_decode($formJson, true);
            $structureValidation = ValidationHelper::validateFormStructure($formData);
            if (!$structureValidation['valid']) {
                return ['success' => false, 'error' => '폼 구조 오류: ' . implode(', ', $structureValidation['errors'])];
            }
            
            $currentTime = date('Y-m-d H:i:s');
            
            if ($id) {
                // 업데이트
                $sql = "UPDATE sandbox_forms SET title = ?, description = ?, form_json = ?, updated_at = ? WHERE id = ?";
                $params = [$title, $description, $formJson, $currentTime, $id];
                $affectedRows = $this->db->update($sql, $params);
                
                if ($affectedRows > 0) {
                    return ['success' => true, 'id' => $id, 'message' => '폼이 성공적으로 업데이트되었습니다.'];
                } else {
                    return ['success' => false, 'error' => '폼을 찾을 수 없습니다.'];
                }
            } else {
                // 새로 생성
                $sql = "INSERT INTO sandbox_forms (title, description, form_json, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
                $params = [$title, $description, $formJson, $currentTime, $currentTime];
                $newId = $this->db->insert($sql, $params);
                
                return ['success' => true, 'id' => $newId, 'message' => '폼이 성공적으로 저장되었습니다.'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => '저장 중 오류가 발생했습니다: ' . $e->getMessage()];
        }
    }
    
    /**
     * 폼 불러오기
     */
    public function loadForm($id)
    {
        try {
            $sql = "SELECT * FROM sandbox_forms WHERE id = ?";
            $form = $this->db->selectOne($sql, [$id]);
            
            if ($form) {
                return ['success' => true, 'form' => $form];
            } else {
                return ['success' => false, 'error' => '폼을 찾을 수 없습니다.'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => '폼을 불러오는 중 오류가 발생했습니다: ' . $e->getMessage()];
        }
    }
    
    /**
     * 모든 폼 목록 조회
     */
    public function getAllForms($search = '', $limit = 20, $offset = 0)
    {
        try {
            $params = [];
            $sql = "SELECT id, title, description, created_at, updated_at FROM sandbox_forms";
            
            if (!empty($search)) {
                $sql .= " WHERE title LIKE ? OR description LIKE ?";
                $searchTerm = '%' . $search . '%';
                $params = [$searchTerm, $searchTerm];
            }
            
            $sql .= " ORDER BY updated_at DESC";
            
            if ($limit > 0) {
                $sql .= " LIMIT ? OFFSET ?";
                $params[] = $limit;
                $params[] = $offset;
            }
            
            $forms = $this->db->select($sql, $params);
            
            // 총 개수 조회
            $countSql = "SELECT COUNT(*) as total FROM sandbox_forms";
            $countParams = [];
            
            if (!empty($search)) {
                $countSql .= " WHERE title LIKE ? OR description LIKE ?";
                $searchTerm = '%' . $search . '%';
                $countParams = [$searchTerm, $searchTerm];
            }
            
            $totalResult = $this->db->selectOne($countSql, $countParams);
            $total = $totalResult['total'];
            
            return [
                'success' => true, 
                'forms' => $forms,
                'total' => $total,
                'has_more' => ($offset + count($forms)) < $total
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => '폼 목록을 불러오는 중 오류가 발생했습니다: ' . $e->getMessage()];
        }
    }
    
    /**
     * 폼 삭제
     */
    public function deleteForm($id)
    {
        try {
            $sql = "DELETE FROM sandbox_forms WHERE id = ?";
            $affectedRows = $this->db->delete($sql, [$id]);
            
            if ($affectedRows > 0) {
                return ['success' => true, 'message' => '폼이 성공적으로 삭제되었습니다.'];
            } else {
                return ['success' => false, 'error' => '폼을 찾을 수 없습니다.'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => '폼을 삭제하는 중 오류가 발생했습니다: ' . $e->getMessage()];
        }
    }
    
    /**
     * 폼 복사
     */
    public function duplicateForm($id)
    {
        try {
            // 원본 폼 조회
            $originalForm = $this->loadForm($id);
            if (!$originalForm['success']) {
                return $originalForm;
            }
            
            $form = $originalForm['form'];
            $newTitle = $form['title'] . ' (복사본)';
            
            // 새로운 폼으로 저장
            return $this->saveForm($newTitle, $form['description'], $form['form_json']);
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => '폼을 복사하는 중 오류가 발생했습니다: ' . $e->getMessage()];
        }
    }
    
    /**
     * 폼 제출 처리
     */
    public function processFormSubmission($id, $submissionData)
    {
        try {
            // 폼 불러오기
            $formResult = $this->loadForm($id);
            if (!$formResult['success']) {
                return $formResult;
            }
            
            $form = $formResult['form'];
            $formStructure = json_decode($form['form_json'], true);
            
            // 제출 데이터 검증
            $validation = ValidationHelper::validateSubmissionData($submissionData, $formStructure);
            
            if (!$validation['valid']) {
                return [
                    'success' => false, 
                    'errors' => $validation['errors'],
                    'form_structure' => $formStructure
                ];
            }
            
            // 제출 성공
            return [
                'success' => true,
                'message' => '폼이 성공적으로 제출되었습니다.',
                'submission_data' => $submissionData,
                'form_structure' => $formStructure
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => '폼 제출 처리 중 오류가 발생했습니다: ' . $e->getMessage()];
        }
    }
    
    /**
     * 통계 정보 조회
     */
    public function getStatistics()
    {
        try {
            $stats = [];
            
            // 총 폼 개수
            $totalResult = $this->db->selectOne("SELECT COUNT(*) as total FROM sandbox_forms");
            $stats['total_forms'] = $totalResult['total'];
            
            // 최근 7일간 생성된 폼 개수
            $recentResult = $this->db->selectOne(
                "SELECT COUNT(*) as recent FROM sandbox_forms WHERE created_at >= datetime('now', '-7 days')"
            );
            $stats['recent_forms'] = $recentResult['recent'];
            
            // 가장 최근 폼
            $latestResult = $this->db->selectOne(
                "SELECT title, created_at FROM sandbox_forms ORDER BY created_at DESC LIMIT 1"
            );
            $stats['latest_form'] = $latestResult;
            
            return ['success' => true, 'statistics' => $stats];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => '통계 정보를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage()];
        }
    }
}