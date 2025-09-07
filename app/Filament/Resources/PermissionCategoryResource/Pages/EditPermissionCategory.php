<?php

namespace App\Filament\Resources\PermissionCategoryResource\Pages;

use App\Filament\Resources\PermissionCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermissionCategory extends EditRecord
{
    protected static string $resource = PermissionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
