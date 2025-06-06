<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('products.update', $product->id) }}">
                        @csrf
                        @method('PUT') {{-- PENTING: Beritahu Laravel ini adalah proses UPDATE --}}

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                            <input type="text" name="name" id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="brand" class="block text-sm font-medium text-gray-700">Merek</label>
                            <input type="text" name="brand" id="brand"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('brand', $product->brand) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                            <input type="text" name="model" id="model"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('model', $product->model) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="storage" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                            <input type="text" name="storage" id="storage"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('storage', $product->storage) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
                            <input type="text" name="color" id="color"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('color', $product->color) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga
                                Beli</label>
                            <input type="number" name="purchase_price" id="purchase_price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('purchase_price', $product->purchase_price) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="selling_price" class="block text-sm font-medium text-gray-700">Harga
                                Jual</label>
                            <input type="number" name="selling_price" id="selling_price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('selling_price', $product->selling_price) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stok Awal</label>
                            <input type="number" name="stock" id="stock"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('stock', $product->stock) }}" required>
                        </div>

                        {{-- Anda bisa tambahkan field lain seperti model, storage, color dengan cara yang sama --}}

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('products.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
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
