<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Stok untuk: {{ $product->name }}
                </h2>
                <p class="text-sm text-gray-500">Total Stok Saat Ini: {{ $product->stock }} Unit</p>
            </div>
            <a href="{{ route('products.index') }}"
                class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm">
                &larr; Kembali ke Daftar Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- ===== FORM PENCARIAN DIMULAI DI SINI ===== --}}
                    <div class="mb-4">
                        <form action="{{ route('products.showItems', $product->id) }}" method="GET"
                            class="flex items-center">
                            <input type="text" name="search" placeholder="Cari berdasarkan IMEI..."
                                class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm"
                                value="{{ request('search') }}">
                            <button type="submit"
                                class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cari
                            </button>
                        </form>
                    </div>
                    {{-- ===== FORM PENCARIAN SELESAI ===== --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">No</th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">IMEI</th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Harga
                                        Beli (Modal)</th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Status
                                    </th>
                                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Tanggal
                                        Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ $loop->iteration }}</p>
                                        </td>
                                        <td class="px-5 py-5 border-b text-sm">{{ $item->imei }}</td>
                                        <td class="px-5 py-5 border-b text-sm">Rp
                                            {{ number_format($item->purchase_price) }}</td>
                                        <td class="px-5 py-5 border-b text-sm">
                                            @if ($item->status == 'in_stock')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Tersedia
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Terjual
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-5 border-b text-sm">{{ $item->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-10">Belum ada unit stok untuk produk
                                            ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- ===== TAMBAHKAN LINK PAGINASI DI SINI ===== --}}
                        <div class="mt-4">
                            {{ $items->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
