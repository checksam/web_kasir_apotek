<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $users = User::query()
            ->role(['admin', 'kasir'])
            ->with('roles')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('username', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('name')
            ->paginate(5)
            ->withQueryString();

        $activeAdmins = User::role('admin')->where('is_active', true)->count();
        $activeCashiers = User::role('kasir')->where('is_active', true)->count();

        return view('users.index', [
            'users' => $users,
            'search' => $search,
            'activeAdmins' => $activeAdmins,
            'activeCashiers' => $activeCashiers,
        ]);
    }

    public function create(): View
    {
        return view('users.form', ['user' => new User, 'isEditing' => false]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'regex:/^[A-Za-z0-9._-]+$/', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ]);
        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        abort_unless($user->hasAnyRole(['admin', 'kasir']), 404);

        return view('users.form', ['user' => $user, 'isEditing' => true]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->hasAnyRole(['admin', 'kasir']), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'regex:/^[A-Za-z0-9._-]+$/', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (
            $user->hasRole('admin')
            && $user->is_active
            && ($validated['role'] !== 'admin' || ! $request->boolean('is_active'))
            && User::role('admin')->where('is_active', true)->count() === 1
        ) {
            throw ValidationException::withMessages([
                'role' => 'Admin aktif terakhir tidak dapat diturunkan rolenya atau dinonaktifkan.',
            ]);
        }

        $attributes = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (filled($validated['password'] ?? null)) {
            $attributes['password'] = Hash::make($validated['password']);
        }

        $user->update($attributes);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil diperbarui.');
    }
}