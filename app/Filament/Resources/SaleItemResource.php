<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleItemResource\Pages;
use App\Filament\Resources\SaleItemResource\RelationManagers;
use App\Models\SaleItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class SaleItemResource extends Resource
{
    use HasPermissions;

    // Check if user can view the resource
    public static function canViewAny(): bool
    {
        return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin();
    }

    // Check if user can create records
    public static function canCreate(): bool
    {
        return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","create"]) || self::userIsAdmin();
    }

    // Check if user can edit records
    public static function canEdit($record): bool
    {
        return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","update"]) || self::userIsAdmin();
    }

    // Check if user can delete records
    public static function canDelete($record): bool
    {
        return self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","delete"]) || self::userIsAdmin();
    }
    protected static ?string $model = SaleItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Sale Items';
    protected static ?string $navigationGroup = 'Sales & Purchases';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\Select::make('sale_id')->relationship('sale', 'id')->required(),
            Forms\Components\Select::make('product_id')->relationship('product', 'name')->required(),
            Forms\Components\TextInput::make('quantity')->numeric()->required(),
            Forms\Components\TextInput::make('selling_price')->numeric()->required(),
            Forms\Components\TextInput::make('subtotal')->numeric()->required(),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('sale.id'),
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('selling_price')->money('usd', true),
                Tables\Columns\TextColumn::make('subtotal')->money('usd', true),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_at'),
                ])
                ->query(function ($query, array $data) {
                    return $query   
                        ->when($data['created_at'], function($q, $date) {
                            return $q->whereDate('created_at', $date);
                        });
                }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","update"]) || self::userIsAdmin()),
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
            'index' => Pages\ListSaleItems::route('/'),
            'create' => Pages\CreateSaleItem::route('/create'),
            'edit' => Pages\EditSaleItem::route('/{record}/edit'),
        ];
    }
}
