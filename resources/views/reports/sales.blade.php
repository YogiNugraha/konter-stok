<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan') }}
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
                    <form method="GET" action="{{ route('reports.sales') }}" class="mb-6">
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
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Kasir
                                    </th>
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Harga
                                        Satuan</th>
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                    <tr
                                        class="{{ $sale->status === 'returned' ? 'bg-red-100 text-red-500 italic' : '' }}">
                                        <td class="px-5 py-5 border-b text-sm">
                                            {{ $sale->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-5 py-5 border-b text-sm">{{ $sale->product->name }}</td>
                                        <td class="px-5 py-5 border-b text-sm">{{ $sale->user->name }}</td>
                                        <td class="px-5 py-5 border-b text-sm">{{ $sale->quantity }}</td>
                                        <td class="px-5 py-5 border-b text-sm">Rp
                                            {{ number_format($sale->price_at_time) }}</td>
                                        <td class="px-5 py-5 border-b text-sm">Rp
                                            {{ number_format($sale->quantity * $sale->price_at_time) }}</td>
                                        <td class="px-5 py-5 border-b text-sm">
                                            {{-- Tombol Retur hanya muncul jika statusnya 'completed' & ini data baru --}}
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
                                                <span class="text-sm text-gray-500 italic">Sudah Diretur</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10">Tidak ada data penjualan pada
                                            rentang tanggal ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="px-5 py-5 border-t-2 text-right font-bold text-lg">TOTAL
                                        OMZET</td>
                                    <td class="px-5 py-5 border-t-2 font-bold text-lg">Rp
                                        {{ number_format($totalRevenue) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
