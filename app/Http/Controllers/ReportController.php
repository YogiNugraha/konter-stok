<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()))->endOfDay();

        // 1. Ambil SEMUA penjualan dalam rentang tanggal
        $sales = Sale::with(['user', 'productItem.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Filter HANYA untuk perhitungan
        $completedSales = $sales->where('status', 'completed');
        $totalRevenue = $completedSales->sum(function ($sale) {
            return $sale->quantity * $sale->price_at_time;
        });

        // 3. Kirim SEMUA data ($sales) untuk ditampilkan & data TOTAL yang sudah benar
        return view('reports.sales', compact('sales', 'totalRevenue', 'startDate', 'endDate'));
    }


    public function profit(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()))->endOfDay();

        // 1. Ambil SEMUA penjualan dalam rentang tanggal
        $sales = Sale::with(['user', 'productItem.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Filter HANYA untuk perhitungan
        $completedSales = $sales->where('status', 'completed');

        // 3. Lakukan SEMUA kalkulasi menggunakan $completedSales
        $totalRevenue = $completedSales->sum('price_at_time');

        $totalCapital = $completedSales->sum(function ($sale) {
            if ($sale->productItem) {
                return $sale->productItem->purchase_price;
            } elseif ($sale->product) {
                return $sale->quantity * $sale->product->purchase_price;
            }
            return 0;
        });

        $totalProfit = $totalRevenue - $totalCapital;

        // 4. Kirim SEMUA data ($sales) untuk ditampilkan & data TOTAL yang sudah benar
        return view('reports.profit', compact(
            'sales',
            'totalRevenue',
            'totalCapital',
            'totalProfit',
            'startDate',
            'endDate'
        ));
    }

    public function exportSalesExcel(Request $request)
    {
        // Ambil filter tanggal dari URL, sama seperti di laporan biasa
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()))->endOfDay();

        // Siapkan nama file
        $fileName = 'laporan-penjualan-' . $startDate->format('d-m-Y') . '-' . $endDate->format('d-m-Y') . '.xlsx';

        // Panggil class SalesExport dan trigger download
        return Excel::download(new SalesExport($startDate, $endDate), $fileName);
    }
}
