<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Filament\Resources\AuditLogResource\RelationManagers;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\HasPermissions;

class AuditLogResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Audit Logs';
    protected static ?string $navigationGroup = 'Finance & Reports';

        public static function getEloquentQuery(): Builder
        {
            // Eager load 'user' to prevent N+1
            return parent::getEloquentQuery()->with('user');
        }
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
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
                Forms\Components\TextInput::make('action')->required(),
                Forms\Components\TextInput::make('model_type')->required(),
                Forms\Components\TextInput::make('model_id')->numeric()->required(),
                Forms\Components\Textarea::make('old_values'),
                Forms\Components\Textarea::make('new_values'),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table{
        $bulkActions = [];
        if (self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles"]) || self::userIsAdmin()) {
            $bulkActions[] = Tables\Actions\DeleteBulkAction::make()
                ->requiresConfirmation();
        }
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                ->label('User')
                ->badge()
                ->color('info')
                ->default('N/A'),
                
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'login' => 'success',
                        'logout' => 'warning',
                        'created' => 'success',
                        'updated' => 'primary',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('entity_type')->label('Model'),
                Tables\Columns\TextColumn::make('entity_id')->label('Record ID'),
                Tables\Columns\TextColumn::make('performed_at')->dateTime()->sortable(),
            
            ])
            ->filters([
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
            ])
            ->bulkActions(!empty($bulkActions) ? [Tables\Actions\BulkActionGroup::make($bulkActions)] : [])
            ->defaultSort('performed_at', 'desc');
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
            'index' => Pages\ListAuditLogs::route('/'),
            'create' => Pages\CreateAuditLog::route('/create'),
            'edit' => Pages\EditAuditLog::route('/{record}/edit'),
        ];
    }
}
