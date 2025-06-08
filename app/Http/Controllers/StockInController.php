<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Supplier;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('stock_ins.create', compact('products', 'suppliers'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'supplier_id' => 'required|exists:suppliers,id',
    //         'quantity' => 'required|integer|min:1',
    //         'date' => 'required|date',
    //     ]);

    //     try {
    //         DB::transaction(function () use ($request) {
    //             // 1. Buat catatan stok masuk
    //             StockIn::create([
    //                 'product_id' => $request->product_id,
    //                 'supplier_id' => $request->supplier_id,
    //                 'quantity' => $request->quantity,
    //                 'date' => $request->date,
    //             ]);

    //             // 2. Tambah stok di tabel produk
    //             $product = Product::find($request->product_id);
    //             $product->increment('stock', $request->quantity);
    //         });

    //         return redirect()->route('products.index')
    //             ->with('success', 'Stok berhasil ditambahkan!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'supplier_id' => 'required|exists:suppliers,id',
    //         'imeis' => 'required|string', // Validasi dasar untuk textarea
    //         'date' => 'required|date',
    //     ]);

    //     // Pecah textarea menjadi array, hapus baris kosong dan duplikat
    //     $imeis = array_filter(array_unique(explode("\n", str_replace("\r", "", $request->imeis))));

    //     if (empty($imeis)) {
    //         return redirect()->back()->with('error', 'Daftar IMEI tidak boleh kosong.')->withInput();
    //     }

    //     try {
    //         DB::transaction(function () use ($request, $imeis) {
    //             $quantity = count($imeis); // Jumlah stok masuk adalah sebanyak jumlah IMEI

    //             // Buat catatan di tabel stock_ins (seperti biasa)
    //             StockIn::create([
    //                 'product_id' => $request->product_id,
    //                 'supplier_id' => $request->supplier_id,
    //                 'quantity' => $quantity, // Simpan jumlahnya
    //                 'date' => $request->date,
    //             ]);

    //             // Buat setiap item produk dengan IMEI-nya
    //             foreach ($imeis as $imei) {
    //                 ProductItem::create([
    //                     'product_id' => $request->product_id,
    //                     'imei' => trim($imei),
    //                 ]);
    //             }

    //             // Update (increment) stok di tabel products
    //             $product = Product::find($request->product_id);
    //             $product->increment('stock', $quantity);
    //         });

    //         return redirect()->route('products.index')
    //             ->with('success', count($imeis) . ' unit produk berhasil ditambahkan!');
    //     } catch (\Exception $e) {
    //         // Cek jika error karena duplikat IMEI
    //         if (str_contains($e->getMessage(), 'Duplicate entry')) {
    //             return redirect()->back()->with('error', 'Terjadi kesalahan: Salah satu IMEI yang Anda masukkan sudah ada di database.')->withInput();
    //         }
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|integer|min:0', // <-- Validasi baru
            'imeis' => 'required|string',
            'date' => 'required|date',
        ]);

        $imeis = array_filter(array_unique(explode("\n", str_replace("\r", "", $request->imeis))));

        if (empty($imeis)) {
            return redirect()->back()->with('error', 'Daftar IMEI tidak boleh kosong.')->withInput();
        }

        try {
            DB::transaction(function () use ($request, $imeis) {
                $quantity = count($imeis);

                StockIn::create([
                    'product_id' => $request->product_id,
                    'supplier_id' => $request->supplier_id,
                    'quantity' => $quantity,
                    'date' => $request->date,
                ]);

                // Buat setiap item produk DENGAN HARGA BELINYA
                foreach ($imeis as $imei) {
                    ProductItem::create([
                        'product_id' => $request->product_id,
                        'imei' => trim($imei),
                        'purchase_price' => $request->purchase_price, // <-- Simpan harga beli
                    ]);
                }

                $product = Product::find($request->product_id);
                $product->increment('stock', $quantity);

                // (Opsional) Update harga beli standar di tabel produk ke harga terbaru
                $product->update(['purchase_price' => $request->purchase_price]);
            });

            return redirect()->route('products.index')
                ->with('success', count($imeis) . ' unit produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->back()->with('error', 'Terjadi kesalahan: Salah satu IMEI yang Anda masukkan sudah ada di database.')->withInput();
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }
}
