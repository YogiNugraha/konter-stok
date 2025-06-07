<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-600">Pendapatan Hari Ini</h3>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($revenueToday) }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-600">Transaksi Hari Ini</h3>
                    <p class="text-3xl font-bold mt-2">{{ $salesCountToday }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-600">Total Jenis Produk</h3>
                    <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-600">Total Stok Barang</h3>
                    <p class="text-3xl font-bold mt-2">{{ $totalStock }}</p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Produk dengan Stok Hampir Habis (Kurang dari 5)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Nama
                                        Produk</th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Sisa Stok
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lowStockProducts as $product)
                                    <tr>
                                        <td class="px-5 py-5 border-b text-sm">{{ $product->name }}</td>
                                        <td class="px-5 py-5 border-b text-sm font-bold text-red-600">
                                            {{ $product->stock }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5">Tidak ada produk dengan stok kritis.
                                            Bagus!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
