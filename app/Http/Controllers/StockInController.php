<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Supplier;
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

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Buat catatan stok masuk
                StockIn::create([
                    'product_id' => $request->product_id,
                    'supplier_id' => $request->supplier_id,
                    'quantity' => $request->quantity,
                    'date' => $request->date,
                ]);

                // 2. Tambah stok di tabel produk
                $product = Product::find($request->product_id);
                $product->increment('stock', $request->quantity);
            });

            return redirect()->route('products.index')
                ->with('success', 'Stok berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }
}
