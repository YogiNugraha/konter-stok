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

                        <div class="mb-4">
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli per
                                Unit (Modal)</label>
                            <input type="number" name="purchase_price" id="purchase_price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0"
                                value="{{ old('purchase_price') }}">
                        </div>

                        {{-- ===== BLOK SCANNER DIMULAI DI SINI ===== --}}
                        <div class="mb-4 p-4 border rounded-lg bg-gray-50">
                            <h3 class="font-semibold text-lg mb-2 text-gray-800">Scan IMEI dengan Kamera</h3>

                            {{-- Wadah ini akan diisi oleh video kamera --}}
                            <div id="reader" style="width: 100%;" class="border rounded-md"></div>

                            {{-- Tombol untuk kontrol --}}
                            <div class="mt-2 space-x-2">
                                <button type="button" id="start-scan-btn"
                                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-sm">
                                    Mulai Scan
                                </button>
                                <button type="button" id="stop-scan-btn"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm">
                                    Stop Scan
                                </button>
                            </div>
                            <div id="scan-result" class="mt-2 text-sm text-green-700 font-semibold"></div>
                        </div>
                        {{-- ===== BLOK SCANNER SELESAI ===== --}}

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

    {{-- 1. Memuat library html5-qrcode dari CDN --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        // Pastikan semua elemen HTML sudah dimuat sebelum menjalankan skrip
        document.addEventListener('DOMContentLoaded', function() {

            // Ambil elemen-elemen yang kita butuhkan
            const startBtn = document.getElementById('start-scan-btn');
            const stopBtn = document.getElementById('stop-scan-btn');
            const imeiTextarea = document.getElementById('imeis');
            const scanResult = document.getElementById('scan-result');

            // Fungsi yang akan dijalankan ketika scan BERHASIL
            function onScanSuccess(decodedText, decodedResult) {
                // `decodedText` adalah hasil scan (nomor IMEI)
                console.log(`Code matched = ${decodedText}`, decodedResult);

                // Tambahkan hasil scan ke dalam textarea, diikuti baris baru
                imeiTextarea.value += decodedText + "\n";

                // Beri feedback ke pengguna
                scanResult.textContent = `Berhasil Scan: ${decodedText}`;

                // (Opsional) Beri suara 'bip' untuk feedback audio
                const audio = new Audio(
                    '/audio/scan-beep.mp3'); // Anda bisa cari file suara 'bip' dan taruh di public/audio
                audio.play().catch(e => console.error("Error playing sound:", e));
            }

            // Fungsi yang akan dijalankan ketika scan GAGAL (bisa diabaikan)
            function onScanFailure(error) {
                // console.warn(`Code scan error = ${error}`);
            }

            // Buat instance scanner baru
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", // ID dari div wadah kamera
                {
                    fps: 10, // Frame per second

                    // (BARU) Gunakan qrbox berbentuk persegi panjang yang lebar
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        return {
                            width: viewfinderWidth * 0.90, // Gunakan 90% lebar wadah
                            height: viewfinderHeight * 0.20 // Tapi hanya 20% tinggi, jadi lebih pendek
                        };
                    },

                    // (BARU) Beritahu scanner untuk fokus pada format barcode yang umum untuk produk
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.EAN_8,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E
                    ]
                },
                /* verbose= */
                false
            );

            // Event listener untuk tombol 'Mulai Scan'
            startBtn.addEventListener('click', () => {
                scanResult.textContent = "Mempersiapkan kamera...";
                // Mulai proses rendering kamera dan scanning
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            });

            // Event listener untuk tombol 'Stop Scan'
            stopBtn.addEventListener('click', () => {
                // Hentikan proses scanning dan matikan kamera
                html5QrcodeScanner.stop().then((ignore) => {
                    scanResult.textContent = "Kamera dimatikan.";
                    console.log("QR Code scanning stopped.");
                }).catch((err) => {
                    console.error("Failed to stop scanning.", err);
                });
            });
        });
    </script>
    {{-- ===== JAVASCRIPT UNTUK SCANNER SELESAI ===== --}}
</x-app-layout>
