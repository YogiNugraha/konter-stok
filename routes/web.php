<?php

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\SupplierController;
use ArielMejiaDev\LarapexCharts\LarapexChart;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
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

    // 1. Ambil rentang waktu dari URL, default-nya 7 hari
    $range = $request->input('range', 7);
    $labels = [];
    $data = [];
    $dateFormat = '';
    $dateGroupFormat = '';
    $subtitle = 'Total pendapatan penjualan per hari.';

    if ($range == 365) {
        // Logika untuk 1 TAHUN (dikelompokkan per bulan)
        $dateGroupFormat = '%Y-%m';
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $date = $startDate->copy()->addMonths($i);
            $labels[] = $date->format('M Y');
        }
        $subtitle = 'Total pendapatan penjualan per bulan.';
    } else {
        // Logika untuk 7 atau 30 HARI (dikelompokkan per hari)
        $dateGroupFormat = '%Y-%m-%d';
        $startDate = Carbon::now()->subDays($range - 1);
        for ($i = 0; $i < $range; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('d M');
        }
    }

    // 2. Ambil data penjualan sesuai rentang waktu & pengelompokan
    $salesData = Sale::select(
        DB::raw("DATE_FORMAT(created_at, '$dateGroupFormat') as date_group"),
        DB::raw('SUM(price_at_time * quantity) as total')
    )
        ->where('status', 'completed')
        ->where('created_at', '>=', $startDate->startOfDay())
        ->groupBy('date_group')
        ->get()
        ->keyBy('date_group');

    // 3. Siapkan data untuk grafik
    foreach ($labels as $label) {
        $dateKey = '';
        if ($range == 365) {
            // Konversi label "Aug 2025" menjadi "2025-08" untuk dicocokkan
            $dateKey = Carbon::createFromFormat('M Y', $label)->format('Y-m');
        } else {
            // Konversi label "10 Aug" menjadi "2025-08-10"
            $dateKey = Carbon::createFromFormat('d M', $label)->format('Y-m-d');
        }
        $data[] = $salesData[$dateKey]->total ?? 0;
    }

    // 3. Buat objek chart menggunakan Larapex
    // 4. Buat objek chart dengan judul dinamis
    $chart = (new LarapexChart)->barChart()
        ->setTitle('Pendapatan ' . ($range == 365 ? '1 Tahun' : $range . ' Hari') . ' Terakhir')
        ->setSubtitle($subtitle)
        ->addData('Pendapatan', $data)
        ->setXAxis($labels);
    // ->setColors(['#00aeef'])
    // // ... sisa konfigurasi chart lainnya tetap sama ...
    // ->setStroke(2)
    // ->setDataLabels(true)
    // ->setMarkers(['#FFFFFF'], 5, 8);
    // Anda bisa tambahkan kembali setOption yaxis & annotations jika mau

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
        Route::delete('/products', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');
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

    Route::resource('users', UserController::class);
});

Route::get('/api/products/{product}/available-imeis', function (Product $product) {
    $imeis = ProductItem::where('product_id', $product->id)
        ->where('status', 'in_stock')
        ->pluck('imei');

    return response()->json($imeis);
})->middleware('auth');

require __DIR__ . '/auth.php';
