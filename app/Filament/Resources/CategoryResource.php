<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class CategoryResource extends Resource
{
    use HasPermissions;
    // Permission-based access control
    public static function canViewAny(): bool
    {
        return self::userCanAny(["manage_products","manage_sales","manage_purchases","manage_inventory","view_reports","read"]) || self::userIsAdmin();
    }
 
    public static function canCreate(): bool
    {
        return self::userCanAny(["manage_products","manage_sales","manage_purchases","manage_inventory","view_reports","create"]) || self::userIsAdmin();
    }
 
    public static function canEdit($record): bool
    {
        return self::userCanAny(["manage_products","manage_sales","manage_purchases","manage_inventory","view_reports","update"]) || self::userIsAdmin();
    }
 
    public static function canDelete($record): bool
    {
        return self::userCanAny(["manage_products","manage_sales","manage_purchases","manage_inventory","view_reports","delete"]) || self::userIsAdmin();
    }

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('description'),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description'),
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
            ->defaultSort('name', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn(): bool => self::userCanAny(["manage_products","manage_sales","manage_purchases","manage_inventory","view_reports","read"]) || self::userIsAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(fn(): bool => self::userCanAny(["manage_products","manage_sales","manage_purchases","manage_inventory","view_reports","update"]) || self::userIsAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(): bool => self::userCanAny(["manage_products","manage_sales","manage_purchases","manage_inventory","view_reports","delete"]) || self::userIsAdmin())
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
