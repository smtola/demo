<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class ExpenseResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Expenses';
    protected static ?string $navigationGroup = 'Finance & Reports';

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

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form->schema([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('amount')->numeric()->required(),
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
            Forms\Components\DatePicker::make('expense_date')->required(),
            Forms\Components\Textarea::make('note'),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('amount')->money('usd', true),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('expense_date')->date(),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
