<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\PermissionCategory;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Illuminate\Database\Eloquent\Model;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    
    protected static ?string $navigationLabel = '권한 관리';
    
    protected static ?string $modelLabel = '권한';
    
    protected static ?string $pluralModelLabel = '권한들';
    
    protected static ?string $navigationGroup = '사용자 관리';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('기본 정보')
                    ->schema([
                        TextInput::make('name')
                            ->label('권한명')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('예: view users, create projects')
                            ->helperText('영문 소문자와 공백으로 구성된 권한 식별자'),
                            
                        TextInput::make('display_name')
                            ->label('표시명')
                            ->maxLength(255)
                            ->placeholder('예: 사용자 보기, 프로젝트 생성')
                            ->helperText('사용자에게 보여질 한글 권한명'),
                            
                        Textarea::make('description')
                            ->label('설명')
                            ->rows(3)
                            ->placeholder('이 권한의 기능과 범위를 설명해주세요')
                            ->helperText('권한의 상세한 기능 설명'),
                    ]),
                    
                Forms\Components\Section::make('분류 및 설정')
                    ->schema([
                        Select::make('category_id')
                            ->label('카테고리')
                            ->options(fn () => PermissionCategory::query()
                                ->orderBy('sort_order')
                                ->pluck('display_name', 'id')
                            )
                            ->placeholder('카테고리를 선택하세요')
                            ->helperText('권한을 분류할 카테고리'),
                            
                        Select::make('guard_name')
                            ->label('Guard')
                            ->options([
                                'web' => 'Web',
                                'api' => 'API',
                            ])
                            ->default('web')
                            ->required()
                            ->helperText('권한이 적용될 인증 가드'),
                            
                        Toggle::make('is_active')
                            ->label('활성화')
                            ->default(true)
                            ->helperText('비활성화된 권한은 시스템에서 사용되지 않음'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('권한명')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('medium'),
                    
                TextColumn::make('display_name')
                    ->label('표시명')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),
                    
                BadgeColumn::make('category.display_name')
                    ->label('카테고리')
                    ->colors([
                        'primary' => static fn ($state): bool => !is_null($state),
                        'gray' => static fn ($state): bool => is_null($state),
                    ])
                    ->placeholder('미분류'),
                    
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'web' => 'success',
                        'api' => 'warning',
                        default => 'gray',
                    }),
                    
                BooleanColumn::make('is_active')
                    ->label('활성화')
                    ->sortable(),
                    
                TextColumn::make('users_count')
                    ->label('사용자 수')
                    ->counts('users')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                    
                TextColumn::make('roles_count')
                    ->label('역할 수')
                    ->counts('roles')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                    
                TextColumn::make('created_at')
                    ->label('생성일')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->label('수정일')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('카테고리')
                    ->options(fn () => PermissionCategory::query()
                        ->orderBy('sort_order')
                        ->pluck('display_name', 'id')
                    )
                    ->placeholder('전체 카테고리'),
                    
                SelectFilter::make('guard_name')
                    ->label('Guard')
                    ->options([
                        'web' => 'Web',
                        'api' => 'API',
                    ])
                    ->placeholder('전체 Guard'),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('활성화 상태')
                    ->placeholder('전체')
                    ->trueLabel('활성화')
                    ->falseLabel('비활성화'),
            ])
            ->actions([
                Action::make('viewUsers')
                    ->label('사용자 보기')
                    ->icon('heroicon-s-users')
                    ->color('info')
                    ->url(fn (Permission $record): string => 
                        '/admin/users?tableFilters[permissions][values][]=' . $record->id
                    )
                    ->visible(fn (Permission $record): bool => $record->users_count > 0),
                    
                Action::make('viewRoles')
                    ->label('역할 보기')
                    ->icon('heroicon-s-shield-check')
                    ->color('warning')
                    ->url(fn (Permission $record): string => 
                        '/admin/roles?tableFilters[permissions][values][]=' . $record->id
                    )
                    ->visible(fn (Permission $record): bool => $record->roles_count > 0),
                    
                Tables\Actions\ViewAction::make()
                    ->label('상세보기'),
                Tables\Actions\EditAction::make()
                    ->label('수정'),
                Tables\Actions\DeleteAction::make()
                    ->label('삭제')
                    ->requiresConfirmation()
                    ->modalHeading('권한 삭제 확인')
                    ->modalDescription('이 권한을 삭제하면 관련된 모든 사용자와 역할에서 제거됩니다. 정말 삭제하시겠습니까?')
                    ->before(function (Permission $record) {
                        // 권한 삭제 전에 연결된 사용자와 역할에서 제거
                        $record->users()->detach();
                        $record->roles()->detach();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('선택 삭제')
                        ->requiresConfirmation()
                        ->modalHeading('권한 일괄 삭제 확인')
                        ->modalDescription('선택된 권한들을 삭제하면 관련된 모든 사용자와 역할에서 제거됩니다. 정말 삭제하시겠습니까?')
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $record->users()->detach();
                                $record->roles()->detach();
                            }
                        }),
                        
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('활성화 토글')
                        ->icon('heroicon-s-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => !$record->is_active]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('category_id')
            ->groups([
                Tables\Grouping\Group::make('category.display_name')
                    ->label('카테고리별')
                    ->collapsible(),
                Tables\Grouping\Group::make('guard_name')
                    ->label('Guard별')
                    ->collapsible(),
            ])
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->persistSearchInSession();
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('기본 정보')
                    ->schema([
                        TextEntry::make('name')
                            ->label('권한명')
                            ->copyable(),
                        TextEntry::make('display_name')
                            ->label('표시명'),
                        TextEntry::make('description')
                            ->label('설명')
                            ->placeholder('설명이 없습니다'),
                    ])
                    ->columns(2),
                    
                Section::make('분류 및 설정')
                    ->schema([
                        TextEntry::make('category.display_name')
                            ->label('카테고리')
                            ->placeholder('미분류'),
                        TextEntry::make('guard_name')
                            ->label('Guard')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'web' => 'success',
                                'api' => 'warning',
                                default => 'gray',
                            }),
                        TextEntry::make('is_active')
                            ->label('활성화 상태')
                            ->formatStateUsing(fn (bool $state): string => $state ? '활성화' : '비활성화')
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                    ])
                    ->columns(3),
                    
                Section::make('통계 정보')
                    ->schema([
                        TextEntry::make('users_count')
                            ->label('할당된 사용자 수'),
                        TextEntry::make('roles_count')
                            ->label('포함된 역할 수'),
                        TextEntry::make('created_at')
                            ->label('생성일')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('최종 수정일')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RolesRelationManager::class,
            // UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'view' => Pages\ViewPermission::route('/{record}'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
    
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            '카테고리' => $record->category?->display_name ?? '미분류',
            'Guard' => $record->guard_name,
            '상태' => $record->is_active ? '활성화' : '비활성화',
        ];
    }
}
