<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function create()
    {
        // Hanya tampilkan produk yang stoknya > 0
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Pengecekan Stok
        if ($product->stock < $request->quantity) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi! Sisa stok: ' . $product->stock)
                ->withInput(); // withInput() untuk menjaga input user tetap ada
        }

        try {
            DB::transaction(function () use ($request, $product) {
                // 1. Buat catatan penjualan
                Sale::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(), // ID user yang sedang login
                    'quantity' => $request->quantity,
                    'price_at_time' => $product->selling_price, // Ambil harga jual dari produk
                ]);

                // 2. Kurangi stok di tabel produk
                $product->decrement('stock', $request->quantity);
            });

            return redirect()->route('sales.create')
                ->with('success', 'Penjualan berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
