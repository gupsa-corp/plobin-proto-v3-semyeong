<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // display_name이 비어있으면 name을 기본값으로 설정
        if (empty($data['display_name'])) {
            $data['display_name'] = $data['name'];
        }
        
        // 기본값 설정
        $data['is_active'] = $data['is_active'] ?? true;
        $data['guard_name'] = $data['guard_name'] ?? 'web';
        
        return $data;
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return '새 권한이 생성되었습니다.';
    }
}
