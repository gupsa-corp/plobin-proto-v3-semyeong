<?php
/**
 * Form Publisher - 폼 에디터 페이지
 */

// 백엔드 클래스 로드
require_once __DIR__ . '/../../Backend/Functions/FormPublisher/FormManager.php';

$formManager = new FormManager();
$message = '';
$error = '';
$currentForm = null;
$editingId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;

// 편집할 폼 불러오기
if ($editingId) {
    $loadResult = $formManager->loadForm($editingId);
    if ($loadResult['success']) {
        $currentForm = $loadResult['form'];
    } else {
        $error = $loadResult['error'];
    }
}

// 폼 저장 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $formJson = $_POST['form_json'] ?? '';
    
    $saveResult = $formManager->saveForm($title, $description, $formJson, $editingId);
    
    if ($saveResult['success']) {
        $message = $saveResult['message'];
        if (!$editingId) {
            // 새로 저장된 경우 편집 모드로 전환
            $editingId = $saveResult['id'];
            $currentForm = [
                'id' => $editingId,
                'title' => $title,
                'description' => $description,
                'form_json' => $formJson
            ];
        }
    } else {
        $error = $saveResult['error'];
    }
}

// 기본 템플릿 JSON
$defaultJson = json_encode([
    'title' => '새로운 폼',
    'description' => '폼 설명을 입력하세요',
    'fields' => [
        [
            'type' => 'text',
            'name' => 'name',
            'label' => '이름',
            'placeholder' => '이름을 입력하세요',
            'required' => true
        ],
        [
            'type' => 'email',
            'name' => 'email',
            'label' => '이메일',
            'placeholder' => '이메일을 입력하세요',
            'required' => true
        ],
        [
            'type' => 'textarea',
            'name' => 'message',
            'label' => '메시지',
            'placeholder' => '메시지를 입력하세요',
            'rows' => 4,
            'required' => false
        ]
    ]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Publisher - 폼 에디터</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            transition: background 0.3s;
        }
        
        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .form-info {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-info h2 {
            margin-bottom: 1rem;
            color: #333;
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .editor-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .json-editor,
        .form-preview {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .json-editor h3,
        .form-preview h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        
        .json-textarea {
            width: 100%;
            height: 500px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.4;
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 1rem;
            resize: vertical;
        }
        
        .json-textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .preview-area {
            min-height: 500px;
            max-height: 500px;
            overflow-y: auto;
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 1rem;
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* 동적 폼 스타일 */
        .form-container h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-description {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .dynamic-form .form-group {
            margin-bottom: 1rem;
        }
        
        .dynamic-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .dynamic-form input,
        .dynamic-form textarea,
        .dynamic-form select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 0.9rem;
        }
        
        .radio-option,
        .checkbox-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.3rem 0;
        }
        
        .radio-option input,
        .checkbox-option input {
            width: auto;
        }
        
        .form-help {
            display: block;
            margin-top: 0.3rem;
            font-size: 0.8rem;
            color: #666;
        }
        
        .dynamic-form .btn {
            margin-right: 0.5rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>🎨 Form Publisher - 폼 에디터</h1>
        <nav class="nav-links">
            <a href="/sandbox/form-publisher/list">📋 폼 목록</a>
            <a href="../index.php">🏠 홈</a>
        </nav>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form id="formPublisherForm" onsubmit="return false;">
            <input type="hidden" name="action" value="save">
            
            <div class="form-info">
                <h2><?= $editingId ? '폼 편집' : '새 폼 만들기' ?></h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">폼 제목 *</label>
                        <input type="text" id="title" name="title" 
                               value="<?= htmlspecialchars($currentForm['title'] ?? '') ?>" 
                               placeholder="폼 제목을 입력하세요" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">폼 설명</label>
                        <input type="text" id="description" name="description" 
                               value="<?= htmlspecialchars($currentForm['description'] ?? '') ?>" 
                               placeholder="폼 설명을 입력하세요">
                    </div>
                </div>
            </div>

            <div class="editor-container">
                <div class="json-editor">
                    <h3>📝 JSON 에디터</h3>
                    <textarea id="form_json" name="form_json" class="json-textarea" 
                              placeholder="JSON 형태의 폼 구조를 입력하세요..."><?= htmlspecialchars($currentForm['form_json'] ?? $defaultJson) ?></textarea>
                </div>

                <div class="form-preview">
                    <h3>👀 실시간 미리보기</h3>
                    <div id="preview-area" class="preview-area">
                        <!-- 미리보기가 여기에 표시됩니다 -->
                    </div>
                </div>
            </div>

            <div class="actions">
                <button type="button" class="btn btn-primary" onclick="saveForm()">💾 폼 저장</button>
                <button type="button" class="btn btn-secondary" onclick="loadTemplate()">📄 템플릿 불러오기</button>
                <button type="button" class="btn btn-success" onclick="updatePreview()">🔄 미리보기 업데이트</button>
            </div>
        </form>
    </div>

    <script>
        // JSON 에디터와 미리보기 연동
        const jsonTextarea = document.getElementById('form_json');
        const previewArea = document.getElementById('preview-area');
        
        function updatePreview() {
            const jsonString = jsonTextarea.value;
            
            if (!jsonString.trim()) {
                previewArea.innerHTML = '<p style="color: #999; text-align: center; margin-top: 2rem;">JSON을 입력하면 미리보기가 표시됩니다.</p>';
                return;
            }
            
            // AJAX로 미리보기 요청
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=preview&form_json=' + encodeURIComponent(jsonString)
            })
            .then(response => response.text())
            .then(html => {
                // 응답에서 미리보기 부분만 추출
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const previewContent = doc.querySelector('#preview-content');
                
                if (previewContent) {
                    previewArea.innerHTML = previewContent.innerHTML;
                } else {
                    previewArea.innerHTML = '<p style="color: #e74c3c;">미리보기를 생성할 수 없습니다. JSON을 확인해주세요.</p>';
                }
            })
            .catch(error => {
                previewArea.innerHTML = '<p style="color: #e74c3c;">미리보기 오류: ' + error.message + '</p>';
            });
        }
        
        function loadTemplate() {
            if (confirm('현재 작성 중인 내용이 사라집니다. 계속하시겠습니까?')) {
                jsonTextarea.value = <?= json_encode($defaultJson) ?>;
                updatePreview();
            }
        }
        
        function saveForm() {
            const title = document.querySelector('input[name="title"]').value;
            const description = document.querySelector('input[name="description"]').value;
            const formJson = jsonTextarea.value;
            
            if (!title.trim()) {
                alert('폼 제목을 입력하세요.');
                return;
            }
            
            if (!formJson.trim()) {
                alert('폼 JSON을 입력하세요.');
                return;
            }
            
            // 현재 페이지로 POST 요청
            const formData = new FormData();
            formData.append('action', 'save');
            formData.append('title', title);
            formData.append('description', description);
            formData.append('form_json', formJson);
            
            // 버튼 비활성화
            const saveButton = document.querySelector('button[onclick="saveForm()"]');
            const originalText = saveButton.textContent;
            saveButton.textContent = '저장 중...';
            saveButton.disabled = true;
            
            fetch('/api/sandbox/form-publisher/save', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('폼이 성공적으로 저장되었습니다! (ID: ' + data.form_id + ')');
                    
                    // 폼 목록 페이지로 이동
                    setTimeout(() => {
                        window.location.href = '/sandbox/form-publisher/list';
                    }, 1000);
                } else {
                    alert('저장 실패: ' + data.message);
                }
            })
            .catch(error => {
                alert('저장 중 오류가 발생했습니다: ' + error.message);
            })
            .finally(() => {
                saveButton.textContent = originalText;
                saveButton.disabled = false;
            });
        }
        
        // 페이지 로드 시 초기 미리보기
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
        
        // JSON 입력 시 실시간 미리보기 (디바운스)
        let previewTimeout;
        jsonTextarea.addEventListener('input', function() {
            clearTimeout(previewTimeout);
            previewTimeout = setTimeout(updatePreview, 500);
        });
    </script>
</body>
</html>

<?php
// AJAX 미리보기 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'preview') {
    $formJson = $_POST['form_json'] ?? '';
    
    require_once __DIR__ . '/../../Backend/Functions/FormPublisher/FormRenderer.php';
    
    echo '<div id="preview-content">';
    echo FormRenderer::renderForm($formJson);
    echo '</div>';
    exit;
}
?>