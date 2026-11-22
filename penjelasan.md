# PharmaPOS — Penjelasan Teknis Proyek

Dokumen ini menjelaskan rancangan dan implementasi aplikasi Kasir Apotek (PharmaPOS) berdasarkan kode, konfigurasi, migration, dan automated test yang tersedia di repositori.

## 1. Judul & Identitas Proyek

| Informasi | Keterangan |
|---|---|
| Nama aplikasi | **PharmaPOS (Web Kasir Apotek)** |
| Jenis sistem | Aplikasi web untuk operasional dasar apotek |
| Tujuan | Membantu pengelolaan akun dan hak akses, data obat dan batch stok, pengaturan apotek, serta proses penjualan di kasir |
| Arsitektur aplikasi | Laravel berbasis pola route–controller–model–view (MVC), dengan Blade untuk tampilan |

PharmaPOS ditujukan untuk memusatkan proses penjualan obat pada satu aplikasi. Kasir mencari obat, menyusun keranjang, mencatat pembayaran, dan menyelesaikan transaksi. Sistem kemudian menyimpan transaksi dan detail alokasi batch, menghitung pajak serta biaya berdasarkan pengaturan, dan mengurangi stok secara terkoordinasi. Admin mengelola akun staf dan konfigurasi apotek; pelanggan memiliki akses terbatas ke dashboard pelanggan.

## 2. Daftar Tools, Teknologi, & Framework (Tech Stack)

### Bahasa dan platform

- **PHP:** `composer.json` mensyaratkan `^8.4`; runtime pengembangan yang tercatat pada lingkungan proyek adalah PHP 8.5.6. Karena itu, keterangan “PHP 8.x/8.5” sesuai dengan konfigurasi proyek.
- **JavaScript:** dipakai untuk interaksi sisi browser, antara lain menu dropdown profil dan keranjang POS.
- **HTML5:** struktur halaman dirender melalui template Blade.
- **CSS3:** styling aplikasi menggunakan Tailwind CSS dan beberapa aturan CSS langsung pada layout.

### Backend

- **Laravel 13:** dependency `laravel/framework` disetel ke `^13.17`.
- **Eloquent ORM:** model dan relasi menghubungkan pengguna, pelanggan, obat, batch, transaksi, detail transaksi, dan pengaturan.
- **Artisan CLI:** digunakan untuk menjalankan server pengembangan, migration, seeder, route listing, dan test.
- **Session dan middleware:** autentikasi web serta pembatasan akses diproses di sisi server.

### Frontend dan styling

- **Blade:** template server-side untuk layout, halaman, dan formulir. File aplikasi tampilan berada di `resources/views`.
- **Tailwind CSS 4:** dependency frontend dan integrasi Vite didefinisikan di `package.json`.
- **Alpine.js:** dipakai untuk perilaku interaktif sederhana, termasuk dropdown profil; layout memuatnya melalui CDN.
- **Vite:** membangun aset frontend untuk pengembangan maupun build produksi.
- Tampilan POS juga menggunakan JavaScript browser untuk mengelola keranjang dan memperbarui ringkasan. Harga, diskon, pajak, pembayaran, dan stok tetap divalidasi/dihitung ulang di backend saat checkout; nilai dari browser bukan sumber tepercaya.

> Proyek memakai Blade template. Keberadaan Blade tidak berarti semua tampilan menggunakan Blade Component khusus; halaman yang ada dirender melalui file Blade biasa.

### Database

- Aplikasi Laravel mendukung pemilihan driver melalui konfigurasi environment `DB_*`.
- `.env.example` saat ini menggunakan **SQLite** sebagai konfigurasi awal. Pengujian memakai SQLite in-memory agar setiap test terisolasi.
- Database **MySQL/MariaDB** dapat digunakan dengan mengatur driver, host, port, nama database, dan kredensial pada `.env`. Konfigurasi aktual suatu instalasi ditentukan oleh file environment lokal, bukan hanya oleh repositori.

### Package dan library penting

- **Spatie Laravel-Permission (`^8.3`):** menyimpan role/hak akses dan menyediakan pemeriksaan role pada middleware serta model pengguna.
- **Laravel Pint (`^1.27`, dependency development):** memeriksa dan merapikan format kode PHP.
- **PHPUnit (`^12.5.12`):** menjalankan unit/feature test melalui `php artisan test`.
- **Faker:** membantu pembuatan data untuk factory/test.
- **Laravel Tinker:** console interaktif untuk inspeksi aplikasi.
- **Vite, Tailwind CSS, dan Laravel Vite Plugin:** toolchain aset frontend.
- **Laravel Pail dan Collision:** utilitas pengembangan/debugging yang tercantum sebagai dependency development.

### Tools pengembangan dan lingkungan

- Kode dapat dikembangkan menggunakan editor seperti **Visual Studio Code**. Editor bukan dependency runtime aplikasi.
- **Git** digunakan untuk version control. Penggunaan SSH key bergantung pada konfigurasi Git hosting/mesin developer dan bukan bagian dari kode aplikasi.
- **Postman** dapat digunakan untuk eksplorasi endpoint secara manual, tetapi tidak tercatat sebagai dependency proyek; pengujian otomatis utamanya menggunakan Laravel testing/PHPUnit.
- **XAMPP** atau lingkungan lokal lain dapat menyediakan PHP dan database bila versinya sesuai. Keduanya bukan prasyarat yang dikunci oleh repositori.
- `nixpacks.toml` menyediakan konfigurasi deployment berbasis **Nixpacks** dengan PHP 8.4, Composer, dan Node.js 20. Tahap build menjalankan `npm ci`, `npm run build`, dan instalasi Composer produksi.
- **Docker** dapat digunakan sebagai lingkungan opsional oleh tim, tetapi tidak ada Dockerfile atau Compose yang menjadi konfigurasi utama pada repositori saat dokumen ini dibuat.

## 3. Arsitektur Sistem & Struktur Hak Akses (Multi-Role)

### Alur arsitektur

Alur request web secara umum:

1. Pengguna mengakses URL yang didefinisikan pada `routes/web.php`.
2. Route publik atau route terlindungi memilih controller/aksi. Route terlindungi menggunakan autentikasi, middleware status akun, dan middleware role sesuai kebutuhan.
3. Controller memvalidasi input, menjalankan aturan bisnis, dan berinteraksi dengan model Eloquent/database.
4. Controller mengembalikan view Blade atau redirect beserta status/error validasi.
5. Browser merender HTML dan menjalankan interaksi JavaScript ringan.

Route transaksi penjualan tersedia untuk admin/kasir pada `/transaksi/penjualan` dan untuk kasir pada `/kasir/transaksi/penjualan`. Aksi checkout menggunakan HTTP `POST`. Pengaturan sistem dan manajemen pengguna berada dalam grup route khusus admin.

### Role aplikasi

| Role | Hak akses utama |
|---|---|
| **Admin** | Dashboard admin, data master yang dibatasi untuk admin, pengaturan sistem, manajemen akun staf, transaksi, dan laporan yang tersedia |
| **Kasir** | Dashboard kasir, POS penjualan, pencarian barang, dan halaman operasional/laporan yang diizinkan |
| **Pelanggan (Customer)** | Dashboard pelanggan; tidak diperbolehkan membuka route admin atau kasir |

Role dikelola menggunakan Spatie Laravel-Permission. Untuk route, middleware `role` memeriksa role yang disematkan pada pengguna, sehingga menyembunyikan menu saja tidak dianggap sebagai kontrol keamanan. Route tetap memeriksa otorisasi di server.

### Anti-Lockout Protection akun admin

Di manajemen pengguna, sistem menghitung admin yang masih aktif. Jika hanya tersisa satu admin aktif, perubahan yang akan menurunkan role admin terakhir tersebut menjadi kasir atau menonaktifkannya ditolak dengan error validasi. Pemeriksaan dilakukan sebelum perubahan data dan role disinkronkan, sehingga masih ada akun admin aktif yang dapat mengelola sistem.

### Siklus autentikasi dan status akun

- Login memakai email dan password serta hanya menerima pengguna aktif dengan role yang diizinkan.
- Setelah berhasil login, session diregenerasi dan informasi ringkas pengguna/role disimpan pada session.
- Middleware status akun memeriksa `is_active` pada setiap request terlindungi. Jika akun dinonaktifkan ketika session masih aktif, session diakhiri dan pengguna diarahkan kembali ke login.
- Logout dilakukan melalui `POST`, menghapus autentikasi, menginvalidasi session, dan meregenerasi token CSRF.
- Password dikelola sebagai hash, bukan disimpan sebagai teks biasa.

## 4. Penjelasan Modul Utama & Fitur Unggulan

### Autentikasi dan authorization

- Route login menerima kredensial dengan validasi server.
- Role menentukan dashboard tujuan setelah login.
- Middleware `auth` mengharuskan pengguna login; middleware `role` membatasi fitur berdasarkan role; middleware `active` menolak pengguna yang telah dinonaktifkan.
- Form web yang mengubah data menggunakan metode HTTP sesuai operasi dan token CSRF Laravel.
- Menu logout pada dropdown profil mengirim form `POST` ke route logout dengan `@csrf`.

### Inventaris obat dan batch FIFO

Data master obat disimpan pada tabel `items`; data batch stok disimpan pada `batch_obat`. Setiap batch terkait dengan satu item dan menyimpan nomor batch, jumlah, tanggal penerimaan, serta tanggal kedaluwarsa opsional.

Saat checkout:

1. Backend memvalidasi daftar item dan kuantitas yang diminta.
2. Record item dan batch dikunci selama transaksi database menggunakan `lockForUpdate`, agar proses checkout bersamaan tidak menggunakan stok yang sama secara tidak terkendali pada database yang mendukung row-level locking.
3. Batch bertanggal kedaluwarsa sebelum hari ini tidak ikut dihitung sebagai stok yang dapat dijual.
4. Batch yang masih layak diurutkan menurut `received_at` paling awal, lalu ID sebagai urutan stabil. Pengambilan kuantitas dilakukan satu batch demi satu batch sampai jumlah terpenuhi: **FIFO (First In, First Out)**.
5. Bila stok layak tidak cukup, checkout ditolak. Transaksi database dibatalkan sehingga tidak ada penjualan/detail atau pengurangan stok sebagian.
6. Setelah transaksi berhasil, kuantitas batch dikurangi dan ringkasan stok pada item diperbarui berdasarkan batch yang tersisa.

FIFO di sini didasarkan pada tanggal penerimaan batch. Tanggal kedaluwarsa dipakai untuk mengeluarkan batch yang sudah tidak layak dijual; sistem tidak menjual stok dari batch kedaluwarsa.

### Modul POS kasir dan transaksi

- Halaman POS menyediakan pencarian berdasarkan nama, kode item, atau barcode yang sama persis.
- Barcode disimpan pada kolom `items.barcode` yang opsional dan unik bila diisi. Pemindai barcode yang bertindak sebagai input keyboard dapat mengisi kolom pencarian.
- Keranjang dibangun di browser, tetapi backend menerima ulang daftar item/kuantitas dan melakukan validasi serta perhitungan otoritatif saat checkout.
- Harga unit diambil dari `items.selling_price`, bukan harga yang dikirim browser.
- Diskon tidak boleh lebih besar daripada subtotal.
- Persentase PPN (`vat_percent`) dan biaya embalase (`default_packaging_fee`) diambil dari pengaturan sistem; nilai default tersedia bila belum ada record pengaturan.
- Perhitungan uang dilakukan dalam satuan sen (integer) pada backend untuk menghindari kesalahan umum floating point.
- Pembayaran yang lebih kecil dari total ditolak. Jika cukup, sistem mencatat total dibayar dan kembalian.
- Header transaksi mencatat nomor transaksi, kasir, pelanggan opsional, subtotal, diskon, pajak, embalase, total, pembayaran, kembalian, status, dan waktu. Detail mencatat item, batch yang diambil, kuantitas, harga unit, dan subtotal.
- Pembuatan header, detail, dan pengurangan stok terjadi dalam satu transaksi database.

Status yang dibuat oleh checkout saat ini adalah `completed`. Mekanisme pembatalan/refund dan alur pembayaran tertunda tidak termasuk dalam implementasi checkout ini.

### Integrasi pelanggan

Tabel `customers` menyimpan data pelanggan. Penjualan memiliki foreign key nullable `customer_id` sehingga transaksi dapat dikaitkan dengan pelanggan terdaftar atau dibiarkan sebagai pelanggan umum. Nama kolom aktual pada implementasi adalah `customer_id` (bukan `id_pelanggan`). Tabel `users` juga dapat memiliki relasi `customer_id` untuk akun role pelanggan.

### Pengaturan sistem dan manajemen pengguna

- Pengaturan sistem menyimpan profil apotek, informasi kontak, footer struk, opsi tampilan nama apoteker, persentase PPN, dan biaya embalase.
- Akses perubahan pengaturan dibatasi untuk admin dan input diverifikasi, termasuk batas pajak, email, serta rentang biaya.
- Manajemen pengguna admin dapat mencari, menambah, dan mengubah akun staf admin/kasir; username/email unik, format username dibatasi, dan password baru harus memenuhi validasi konfirmasi serta panjang minimum.
- Akun dapat dinonaktifkan, dan middleware status aktif mencabut akses dari session yang masih berjalan.

## 5. Skema Database & Pengujian (Testing)

### Tabel utama

| Tabel | Fungsi dan hubungan penting |
|---|---|
| `users` | Akun login, identitas, email, username, hash password, status aktif, dan `customer_id` opsional untuk akun pelanggan |
| `roles`, `permissions` | Definisi role dan izin dari Spatie Laravel-Permission |
| `model_has_roles`, `model_has_permissions`, `role_has_permissions` | Tabel pivot relasi role/permission Spatie terhadap pengguna dan role |
| `items` | Master obat/barang, kode unik, barcode opsional, kategori, harga jual, dan ringkasan stok |
| `batch_obat` | Batch stok per item: nomor batch, kuantitas, tanggal penerimaan, dan tanggal kedaluwarsa |
| `customers` | Data pelanggan seperti nama, nomor telepon, dan alamat |
| `penjualan` | Header transaksi, relasi kasir (`user_id`) dan pelanggan (`customer_id`), nilai keuangan, nomor transaksi, dan status |
| `detail_penjualan` | Baris transaksi yang mengaitkan penjualan, item, dan batch serta menyimpan jumlah dan harga |
| `settings` | Konfigurasi apotek termasuk tarif PPN dan biaya embalase default |

Relasi kunci:

```text
items 1 ─── * batch_obat
items 1 ─── * detail_penjualan
batch_obat 1 ─── * detail_penjualan
users 1 ─── * penjualan
customers 1 ─── * penjualan (customer_id nullable pada penjualan)
penjualan 1 ─── * detail_penjualan
customers 1 ─── 0..1 users (akun pelanggan, melalui users.customer_id)
```

Tabel dibuat/diperbarui melalui migration di `database/migrations`; migration juga membentuk foreign key, indeks, dan aturan penghapusan yang sesuai. Database pengujian menggunakan SQLite in-memory sesuai `phpunit.xml`; penerapan produksi perlu menyesuaikan driver dan kredensial lingkungan.

### Automated testing dan kualitas kode

Pada pemeriksaan lengkap yang tercatat, suite lulus dengan **35 tests dan 238 assertions**. Cakupan yang relevan mencakup alur autentikasi/role, akses pelanggan dan kasir, dropdown/logout, pengaturan sistem, manajemen pengguna, serta checkout POS. Tes POS memeriksa alokasi beberapa batch FIFO, pengurangan stok, perhitungan PPN/embalase, pelanggan transaksi, stok kurang/kosong, batch kedaluwarsa, pembayaran kurang, diskon berlebih, dan penolakan akses pelanggan.

Perintah untuk mengulang pengujian:

```bash
php artisan test
```

Laravel Pint digunakan untuk menjaga format PHP:

```bash
vendor/bin/pint --test
```

Hasil test adalah verifikasi otomatis terhadap skenario yang ditulis, bukan jaminan bahwa seluruh kemungkinan bug telah dihilangkan. Pengujian penerimaan manual, khususnya perangkat pemindai barcode dan konfigurasi database target, tetap disarankan.

## 6. Panduan Menjalankan Aplikasi Secara Lokal (Setup & Run)

### Prasyarat

- PHP sesuai kebutuhan proyek (`^8.4`) dan extension yang dibutuhkan Laravel serta driver database yang dipilih.
- Composer.
- Node.js/npm untuk build aset frontend.
- Database yang dipilih dan tersedia; `.env.example` menggunakan SQLite secara default.

### Instalasi awal

Jalankan perintah dari root proyek:

```bash
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Jika PowerShell melaporkan `.env` sudah ada, jangan menimpanya tanpa memeriksa konfigurasi lokal. Sesuaikan koneksi database di `.env` bila menggunakan MySQL/MariaDB. Untuk SQLite, pastikan file database yang dirujuk tersedia dan dapat ditulis.

Instal/build aset frontend:

```bash
npm ci
npm run build
```

### Membuat skema dan data demo

```bash
php artisan migrate --seed
```

Seeder utama membuat akun demo role, data obat, dan batch demo. Jalankan hanya pada database pengembangan atau database baru yang memang disiapkan untuk data demo. Jangan menjalankan seeder demo secara sembarang pada database produksi.

### Menjalankan server

```bash
php artisan serve
```

Buka alamat lokal yang ditampilkan Artisan, biasanya `http://127.0.0.1:8000`. Login dengan akun demo yang dicatat pada `login.md` di mesin pengembangan. Jangan menggunakan atau mengaktifkan password demo tersebut di produksi.

### Perintah kerja harian

```bash
php artisan migrate       # menerapkan migration yang tertunda
php artisan test          # menjalankan automated test
vendor/bin/pint --test    # memeriksa format PHP
npm run dev               # menjalankan Vite dalam mode pengembangan
```

Sebelum presentasi, disarankan memastikan database yang dipakai sudah dimigrasikan, data demo tersedia, dan aplikasi dapat dibuka melalui server lokal.
