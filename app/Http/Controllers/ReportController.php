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

    public function profit(Request $request)
    {
        // Logika filter tanggal (sama seperti laporan penjualan)
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()))->endOfDay();

        // Ambil data penjualan, WAJIB dengan data produk untuk mendapatkan harga beli
        $sales = Sale::with('product') // Eager load 'product' is crucial here!
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // --- Lakukan Kalkulasi Total ---

        // 1. Total Omzet (Pendapatan Kotor)
        $totalRevenue = $sales->sum(function ($sale) {
            return $sale->quantity * $sale->price_at_time;
        });

        // 2. Total Modal (berdasarkan harga beli produk saat ini)
        $totalCapital = $sales->sum(function ($sale) {
            // Pastikan produk masih ada untuk menghindari error
            if ($sale->product) {
                return $sale->quantity * $sale->product->purchase_price;
            }
            return 0;
        });

        // 3. Total Laba Bersih
        $totalProfit = $totalRevenue - $totalCapital;

        // Kirim semua data ke view
        return view('reports.profit', compact(
            'sales',
            'totalRevenue',
            'totalCapital',
            'totalProfit',
            'startDate',
            'endDate'
        ));
    }
}
