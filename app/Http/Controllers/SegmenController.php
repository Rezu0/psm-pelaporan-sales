<?php

namespace App\Http\Controllers;

use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SegmenController extends Controller
{
    public function index()
    {
        $segmen = Segment::withCount(['reports' => fn ($q) => $q->withTrashed()])
            ->orderBy('nama')
            ->get();

        return view('halaman.admin.index-segmen', compact('segmen'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            ['nama' => ['required', 'string', 'max:100', 'unique:segments,nama']],
            $this->pesan()
        );

        Segment::create($data);

        return back()->with('success', "Segmen {$data['nama']} berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $segmen = Segment::findOrFail($id);

        $data = $request->validate(
            ['nama' => ['required', 'string', 'max:100', Rule::unique('segments', 'nama')->ignore($segmen->id)]],
            $this->pesan()
        );

        if ($segmen->nama === $data['nama']) {
            return back()->with('success', 'Tidak ada perubahan yang disimpan.');
        }

        $lama = $segmen->nama;
        $segmen->update($data);

        return back()->with('success', "Segmen {$lama} berhasil diubah menjadi {$data['nama']}.");
    }

    public function destroy($id)
    {
        $segmen = Segment::withCount(['reports' => fn ($q) => $q->withTrashed()])
            ->findOrFail($id);

        if ($segmen->reports_count > 0) {
            return back()->with('error', "Segmen {$segmen->nama} masih dipakai {$segmen->reports_count} laporan, jadi tidak bisa dihapus.");
        }

        $segmen->delete();

        return back()->with('success', "Segmen {$segmen->nama} berhasil dihapus.");
    }

    private function pesan(): array
    {
        return [
            'nama.required' => 'Nama segmen wajib diisi.',
            'nama.max'      => 'Nama segmen maksimal 100 huruf.',
            'nama.unique'   => 'Nama segmen itu sudah ada.',
        ];
    }
}