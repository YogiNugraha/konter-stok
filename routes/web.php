<?php

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // --- Data untuk Stat Cards ---

    // 1. Ambil data penjualan HARI INI
    $todaySales = Sale::whereDate('created_at', Carbon::today())->get();

    // 2. Hitung total pendapatan hari ini
    $revenueToday = $todaySales->sum(function ($sale) {
        return $sale->quantity * $sale->price_at_time;
    });

    // 3. Hitung jumlah transaksi hari ini
    $salesCountToday = $todaySales->count();

    // 4. Hitung total jenis produk
    $totalProducts = Product::count();

    // 5. Hitung total keseluruhan stok dari semua produk
    $totalStock = Product::sum('stock');

    // --- Data untuk Tabel Stok Hampir Habis ---
    $lowStockProducts = Product::where('stock', '<', 5)->orderBy('stock', 'asc')->get();

    // Kirim semua data ke view
    return view('dashboard', compact(
        'revenueToday',
        'salesCountToday',
        'totalProducts',
        'totalStock',
        'lowStockProducts'
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

// Rute yang HANYA bisa diakses oleh ADMIN
Route::middleware(['auth', 'verified', 'is-admin'])->group(function () {
    // Route Product
    Route::resource('products', ProductController::class);
    //Route Suppliers
    Route::resource('suppliers', SupplierController::class);
    // Rute untuk Stok Masuk
    Route::get('/stock-in/create', [StockInController::class, 'create'])->name('stock-in.create');
    Route::post('/stock-in', [StockInController::class, 'store'])->name('stock-in.store');

    // Rute untuk Laporan
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit'); // <-- TAMBAHKAN INI

    Route::post('/sales/{sale}/return', [SaleController::class, 'processReturn'])->name('sales.return');
});

Route::get('/api/products/{product}/available-imeis', function (Product $product) {
    $imeis = ProductItem::where('product_id', $product->id)
        ->where('status', 'in_stock')
        ->pluck('imei');

    return response()->json($imeis);
})->middleware('auth');

require __DIR__ . '/auth.php';
