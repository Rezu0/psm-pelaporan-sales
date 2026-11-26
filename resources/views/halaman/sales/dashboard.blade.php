@extends('layouts.app')
@section('judul', 'Dashboard')

@section('content')
<x-popup />

<div class="rounded-3xl bg-white p-6 shadow-sm">
    <p class="text-gray-500">Halo,</p>
    <h1 class="text-3xl font-bold">{{ $namaDepan }}</h1>
    <a href="{{ route('laporan.create') }}"
       class="mt-5 inline-block rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">
        + Isi Laporan
    </a>
</div>

<div class="mt-8 flex items-center justify-between">
    <h2 class="text-xl font-semibold">Laporan terbaru</h2>
    <a href="{{ route('laporan.saya') }}" class="text-sm font-semibold text-blue-600 hover:underline">
        Lihat semua →
    </a>
</div>

<div class="mt-3 rounded-3xl bg-white p-4 shadow-sm">
    @include('halaman.sales.tabel-saya', ['laporan' => $laporan, 'tombol' => false])

    <div class="mt-4 text-center">
        <a href="{{ route('laporan.saya') }}"
           class="inline-block rounded-xl border border-blue-600 px-5 py-2 font-semibold text-blue-600 hover:bg-blue-50">
            Lihat semua tabel laporan
        </a>
    </div>
</div>
@endsection
