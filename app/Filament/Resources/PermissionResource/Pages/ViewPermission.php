<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;

class ViewPermission extends ViewRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('수정'),
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
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->getRecord())
            ->schema(PermissionResource::infolist($infolist)->getComponents());
    }
}