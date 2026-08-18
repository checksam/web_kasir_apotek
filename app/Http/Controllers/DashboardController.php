<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->session()->get('user');

        $stats = [
            ['label' => 'Pengguna', 'value' => 3, 'color' => 'blue'],
            ['label' => 'Supplier', 'value' => 2, 'color' => 'teal'],
            ['label' => 'Pelanggan', 'value' => 2, 'color' => 'yellow'],
            ['label' => 'Item Barang', 'value' => 4, 'color' => 'red'],
        ];

        $stockAlerts = [
            'Amoxicillin 250mg',
            'Paracetamol 500mg',
        ];

        return view('dashboard.index', [
            'user' => $user,
            'stats' => $stats,
            'stockAlerts' => $stockAlerts,
            'dailyIncome' => 'Rp 400.000',
        ]);
    }
}
