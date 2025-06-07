<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Stok Masuk / Pembelian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('stock-in.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="product_id" id="product_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stok:
                                        {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id" id="supplier_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah Masuk</label>
                            <input type="number" name="quantity" id="quantity"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="1">
                        </div> --}}

                        {{-- GANTI INPUT QUANTITY DENGAN INI --}}
                        <div class="mb-4">
                            <label for="imeis" class="block text-sm font-medium text-gray-700">Daftar IMEI (Satu per
                                baris)</label>
                            <textarea name="imeis" id="imeis" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>{{ old('imeis') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="date" id="date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required
                                value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
