<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Tampilkan pesan sukses --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- ===== FORM PENCARIAN DIMULAI DI SINI ===== --}}
                    <div class="mb-4">
                        <form action="{{ route('products.index') }}" method="GET" class="flex items-center">
                            <input type="text" name="search" placeholder="Cari nama atau brand produk..."
                                class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                value="{{ request('search') }}">
                            <button type="submit"
                                class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cari
                            </button>
                        </form>
                    </div>
                    {{-- ===== FORM PENCARIAN SELESAI ===== --}}

                    {{-- ===== FORM UNTUK AKSI MASSAL DIMULAI DI SINI ===== --}}
                    <form action="{{ route('products.bulkDestroy') }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua produk yang dipilih? Aksi ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')

                        <div class="mb-4 flex items-center space-x-4">
                            <a href="{{ route('products.create') }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Tambah Produk
                            </a>
                            <button type="submit" id="bulk-delete-btn"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm disabled:bg-gray-400"
                                disabled>
                                Hapus yang Dipilih
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal mt-4">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs w-1/12">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Nama Produk
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Model
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Kapasitas
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Warna
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Harga Jual
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Stok
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                                    class="row-checkbox">
                                            </td>
                                            {{-- TAMBAHKAN SEL UNTUK NOMOR DI SINI --}}
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $loop->iteration }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $product->name }}</p>
                                                <p class="text-gray-600 whitespace-no-wrap">{{ $product->brand }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $product->model }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $product->storage }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $product->color }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">Rp
                                                    {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $product->stock }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <div class="flex items-center">
                                                    <a href="{{ route('products.showItems', $product->id) }}"
                                                        class="text-blue-600 hover:text-blue-900 mr-4">Detail</a>

                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('products.edit', $product->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9"
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                                Data Produk belum Tersedia.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                    {{-- ===== FORM SELESAI ===== --}}
                    <div class="mt-4">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT UNTUK CHECKBOX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const deleteBtn = document.getElementById('bulk-delete-btn');

            function toggleDeleteButton() {
                const anyChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
                deleteBtn.disabled = !anyChecked;
            }

            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleDeleteButton();
            });

            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAll.checked = false;
                    }
                    toggleDeleteButton();
                });
            });
        });
    </script>
</x-app-layout>
