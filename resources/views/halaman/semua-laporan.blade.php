@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('dashboard') }}"
                class="text-sm text-blue-600 hover:underline">&larr; Kembali ke dashboard</a>
            <h1 class="text-2xl font-semibold">Semua Laporan</h1>
            <p class="text-sm text-gray-500">Pilih bulan untuk melihat laporan yang masuk di bulan itu.</p>
        </div>

        {{-- Pindah tahun --}}
        <div class="flex items-center gap-2">
            @if ($tahun > $tahunAwal)
                <a href="{{ route('semua-laporan.index', ['tahun' => $tahun - 1]) }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">&larr;</a>
            @else
                <span class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-300">&larr;</span>
            @endif

            <span class="min-w-20 text-center text-lg font-semibold">{{ $tahun }}</span>

            @if ($tahun < $tahunIni)
                <a href="{{ route('semua-laporan.index', ['tahun' => $tahun + 1]) }}"
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">&rarr;</a>
            @else
                <span class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-300">&rarr;</span>
            @endif
        </div>
    </div>

    {{-- Kartu 12 bulan --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
        @for ($b = 1; $b <= 12; $b++)
            @php
                $tgl       = \Carbon\Carbon::create($tahun, $b, 1);
                $masaDepan = $tgl->isFuture();
                $sekarang  = $tahun === $tahunIni && $b === now()->month;
                $jumlah    = $aktif[$b] ?? 0;
                $hapus     = $dihapus[$b] ?? 0;
            @endphp

            @if ($masaDepan)
                <div class="rounded-2xl bg-gray-100 p-5 text-gray-400">
                    <p class="text-sm">{{ $tgl->translatedFormat('F') }}</p>
                    <p class="mt-1 text-3xl font-bold">-</p>
                </div>
            @else
                <a href="{{ route('semua-laporan.bulan', $tgl->format('Y-m')) }}"
                   class="block rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md hover:ring-1 hover:ring-blue-200
                          {{ $sekarang ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">{{ $tgl->translatedFormat('F') }}</p>
                        @if ($sekarang)
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[11px] text-blue-700">Bulan ini</span>
                        @endif
                    </div>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlah }}</p>
                    <p class="text-xs text-gray-400">
                        laporan
                        @if ($hapus > 0)
                            <span class="text-red-400">({{ $hapus }} dihapus)</span>
                        @endif
                    </p>
                </a>
            @endif
        @endfor
    </div>
</div>
@endsection