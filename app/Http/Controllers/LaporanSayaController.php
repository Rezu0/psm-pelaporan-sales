<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanSayaController extends Controller
{
    // Semua laporan milik sales yang login
    public function index(Request $request)
    {
        $request->validate(['cari' => ['nullable', 'string', 'max:100']]);
        $kata = trim((string) $request->input('cari', ''));

        $laporan = Report::milik(Auth::id())
            ->cari($kata)
            ->paginate(10)
            ->withQueryString();

        return view('halaman.sales.laporan-saya', compact('laporan', 'kata'));
    }
}