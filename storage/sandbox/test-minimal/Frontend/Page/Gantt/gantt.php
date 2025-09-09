<?php
/**
 * 간트차트 페이지
 * 간트차트 블록을 구현한 프론트엔드 페이지
 */

// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 통일된 색상 팔레트 시스템 (Material Design Blue 계열)
function getProjectColor($progress) {
    // 진행률에 따른 색상 강도 조절
    if ($progress >= 90) return '#0D47A1';      // Blue 900 (완료에 가까움)
    if ($progress >= 70) return '#1565C0';      // Blue 800 (높은 진행률)
    if ($progress >= 50) return '#1976D2';      // Blue 700 (중간 진행률)
    if ($progress >= 30) return '#2196F3';      // Blue 500 (기본)
    return '#42A5F5';                           // Blue 400 (낮은 진행률)
}

// 샘플 프로젝트 데이터
$projects = [
    [
        'id' => 1,
        'name' => '웹사이트 리뉴얼 프로젝트',
        'start_date' => '2024-01-15',
        'end_date' => '2024-03-30',
        'progress' => 65
    ],
    [
        'id' => 2,
        'name' => 'API 개발',
        'start_date' => '2024-02-01',
        'end_date' => '2024-04-15',
        'progress' => 40
    ],
    [
        'id' => 3,
        'name' => '데이터베이스 마이그레이션',
        'start_date' => '2024-01-20',
        'end_date' => '2024-02-28',
        'progress' => 90
    ],
    [
        'id' => 4,
        'name' => '모바일 앱 개발',
        'start_date' => '2024-03-01',
        'end_date' => '2024-05-30',
        'progress' => 20
    ],
    [
        'id' => 5,
        'name' => '테스트 및 배포',
        'start_date' => '2024-04-15',
        'end_date' => '2024-05-15',
        'progress' => 10
    ]
];

// 각 프로젝트에 동적 색상 할당
foreach ($projects as &$project) {
    $project['color'] = getProjectColor($project['progress']);
}

// AJAX 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'update_progress':
            $project_id = (int)$_POST['project_id'];
            $new_progress = (int)$_POST['progress'];
            
            // 실제로는 데이터베이스에 저장해야 하지만, 여기서는 시뮬레이션
            foreach ($projects as &$project) {
                if ($project['id'] === $project_id) {
                    $project['progress'] = max(0, min(100, $new_progress));
                    $project['color'] = getProjectColor($project['progress']); // 통일된 색상 시스템 적용
                    break;
                }
            }
            
            echo json_encode([
                'success' => true, 
                'message' => '진행률이 업데이트되었습니다.',
                'new_color' => getProjectColor($new_progress)
            ]);
            exit;
            
        case 'get_projects':
            echo json_encode($projects);
            exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>간트차트 - 프로젝트 관리</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }



        .gantt-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .gantt-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .gantt-header h2 {
            color: #495057;
            margin-bottom: 10px;
        }

        .date-range {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .gantt-timeline {
            display: flex;
            background: #f1f3f4;
            padding: 10px 20px;
            border-bottom: 1px solid #e9ecef;
            overflow-x: auto;
        }

        .timeline-month {
            flex: 1;
            min-width: 120px;
            text-align: center;
            font-weight: 600;
            color: #495057;
            padding: 5px;
            border-right: 1px solid #dee2e6;
        }

        .timeline-month:last-child {
            border-right: none;
        }

        .gantt-chart {
            padding: 0;
        }

        .gantt-row {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f8f9fa;
            min-height: 60px;
            position: relative;
            transition: background-color 0.2s ease;
        }

        .gantt-row:hover {
            background-color: #f8f9fa;
        }

        .project-info {
            width: 300px;
            padding: 15px 20px;
            border-right: 2px solid #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .project-name {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 5px;
        }

        .project-dates {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .progress-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 0.8rem;
            font-weight: 600;
            color: #495057;
            min-width: 35px;
            text-align: right;
        }

        .timeline-area {
            flex: 1;
            position: relative;
            padding: 10px 20px;
            min-height: 40px;
        }

        .gantt-bar {
            height: 20px;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .gantt-bar:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }

        .gantt-bar-progress {
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            position: absolute;
            top: 0;
            left: 0;
        }

        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.2s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-completed {
            background: #28a745;
        }

        .status-in-progress {
            background: #ffc107;
        }

        .status-pending {
            background: #6c757d;
        }



        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .project-info {
                width: 250px;
            }


        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
        }

        .modal-header h3 {
            color: #495057;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #495057;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="gantt-container">
            <div class="gantt-header">
                <h2>프로젝트 타임라인</h2>
                <div class="date-range">
                    <span>📅 기간: 2024년 1월 ~ 5월</span>
                    <span>📈 총 프로젝트: <?= count($projects) ?>개</span>
                </div>
            </div>

            <div class="gantt-timeline">
                <div class="timeline-month">2024년 1월</div>
                <div class="timeline-month">2024년 2월</div>
                <div class="timeline-month">2024년 3월</div>
                <div class="timeline-month">2024년 4월</div>
                <div class="timeline-month">2024년 5월</div>
            </div>

            <div class="gantt-chart" id="gantt-chart">
                <?php foreach ($projects as $project): ?>
                <div class="gantt-row" data-project-id="<?= $project['id'] ?>">
                    <div class="project-info">
                        <div class="project-name">
                            <span class="status-indicator <?= $project['progress'] >= 90 ? 'status-completed' : ($project['progress'] > 0 ? 'status-in-progress' : 'status-pending') ?>"></span>
                            <?= htmlspecialchars($project['name']) ?>
                        </div>
                        <div class="project-dates">
                            <?= date('Y.m.d', strtotime($project['start_date'])) ?> ~ <?= date('Y.m.d', strtotime($project['end_date'])) ?>
                        </div>
                        <div class="progress-info">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $project['progress'] ?>%; background: <?= $project['color'] ?>;"></div>
                            </div>
                            <div class="progress-text"><?= $project['progress'] ?>%</div>
                        </div>
                    </div>
                    <div class="timeline-area">
                        <div class="gantt-bar" 
                             style="background: <?= $project['color'] ?>; 
                                    left: <?= calculatePosition($project['start_date']) ?>%; 
                                    width: <?= calculateWidth($project['start_date'], $project['end_date']) ?>%;"
                             onclick="editProject(<?= $project['id'] ?>)">
                            <div class="gantt-bar-progress" style="width: <?= $project['progress'] ?>%;"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>


    </div>

    <!-- 프로젝트 편집 모달 -->
    <div class="modal" id="edit-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>프로젝트 편집</h3>
            </div>
            <form id="edit-form">
                <div class="form-group">
                    <label for="edit-progress">진행률 (%)</label>
                    <input type="number" id="edit-progress" min="0" max="100" step="1">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">취소</button>
                    <button type="submit" class="btn">저장</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentProjectId = null;

        function calculatePosition(startDate) {
            // 2024년 1월 1일을 기준으로 계산
            const baseDate = new Date('2024-01-01');
            const projectStart = new Date(startDate);
            const totalDays = 150; // 5개월 대략 150일
            const daysDiff = Math.floor((projectStart - baseDate) / (1000 * 60 * 60 * 24));
            return Math.max(0, (daysDiff / totalDays) * 100);
        }

        function calculateWidth(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const duration = Math.floor((end - start) / (1000 * 60 * 60 * 24));
            const totalDays = 150; // 5개월 대략 150일
            return Math.min(100, (duration / totalDays) * 100);
        }

        function editProject(projectId) {
            currentProjectId = projectId;
            const row = document.querySelector(`[data-project-id="${projectId}"]`);
            const progressText = row.querySelector('.progress-text').textContent;
            const progress = parseInt(progressText.replace('%', ''));
            
            document.getElementById('edit-progress').value = progress;
            document.getElementById('edit-modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('edit-modal').style.display = 'none';
            currentProjectId = null;
        }

        // 진행률에 따른 통일된 색상 반환 (PHP와 동일한 로직)
        function getProjectColor(progress) {
            if (progress >= 90) return '#0D47A1';      // Blue 900 (완료에 가까움)
            if (progress >= 70) return '#1565C0';      // Blue 800 (높은 진행률)
            if (progress >= 50) return '#1976D2';      // Blue 700 (중간 진행률)
            if (progress >= 30) return '#2196F3';      // Blue 500 (기본)
            return '#42A5F5';                          // Blue 400 (낮은 진행률)
        }

        function updateProgress(projectId, newProgress) {
            const formData = new FormData();
            formData.append('action', 'update_progress');
            formData.append('project_id', projectId);
            formData.append('progress', newProgress);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // UI 업데이트
                    const row = document.querySelector(`[data-project-id="${projectId}"]`);
                    const progressBar = row.querySelector('.progress-fill');
                    const progressText = row.querySelector('.progress-text');
                    const ganttBar = row.querySelector('.gantt-bar');
                    const ganttBarProgress = row.querySelector('.gantt-bar-progress');
                    const statusIndicator = row.querySelector('.status-indicator');
                    
                    // 통일된 색상 시스템 적용
                    const newColor = getProjectColor(newProgress);
                    
                    progressBar.style.width = newProgress + '%';
                    progressBar.style.background = newColor;
                    progressText.textContent = newProgress + '%';
                    ganttBar.style.background = newColor;
                    ganttBarProgress.style.width = newProgress + '%';
                    
                    // 상태 표시 업데이트
                    statusIndicator.className = 'status-indicator ' + 
                        (newProgress >= 90 ? 'status-completed' : 
                         newProgress > 0 ? 'status-in-progress' : 'status-pending');
                    
                    // 성공 메시지 표시 (alert 대신 더 나은 UX)
                    showNotification(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('업데이트 중 오류가 발생했습니다.', 'error');
            });
        }

        // 알림 표시 함수 (UX 개선)
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#007bff'};
                color: white;
                border-radius: 5px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                font-size: 14px;
                max-width: 300px;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            // 애니메이션 효과
            requestAnimationFrame(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            });
            
            // 3초 후 자동 제거
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }



        // 폼 제출 이벤트
        document.getElementById('edit-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (currentProjectId) {
                const newProgress = parseInt(document.getElementById('edit-progress').value);
                updateProgress(currentProjectId, newProgress);
                closeModal();
            }
        });

        // 모달 외부 클릭시 닫기
        document.getElementById('edit-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // ESC 키로 모달 닫기
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('edit-modal').style.display === 'flex') {
                closeModal();
            }
        });

        console.log('간트차트 페이지가 로드되었습니다.');
        console.log('프로젝트 수:', <?= count($projects) ?>);
    </script>
</body>
</html>

<?php
function calculatePosition($startDate) {
    $baseDate = new DateTime('2024-01-01');
    $projectStart = new DateTime($startDate);
    $totalDays = 150; // 5개월 대략 150일
    $daysDiff = $projectStart->diff($baseDate)->days;
    return max(0, ($daysDiff / $totalDays) * 100);
}

function calculateWidth($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $duration = $end->diff($start)->days;
    $totalDays = 150; // 5개월 대략 150일
    return min(100, ($duration / $totalDays) * 100);
}
?>