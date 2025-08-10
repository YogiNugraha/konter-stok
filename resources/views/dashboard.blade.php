<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 px-5 md:px-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-100">Pendapatan Hari Ini</h3>
                        <p class="text-3xl font-bold mt-2">Rp {{ number_format($revenueToday) }}</p>
                    </div>
                    <div>
                        {{-- Ikon Uang dari Heroicons --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12 opacity-75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-indigo-500 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-indigo-100">Transaksi Hari Ini</h3>
                        <p class="text-3xl font-bold mt-2">{{ $salesCountToday }}</p>
                    </div>
                    <div>
                        {{-- Ikon Transaksi dari Heroicons --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12 opacity-75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-amber-500 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-amber-100">Total Jenis Produk</h3>
                        <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
                    </div>
                    <div>
                        {{-- Ikon Produk dari Heroicons --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12 opacity-75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-teal-500 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-teal-100">Total Stok Barang</h3>
                        <p class="text-3xl font-bold mt-2">{{ $totalStock }}</p>
                    </div>
                    <div>
                        {{-- Ikon Stok dari Heroicons --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12 opacity-75">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 15.347 16.556 17.25 12 17.25s-8.25-1.903-8.25-4.125V10.125" />
                        </svg>
                    </div>
                </div>

            </div>

            {{-- ===== WADAH UNTUK GRAFIK BARU DIMULAI DI SINI ===== --}}
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {!! $chart->container() !!}
                </div>
            </div>
            {{-- ===== WADAH UNTUK GRAFIK SELESAI DI SINI ===== --}}

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

    {{-- ===== SKRIP UNTUK GRAFIK DIMULAI DI SINI ===== --}}
    <script src="{{ $chart->cdn() }}"></script>
    {!! $chart->script() !!}
    {{-- ===== SKRIP UNTUK GRAFIK SELESAI DI SINI ===== --}}
</x-app-layout>
