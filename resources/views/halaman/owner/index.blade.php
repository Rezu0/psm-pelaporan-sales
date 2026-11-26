@extends('layouts.app')

@section('content')
<div x-data="{ cari: '' }" class="space-y-8">

    <a href="{{ route('dashboard') }}"
                class="text-sm text-blue-600 hover:underline">&larr; Kembali ke dashboard</a>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold">Kelola Akun</h1>

        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 inset-s-0 flex items-center ps-3">
                <svg class="h-4 w-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                          d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <label for="cari-akun" class="sr-only">Cari</label>
            <input type="text" id="cari-akun" x-model="cari" placeholder="Cari nama, email, atau no. telp"
                   class="block w-72 rounded-lg border border-gray-300 bg-gray-50 py-2 ps-9 pe-3 text-sm
                          placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-green-100 px-4 py-3 text-green-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-lg bg-red-100 px-4 py-3 text-red-800">{{ session('error') }}</div>
    @endif

    <section class="space-y-3">
        <h2 class="text-lg font-medium">
            Belum disetujui
            <span class="ms-1 rounded-full bg-amber-100 px-2 py-0.5 text-sm text-amber-700">{{ $menunggu->count() }}</span>
        </h2>
        @include('halaman.owner.tabel-akun', [
            'daftar' => $menunggu,
            'pesanKosong' => 'Tidak ada akun yang menunggu persetujuan.',
        ])
    </section>

    <section class="space-y-3">
        <h2 class="text-lg font-medium">
            Sudah disetujui
            <span class="ms-1 rounded-full bg-green-100 px-2 py-0.5 text-sm text-green-700">{{ $aktif->count() }}</span>
        </h2>
        @include('halaman.owner.tabel-akun', [
            'daftar' => $aktif,
            'pesanKosong' => 'Belum ada akun yang aktif.',
        ])
    </section>
</div>
@endsection