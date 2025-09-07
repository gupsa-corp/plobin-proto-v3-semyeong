<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('상세보기'),
            Actions\DeleteAction::make()
                ->label('삭제')
                ->requiresConfirmation()
                ->modalHeading('권한 삭제 확인')
                ->modalDescription('이 권한을 삭제하면 관련된 모든 사용자와 역할에서 제거됩니다. 정말 삭제하시겠습니까?')
                ->before(function ($record) {
                    // 권한 삭제 전에 연결된 사용자와 역할에서 제거
                    $record->users()->detach();
                    $record->roles()->detach();
                }),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // display_name이 비어있으면 name을 기본값으로 설정
        if (empty($data['display_name'])) {
            $data['display_name'] = $data['name'];
        }
        
        return $data;
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return '권한이 수정되었습니다.';
    }
}
