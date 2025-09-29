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

class SaleItemResource extends Resource
{
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSaleItems::route('/'),
            'create' => Pages\CreateSaleItem::route('/create'),
            'edit' => Pages\EditSaleItem::route('/{record}/edit'),
        ];
    }
}
