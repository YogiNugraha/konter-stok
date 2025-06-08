<?php

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\SupplierController;
use ArielMejiaDev\LarapexCharts\LarapexChart;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // --- Data untuk Stat Cards (ini tetap sama) ---
    $todaySales = Sale::whereDate('created_at', Carbon::today())->where('status', 'completed')->get();
    $revenueToday = $todaySales->sum(function ($sale) {
        return $sale->quantity * $sale->price_at_time;
    });
    $salesCountToday = $todaySales->count();
    $totalProducts = Product::count();
    $totalStock = Product::sum('stock');
    $lowStockProducts = Product::where('stock', '<', 5)->orderBy('stock', 'asc')->get();

    // =====================================================================
    // --- LOGIKA BARU UNTUK MEMBUAT GRAFIK PENJUALAN 7 HARI TERAKHIR ---
    // =====================================================================

    // 1. Ambil data penjualan 7 hari terakhir yang sudah selesai
    $salesData = Sale::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(price_at_time * quantity) as total')
    )
        ->where('status', 'completed')
        ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get()
        ->keyBy('date'); // `keyBy` untuk memudahkan pencarian berdasarkan tanggal

    // 2. Siapkan array untuk label (7 hari terakhir) dan data (total penjualan)
    $labels = [];
    $data = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        $dateString = $date->format('Y-m-d');

        // Tambahkan label tanggal (cth: "08 Jun")
        array_push($labels, $date->format('d M'));

        // Tambahkan data penjualan, jika tidak ada penjualan di hari itu, masukkan 0
        array_push($data, $salesData[$dateString]->total ?? 0);
    }

    // 3. Buat objek chart menggunakan Larapex
    $chart = (new LarapexChart)->barChart()
        ->setTitle('Pendapatan 7 Hari Terakhir')
        ->setSubtitle('Total pendapatan penjualan per hari.')
        ->addData('Pendapatan', $data)
        ->setXAxis($labels);

    // Kirim semua data, TERMASUK $chart, ke view
    return view('dashboard', compact(
        'revenueToday',
        'salesCountToday',
        'totalProducts',
        'totalStock',
        'lowStockProducts',
        'chart' // <-- KIRIM OBJEK CHART
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute yang bisa diakses semua user yang sudah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Penjualan
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
});

// Rute yang bisa diakses oleh role 'manage-inventory'
Route::middleware(['auth', 'verified', 'can:manage-inventory'])->group(
    function () {
        // Route Product
        Route::resource('products', ProductController::class);
        Route::get('/products/{product}/items', [ProductController::class, 'showItems'])->name('products.showItems');
        //Route Suppliers
        Route::resource('suppliers', SupplierController::class);
        // Rute untuk Stok Masuk
        Route::get('/stock-in/create', [StockInController::class, 'create'])->name('stock-in.create');
        Route::post('/stock-in', [StockInController::class, 'store'])->name('stock-in.store');
    }
);

// Rute yang HANYA bisa diakses oleh ADMIN
Route::middleware(['auth', 'verified', 'is-admin'])->group(function () {

    // Rute untuk Laporan
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit'); // <-- TAMBAHKAN INI

    Route::post('/sales/{sale}/return', [SaleController::class, 'processReturn'])->name('sales.return');

    // export excel
    Route::get('/reports/sales/export-excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.exportExcel');
});

Route::get('/api/products/{product}/available-imeis', function (Product $product) {
    $imeis = ProductItem::where('product_id', $product->id)
        ->where('status', 'in_stock')
        ->pluck('imei');

    return response()->json($imeis);
})->middleware('auth');

require __DIR__ . '/auth.php';
