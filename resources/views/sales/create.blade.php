<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Penjualan Baru (Point of Sale)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tampilkan pesan error jika stok tidak cukup --}}
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @elseif (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sales.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="product_id" id="product_id" data-imei-url="{{ url('api/products') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    {{-- Tambahkan atribut data-price di dalam tag option --}}
                                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TAMBAHKAN BLOK INI UNTUK IMEI --}}
                        <div class="mb-4">
                            <label for="imei" class="block text-sm font-medium text-gray-700">Pilih IMEI</label>
                            <select name="imei" id="imei"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Pilih Produk Terlebih Dahulu --</option>
                            </select>
                        </div>
                        {{-- ===== TAMBAHKAN BLOK INPUT BARU DI SINI ===== --}}
                        <div class="mb-4">
                            <label for="selling_price" class="block text-sm font-medium text-gray-700">Harga Jual
                                (Rp)</label>
                            <input type="number" name="selling_price" id="selling_price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0"
                                value="{{ old('selling_price') }}">
                        </div>

                        {{-- <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah Jual</label>
                            <input type="number" name="quantity" id="quantity"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="1"
                                value="{{ old('quantity', 1) }}">
                        </div> --}}

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Penjualan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('product_id').addEventListener('change', function() {
            const productId = this.value;
            const imeiSelect = document.getElementById('imei');
            const priceInput = document.getElementById('selling_price');

            // Ambil harga dari atribut data-price
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.dataset.price;
            priceInput.value = price; // <-- Atur nilai input harga

            // Kosongkan pilihan IMEI dan tampilkan pesan loading
            imeiSelect.innerHTML = '<option value="">Memuat IMEI...</option>';

            if (productId) {
                const productSelect = document.getElementById('product_id');
                const baseUrl = productSelect.dataset.imeiUrl; // <-- Ambil URL dasar dari atribut data-*
                fetch(`${baseUrl}/${productId}/available-imeis`) // <-- Gunakan URL yang sudah benar
                    .then(response => response.json())
                    .then(data => {
                        imeiSelect.innerHTML = '<option value="">Pilih IMEI</option>'; // Reset
                        if (data.length > 0) {
                            data.forEach(imei => {
                                const option = document.createElement('option');
                                option.value = imei;
                                option.textContent = imei;
                                imeiSelect.appendChild(option);
                            });
                        } else {
                            imeiSelect.innerHTML =
                                '<option value="">-- Stok IMEI untuk produk ini habis --</option>';
                        }
                    });
            } else {
                imeiSelect.innerHTML = '<option value="">-- Pilih Produk Terlebih Dahulu --</option>';
                priceInput.value = ''; // <-- Kosongkan harga jika tidak ada produk dipilih
            }
        });
    </script>
</x-app-layout>
