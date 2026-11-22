<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PharmaPOS membantu apotek mengelola stok, batch, transaksi, dan laporan dalam satu sistem yang praktis.">
    <meta name="theme-color" content="#f8fafc">
    <title>PharmaPOS — Operasional Apotek Lebih Terkelola</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        .hero-grid {
            background-image: linear-gradient(rgba(15, 118, 110, .045) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 118, 110, .045) 1px, transparent 1px);
            background-size: 34px 34px;
            mask-image: linear-gradient(to bottom, black, transparent 80%);
        }
    </style>
</head>
<body id="home" class="bg-white text-slate-800 antialiased">
@php
    $dashboardUrl = null;
    if (auth()->check()) {
        $dashboardUrl = auth()->user()->hasRole('admin')
            ? route('admin.dashboard')
            : (auth()->user()->hasRole('kasir') ? route('kasir.dashboard') : route('pelanggan.dashboard'));
    }
    $primaryUrl = $dashboardUrl ?? route('login');
    $primaryLabel = auth()->check() ? 'Buka Dashboard' : 'Masuk ke PharmaPOS';
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3.5 sm:px-8">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5" aria-label="PharmaPOS, beranda">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-700 text-white shadow-sm">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7V3Z" fill="currentColor"/>
                </svg>
            </span>
            <span class="text-lg font-bold tracking-tight text-slate-900">Pharma<span class="text-teal-700">POS</span></span>
        </a>

        <nav class="hidden items-center gap-7 md:flex" aria-label="Navigasi utama">
            <a href="#fitur" class="text-sm font-medium text-slate-600 transition hover:text-teal-700">Fitur</a>
            <a href="#cara-kerja" class="text-sm font-medium text-slate-600 transition hover:text-teal-700">Cara Kerja</a>
            <a href="#testimoni" class="text-sm font-medium text-slate-600 transition hover:text-teal-700">Testimoni</a>
            <a href="#tentang" class="text-sm font-medium text-slate-600 transition hover:text-teal-700">Tentang</a>
        </nav>

        <div class="hidden items-center gap-3 sm:flex">
            @if (auth()->check())
                <a href="{{ $dashboardUrl }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-semibold text-slate-700 transition hover:text-teal-700">Masuk</a>
                <a href="#mulai" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">Jelajahi PharmaPOS</a>
            @endif
        </div>

        <details class="relative sm:hidden">
            <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 text-slate-700" aria-label="Buka menu navigasi">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </summary>
            <nav class="absolute right-0 top-12 flex w-56 flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-xl" aria-label="Navigasi seluler">
                <a href="#fitur" class="rounded-lg px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Fitur</a>
                <a href="#cara-kerja" class="rounded-lg px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Cara Kerja</a>
                <a href="#testimoni" class="rounded-lg px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Testimoni</a>
                <a href="{{ $primaryUrl }}" class="mt-1 rounded-lg bg-teal-700 px-3 py-2.5 text-center text-sm font-semibold text-white">{{ auth()->check() ? 'Dashboard' : 'Masuk' }}</a>
            </nav>
        </details>
    </div>
</header>

<main>
    <section class="relative isolate overflow-hidden bg-slate-50">
        <div class="hero-grid absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="absolute -left-40 top-12 -z-10 h-80 w-80 rounded-full bg-teal-100/70 blur-3xl" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 pb-14 pt-16 sm:px-8 sm:pb-20 sm:pt-20 lg:pt-24">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-teal-200 bg-white/85 px-3.5 py-1.5 text-xs font-semibold text-teal-800 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Platform operasional apotek modern
                </div>
                <h1 class="text-4xl font-bold leading-tight tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    Sistem Manajemen Apotek yang <span class="text-teal-700">Bersih, Cepat, dan Akurat</span>
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                    Kelola stok, transaksi, dan laporan apotek dalam satu alur kerja yang mudah dipahami—agar tim Anda bisa fokus melayani pasien.
                </p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ $primaryUrl }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-700 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-900/10 transition hover:-translate-y-0.5 hover:bg-teal-800">
                        {{ $primaryLabel }}
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                    <a href="#fitur" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-teal-300 hover:text-teal-800">Lihat fitur PharmaPOS</a>
                </div>
            </div>

            <div class="mx-auto mt-12 max-w-5xl rounded-2xl border border-slate-200 bg-white p-2 shadow-[0_24px_70px_-28px_rgba(15,23,42,.35)] sm:mt-16 sm:p-3">
                <div class="overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                    <div class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 sm:px-6">
                        <div class="flex items-center gap-2">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-700 text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7V3Z" fill="currentColor"/></svg>
                            </span>
                            <span class="text-sm font-bold text-slate-800">Ringkasan Apotek</span>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-700 sm:text-xs">● Sistem aktif</span>
                    </div>
                    <div class="grid gap-3 p-3 sm:grid-cols-3 sm:gap-4 sm:p-5">
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Penjualan hari ini</p>
                            <p class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Rp 5.840.000</p>
                            <p class="mt-2 text-xs font-medium text-emerald-700">↗ Ringkasan transaksi</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Nota diproses</p>
                            <p class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">128 <span class="text-sm font-medium text-slate-500">nota</span></p>
                            <p class="mt-2 text-xs font-medium text-slate-500">Transaksi tercatat hari ini</p>
                        </div>
                        <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-4">
                            <p class="text-xs font-medium text-slate-500">Perlu perhatian</p>
                            <p class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">4 <span class="text-sm font-medium text-slate-500">produk</span></p>
                            <p class="mt-2 text-xs font-medium text-amber-700">Stok mendekati batas minimum</p>
                        </div>
                    </div>
                    <div class="grid gap-3 px-3 pb-3 sm:grid-cols-5 sm:gap-4 sm:px-5 sm:pb-5">
                        <div class="rounded-xl border border-slate-200 bg-white p-4 sm:col-span-3">
                            <div class="flex items-center justify-between">
                                <div><p class="text-sm font-semibold text-slate-800">Aktivitas penjualan</p><p class="mt-1 text-xs text-slate-500">Ringkasan visual transaksi</p></div>
                                <span class="rounded-md bg-slate-50 px-2 py-1 text-[10px] text-slate-500">Minggu ini</span>
                            </div>
                            <div class="mt-5 flex h-24 items-end gap-2 border-b border-slate-100 px-1 sm:h-32 sm:gap-3">
                                <div class="h-[34%] flex-1 rounded-t bg-teal-100"></div><div class="h-[53%] flex-1 rounded-t bg-teal-200"></div><div class="h-[43%] flex-1 rounded-t bg-teal-300"></div><div class="h-[76%] flex-1 rounded-t bg-teal-600"></div><div class="h-[91%] flex-1 rounded-t bg-teal-700"></div><div class="h-[68%] flex-1 rounded-t bg-teal-600"></div><div class="h-[39%] flex-1 rounded-t bg-teal-200"></div><div class="h-[82%] flex-1 rounded-t bg-teal-700"></div><div class="h-[58%] flex-1 rounded-t bg-teal-400"></div>
                            </div>
                            <div class="mt-2 flex justify-between px-1 text-[10px] text-slate-400"><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span></div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4 sm:col-span-2">
                            <div class="flex items-center justify-between"><div><p class="text-sm font-semibold text-slate-800">Perlu dipantau</p><p class="mt-1 text-xs text-slate-500">Informasi stok apotek</p></div><span class="text-xs font-semibold text-teal-700">Lihat semua</span></div>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between gap-2 rounded-lg bg-slate-50 px-3 py-2.5"><div><p class="text-xs font-semibold text-slate-700">Vitamin C 500 mg</p><p class="mt-1 text-[10px] text-slate-500">Stok tersisa</p></div><span class="rounded-md bg-amber-100 px-2 py-1 text-xs font-bold text-amber-800">8 pcs</span></div>
                                <div class="flex items-center justify-between gap-2 rounded-lg bg-slate-50 px-3 py-2.5"><div><p class="text-xs font-semibold text-slate-700">Masker medis</p><p class="mt-1 text-[10px] text-slate-500">Stok tersisa</p></div><span class="rounded-md bg-rose-100 px-2 py-1 text-xs font-bold text-rose-700">3 box</span></div>
                                <div class="flex items-center justify-between gap-2 rounded-lg bg-teal-50 px-3 py-2.5"><div><p class="text-xs font-semibold text-slate-700">Batch terpantau</p><p class="mt-1 text-[10px] text-slate-500">FIFO dan kedaluwarsa</p></div><span class="text-xs font-bold text-teal-800">Aktif</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-xs font-semibold text-slate-400 sm:gap-x-12">
                <span>UNTUK APOTEK MANDIRI</span><span>ALUR STOK TERJAGA</span><span>TRANSAKSI TERCATAT</span><span>LAPORAN TERPADU</span>
            </div>
        </div>
    </section>

    <section id="fitur" class="scroll-mt-24 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-bold uppercase tracking-[.18em] text-teal-700">Dibangun untuk kebutuhan apotek</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Didesain Khusus untuk Standar Ketat Farmasi Indonesia</h2>
                <p class="mt-4 leading-7 text-slate-600">Proses harian apotek jadi lebih tertata dengan informasi yang mudah ditemukan dan alur kerja yang saling terhubung.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:mt-14 lg:grid-cols-3">
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9ZM12 12l8-4.5M12 12v9m0-9L4 7.5m4-2.25 8 4.5"/></svg></span>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Manajemen Batch &amp; FIFO</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Kelola batch dan tanggal kedaluwarsa dengan alur stok yang membantu barang masuk lebih dulu diprioritaskan.</p>
                    <a href="#cara-kerja" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-teal-700 hover:text-teal-900">Pelajari alurnya <span aria-hidden="true">→</span></a>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 5 6v5c0 4.5 2.9 8.2 7 10 4.1-1.8 7-5.5 7-10V6l-7-3Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 12 2 2 4-4"/></svg></span>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Akses &amp; Keamanan Terstruktur</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Hak akses Admin dan Kasir membantu setiap anggota tim melihat fitur sesuai tanggung jawabnya.</p>
                    <a href="#cara-kerja" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-teal-700 hover:text-teal-900">Kenali kontrol akses <span aria-hidden="true">→</span></a>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8v-6m4 6V4"/></svg></span>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Laporan Lebih Mudah Dibaca</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Pantau penjualan dan stok melalui ringkasan yang membantu pengambilan keputusan harian.</p>
                    <a href="#cara-kerja" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-teal-700 hover:text-teal-900">Lihat ringkasan <span aria-hidden="true">→</span></a>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h4"/></svg></span>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Kasir yang Praktis</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Cari produk, kelola keranjang, dan catat pembayaran dalam antarmuka yang ringkas.</p>
                    <a href="#mulai" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-teal-700 hover:text-teal-900">Kenali PharmaPOS <span aria-hidden="true">→</span></a>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v5l3 2m5-3a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/></svg></span>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Pengingat Stok &amp; Kedaluwarsa</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Kenali stok menipis dan pantau masa berlaku batch agar tindak lanjut lebih terencana.</p>
                    <a href="#cara-kerja" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-teal-700 hover:text-teal-900">Jelajahi fitur stok <span aria-hidden="true">→</span></a>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M7 4v6m10-6v6M5 12h14v8H5zM8 15h3m2 0h3"/></svg></span>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Master Data Terpadu</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Atur barang, supplier, pengguna, dan pengaturan dasar dari satu tempat.</p>
                    <a href="#mulai" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-teal-700 hover:text-teal-900">Lihat kemudahan kelola <span aria-hidden="true">→</span></a>
                </article>
            </div>
        </div>
    </section>

    <section class="border-y border-slate-200 bg-slate-50 py-10 sm:py-14" aria-label="Keandalan PharmaPOS">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 sm:grid-cols-3 sm:px-8">
            <div class="text-center">
                <p class="text-4xl font-bold tracking-tight text-teal-800 sm:text-5xl">99.8%</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">Data transaksi tercatat</p>
                <p class="mt-1 text-xs text-slate-500">Setiap proses tersimpan dalam sistem.</p>
            </div>
            <div class="border-slate-200 text-center sm:border-x">
                <p class="text-4xl font-bold tracking-tight text-teal-800 sm:text-5xl">3x <span class="text-3xl">Lebih Cepat</span></p>
                <p class="mt-2 text-sm font-semibold text-slate-800">Alur operasional lebih ringkas</p>
                <p class="mt-1 text-xs text-slate-500">Fokus pada proses penting apotek.</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold tracking-tight text-teal-800 sm:text-5xl">0 Menit</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">Mencari catatan manual</p>
                <p class="mt-1 text-xs text-slate-500">Informasi transaksi tersedia di satu sistem.</p>
            </div>
        </div>
        <p class="mx-auto mt-6 max-w-3xl px-5 text-center text-[11px] leading-5 text-slate-400">Angka pada bagian ini merupakan ilustrasi materi desain, bukan hasil pengukuran kinerja terverifikasi.</p>
    </section>

    <section id="cara-kerja" class="scroll-mt-24 py-16 sm:py-24">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-5 sm:px-8 lg:grid-cols-2 lg:gap-16">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.18em] text-teal-700">Alur kerja terhubung</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Semua yang Dibutuhkan Apotek Modern Tanpa Kompleksitas Rumit.</h2>
                <p class="mt-4 leading-7 text-slate-600">Dari barang datang hingga transaksi di kasir, PharmaPOS membantu tim bekerja dengan informasi yang sama dan alur yang konsisten.</p>
                <ul class="mt-7 space-y-5">
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-teal-50 text-sm font-bold text-teal-700">✓</span>
                        <div><h3 class="font-semibold text-slate-900">Kelola data produk lebih teratur</h3><p class="mt-1 text-sm leading-6 text-slate-600">Simpan kode, kategori, harga, satuan, dan batas minimum stok.</p></div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-teal-50 text-sm font-bold text-teal-700">✓</span>
                        <div><h3 class="font-semibold text-slate-900">Pantau batch dari penerimaan hingga penjualan</h3><p class="mt-1 text-sm leading-6 text-slate-600">Catat batch dan tanggal kedaluwarsa untuk membantu pengelolaan FIFO.</p></div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-teal-50 text-sm font-bold text-teal-700">✓</span>
                        <div><h3 class="font-semibold text-slate-900">Lihat aktivitas apotek dengan ringkas</h3><p class="mt-1 text-sm leading-6 text-slate-600">Transaksi dan ringkasan stok tersaji dalam satu pengalaman aplikasi.</p></div>
                    </li>
                </ul>
                <a href="{{ $primaryUrl }}" class="mt-8 inline-flex items-center gap-2 rounded-lg bg-teal-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800">{{ auth()->check() ? 'Buka Dashboard' : 'Mulai dari Login' }} <span aria-hidden="true">→</span></a>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm sm:p-6">
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div><p class="text-sm font-bold text-slate-900">Ringkasan stok</p><p class="mt-1 text-xs text-slate-500">Pantau ketersediaan produk</p></div>
                        <span class="rounded-lg bg-teal-50 px-2.5 py-1.5 text-xs font-semibold text-teal-700">Semua kategori</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 p-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-teal-50 text-teal-700">✚</span><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">Paracetamol 500 mg</p><p class="mt-0.5 text-xs text-slate-500">Tablet · Batch aktif</p></div><div class="text-right"><p class="text-sm font-bold text-slate-800">248</p><p class="text-[10px] text-slate-400">unit</p></div>
                        </div>
                        <div class="flex items-center gap-3 rounded-xl border border-amber-100 bg-amber-50/40 p-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-700">✚</span><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">Vitamin C 500 mg</p><p class="mt-0.5 text-xs text-slate-500">Tablet · Stok menipis</p></div><div class="text-right"><p class="text-sm font-bold text-amber-800">8</p><p class="text-[10px] text-slate-400">unit</p></div>
                        </div>
                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 p-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50 text-sky-700">✚</span><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">Amoxicillin 250 mg</p><p class="mt-0.5 text-xs text-slate-500">Kapsul · Batch aktif</p></div><div class="text-right"><p class="text-sm font-bold text-slate-800">126</p><p class="text-[10px] text-slate-400">unit</p></div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between rounded-xl bg-slate-900 p-4 text-white">
                        <div><p class="text-sm font-semibold">Stok dan batch terpantau</p><p class="mt-1 text-xs text-slate-300">Ringkasan operasional yang mudah dipahami</p></div>
                        <span class="rounded-lg bg-teal-600 px-3 py-2 text-xs font-semibold">Terkelola</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="scroll-mt-24 bg-slate-50 py-14 sm:py-20">
        <div class="mx-auto max-w-4xl px-5 sm:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center shadow-sm sm:px-14 sm:py-14">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.2 11.2H3.5c.3-3.4 2.2-5.4 5.8-6v2.2c-2 .5-3 1.6-3.2 3.2h3.2v8H3.5v-5.8c0-.7.1-1.2.3-1.6.5 0 1.6 0 3.4 0Zm10 0h-3.7c.3-3.4 2.2-5.4 5.8-6v2.2c-2 .5-3 1.6-3.2 3.2h3.2v8h-5.8v-5.8c0-.7.1-1.2.3-1.6.5 0 1.6 0 3.4 0Z"/></svg>
                </span>
                <blockquote class="mt-6 text-xl font-semibold leading-8 tracking-tight text-slate-900 sm:text-2xl">“Alur yang jelas membantu tim bekerja lebih konsisten—mulai dari memantau stok sampai melayani transaksi.”</blockquote>
                <p class="mt-5 text-sm font-semibold text-teal-800">Ilustrasi perspektif praktisi farmasi</p>
                <p class="mt-1 text-xs text-slate-500">Contoh kutipan, bukan testimoni pengguna terverifikasi.</p>
            </div>
        </div>
    </section>

    <section id="mulai" class="scroll-mt-24 px-5 py-16 sm:px-8 sm:py-24">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-3xl bg-slate-950 px-6 py-12 text-white sm:px-12 sm:py-16">
            <div class="absolute -right-10 -top-20 h-72 w-72 rounded-full bg-teal-500/20 blur-3xl" aria-hidden="true"></div>
            <div class="absolute -bottom-16 right-10 text-[180px] font-light leading-none text-white/[.04]" aria-hidden="true">+</div>
            <div class="relative max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-xs font-medium text-teal-200"><span class="h-1.5 w-1.5 rounded-full bg-teal-400"></span> PharmaPOS untuk operasional harian</span>
                <h2 class="mt-5 text-3xl font-bold tracking-tight sm:text-4xl">Siap memodernisasi operasional apotek Anda hari ini?</h2>
                <p class="mt-4 max-w-xl leading-7 text-slate-300">Mulai kelola data, stok, dan transaksi apotek dengan alur yang praktis dan mudah diikuti tim.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $primaryUrl }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-teal-50">{{ $primaryLabel }} <span aria-hidden="true">→</span></a>
                    <a href="#fitur" class="inline-flex items-center justify-center rounded-lg border border-white/20 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Jelajahi fitur</a>
                </div>
            </div>
        </div>
    </section>
</main>

<footer id="tentang" class="border-t border-slate-200 bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-12 sm:px-8 md:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-700 text-white"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7V3Z" fill="currentColor"/></svg></span>
                <span class="text-lg font-bold text-slate-900">Pharma<span class="text-teal-700">POS</span></span>
            </a>
            <p class="mt-4 max-w-xs text-sm leading-6 text-slate-500">Membantu tim apotek mengelola kegiatan operasional dengan lebih teratur.</p>
        </div>
        <div>
            <h2 class="text-sm font-bold text-slate-900">Navigasi</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-500">
                <li><a href="#fitur" class="hover:text-teal-700">Fitur PharmaPOS</a></li>
                <li><a href="#cara-kerja" class="hover:text-teal-700">Cara Kerja</a></li>
                <li><a href="#testimoni" class="hover:text-teal-700">Testimoni</a></li>
            </ul>
        </div>
        <div>
            <h2 class="text-sm font-bold text-slate-900">Akses</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-500">
                @guest
                    <li><a href="{{ route('login') }}" class="hover:text-teal-700">Masuk ke akun</a></li>
                @else
                    <li><a href="{{ $dashboardUrl }}" class="hover:text-teal-700">Dashboard</a></li>
                @endguest
                <li><a href="#mulai" class="hover:text-teal-700">Mulai menggunakan PharmaPOS</a></li>
            </ul>
        </div>
        <div>
            <h2 class="text-sm font-bold text-slate-900">Dukungan</h2>
            <p class="mt-4 text-sm leading-6 text-slate-500">Untuk informasi dan bantuan penggunaan, silakan hubungi administrator PharmaPOS Anda.</p>
        </div>
    </div>
    <div class="border-t border-slate-100">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-5 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-8">
            <p>© {{ now()->year }} PharmaPOS. Hak cipta dilindungi.</p>
            <a href="#home" class="hover:text-teal-700">Kembali ke atas ↑</a>
        </div>
    </div>
</footer>
</body>
</html>
