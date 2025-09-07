<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionCategoryResource\Pages;
use App\Models\PermissionCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\BadgeColumn;

class PermissionCategoryResource extends Resource
{
    protected static ?string $model = PermissionCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    
    protected static ?string $navigationLabel = '권한 카테고리';
    
    protected static ?string $modelLabel = '권한 카테고리';
    
    protected static ?string $pluralModelLabel = '권한 카테고리들';
    
    protected static ?string $navigationGroup = '사용자 관리';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('카테고리 정보')
                    ->schema([
                        TextInput::make('name')
                            ->label('카테고리명 (영문)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('예: member_management, project_management')
                            ->helperText('영문 소문자와 언더스코어로 구성된 카테고리 식별자'),
                            
                        TextInput::make('display_name')
                            ->label('표시명 (한글)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('예: 멤버 관리, 프로젝트 관리')
                            ->helperText('사용자에게 보여질 한글 카테고리명'),
                            
                        Textarea::make('description')
                            ->label('설명')
                            ->rows(3)
                            ->placeholder('이 카테고리에 포함되는 권한들의 설명을 입력하세요')
                            ->helperText('카테고리의 상세한 기능 설명'),
                    ]),
                    
                Forms\Components\Section::make('설정')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('정렬 순서')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('작은 숫자일수록 먼저 표시됩니다'),
                            
                        Toggle::make('is_active')
                            ->label('활성화')
                            ->default(true)
                            ->helperText('비활성화된 카테고리는 새로운 권한에서 선택할 수 없습니다'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label('표시명')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                TextColumn::make('name')
                    ->label('카테고리명')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->color('gray'),
                    
                TextColumn::make('permissions_count')
                    ->label('권한 수')
                    ->counts('permissions')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                    
                TextColumn::make('sort_order')
                    ->label('정렬 순서')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                    
                BooleanColumn::make('is_active')
                    ->label('활성화')
                    ->sortable(),
                    
                TextColumn::make('description')
                    ->label('설명')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->placeholder('설명 없음'),
                    
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('활성화 상태')
                    ->placeholder('전체')
                    ->trueLabel('활성화')
                    ->falseLabel('비활성화'),
            ])
            ->actions([
                Tables\Actions\Action::make('viewPermissions')
                    ->label('권한 보기')
                    ->icon('heroicon-s-key')
                    ->color('info')
                    ->url(fn (PermissionCategory $record): string => 
                        '/admin/permissions?tableFilters[category_id][value]=' . $record->id
                    )
                    ->visible(fn (PermissionCategory $record): bool => $record->permissions_count > 0),
                    
                Tables\Actions\EditAction::make()
                    ->label('수정'),
                    
                Tables\Actions\DeleteAction::make()
                    ->label('삭제')
                    ->requiresConfirmation()
                    ->modalHeading('카테고리 삭제 확인')
                    ->modalDescription('이 카테고리를 삭제하면 연결된 권한들은 미분류로 변경됩니다. 정말 삭제하시겠습니까?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('선택 삭제')
                        ->requiresConfirmation()
                        ->modalHeading('카테고리 일괄 삭제 확인')
                        ->modalDescription('선택된 카테고리들을 삭제하면 연결된 권한들은 미분류로 변경됩니다. 정말 삭제하시겠습니까?'),
                        
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissionCategories::route('/'),
            'create' => Pages\CreatePermissionCategory::route('/create'),
            'edit' => Pages\EditPermissionCategory::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}
