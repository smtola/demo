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
use App\Traits\HasPermissions;

class SaleResource extends Resource
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
            Forms\Components\Select::make('payment_method'),
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
                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference/Transection'),
                Tables\Columns\TextColumn::make('subtotal')->money('usd', true),
                Tables\Columns\TextColumn::make('discount')->money('usd', true),
                Tables\Columns\TextColumn::make('tax')->money('usd', true),
                Tables\Columns\TextColumn::make('total_amount')->money('usd', true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'info',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('sale_date')->date(),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name'),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
