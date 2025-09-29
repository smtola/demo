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

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Audit Logs';
    protected static ?string $navigationGroup = 'Finance & Reports';

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
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('action'),
                Tables\Columns\TextColumn::make('model_type'),
                Tables\Columns\TextColumn::make('model_id'),
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
            'index' => Pages\ListAuditLogs::route('/'),
            'create' => Pages\CreateAuditLog::route('/create'),
            'edit' => Pages\EditAuditLog::route('/{record}/edit'),
        ];
    }
}
