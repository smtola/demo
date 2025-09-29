<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Products';
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('sku')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('author')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('ISBN')->required()->unique(ignoreRecord: true),
            
            Forms\Components\Select::make('category_id')
                ->relationship('category', 'name')
                ->required(),
            Forms\Components\TextInput::make('brand'),
            Forms\Components\FileUpload::make('image_url')
                ->disk('s3')
                ->directory('products')
                ->visibility('public')
                ->image()
                ->imageEditor()
                ->downloadable()
                ->openable()
                ->maxSize(5120)
                ->helperText('Upload product image (max 5MB)'),

            Forms\Components\TextInput::make('unit_price')->numeric()->required(),
            Forms\Components\TextInput::make('cost_price')->numeric(),
            Forms\Components\TextInput::make('quantity_available')
                ->numeric()
                ->required(),
            Forms\Components\Select::make('warehouse_id')
                ->relationship('warehouse', 'name') 
                ->required(),
            Forms\Components\TextInput::make('barcode')
                ->required(),
            Forms\Components\DatePicker::make('expiry_date'),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->disk('s3')
                    ->square()
                    ->size(40),
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label("SKU")
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')->searchable(),
                Tables\Columns\TextColumn::make('ISBN')
                    ->label('ISBN')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('brand'),
                Tables\Columns\TextColumn::make('quantity_available'),   
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
