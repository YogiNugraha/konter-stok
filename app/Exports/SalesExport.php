<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        // Query ini sama persis dengan yang ada di ReportController
        return Sale::query()
            ->with(['user', 'productItem.product']) // Eager load relasi
            ->where('status', 'completed')
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);
    }

    public function headings(): array
    {
        // Ini adalah baris header di file Excel
        return [
            'Tanggal',
            'Nama Produk',
            'IMEI',
            'Kasir',
            'Harga Jual',
            'Harga Modal',
            'Laba',
        ];
    }

    public function map($sale): array
    {
        // Ini mengatur bagaimana setiap baris data akan ditampilkan
        $laba = $sale->productItem ? ($sale->price_at_time - $sale->productItem->purchase_price) : 0;

        return [
            $sale->created_at->format('d-m-Y H:i:s'),
            $sale->productItem->product->name ?? $sale->product->name ?? 'N/A',
            $sale->productItem->imei ?? 'N/A',
            $sale->user->name,
            $sale->price_at_time,
            $sale->productItem->purchase_price ?? 0,
            $laba,
        ];
    }
}
