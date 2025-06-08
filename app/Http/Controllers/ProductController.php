<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Mulai dengan query dasar ke model Product
        $query = Product::query();

        // 2. Cek apakah ada input pencarian
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            // Tambahkan kondisi WHERE untuk memfilter data
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('brand', 'like', '%' . $searchTerm . '%');
            });
        }

        // 3. Ambil hasil query dengan urutan terbaru dan paginasi
        // Ganti paginate(10) jika Anda ingin jumlah item per halaman yang berbeda
        $products = $query->latest()->paginate(10);

        // Kirim data products ke view 'products.index'
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'string|max:255',
            'storage' => 'string|max:255',
            'color' => 'string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // 2. Simpan data ke database
        Product::create($request->all());

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'string|max:255',
            'storage' => 'string|max:255',
            'color' => 'string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // 2. Update data di database
        $product->update($request->all());

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus data produk dari database
        $product->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function showItems(Request $request, Product $product)
    {
        // 1. Mulai query dari relasi productItems(), bukan memuat semuanya sekaligus
        $query = $product->productItems();

        // 2. Cek jika ada request pencarian
        if ($request->has('search') && $request->search != '') {
            // Filter berdasarkan IMEI
            $query->where('imei', 'like', '%' . $request->search . '%');
        }

        // 3. Ambil hasilnya dengan urutan terbaru dan paginasi
        $items = $query->latest()->paginate(15); // Anda bisa ganti angka 15

        return view('products.show-items', compact('product', 'items'));
    }
}
