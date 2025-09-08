@php
$categoryConfig = [
    'data-management' => ['name' => '데이터 관리', 'color' => 'blue', 'icon' => '📊'],
    'authentication' => ['name' => '인증', 'color' => 'red', 'icon' => '🔐'],
    'api' => ['name' => 'API', 'color' => 'green', 'icon' => '🔌'],
    'data' => ['name' => '데이터', 'color' => 'yellow', 'icon' => '💾'],
    'utility' => ['name' => '유틸리티', 'color' => 'purple', 'icon' => '🛠️'],
    'default' => ['name' => '기타', 'color' => 'gray', 'icon' => '📄']
];
$config = $categoryConfig[$category] ?? $categoryConfig['default'];
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 mb-2">
    <span class="mr-1">{{ $config['icon'] }}</span>
    {{ $config['name'] }}
</span>