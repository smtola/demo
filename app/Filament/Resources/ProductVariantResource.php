<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariantResource\Pages;
use App\Filament\Resources\ProductVariantResource\RelationManagers;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class ProductVariantResource extends Resource
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
    protected static ?string $model = ProductVariant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Product Variants';
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name')
                ->required(),
            Forms\Components\TextInput::make('variant_name')->required(),
            Forms\Components\TextInput::make('variant_value')->required(),
            Forms\Components\TextInput::make('price_adjustment')->numeric(),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('product.name')->searchable(),
                Tables\Columns\TextColumn::make('variant_name')->searchable(),
                Tables\Columns\TextColumn::make('variant_value')->searchable(),
                Tables\Columns\TextColumn::make('price_adjustment'),
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
                    })
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn(): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(fn(): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","update"]) || self::userIsAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","delete"]) || self::userIsAdmin())
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
            'index' => Pages\ListProductVariants::route('/'),
            'create' => Pages\CreateProductVariant::route('/create'),
            'edit' => Pages\EditProductVariant::route('/{record}/edit'),
        ];
    }
}
