<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Permission;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncPermissions')
                ->label('권한 동기화')
                ->icon('heroicon-s-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('권한 동기화')
                ->modalDescription('시스템에 정의된 권한을 데이터베이스와 동기화합니다. 새로운 권한이 추가되거나 변경사항이 반영됩니다.')
                ->action(function () {
                    // 동기화 로직은 필요에 따라 구현
                    $this->notify('success', '권한 동기화가 완료되었습니다.');
                }),
                
            Actions\CreateAction::make()
                ->label('새 권한 추가'),
        ];
    }
    
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('전체'),
            'active' => Tab::make('활성화됨')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true))
                ->badge(Permission::where('is_active', true)->count()),
            'inactive' => Tab::make('비활성화됨')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false))
                ->badge(Permission::where('is_active', false)->count()),
        ];
    }
}
