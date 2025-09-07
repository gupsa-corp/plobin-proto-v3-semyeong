<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    
    protected static ?string $navigationGroup = '권한 관리';
    
    protected static ?string $modelLabel = '역할';
    
    protected static ?string $pluralModelLabel = '역할';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('기본 정보')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('역할명')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('영문으로 입력 (예: organization_admin)'),
                            
                        Forms\Components\TextInput::make('guard_name')
                            ->label('가드명')
                            ->default('web')
                            ->required()
                            ->maxLength(255)
                            ->helperText('기본값: web'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('권한 할당')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('권한')
                            ->relationship('permissions', 'name')
                            ->options(Permission::all()->pluck('name', 'id'))
                            ->descriptions(
                                Permission::all()->mapWithKeys(function ($permission) {
                                    return [$permission->id => $this->getPermissionDescription($permission->name)];
                                })->toArray()
                            )
                            ->columns(3)
                            ->searchable()
                            ->bulkToggleable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('역할명')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('guard_name')
                    ->label('가드')
                    ->badge()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('권한 수')
                    ->counts('permissions')
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('users_count')
                    ->label('사용자 수')
                    ->counts('users')
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('생성일')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->label('가드')
                    ->options([
                        'web' => 'Web',
                        'api' => 'API',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
