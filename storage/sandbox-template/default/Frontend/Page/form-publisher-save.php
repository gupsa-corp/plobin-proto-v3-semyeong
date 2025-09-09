<?php
/**
 * Form Publisher - 직접 저장 처리 (Laravel 우회)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// 백엔드 클래스 로드
require_once __DIR__ . '/../../Backend/Functions/FormPublisher/FormManager.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $formJson = $_POST['form_json'] ?? '';
    $editingId = isset($_POST['editing_id']) ? (int)$_POST['editing_id'] : null;

    if (empty($title)) {
        throw new Exception('폼 제목이 필요합니다.');
    }

    if (empty($formJson)) {
        throw new Exception('폼 JSON이 필요합니다.');
    }

    $formManager = new FormManager();
    $result = $formManager->saveForm($title, $description, $formJson, $editingId);

    echo json_encode([
        'success' => $result['success'],
        'message' => $result['success'] ? $result['message'] : $result['error'],
        'form_id' => $result['success'] ? $result['id'] : null
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}