<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\Sale;
use App\Models\User;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key untuk mengosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel-tabel transaksi untuk memulai dari awal
        Sale::truncate();
        ProductItem::truncate();
        Product::truncate();
        // Anda bisa tambahkan tabel lain jika perlu dikosongkan

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- Mulai Buat Data Baru ---

        // 1. Pastikan ada user (admin) untuk jadi kasir
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            $user = User::factory()->create(['role' => 'admin']);
        }

        // 2. Buat 15 produk palsu menggunakan Factory
        $products = Product::factory(15)->create();

        // 3. Isi stok (ProductItems) untuk setiap produk
        $allProductItems = collect();
        foreach ($products as $product) {
            $stockCount = rand(10, 50); // Setiap produk akan punya 50-200 unit
            for ($i = 0; $i < $stockCount; $i++) {
                $item = ProductItem::create([
                    'product_id' => $product->id,
                    'imei' => fake()->unique()->numerify('####################'),
                    'purchase_price' => $product->purchase_price,
                    'status' => 'in_stock'
                ]);
                $allProductItems->push($item);
            }
            // Update jumlah stok di tabel produk
            $product->update(['stock' => $stockCount]);
        }

        // 4. Buat 200 transaksi penjualan acak dalam 7 hari terakhir
        for ($i = 0; $i < 50; $i++) {
            // Ambil satu item acak yang masih tersedia
            $itemToSell = $allProductItems->where('status', 'in_stock')->random();

            if ($itemToSell) {
                // Tentukan tanggal penjualan acak dalam 7 hari terakhir
                $saleDate = fake()->dateTimeBetween('-30 days', 'now');

                // Buat data penjualan
                $sale = Sale::create([
                    'product_id' => $itemToSell->product_id,
                    'user_id' => $user->id,
                    'quantity' => 1,
                    'price_at_time' => $itemToSell->product->selling_price,
                    'status' => 'completed',
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ]);

                // Update status item menjadi 'sold'
                $itemToSell->update([
                    'status' => 'sold',
                    'sale_id' => $sale->id,
                ]);

                // Kurangi stok di produk utama
                $itemToSell->product->decrement('stock');
            }
        }
    }
}
