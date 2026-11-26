<?php

namespace App\Http\Controllers;

use App\Models\Report;

class LaporanBagikanController extends Controller
{
    public function __invoke($id)
    {
        $item = Report::with(['segment', 'images', 'user'])->findOrFail($id);

        return view('halaman.sales.kartu-laporan', ['item' => $item]);
    }
}