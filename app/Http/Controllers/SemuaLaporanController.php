<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SemuaLaporanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['tahun' => ['nullable', 'integer', 'min:2000', 'max:2100']]);

        $tahunIni = now()->year;

        $pertama   = Report::withTrashed()->min('created_at');
        $tahunAwal = $pertama ? Carbon::parse($pertama)->year : $tahunIni;

        $tahun = (int) $request->input('tahun', $tahunIni);
        $tahun = max($tahunAwal, min($tahun, $tahunIni));

        $awal  = Carbon::create($tahun, 1, 1)->startOfDay();
        $akhir = Carbon::create($tahun, 12, 31)->endOfDay();

        $hitung = fn ($query) => $query
            ->whereBetween('created_at', [$awal, $akhir])
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan');

        return view('halaman.semua-laporan', [
            'tahun'     => $tahun,
            'tahunAwal' => $tahunAwal,
            'tahunIni'  => $tahunIni,
            'aktif'     => $hitung(Report::query()),       // laporan yang tidak dihapus
            'dihapus'   => $hitung(Report::onlyTrashed()), // laporan yang dihapus
        ]);
    }

    public function bulan(Request $request, string $bulan)
    {
        $request->validate([
            'cari'   => ['nullable', 'string', 'max:100'],
            'baris'  => ['nullable', 'in:10,25,50,100'],
            'detail' => ['nullable', 'integer'],
        ]);

        $awal = Carbon::createFromFormat('!Y-m', $bulan)->startOfMonth();

        abort_if($awal->isFuture(), 404);

        $akhir      = $awal->copy()->endOfMonth();
        $perHalaman = (int) $request->input('baris', 25);
        $kata       = trim((string) $request->input('cari', ''));

        $laporan = Report::withTrashed()
            ->with(['user', 'segment', 'images'])
            ->whereBetween('created_at', [$awal, $akhir])
            ->when($kata !== '', function ($q) use ($kata) {
                $q->where(function ($q) use ($kata) {
                    $q->where('company_name', 'like', "%{$kata}%")
                      ->orWhere('contact_name', 'like', "%{$kata}%")
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$kata}%"));
                });
            })
            ->latest()       
            ->latest('id')
            ->paginate($perHalaman)
            ->appends($request->except('detail')); 

        $detail = null;
        if ($request->filled('detail')) {
            $detail = Report::withTrashed()
                ->with(['user', 'segment', 'images'])
                ->whereBetween('created_at', [$awal, $akhir])
                ->find($request->input('detail'));
        }

        return view('halaman.laporan-bulan', [
            'awal'       => $awal,
            'sebelumnya' => $awal->copy()->subMonth(),
            'berikutnya' => $awal->copy()->addMonth(),
            'laporan'    => $laporan,
            'perHalaman' => $perHalaman,
            'kata'       => $kata,
            'detail'     => $detail,
        ]);
    }
}