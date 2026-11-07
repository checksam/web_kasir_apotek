<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $setting = Setting::query()->firstOrCreate(
            ['id' => 1],
            [
                'pharmacy_name' => 'MediFlow Farma Utama',
                'address' => '',
                'phone' => '',
                'official_email' => '',
                'receipt_footer' => 'Terima kasih atas kunjungan Anda.',
                'show_pharmacist_name' => true,
                'vat_percent' => 11,
                'default_packaging_fee' => 2500,
            ]
        );

        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pharmacy_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:40'],
            'official_email' => ['nullable', 'email', 'max:255'],
            'receipt_footer' => ['nullable', 'string', 'max:2000'],
            'vat_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'default_packaging_fee' => ['required', 'integer', 'min:0', 'max:1000000000'],
        ]);
        $validated['show_pharmacist_name'] = $request->boolean('show_pharmacist_name');

        Setting::query()->updateOrCreate(['id' => 1], $validated);

        return redirect()->route('settings.edit')->with('status', 'Pengaturan berhasil disimpan.');
    }
}