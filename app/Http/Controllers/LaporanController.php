<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanRequest;
use App\Models\Report;
use App\Models\Segment;
use App\Support\FotoLaporan;
use App\Support\RiwayatLaporan;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LaporanController extends Controller
{
    public function create()
    {
        return view('halaman.sales.isi-laporan', [
            'segmen' => Segment::orderBy('nama')->get(),
        ]);
    }

    public function store(LaporanRequest $request)
    {
        $data = $request->validated();

        try {
            [$path, $ukuran] = FotoLaporan::simpan($request->file('foto'));
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        try {
            $laporan = DB::transaction(function () use ($data, $path, $ukuran) {
                $laporan = Report::create(['user_id' => Auth::id()] + Arr::except($data, ['foto']));
                $laporan->images()->create(['path' => $path, 'size_bytes' => $ukuran]);

                return $laporan;
            });
        } catch (\Throwable $e) {
            FotoLaporan::hapus($path);
            throw $e;
        }

        return redirect()->route('laporan.saya')->with('bagikan', $laporan->id);
    }

    public function edit($id)
    {
        $laporan = $this->ambil($id);

        return view('halaman.edit-laporan', [
            'laporan' => $laporan,
            'segmen'  => Segment::orderBy('nama')->get(),
            'foto'    => $laporan->images()->latest('id')->first(),
            'batal'   => $this->tujuan(),
        ]);
    }

    public function update(LaporanRequest $request, $id)
    {
        $laporan   = $this->ambil($id);
        $data      = $request->validated();
        $perubahan = RiwayatLaporan::cariPerubahan($laporan, $data);

        $fotoBaru = null;

        if ($request->hasFile('foto')) {
            try {
                $fotoBaru = FotoLaporan::simpan($request->file('foto'));
            } catch (RuntimeException $e) {
                return back()->withInput()->with('error', $e->getMessage());
            }
        }

        if (! $perubahan && ! $fotoBaru) {
            return redirect()->to($this->tujuan())->with('success', 'Tidak ada perubahan yang disimpan.');
        }

        try {
            DB::transaction(function () use ($laporan, $data, $perubahan, $fotoBaru) {
                $fotoLama = $laporan->images()->latest('id')->first();

                $laporan->update(Arr::except($data, ['foto']));

                if ($fotoBaru) {
                    [$path, $ukuran] = $fotoBaru;

                    $laporan->images()->create(['path' => $path, 'size_bytes' => $ukuran]);

                    $perubahan[] = ['kolom' => 'Foto', 'isi_lama' => $fotoLama?->path, 'isi_baru' => $path];
                }

                RiwayatLaporan::catatUbah($laporan, $perubahan);
            });
        } catch (\Throwable $e) {
            if ($fotoBaru) {
                FotoLaporan::hapus($fotoBaru[0]);
            }
            throw $e;
        }

        return redirect()->to($this->tujuan())->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = $this->ambil($id);
        $dipindah = [];

        try {
            DB::transaction(function () use ($laporan, &$dipindah) {
                RiwayatLaporan::catatHapus($laporan);

                foreach ($laporan->images as $foto) {
                    $baru = FotoLaporan::pindahKeDihapus($foto->path);

                    if ($baru !== $foto->path) {
                        $dipindah[] = [$foto->path, $baru];
                        $foto->update(['path' => $baru]);
                    }
                }

                $laporan->delete();
            });
        } catch (\Throwable $e) {
            foreach ($dipindah as [$lama, $baru]) {
                FotoLaporan::pindah($baru, $lama);
            }
            throw $e;
        }

        return back()->with('success', "Laporan {$laporan->company_name} berhasil dihapus.");
    }

    private function ambil($id): Report
    {
        $laporan = Report::findOrFail($id);

        if (Auth::user()->role === 'sales' && (int) $laporan->user_id !== (int) Auth::id()) {
            abort(403);
        }

        return $laporan;
    }

    private function tujuan(): string
    {
        return Auth::user()->role === 'sales' ? route('laporan.saya') : route('dashboard');
    }
}