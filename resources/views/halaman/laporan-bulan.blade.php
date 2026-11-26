@extends('layouts.app')

@section('content')
<div class="space-y-3">

    <a href="{{ route('semua-laporan.index', ['tahun' => $awal->year]) }}"
       class="text-sm text-blue-600 hover:underline">&larr; Semua bulan</a>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-semibold">
            Laporan {{ $awal->translatedFormat('F Y') }}
            <span class="ms-1 rounded-full bg-blue-100 px-2 py-0.5 text-sm font-normal text-blue-700">{{ $laporan->total() }}</span>
        </h1>

        {{-- Bulan sebelumnya / berikutnya --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('semua-laporan.bulan', $sebelumnya->format('Y-m')) }}"
               class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">&larr;</a>

            @if ($berikutnya->isFuture())
                <span class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-300">&rarr;</span>
            @else
                <a href="{{ route('semua-laporan.bulan', $berikutnya->format('Y-m')) }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">&rarr;</a>
            @endif
        </div>
    </div>

    @include('halaman.tabel-laporan', [
        'aksi'        => route('semua-laporan.bulan', $awal->format('Y-m')),
        'tersembunyi' => [],
        'resetUrl'    => route('semua-laporan.bulan', ['bulan' => $awal->format('Y-m'), 'baris' => $perHalaman]),
        'kelompok'    => true,
        'pesanKosong' => 'Belum ada laporan di bulan ini.',
    ])
</div>
@endsection