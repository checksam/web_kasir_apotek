@extends('layouts.app')

@section('title', 'Manajemen Pengguna | PharmaPOS')
@section('breadcrumb', 'Home / Pengguna')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-slate-600">Kelola akses staf dan pengaturan sistem.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 self-start rounded-md bg-[#006680] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#004d60]">
            <span aria-hidden="true">＋</span> Tambah User
        </a>
    </div>

    @if (session('status'))
        <div role="status" class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 items-stretch gap-5 xl:grid-cols-[minmax(0,1.8fr)_minmax(280px,0.9fr)]">
        <section class="min-w-0 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="font-semibold text-[#006680]">Daftar Staf</h2>
                <form method="GET" action="{{ route('users.index') }}" role="search" class="flex w-full gap-2 sm:w-auto">
                    <label for="search" class="sr-only">Cari staf</label>
                    <input id="search" name="search" value="{{ $search }}" placeholder="Cari staf..." class="min-w-0 flex-1 rounded-md border border-slate-300 px-3 py-2 text-xs focus:border-[#007A87] focus:outline-none sm:w-48">
                    <button class="rounded-md border border-slate-300 px-3 text-xs font-medium text-slate-600 hover:bg-slate-50">Cari</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Nama Lengkap</th>
                            <th class="px-4 py-3 font-semibold">Username</th>
                            <th class="px-4 py-3 font-semibold">Role</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            @php($role = $user->getRoleNames()->first())
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#0e83a2] text-xs font-bold text-white">{{ collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}</span>
                                        <div class="min-w-0"><p class="truncate font-medium text-slate-800">{{ $user->name }}</p><p class="truncate text-xs text-slate-500">{{ $user->email }}</p></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->username }}</td>
                                <td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $role === 'admin' ? 'bg-sky-100 text-sky-800' : 'bg-teal-100 text-teal-800' }}">{{ ucfirst($role) }}</span></td>
                                <td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs {{ $user->is_active ? 'bg-slate-100 text-slate-600' : 'bg-rose-50 text-rose-700' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('users.edit', $user) }}" aria-label="Edit {{ $user->name }}" title="Edit pengguna" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-[#006680] hover:bg-sky-50"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">Tidak ada staf yang cocok dengan pencarian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-200 px-4 py-3 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <span>Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna</span>
                {{ $users->links() }}
            </div>
        </section>

        <aside class="flex flex-col gap-5">
            <section class="overflow-hidden rounded-lg bg-[#0e83a2] p-5 text-white shadow-sm">
                <h2 class="text-sm font-semibold">Total Akses Aktif</h2>
                <p class="mt-1 text-3xl font-bold">{{ $activeAdmins + $activeCashiers }} <span class="text-xs font-medium text-white/80">Pengguna</span></p>
                <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-white/90">
                    <p>Admin<br><strong class="text-sm text-white">{{ $activeAdmins }}</strong></p>
                    <p>Kasir<br><strong class="text-sm text-white">{{ $activeCashiers }}</strong></p>
                </div>
            </section>

            <section class="flex-1 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Hak Akses Role</h2>
                <div class="space-y-4">
                    <div class="border-l-2 border-[#0e83a2] pl-3">
                        <h3 class="text-sm font-semibold text-slate-800">Admin</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Akses penuh ke master data, transaksi, laporan, pengaturan, dan manajemen pengguna.</p>
                    </div>
                    <div class="border-l-2 border-amber-600 pl-3">
                        <h3 class="text-sm font-semibold text-slate-800">Kasir</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Akses terbatas pada transaksi penjualan, pencarian barang, dan laporan penjualan.</p>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</div>
@endsection