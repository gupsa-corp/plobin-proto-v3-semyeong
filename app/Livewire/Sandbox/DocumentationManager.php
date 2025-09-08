<?php

namespace App\Livewire\Sandbox;

use Illuminate\Support\Facades\File;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class DocumentationManager extends Component implements HasForms
{
    use InteractsWithForms;

    public string $selectedSection = '';
    public string $content = '';
    public string $previewContent = '';
    public array $sections = [];
    public bool $showPreview = true;
    public string $newSectionName = '';
    public bool $isCreatingSection = false;

    public function mount()
    {
        $this->loadSections();
        if (!empty($this->sections)) {
            $this->selectedSection = $this->sections[0]['id'];
            $this->loadContent();
        }
    }

    /**
     * 사용 가능한 문서 섹션 로드
     */
    public function loadSections()
    {
        $this->sections = [];
        $sectionsPath = $this->getSectionsPath();
        
        if (!File::exists($sectionsPath)) {
            File::makeDirectory($sectionsPath, 0755, true);
        }

        $files = File::files($sectionsPath);
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'md') {
                $filename = $file->getFilename();
                $content = File::get($file->getPathname());
                
                // 파일의 첫 번째 # 헤더를 제목으로 사용
                preg_match('/^# (.+)$/m', $content, $matches);
                $title = $matches[1] ?? $filename;
                
                $this->sections[] = [
                    'id' => pathinfo($filename, PATHINFO_FILENAME),
                    'filename' => $filename,
                    'title' => $title,
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'modified' => $file->getMTime()
                ];
            }
        }

        // 파일명으로 정렬
        usort($this->sections, function($a, $b) {
            return strcmp($a['filename'], $b['filename']);
        });
    }

    /**
     * 선택된 섹션의 내용 로드
     */
    public function loadContent()
    {
        if (empty($this->selectedSection)) {
            $this->content = '';
            $this->previewContent = '';
            return;
        }

        $section = collect($this->sections)->firstWhere('id', $this->selectedSection);
        if ($section && File::exists($section['path'])) {
            $this->content = File::get($section['path']);
            $this->updatePreview();
        } else {
            $this->content = '';
            $this->previewContent = '';
        }
    }

    /**
     * 섹션 선택
     */
    public function selectSection($sectionId)
    {
        $this->selectedSection = $sectionId;
        $this->loadContent();
    }

    /**
     * 내용 저장
     */
    public function saveContent()
    {
        if (empty($this->selectedSection)) {
            session()->flash('error', '섹션이 선택되지 않았습니다.');
            return;
        }

        try {
            $section = collect($this->sections)->firstWhere('id', $this->selectedSection);
            if (!$section) {
                session()->flash('error', '선택된 섹션을 찾을 수 없습니다.');
                return;
            }

            File::put($section['path'], $this->content);
            $this->updatePreview();
            
            session()->flash('message', '문서가 저장되었습니다.');
            
            // 섹션 정보 다시 로드 (수정시간 업데이트)
            $this->loadSections();
            
        } catch (\Exception $e) {
            session()->flash('error', '저장 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    /**
     * 새 섹션 생성
     */
    public function createSection()
    {
        if (empty($this->newSectionName)) {
            session()->flash('error', '섹션 이름을 입력해주세요.');
            return;
        }

        try {
            // 파일명 생성 (숫자 접두사 추가)
            $nextNumber = count($this->sections) + 1;
            $filename = sprintf('%03d-%s.md', $nextNumber, $this->sanitizeFilename($this->newSectionName));
            $filePath = $this->getSectionsPath() . '/' . $filename;

            // 기본 내용으로 파일 생성
            $defaultContent = "# " . $this->newSectionName . "\n\n새로운 문서 섹션입니다. 여기에 내용을 작성하세요.\n\n## 개요\n\n## 상세 내용\n\n## 참고사항\n";
            
            File::put($filePath, $defaultContent);
            
            // 섹션 목록 다시 로드
            $this->loadSections();
            
            // 새로 생성된 섹션 선택
            $newSectionId = pathinfo($filename, PATHINFO_FILENAME);
            $this->selectedSection = $newSectionId;
            $this->loadContent();
            
            $this->newSectionName = '';
            $this->isCreatingSection = false;
            
            session()->flash('message', '새 섹션이 생성되었습니다.');
            
        } catch (\Exception $e) {
            session()->flash('error', '섹션 생성 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    /**
     * 섹션 삭제
     */
    public function deleteSection($sectionId)
    {
        try {
            $section = collect($this->sections)->firstWhere('id', $sectionId);
            if (!$section) {
                session()->flash('error', '삭제할 섹션을 찾을 수 없습니다.');
                return;
            }

            File::delete($section['path']);
            
            // 선택된 섹션이 삭제된 경우 초기화
            if ($this->selectedSection === $sectionId) {
                $this->selectedSection = '';
                $this->content = '';
                $this->previewContent = '';
            }
            
            $this->loadSections();
            
            session()->flash('message', '섹션이 삭제되었습니다.');
            
        } catch (\Exception $e) {
            session()->flash('error', '삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    /**
     * 미리보기 업데이트 (실시간)
     */
    public function updatedContent()
    {
        $this->updatePreview();
    }

    /**
     * 마크다운을 HTML로 변환하여 미리보기 업데이트
     */
    private function updatePreview()
    {
        // 간단한 마크다운 to HTML 변환
        $html = $this->markdownToHtml($this->content);
        $this->previewContent = $html;
    }

    /**
     * 간단한 마크다운 to HTML 변환기
     */
    private function markdownToHtml($markdown)
    {
        $html = $markdown;
        
        // 헤더 변환
        $html = preg_replace('/^### (.+)$/m', '<h3 class="text-lg font-semibold text-gray-800 mt-4 mb-2">$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2 class="text-xl font-bold text-gray-900 mt-6 mb-3">$1</h2>', $html);
        $html = preg_replace('/^# (.+)$/m', '<h1 class="text-2xl font-bold text-gray-900 mt-8 mb-4">$1</h1>', $html);
        
        // 코드 블록 변환
        $html = preg_replace('/```(\w+)?\n(.*?)\n```/s', '<pre class="bg-gray-100 p-4 rounded-lg overflow-x-auto mb-4"><code class="text-sm">$2</code></pre>', $html);
        
        // 인라인 코드 변환
        $html = preg_replace('/`([^`]+)`/', '<code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">$1</code>', $html);
        
        // 굵은 글씨 변환
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong class="font-semibold">$1</strong>', $html);
        
        // 기울임 글씨 변환
        $html = preg_replace('/\*(.+?)\*/', '<em class="italic">$1</em>', $html);
        
        // 링크 변환
        $html = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2" class="text-blue-600 hover:text-blue-800 underline">$1</a>', $html);
        
        // 목록 변환 (간단한 버전)
        $html = preg_replace('/^- (.+)$/m', '<li class="ml-4 list-disc">$1</li>', $html);
        $html = preg_replace('/^(\d+)\. (.+)$/m', '<li class="ml-4 list-decimal">$2</li>', $html);
        
        // 줄바꿈을 <br>로 변환 (단락 구분)
        $html = preg_replace('/\n\n/', '</p><p class="mb-4">', $html);
        $html = '<p class="mb-4">' . $html . '</p>';
        
        // 빈 p 태그 제거
        $html = preg_replace('/<p class="mb-4"><\/p>/', '', $html);
        
        return $html;
    }

    /**
     * 파일명 새니타이징
     */
    private function sanitizeFilename($filename)
    {
        // 한글, 영문, 숫자, 하이픈, 언더스코어만 허용
        $filename = preg_replace('/[^\w\-\가-힣]/', '-', $filename);
        $filename = preg_replace('/[-]+/', '-', $filename);
        return trim($filename, '-');
    }

    /**
     * 섹션 디렉토리 경로
     */
    private function getSectionsPath()
    {
        return storage_path('app/sandbox-documentation/sections');
    }

    public function render()
    {
        return view('livewire.sandbox.documentation-manager');
    }
}