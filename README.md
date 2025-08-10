# Sistem Inventaris Konter HP (Konter +62)

![Screenshot Dashboard Konter +62](https://imgur.com/a/md7HMvL)

## 📄 Tentang Proyek

**Sistem Inventaris Konter +62** adalah aplikasi web lengkap yang dibangun menggunakan Laravel 11 untuk mengelola seluruh alur kerja di sebuah konter handphone. Aplikasi ini dirancang untuk mengatasi tantangan spesifik dalam bisnis retail HP, seperti pelacakan unit individual berdasarkan IMEI dan penanganan harga beli/jual yang dinamis.

Proyek ini mencakup fungsionalitas dari backend yang kompleks (manajemen data, transaksi, otorisasi) hingga frontend yang interaktif dan responsif (dashboard, laporan, pencarian).

---

## ✨ Fitur Utama

Aplikasi ini dilengkapi dengan serangkaian fitur profesional untuk mendukung operasional bisnis:

#### **Manajemen Inventaris Lanjutan**

-   **Dua Tipe Produk:** Mendukung produk **Serialisasi** (dilacak per-IMEI seperti smartphone) dan produk **Kuantitas** (seperti aksesoris, kartu perdana).
-   **Stok Masuk Dinamis:** Mencatat barang masuk beserta harga modal aktual per-unit/batch.
-   **Pelacakan per-IMEI:** Setiap unit smartphone memiliki catatan tersendiri, lengkap dengan riwayat harga beli dan statusnya (`in_stock`, `sold`).
-   **Detail Stok:** Halaman khusus untuk melihat daftar lengkap IMEI yang tersedia untuk setiap tipe produk.

#### **Manajemen Penjualan & Transaksi**

-   **Point of Sale (POS) Sederhana:** Antarmuka kasir yang mudah digunakan untuk memproses penjualan.
-   **Harga Jual Dinamis:** Kasir dapat menyesuaikan harga jual saat transaksi untuk mengakomodasi diskon atau negosiasi.
-   **Scan Barcode/QR Code:** Mempercepat proses input stok atau penjualan dengan memindai IMEI menggunakan kamera perangkat.
-   **Transaksi Pembalikan (Retur):** Sistem retur yang profesional, di mana transaksi tidak dihapus melainkan dibatalkan, menjaga keutuhan data (audit trail) dan mengembalikan stok secara otomatis.

#### **Analisis & Pelaporan**

-   **Dashboard Interaktif:** Menampilkan ringkasan data real-time seperti pendapatan harian, jumlah transaksi, dan total stok.
-   **Grafik Penjualan Dinamis:** Grafik garis yang menampilkan tren pendapatan dengan filter rentang waktu (7 hari, 30 hari, 1 tahun).
-   **Laporan Penjualan & Laba/Rugi:** Laporan detail yang dapat difilter berdasarkan tanggal untuk menganalisis performa bisnis.
-   **Ekspor Data:** Mengekspor data laporan ke format **Excel** dan **PDF** untuk keperluan akuntansi atau pengarsipan.

#### **Manajemen Pengguna & Akses**

-   **Sistem Peran (Roles):** Hak akses terpisah untuk **Admin** (akses penuh) dan **Kasir** (akses terbatas).
-   **Pengelolaan Pengguna:** Admin dapat menambah, mengubah, dan menghapus akun pengguna lain melalui antarmuka khusus.

#### **Pengalaman Pengguna (UX)**

-   **Pencarian & Paginasi:** Fitur pencarian dan paginasi di semua tabel data utama untuk memudahkan navigasi.
-   **Desain Responsif:** Tampilan yang menyesuaikan dengan baik di perangkat desktop maupun mobile.
-   **Dropdown dengan Pencarian:** Memudahkan pemilihan IMEI saat penjualan jika stok sangat banyak.

---

## 🚀 Teknologi yang Digunakan

-   **Backend:** PHP 8.3, Laravel 11
-   **Frontend:** Blade, Tailwind CSS, Alpine.js
-   **Database:** MySQL / MariaDB
-   **Paket Utama:**
    -   `laravel/breeze` - untuk sistem autentikasi.
    -   `maatwebsite/excel` - untuk ekspor ke Excel.
    -   `barryvdh/laravel-dompdf` - untuk ekspor ke PDF.
    -   `arielmejiadev/larapex-charts` - untuk membuat grafik di dashboard.
-   **Library Frontend:**
    -   `html5-qrcode` - untuk fungsionalitas scan barcode.
    -   `Select2` - untuk dropdown dengan fitur pencarian.

---

## 🛠️ Panduan Instalasi Lokal

Untuk menjalankan proyek ini di lingkungan pengembangan lokal, ikuti langkah-langkah berikut:

1.  **Clone repository ini:**

    ```bash
    git clone [URL_REPOSITORY_ANDA]
    ```

2.  **Masuk ke direktori proyek:**

    ```bash
    cd nama-proyek
    ```

3.  **Install dependensi Composer:**

    ```bash
    composer install
    ```

4.  **Salin file environment:**

    ```bash
    cp .env.example .env
    ```

5.  **Generate application key:**

    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi file `.env`:**

    -   Atur koneksi database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    -   Pastikan `APP_URL` sudah benar.

7.  **Jalankan migrasi database:**

    ```bash
    php artisan migrate
    ```

8.  **(Opsional) Isi database dengan data awal:**
    Jalankan seeder untuk membuat akun admin default.

    ```bash
    php artisan db:seed --class=UserSeeder
    ```

9.  **Install dependensi NPM:**

    ```bash
    npm install
    ```

10. **Compile aset frontend:**

    ```bash
    npm run dev
    ```

11. **Jalankan server pengembangan:**
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang berjalan di `http://127.0.0.1:8000`.

---

## 👨‍💻 Cara Menggunakan

Setelah instalasi dan seeding, Anda bisa login menggunakan akun default:

-   **Email:** `admin@konter.com`
-   **Password:** `password`

_(Sangat disarankan untuk segera mengganti password setelah login pertama kali melalui menu Profil)._

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE.md).
