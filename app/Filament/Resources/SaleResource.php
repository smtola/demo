<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Sales';
    protected static ?string $navigationGroup = 'Sales & Purchases';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\Select::make('customer_id')->relationship('customer', 'name')->required(),
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
            Forms\Components\TextInput::make('reference'),
            Forms\Components\TextInput::make('subtotal'),
            Forms\Components\TextInput::make('discount'),
            Forms\Components\TextInput::make('tax'),
            Forms\Components\TextInput::make('total_amount')->numeric()->required(),
            Forms\Components\Select::make('status')
                ->options(['pending' => 'pending', 'paid' => 'paid', 'failed' => 'failed'])
                ->required(),
            Forms\Components\DatePicker::make('sale_date')->required(),
            Forms\Components\Textarea::make('customer_info'),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('customer.name'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('reference'),
                Tables\Columns\TextColumn::make('subtotal')->money('usd', true),
                Tables\Columns\TextColumn::make('discount')->money('usd', true),
                Tables\Columns\TextColumn::make('tax')->money('usd', true),
                Tables\Columns\TextColumn::make('total_amount')->money('usd', true),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('sale_date')->date(),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
