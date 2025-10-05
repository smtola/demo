<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Customer Name',
            'User',
            'Reference',
            'Subtotal',
            'Discount',
            'Tax',
            'Total Amount',
            'Payment Method',
            'Status',
            'Sale Date',
            'Created At',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->customer->name ?? 'N/A',
            $sale->user->name ?? 'N/A',
            $sale->reference ?? 'N/A',
            number_format($sale->subtotal ?? 0, 2),
            number_format($sale->discount ?? 0, 2),
            number_format($sale->tax ?? 0, 2),
            number_format($sale->total_amount, 2),
            $sale->payment_method ?? 'N/A',
            ucfirst($sale->status),
            $sale->sale_date->format('Y-m-d'),
            $sale->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 20,
            'C' => 15,
            'D' => 15,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 15,
            'I' => 15,
            'J' => 12,
            'K' => 12,
            'L' => 20,
        ];
    }
}
