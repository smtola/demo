<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Filament\Resources\WarehouseResource\RelationManagers;
use App\Models\Warehouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class WarehouseResource extends Resource
{
    use HasPermissions;

        // Permission-based access control
        public static function canViewAny(): bool
        {
            return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin();
        }
    
        public static function canCreate(): bool
        {
            return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","create"]) || self::userIsAdmin();
        }
    
        public static function canEdit($record): bool
        {
            return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","update"]) || self::userIsAdmin();
        }
    
        public static function canDelete($record): bool
        {
            return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","delete"]) || self::userIsAdmin();
        }

    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Warehouses';
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('location')->required(),
            Forms\Components\Textarea::make('description'),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('location')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_at'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query 
                            ->when($data['created_at'], function($q, $date) {
                                return $q->whereDate('created_at', $date);
                            });
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn (): bool => self::userCanAny(["read", "manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles"]) || self::userIsAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => self::userCanAny(["update","manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles"]) || self::userIsAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","delete"]) || self::userIsAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","delete"]) || self::userIsAdmin()),
                ]),
            ]);
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
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
