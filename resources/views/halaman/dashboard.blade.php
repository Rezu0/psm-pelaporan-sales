@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    @include('halaman.card', [
        'judul' => 'Total Laporan',
        'angka' => $totalLaporan,
        'url'   => route('semua-laporan.index'),
    ])

    @include('halaman.card', [
        'judul' => 'Laporan Bulan Ini',
        'angka' => $bulanIni,
        'url'   => route('semua-laporan.bulan', now()->format('Y-m')),
    ])

    @include('halaman.card', [
        'judul' => 'Jumlah Segmen',
        'angka' => $totalSegmen,
        'url'   => auth()->user()->role === 'admin' ? route('segmen.index') : null,
    ])
</div>

     <div class="mt-6">
        @include('halaman.tabel')
    </div>
@endsection