@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p class="mt-1 text-slate-600">Ringkasan laporan aktivitas sales.</p>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Laporan</p>
            <p class="mt-2 text-3xl font-bold">{{ $totalLaporan }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Laporan Bulan Ini</p>
            <p class="mt-2 text-3xl font-bold">{{ $laporanBulanIni }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Jumlah Segmen</p>
            <p class="mt-2 text-3xl font-bold">{{ $jumlahSegment }}</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-semibold">Laporan Terbaru</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Perusahaan</th>
                        <th class="py-2 pr-4">Segmen</th>
                        <th class="py-2 pr-4">Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanTerbaru as $laporan)
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4">{{ \Carbon\Carbon::parse($laporan->market_date)->format('d M Y') }}</td>
                            <td class="py-3 pr-4">{{ $laporan->company_name }}</td>
                            <td class="py-3 pr-4">{{ $laporan->segment->name ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $laporan->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500">Belum ada laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection