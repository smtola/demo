<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class RoleResource extends Resource
{
    use HasPermissions;
    // Permission-based access control
    public static function canViewAny(): bool
    {
        return self::userCanAny(['*']) || self::userIsAdmin();
    }
 
    public static function canCreate(): bool
    {
        return self::userCanAny(['*']) || self::userIsAdmin();
    }
 
    public static function canEdit($record): bool
    {
        return self::userCanAny(['*']) || self::userIsAdmin();
    }
 
    public static function canDelete($record): bool
    {
        return self::userCanAny(['*']) || self::userIsAdmin();
    }
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            
            Forms\Components\Textarea::make('description')
                ->maxLength(500)
                ->columnSpanFull(),
            
            Forms\Components\CheckboxList::make('permissions')
                ->label('Permissions')
                ->options([
                    'read' => 'Read',
                    'create' => 'Add/Create',
                    'update' => 'Edit/Update',
                    'delete' => 'Delete',
                    'manage_users' => 'Manage Users',
                    'manage_roles' => 'Manage Roles',
                    'manage_products' => 'Manage Products',
                    'manage_sales' => 'Manage Sales',
                    'manage_purchases' => 'Manage Purchases',
                    'manage_inventory' => 'Manage Inventory',
                    'view_reports' => 'View Reports',
                    'manage_settings' => 'Manage Settings',
                ])
                ->columns(2)
                ->gridDirection('row')
                ->descriptions([
                    'read' => 'View records and data',
                    'create' => 'Add new records',
                    'update' => 'Edit existing records',
                    'delete' => 'Remove records',
                    'manage_users' => 'Full user management access',
                    'manage_roles' => 'Role and permission management',
                    'manage_products' => 'Product catalog management',
                    'manage_sales' => 'Sales and orders management',
                    'manage_purchases' => 'Purchase and supplier management',
                    'manage_inventory' => 'Stock and warehouse management',
                    'view_reports' => 'Access to reports and analytics',
                    'manage_settings' => 'System configuration access',
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Admin' => 'danger',
                        'Manager' => 'warning',
                        'Accountant' => 'info',
                        'Sales' => 'success',
                        'Support' => 'gray',
                        default => 'primary',
                    }),
                    
                    Tables\Columns\TextColumn::make('permissions')
                    ->label('Permissions')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return 'No permissions';
                        }
                
                        $permissionLabels = [
                            '*' => 'All Permissions',
                            'read' => 'Read',
                            'create' => 'Add',
                            'update' => 'Edit',
                            'delete' => 'Delete',
                            'manage_users' => 'Users',
                            'manage_roles' => 'Roles',
                            'manage_products' => 'Products',
                            'manage_sales' => 'Sales',
                            'manage_purchases' => 'Purchases',
                            'manage_inventory' => 'Inventory',
                            'view_reports' => 'Reports',
                            'manage_settings' => 'Settings',
                        ];
                
                        return collect($state)
                            ->map(fn ($p) => $permissionLabels[$p] ?? $p)
                            ->implode(', ');
                    })
                    ->wrap()
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn ($state) => collect($state)->implode(', '))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
                    
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('name')
                    ->options([
                        'Admin' => 'Admin',
                        'Manager' => 'Manager',
                        'Accountant' => 'Accountant',
                        'Sales' => 'Sales',
                        'Support' => 'Support',
                    ])
                    ->multiple(),
            ])
           ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn(): bool => self::userCanAny(['*']) || self::userIsAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(fn(): bool => self::userCanAny(['*']) || self::userIsAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(): bool => self::userCanAny(['*']) || self::userIsAdmin())
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
