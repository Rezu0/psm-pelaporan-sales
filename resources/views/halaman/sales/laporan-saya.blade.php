@extends('layouts.app')
@section('judul', 'Laporan Saya')

@section('content')
@php
    $pesan = session('success') ? ['tipe' => 'sukses', 'teks' => session('success')]
           : (session('error')  ? ['tipe' => 'gagal',  'teks' => session('error')]
           : ($errors->any()    ? ['tipe' => 'gagal',  'teks' => $errors->first()] : null));
@endphp

<div x-data="{ toast: {{ Js::from($pesan) }} }"
     x-init="if (toast) setTimeout(() => toast = null, 3500)">
    <div x-show="toast" style="display:none"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="-translate-y-4 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed left-1/2 top-4 z-60 w-[calc(100%-2rem)] max-w-sm -translate-x-1/2" role="status">
        <div class="flex items-start gap-3 rounded-2xl border bg-white px-4 py-3 shadow-lg"
             :class="toast && toast.tipe === 'sukses' ? 'border-green-200' : 'border-red-200'">
            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white"
                  :class="toast && toast.tipe === 'sukses' ? 'bg-green-500' : 'bg-red-500'"
                  x-text="toast && toast.tipe === 'sukses' ? '✓' : '!'"></span>
            <p class="flex-1 text-sm text-gray-800" x-text="toast && toast.teks"></p>
            <button type="button" @click="toast = null" class="text-lg leading-none text-gray-400 hover:text-gray-600" aria-label="Tutup">&times;</button>
        </div>
    </div>
</div>

<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold">Laporan Saya</h1>
        <p id="jumlah-laporan" class="text-sm text-gray-500">{{ $laporan->total() }} laporan.</p>
    </div>
    <a href="{{ route('laporan.create') }}"
       class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">+ Isi Laporan</a>
</div>

<div class="mt-4 rounded-3xl bg-white p-4 shadow-sm">
    <form id="form-cari" method="GET" action="{{ route('laporan.saya') }}" class="mb-4 flex items-center justify-end gap-2">
        <span id="tanda-memuat" style="display:none" class="text-xs text-gray-400">Mencari...</span>
        <input id="input-cari" type="text" name="cari" value="{{ $kata }}" autocomplete="off"
               placeholder="Cari perusahaan / kontak / segmen..."
               class="w-64 rounded-xl border border-gray-300 px-3 py-2 text-sm">
        <button class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold hover:bg-gray-200">Cari</button>
    </form>

    <div id="hasil-laporan">
        @include('halaman.sales.tabel-saya', [
            'laporan'     => $laporan,
            'tombol'      => true,
            'pesanKosong' => $kata !== '' ? 'Tidak ada laporan yang cocok.' : 'Belum ada laporan.',
        ])

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            <span>Showing {{ $laporan->firstItem() ?? 0 }} to {{ $laporan->lastItem() ?? 0 }} of {{ $laporan->total() }} entries</span>
            {{ $laporan->links('halaman.datatables') }}
        </div>
    </div>
</div>

<x-popup-bagikan />

@endsection