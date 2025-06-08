<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Laba/Rugi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Tampilkan pesan sukses atau error --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Form Filter Tanggal --}}
                    <form method="GET" action="{{ route('reports.profit') }}" class="mb-6">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Mulai</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ $startDate->format('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Selesai</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ $endDate->format('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="pt-6">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Laporan --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Tanggal
                                    </th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Produk
                                    </th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Kasir
                                    </th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Jumlah
                                    </th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Harga
                                        Jual</th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">
                                        Modal/Unit</th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Omzet
                                    </th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Laba</th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                    {{-- Tambahkan class bersyarat untuk membedakan baris retur --}}
                                    <tr
                                        class="{{ $sale->status === 'returned' ? 'bg-gray-100 text-gray-500 italic' : '' }}">
                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                            {{ $sale->created_at->format('d M Y, H:i') }}</td>

                                        {{-- Gunakan data dari productItem jika ada, jika tidak, fallback ke product --}}
                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                            {{ $sale->productItem->product->name ?? ($sale->product->name ?? 'N/A') }}
                                        </td>

                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $sale->user->name }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $sale->quantity }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">Rp
                                            {{ number_format($sale->price_at_time) }}</td>

                                        {{-- Gunakan if untuk menampilkan modal --}}
                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                            @if ($sale->productItem)
                                                Rp {{ number_format($sale->productItem->purchase_price) }}
                                            @elseif($sale->product)
                                                Rp {{ number_format($sale->product->purchase_price) }}<span
                                                    class="text-gray-400">*</span>
                                            @else
                                                Rp 0
                                            @endif
                                        </td>

                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">Rp
                                            {{ number_format($sale->price_at_time * $sale->quantity) }}</td>

                                        {{-- Gunakan if untuk menghitung laba --}}
                                        <td
                                            class="px-5 py-5 border-b border-gray-200 text-sm font-bold {{ $sale->status === 'returned' ? '' : 'text-green-600' }}">
                                            @if ($sale->status === 'returned')
                                                -
                                            @elseif ($sale->productItem)
                                                Rp
                                                {{ number_format($sale->price_at_time - $sale->productItem->purchase_price) }}
                                            @elseif ($sale->product)
                                                Rp
                                                {{ number_format($sale->price_at_time * $sale->quantity - $sale->product->purchase_price * $sale->quantity) }}
                                            @endif
                                        </td>

                                        {{-- Kolom Aksi yang cerdas --}}
                                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                            @if ($sale->status === 'completed' && $sale->productItem)
                                                <form action="{{ route('sales.return', $sale->id) }}" method="POST"
                                                    onsubmit="return confirm('Anda yakin ingin memproses retur untuk transaksi ini? Stok akan dikembalikan.');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-sm text-yellow-600 hover:text-yellow-900">
                                                        Retur
                                                    </button>
                                                </form>
                                            @elseif ($sale->status === 'returned')
                                                <span class="text-sm">Sudah Diretur</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-10">Tidak ada data penjualan pada
                                            rentang tanggal ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="text-right">
                                    <td colspan="8" class="px-5 py-2 border-t-2 font-semibold">Total Omzet
                                        (Pendapatan Kotor)</td>
                                    <td class="px-5 py-2 border-t-2 font-semibold">Rp
                                        {{ number_format($totalRevenue) }}</td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="8" class="px-5 py-2 font-semibold">Total Modal (HPP)</td>
                                    <td class="px-5 py-2 font-semibold">Rp {{ number_format($totalCapital) }}</td>
                                </tr>
                                <tr class="text-right bg-gray-200">
                                    <td colspan="8" class="px-5 py-3 font-bold text-lg">TOTAL LABA BERSIH</td>
                                    <td class="px-5 py-3 font-bold text-lg text-green-700">Rp
                                        {{ number_format($totalProfit) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
