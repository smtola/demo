<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierProductResource\Pages;
use App\Filament\Resources\SupplierProductResource\RelationManagers;
use App\Models\SupplierProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierProductResource extends Resource
{
    protected static ?string $model = SupplierProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationLabel = 'Supplier Products';
    protected static ?string $navigationGroup = 'Business Partners';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\Select::make('supplier_id')
                ->relationship('supplier', 'name')
                ->required(),
            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name')
                ->required(),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')->searchable(),
                Tables\Columns\TextColumn::make('product.name')->searchable(),
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
            'index' => Pages\ListSupplierProducts::route('/'),
            'create' => Pages\CreateSupplierProduct::route('/create'),
            'edit' => Pages\EditSupplierProduct::route('/{record}/edit'),
        ];
    }
}
