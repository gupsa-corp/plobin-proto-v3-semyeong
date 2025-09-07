<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DynamicPermissionRuleResource\Pages;
use App\Models\DynamicPermissionRule;
use App\Models\PermissionCategory;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DynamicPermissionRuleResource extends Resource
{
    protected static ?string $model = DynamicPermissionRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationGroup = '권한 관리';
    
    protected static ?string $modelLabel = '동적 권한 규칙';
    
    protected static ?string $pluralModelLabel = '동적 권한 규칙';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('기본 규칙 정보')
                    ->schema([
                        Forms\Components\Select::make('resource_type')
                            ->label('리소스 타입')
                            ->options(PermissionCategory::active()->pluck('display_name', 'name'))
                            ->required()
                            ->searchable()
                            ->helperText('권한을 적용할 리소스 타입을 선택하세요'),
                            
                        Forms\Components\TextInput::make('action')
                            ->label('액션')
                            ->required()
                            ->maxLength(255)
                            ->helperText('view, create, edit, delete, invite 등'),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('설명')
                            ->maxLength(500)
                            ->helperText('이 규칙이 무엇을 하는지 설명하세요'),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('활성화')
                            ->default(true),
                    ])->columns(2),
                    
                Forms\Components\Section::make('권한 요구사항')
                    ->schema([
                        Forms\Components\CheckboxList::make('required_permissions')
                            ->label('필수 권한')
                            ->options(Permission::all()->pluck('name', 'name'))
                            ->descriptions(
                                Permission::all()->mapWithKeys(function ($permission) {
                                    return [$permission->name => self::getPermissionDescription($permission->name)];
                                })->toArray()
                            )
                            ->columns(3)
                            ->searchable()
                            ->helperText('사용자가 가져야 할 권한들을 선택하세요'),
                            
                        Forms\Components\CheckboxList::make('required_roles')
                            ->label('필수 역할')
                            ->options(Role::all()->pluck('name', 'name'))
                            ->columns(3)
                            ->searchable()
                            ->helperText('사용자가 가져야 할 역할들을 선택하세요'),
                    ]),
                    
                Forms\Components\Section::make('고급 설정')
                    ->schema([
                        Forms\Components\TextInput::make('minimum_role_level')
                            ->label('최소 역할 레벨')
                            ->numeric()
                            ->helperText('기존 시스템과의 호환성을 위한 레벨 (0-550)'),
                            
                        Forms\Components\Textarea::make('custom_logic')
                            ->label('커스텀 로직 (JSON)')
                            ->rows(5)
                            ->helperText('JSON 형태로 복잡한 조건을 설정할 수 있습니다')
                            ->placeholder('{"and": [{"type": "user_id", "value": 123}, {"type": "organization_owner", "value": true}]}'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('resource_type')
                    ->label('리소스')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($state) => PermissionCategory::where('name', $state)->first()?->display_name ?? $state)
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('action')
                    ->label('액션')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('설명')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),
                    
                Tables\Columns\TagsColumn::make('required_permissions')
                    ->label('필수 권한')
                    ->separator(',')
                    ->limit(3),
                    
                Tables\Columns\TagsColumn::make('required_roles')
                    ->label('필수 역할')
                    ->separator(',')
                    ->limit(3),
                    
                Tables\Columns\TextColumn::make('minimum_role_level')
                    ->label('최소 레벨')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => $state ? "Level {$state}" : '-')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('활성화')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('생성일')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('resource_type')
                    ->label('리소스 타입')
                    ->options(PermissionCategory::active()->pluck('display_name', 'name')),
                    
                Tables\Filters\SelectFilter::make('action')
                    ->label('액션')
                    ->options([
                        'view' => 'View',
                        'create' => 'Create',
                        'edit' => 'Edit',
                        'delete' => 'Delete',
                        'invite' => 'Invite',
                        'manage' => 'Manage',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('활성화 상태'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('resource_type');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDynamicPermissionRules::route('/'),
            'create' => Pages\CreateDynamicPermissionRule::route('/create'),
            'edit' => Pages\EditDynamicPermissionRule::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    private static function getPermissionDescription(string $permissionName): string
    {
        $descriptions = [
            // 회원 관리
            'view members' => '회원 목록 조회',
            'invite members' => '회원 초대',
            'edit members' => '회원 정보 수정', 
            'delete members' => '회원 삭제',
            'manage member permissions' => '회원 권한 관리',
            
            // 프로젝트 관리
            'view projects' => '프로젝트 조회',
            'create projects' => '프로젝트 생성',
            'edit projects' => '프로젝트 수정',
            'delete projects' => '프로젝트 삭제',
            'assign project members' => '프로젝트 멤버 배정',
            
            // 결제 관리
            'view billing' => '결제 정보 조회',
            'edit billing' => '결제 정보 수정',
            'download receipts' => '영수증 다운로드',
            'change subscription plan' => '구독 플랜 변경',
            
            // 조직 설정
            'view organization settings' => '조직 설정 조회',
            'edit organization settings' => '조직 설정 수정',
            'delete organization' => '조직 삭제',
            
            // 권한 관리
            'view permissions' => '권한 조회',
            'create permissions' => '권한 생성',
            'edit permissions' => '권한 수정',
            'delete permissions' => '권한 삭제',
            'assign roles' => '역할 할당',
            
            // 시스템 관리
            'access admin panel' => '관리자 패널 접근',
            'manage system settings' => '시스템 설정 관리',
        ];

        return $descriptions[$permissionName] ?? $permissionName;
    }
}
