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
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Barryvdh\DomPDF\Facade\Pdf;

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
                Tables\Actions\Action::make('print_sticker')
                    ->label('Print Sticker')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin())
                    ->form([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->default(fn ($record) => $record->customer->name ?? ''),
                        Forms\Components\TextInput::make('sender_number')
                            ->label('Sender Number')
                            ->required(),
                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Customer Phone Number')
                            ->required()
                            ->default(fn ($record) => $record->customer->phone ?? ''),
                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->required(),
                        Forms\Components\Textarea::make('products')
                            ->label('Products')
                            ->required()
                            ->default(fn ($record) => $record->items->map(function($item) {
                                return $item->product->name . ' x' . $item->quantity . ' - $' . number_format($item->subtotal, 2);
                            })->join("\n")),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->required()
                            ->default(fn ($record) => '$' . number_format($record->total_amount, 2)),
                    ])
                    ->action(function (array $data, $record) {
                        return redirect()->route('print.sticker', [
                            'sale_id' => $record->id,
                            'customer_name' => $data['customer_name'],
                            'sender_number' => $data['sender_number'],
                            'customer_phone' => $data['customer_phone'],
                            'location' => $data['location'],
                            'products' => $data['products'],
                            'total_amount' => $data['total_amount'],
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","delete"]) || self::userIsAdmin()),
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->withColumns([
                                    \pxlrbt\FilamentExcel\Columns\Column::make('id')->heading('ID'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('customer.name')->heading('Customer Name'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('user.name')->heading('User'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('reference')->heading('Reference'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('subtotal')->heading('Subtotal'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('discount')->heading('Discount'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('tax')->heading('Tax'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('total_amount')->heading('Total Amount'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('payment_method')->heading('Payment Method'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('status')->heading('Status'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('sale_date')->heading('Sale Date'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('created_at')->heading('Created At'),
                                ])
                        ])
                        ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin()),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->withColumns([
                                \pxlrbt\FilamentExcel\Columns\Column::make('id')->heading('ID'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('customer.name')->heading('Customer Name'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('user.name')->heading('User'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('reference')->heading('Reference'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('subtotal')->heading('Subtotal'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('discount')->heading('Discount'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('tax')->heading('Tax'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('total_amount')->heading('Total Amount'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('payment_method')->heading('Payment Method'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('status')->heading('Status'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('sale_date')->heading('Sale Date'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('created_at')->heading('Created At'),
                            ])
                    ])
                    ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin()),
                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->visible(fn (): bool => self::userCanAny(["manage_users","manage_products","manage_purchases","view_reports","manage_settings","manage_inventory","manage_sales","manage_roles","read"]) || self::userIsAdmin())
                    ->action(function () {
                        $sales = Sale::with(['customer', 'user', 'items.product'])->get();
                        $pdf = Pdf::loadView('exports.sales-pdf', compact('sales'));
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'sales-report-' . now()->format('Y-m-d') . '.pdf');
                    }),
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
