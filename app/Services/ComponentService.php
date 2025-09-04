<?php

namespace App\Services;

use InvalidArgumentException;

class ComponentService
{
    /**
     * 컴포넌트 렌더링
     */
    public function render(string $configFile, string $componentName, array $overrides = []): string
    {
        // 모든 컴포넌트를 통합 파일에서 로드 (configFile 파라미터는 하위호환성 유지용)
        $config = config("components.{$componentName}");
        
        if (!$config) {
            throw new InvalidArgumentException("Component '{$componentName}' not found in components config");
        }
        
        // 설정 오버라이드 적용
        $config = array_merge_recursive($config, $overrides);
        
        return $this->renderFromConfig($config);
    }

    /**
     * 설정 기반으로 컴포넌트 렌더링
     */
    protected function renderFromConfig(array $config): string
    {
        $type = $config['type'] ?? 'single';
        
        switch ($type) {
            case 'nav_links':
                return $this->renderNavLinks($config);
            case 'nav_menu':
                return $this->renderNavMenu($config);
            case 'sidebar':
                return $this->renderSidebar($config);
            
            // 복잡한 컴포넌트들
            case 'form':
                return $this->renderForm($config);
            case 'card_list':
            case 'item_list':  
            case 'list':
                return $this->renderItemList($config);
            case 'table':
                return $this->renderTable($config);
            
            // 단순 엘리먼트들은 모두 renderSingleElement로 처리
            case 'text':
            case 'button':
            case 'input':
            case 'label':
            case 'link':
            case 'single':
            case 'wrapper':
            case 'alert':
            case 'badge':
            case 'checkbox':
            case 'email':
            case 'number':
            case 'password':
            case 'radio':
            case 'select':
            case 'submit':
            case 'spinner':
            case 'progress':
            case 'modal':
            case 'dropdown':
            case 'popover':
            case 'tooltip':
            case 'toast':
            case 'table':
            case 'timeline':
            case 'drawer':
            case 'confirmation_modal':
            case 'loading_overlay':
                return $this->renderSingleElement($config);
                
            default:
                throw new InvalidArgumentException("Unknown component type: {$type}");
        }
    }

    /**
     * 네비게이션 링크 렌더링
     */
    protected function renderNavLinks(array $config): string
    {
        $html = '';
        $wrapper = $config['wrapper'] ?? null;
        
        if ($wrapper) {
            $html .= sprintf('<%s class="%s">', $wrapper['tag'], $wrapper['classes'] ?? '');
        }
        
        foreach ($config['items'] as $item) {
            $html .= $this->renderSingleElement($item);
        }
        
        if ($wrapper) {
            $html .= sprintf('</%s>', $wrapper['tag']);
        }
        
        return $html;
    }

    /**
     * 네비게이션 메뉴 렌더링
     */
    protected function renderNavMenu(array $config): string
    {
        return $this->renderNavLinks($config); // 동일한 로직
    }

    /**
     * 사이드바 렌더링
     */
    protected function renderSidebar(array $config): string
    {
        $html = '';
        $wrapper = $config['wrapper'] ?? null;
        
        if ($wrapper) {
            $html .= sprintf('<%s class="%s">', $wrapper['tag'], $wrapper['classes'] ?? '');
        }
        
        // 오버레이 렌더링 (모바일용)
        if (isset($config['overlay'])) {
            $html .= $this->renderSingleElement($config['overlay']);
        }
        
        // 헤더 렌더링
        if (isset($config['header'])) {
            $html .= $this->renderSidebarSection($config['header']);
        }
        
        // 메뉴 렌더링
        if (isset($config['menu'])) {
            $html .= $this->renderSidebarMenu($config['menu']);
        }
        
        if ($wrapper) {
            $html .= sprintf('</%s>', $wrapper['tag']);
        }
        
        return $html;
    }

    /**
     * 사이드바 섹션 렌더링
     */
    protected function renderSidebarSection(array $section): string
    {
        $html = '';
        $tag = $section['tag'];
        $classes = $section['classes'] ?? '';
        
        $html .= sprintf('<%s class="%s">', $tag, $classes);
        
        if (isset($section['content'])) {
            if (is_array($section['content'])) {
                // 여러 컨텐츠가 있는 경우
                if (isset($section['content']['tag'])) {
                    // 단일 엘리먼트
                    $html .= $this->renderSingleElement($section['content']);
                } else {
                    // 여러 엘리먼트 배열
                    foreach ($section['content'] as $item) {
                        $html .= $this->renderSingleElement($item);
                    }
                }
            } else {
                $html .= htmlspecialchars($section['content']);
            }
        }
        
        $html .= sprintf('</%s>', $tag);
        
        return $html;
    }

    /**
     * 사이드바 메뉴 렌더링
     */
    protected function renderSidebarMenu(array $menu): string
    {
        $html = '';
        $tag = $menu['tag'];
        $classes = $menu['classes'] ?? '';
        
        $html .= sprintf('<%s class="%s">', $tag, $classes);
        
        if (isset($menu['items'])) {
            foreach ($menu['items'] as $group) {
                $html .= $this->renderMenuGroup($group);
            }
        }
        
        $html .= sprintf('</%s>', $tag);
        
        return $html;
    }

    /**
     * 메뉴 그룹 렌더링
     */
    protected function renderMenuGroup(array $group): string
    {
        $html = '';
        
        // 그룹 제목이 있으면 렌더링
        if (!empty($group['title'])) {
            $html .= sprintf(
                '<div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">%s</div>',
                htmlspecialchars($group['title'])
            );
        }
        
        // 그룹 아이템들 렌더링
        if (isset($group['items'])) {
            $html .= '<div class="space-y-1">';
            foreach ($group['items'] as $item) {
                if ($item['type'] === 'menu_item') {
                    $html .= $this->renderMenuItem($item);
                }
            }
            $html .= '</div>';
            
            // 그룹 구분을 위한 여백
            if (!empty($group['title'])) {
                $html .= '<div class="mt-6"></div>';
            }
        }
        
        return $html;
    }

    /**
     * 메뉴 아이템 렌더링
     */
    protected function renderMenuItem(array $item): string
    {
        $html = '';
        $tag = $item['tag'];
        $classes = $item['classes'] ?? '';
        $url = $item['url'] ?? '';
        $text = $item['text'] ?? '';
        $icon = $item['icon'] ?? '';
        
        $html .= sprintf('<%s href="%s" class="%s">', $tag, htmlspecialchars($url), htmlspecialchars($classes));
        
        // 아이콘이 있으면 추가 (아이콘은 실제 구현에서 icon font나 SVG로 교체)
        if ($icon) {
            $html .= sprintf('<span class="icon-%s mr-3"></span>', $icon);
        }
        
        $html .= htmlspecialchars($text);
        $html .= sprintf('</%s>', $tag);
        
        return $html;
    }

    /**
     * 단일 엘리먼트 렌더링
     */
    protected function renderSingleElement(array $config): string
    {
        $tag = $config['tag'];
        $classes = $config['classes'] ?? '';
        $attributes = $config['attributes'] ?? [];
        $text = $config['text'] ?? '';
        $url = $config['url'] ?? '';
        
        // 속성 문자열 생성
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= sprintf(' %s="%s"', $key, htmlspecialchars($value));
        }
        
        // URL이 있으면 href 속성 추가
        if ($url) {
            $attrString .= sprintf(' href="%s"', htmlspecialchars($url));
        }
        
        // 클래스가 있으면 class 속성 추가  
        if ($classes) {
            $attrString .= sprintf(' class="%s"', htmlspecialchars($classes));
        }
        
        // 자체 닫힘 태그 처리
        if (in_array($tag, ['input', 'img', 'br', 'hr'])) {
            return sprintf('<%s%s />', $tag, $attrString);
        }
        
        // 특별한 컴포넌트 처리
        if (isset($config['title']) && isset($config['description'])) {
            // page_header 같은 복합 컴포넌트
            $content = sprintf(
                '<h1 class="text-2xl font-bold text-white">%s</h1><p class="text-gray-300 mt-2">%s</p>',
                htmlspecialchars($config['title']),
                htmlspecialchars($config['description'])
            );
            return sprintf('<%s%s>%s</%s>', $tag, $attrString, $content, $tag);
        } elseif (isset($config['title']) && !isset($config['description'])) {
            // card_header 같은 단순 제목 컴포넌트
            $content = sprintf(
                '<h2 class="text-lg font-medium text-white">%s</h2>',
                htmlspecialchars($config['title'])
            );
            return sprintf('<%s%s>%s</%s>', $tag, $attrString, $content, $tag);
        }
        
        return sprintf('<%s%s>%s</%s>', $tag, $attrString, htmlspecialchars($text), $tag);
    }

    /**
     * 폼 렌더링
     */
    protected function renderForm(array $config): string
    {
        $html = '';
        $tag = $config['tag'] ?? 'form';
        $classes = $config['classes'] ?? '';
        $attributes = $config['attributes'] ?? [];
        
        // 속성 문자열 생성
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= sprintf(' %s="%s"', $key, htmlspecialchars($value));
        }
        
        if ($classes) {
            $attrString .= sprintf(' class="%s"', htmlspecialchars($classes));
        }
        
        $html .= sprintf('<%s%s>', $tag, $attrString);
        
        // 폼 필드들 렌더링
        if (isset($config['fields']) && is_array($config['fields'])) {
            foreach ($config['fields'] as $field) {
                $html .= $this->renderSingleElement($field);
            }
        }
        
        // 내용 렌더링
        if (isset($config['content'])) {
            $html .= $config['content'];
        }
        
        $html .= sprintf('</%s>', $tag);
        
        return $html;
    }

    /**
     * 아이템 리스트 렌더링
     */
    protected function renderItemList(array $config): string
    {
        $html = '';
        $tag = $config['tag'] ?? 'ul';
        $classes = $config['classes'] ?? '';
        $attributes = $config['attributes'] ?? [];
        
        // 속성 문자열 생성
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= sprintf(' %s="%s"', $key, htmlspecialchars($value));
        }
        
        if ($classes) {
            $attrString .= sprintf(' class="%s"', htmlspecialchars($classes));
        }
        
        $html .= sprintf('<%s%s>', $tag, $attrString);
        
        // 리스트 아이템들 렌더링
        if (isset($config['items']) && is_array($config['items'])) {
            foreach ($config['items'] as $item) {
                $html .= $this->renderSingleElement($item);
            }
        }
        
        // 내용 렌더링
        if (isset($config['content'])) {
            $html .= $config['content'];
        }
        
        $html .= sprintf('</%s>', $tag);
        
        return $html;
    }

    /**
     * 테이블 렌더링
     */
    protected function renderTable(array $config): string
    {
        $html = '';
        
        // 래퍼가 있으면 추가
        if (isset($config['wrapper'])) {
            $wrapper = $config['wrapper'];
            $html .= sprintf('<%s class="%s">', $wrapper['tag'], $wrapper['classes'] ?? '');
        }
        
        // 테이블 시작
        if (isset($config['table'])) {
            $table = $config['table'];
            $html .= sprintf('<%s class="%s">', $table['tag'], $table['classes'] ?? '');
            
            // thead 렌더링
            if (isset($table['thead'])) {
                $thead = $table['thead'];
                $html .= sprintf('<%s class="%s">', $thead['tag'], $thead['classes'] ?? '');
                
                if (isset($thead['tr'])) {
                    $tr = $thead['tr'];
                    $html .= sprintf('<%s>', $tr['tag']);
                    
                    if (isset($tr['cells'])) {
                        foreach ($tr['cells'] as $cell) {
                            $html .= sprintf('<%s class="%s">%s</%s>', 
                                $cell['tag'], 
                                $cell['classes'] ?? '', 
                                htmlspecialchars($cell['text'] ?? ''),
                                $cell['tag']
                            );
                        }
                    }
                    
                    $html .= sprintf('</%s>', $tr['tag']);
                }
                
                $html .= sprintf('</%s>', $thead['tag']);
            }
            
            // tbody 렌더링
            if (isset($table['tbody'])) {
                $tbody = $table['tbody'];
                $html .= sprintf('<%s class="%s">', $tbody['tag'], $tbody['classes'] ?? '');
                
                if (isset($tbody['rows'])) {
                    foreach ($tbody['rows'] as $row) {
                        $html .= sprintf('<%s class="%s">', $row['tag'], $row['classes'] ?? '');
                        
                        if (isset($row['cells'])) {
                            foreach ($row['cells'] as $cell) {
                                $cellContent = '';
                                if (isset($cell['content'])) {
                                    $cellContent = $cell['content'];
                                } elseif (isset($cell['text'])) {
                                    $cellContent = htmlspecialchars($cell['text']);
                                }
                                
                                $html .= sprintf('<%s class="%s">%s</%s>', 
                                    $cell['tag'], 
                                    $cell['classes'] ?? '', 
                                    $cellContent,
                                    $cell['tag']
                                );
                            }
                        }
                        
                        $html .= sprintf('</%s>', $row['tag']);
                    }
                }
                
                $html .= sprintf('</%s>', $tbody['tag']);
            }
            
            $html .= sprintf('</%s>', $table['tag']);
        }
        
        // 래퍼 닫기
        if (isset($config['wrapper'])) {
            $html .= sprintf('</%s>', $config['wrapper']['tag']);
        }
        
        return $html;
    }

    /**
     * 인증 링크 렌더링 (편의 메소드)
     */
    public function renderAuthLinks(): string
    {
        return $this->render('', 'auth_links');
    }
}