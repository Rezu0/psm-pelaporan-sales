<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BerandaController extends Controller
{
    public function index()
    {
        return view('halaman.sales.dashboard', [
            'laporan'   => Report::milik(Auth::id())->take(5)->get(),
            'namaDepan' => Str::before(Auth::user()->name, ' '),
        ]);
    }
}
