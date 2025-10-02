<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class UserResource extends Resource
{
    use HasPermissions;
   // Permission-based access control
   public static function canViewAny(): bool
   {
       return self::userCanAny(['*']) || self::userIsAdmin();
   }

   public static function canCreate(): bool
   {
       return self::userCanAny(['*']) || self::userIsAdmin();
   }

   public static function canEdit($record): bool
   {
       return self::userCanAny(['*']) || self::userIsAdmin();
   }

   public static function canDelete($record): bool
   {
       return self::userCanAny(['*']) || self::userIsAdmin();
   }

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form{
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->unique(ignoreRecord: true)->required(),
                Forms\Components\TextInput::make('password')->password()->required(),
                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required(),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Admin' => 'danger',
                        'Manager' => 'warning',
                        'Accountant' => 'info',
                        'Sales' => 'success',
                        'Support' => 'gray',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('role', 'name'),
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
                    ->visible(fn(): bool => self::userCanAny(['*']) || self::userIsAdmin()),
                Tables\Actions\EditAction::make()
                    ->visible(fn(): bool => self::userCanAny(['*']) || self::userIsAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(): bool => self::userCanAny(['*']) || self::userIsAdmin())
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
