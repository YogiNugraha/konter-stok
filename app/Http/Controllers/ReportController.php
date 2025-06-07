<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        // Tetapkan tanggal default: awal bulan ini sampai hari ini
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()))->endOfDay();

        // Ambil data penjualan berdasarkan rentang tanggal
        $sales = Sale::with(['product', 'user']) // Eager Loading untuk performa
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total pendapatan dari data yang difilter
        $totalRevenue = $sales->sum(function ($sale) {
            return $sale->quantity * $sale->price_at_time;
        });

        return view('reports.sales', compact('sales', 'totalRevenue', 'startDate', 'endDate'));
    }
}
