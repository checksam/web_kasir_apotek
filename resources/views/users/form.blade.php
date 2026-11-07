@extends('layouts.app')

@section('title', ($isEditing ? 'Edit' : 'Tambah').' Pengguna | PharmaPOS')
@section('breadcrumb', 'Home / Pengguna / '.($isEditing ? 'Edit' : 'Tambah'))

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $isEditing ? 'Edit Pengguna' : 'Tambah User' }}</h1>
            <p class="mt-1 text-sm text-slate-600">Kelola identitas, role, dan status akses staf.</p>
        </div>
        <a href="{{ route('users.index') }}" class="text-sm font-medium text-[#006680] hover:underline">Kembali</a>
    </div>

    <form method="POST" action="{{ $isEditing ? route('users.update', $user) : route('users.store') }}" class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        @csrf
        @if ($isEditing) @method('PUT') @endif

        @php($currentRole = old('role', $isEditing ? $user->getRoleNames()->first() : 'kasir'))
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-xs font-medium text-slate-600">Nama Lengkap</label>
                <input id="name" name="name" required maxlength="255" value="{{ old('name', $user->name) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="username" class="mb-1.5 block text-xs font-medium text-slate-600">Username</label>
                <input id="username" name="username" required maxlength="50" value="{{ old('username', $user->username) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                @error('username') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="email" class="mb-1.5 block text-xs font-medium text-slate-600">Email</label>
                <input id="email" name="email" type="email" required maxlength="255" value="{{ old('email', $user->email) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="role" class="mb-1.5 block text-xs font-medium text-slate-600">Role</label>
                <select id="role" name="role" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                    <option value="admin" @selected($currentRole === 'admin')>Admin</option>
                    <option value="kasir" @selected($currentRole === 'kasir')>Kasir</option>
                </select>
                @error('role') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 pb-2 text-sm text-slate-700">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEditing ? $user->is_active : true)) class="h-4 w-4 rounded border-slate-300 text-[#006680] focus:ring-[#006680]">
                    Akun aktif
                </label>
            </div>
            <div>
                <label for="password" class="mb-1.5 block text-xs font-medium text-slate-600">{{ $isEditing ? 'Password baru (opsional)' : 'Password' }}</label>
                <input id="password" name="password" type="password" {{ $isEditing ? '' : 'required' }} minlength="8" autocomplete="new-password" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
                @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-1.5 block text-xs font-medium text-slate-600">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" {{ $isEditing ? '' : 'required' }} minlength="8" autocomplete="new-password" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#007A87] focus:outline-none focus:ring-2 focus:ring-[#007A87]/20">
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-2 border-t border-slate-100 pt-4 sm:flex-row">
            <a href="{{ route('users.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-md bg-[#006680] px-4 py-2 text-sm font-semibold text-white hover:bg-[#004d60]">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah User' }}</button>
        </div>
    </form>
</div>
@endsection