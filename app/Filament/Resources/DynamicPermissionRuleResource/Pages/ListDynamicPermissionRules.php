<?php

namespace App\Filament\Resources\DynamicPermissionRuleResource\Pages;

use App\Filament\Resources\DynamicPermissionRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDynamicPermissionRules extends ListRecords
{
    protected static string $resource = DynamicPermissionRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
