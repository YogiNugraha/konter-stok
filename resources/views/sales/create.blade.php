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
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Select2 pada dropdown IMEI saat halaman dimuat
            const imeiSelect = $('#imei').select2({
                placeholder: "-- Pilih Produk Terlebih Dahulu --",
            });

            // Ambil elemen lain yang dibutuhkan
            const productSelectEl = document.getElementById('product_id');
            const priceInput = document.getElementById('selling_price');

            // Event listener saat produk diubah
            productSelectEl.addEventListener('change', function() {
                const productId = this.value;

                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.dataset.price;
                priceInput.value = price;

                // Reset dan tampilkan pesan loading di Select2
                imeiSelect.empty().append('<option value="">Memuat IMEI...</option>').trigger('change');

                if (productId) {
                    const baseUrl = productSelectEl.dataset.imeiUrl;

                    fetch(`${baseUrl}/${productId}/available-imeis`)
                        .then(response => response.json())
                        .then(data => {
                            imeiSelect.empty(); // Kosongkan pilihan
                            if (data.length > 0) {
                                imeiSelect.append('<option value="">Pilih IMEI</option>').trigger(
                                    'change');
                                data.forEach(imei => {
                                    // Tambahkan setiap IMEI sebagai option baru
                                    const option = new Option(imei, imei, false, false);
                                    imeiSelect.append(option).trigger('change');
                                });
                            } else {
                                imeiSelect.append(
                                    '<option value="">-- Stok IMEI untuk produk ini habis --</option>'
                                    ).trigger('change');
                            }
                        });
                } else {
                    imeiSelect.empty().append(
                        '<option value="">-- Pilih Produk Terlebih Dahulu --</option>').trigger(
                        'change');
                    priceInput.value = '';
                }
            });
        });
    </script>
</x-app-layout>
