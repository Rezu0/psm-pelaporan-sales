<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'dari' => ['nullable', 'date_format:Y-m-d'],
            'sampai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'cari'    => ['nullable', 'string', 'max:100'],
            'baris'   => ['nullable', 'in:10,25,50,100'],
            'detail'  => ['nullable', 'integer'],
        ]);

       $akhirInput = $request->input('sampai', $request->input('tanggal'));
       $tanggal = $akhirInput
            ? Carbon::createFromFormat('Y-m-d', $akhirInput)->startOfDay()
            : today();
        if ($tanggal->isFuture()) {
            $tanggal = today();
        }


        $mulai = $request->filled('dari')
            ? Carbon::createFromFormat('Y-m-d', $request->dari)->startOfDay()
            : $tanggal->copy()->subDay(6);
        if ($mulai->gt($tanggal)) {
            $mulai = $tanggal->copy();
        }

        $akhir = $tanggal->copy()->endOfDay();

        $perHalaman = (int) $request->input('baris', 10);
        $kata = trim((string) $request->input('cari', ''));

        $laporan = Report::withoutTrashed()
            ->with(['user', 'segment', 'images'])
            ->whereBetween('created_at', [$mulai, $akhir])
            ->when($kata !== '', function ($q) use($kata) {
                $q->where(function ($q) use($kata){
                    $q->where('contact_name', 'like', "%{$kata}%")
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
            ->find($request->detail);
        }

        return view('halaman.dashboard', [
            'totalLaporan' => Report::count(),
            'bulanIni' => Report::whereYear('created_at', now()->year)
                                ->whereMonth('created_at', now()->month)
                                ->count(),
            'totalSegmen' => Segment::count(),
            'tanggal' => $tanggal,
            'mulai' => $mulai,
            'laporan' => $laporan,
            'perHalaman' => $perHalaman,
            'kata' => $kata,
            'detail' => $detail,
        ]);
    }
}