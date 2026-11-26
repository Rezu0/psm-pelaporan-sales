@extends('layouts.app')
@section('judul', 'Profil')

@section('content')
@php
    $pesan = session('success') ? ['tipe' => 'sukses', 'teks' => session('success')]
           : (session('error')  ? ['tipe' => 'gagal',  'teks' => session('error')]
           : ($errors->any()    ? ['tipe' => 'gagal',  'teks' => $errors->first()] : null));

    $kotak = 'w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200';
@endphp

<div x-data="{
        toast: {{ Js::from($pesan) }},
        konfirmasi: false,
        lihat: false,
        password: '',
     }"
     x-init="if (toast) setTimeout(() => toast = null, 3500)"
     @keydown.escape.window="konfirmasi = false"
     class="mx-auto max-w-3xl">

    <h1 class="text-2xl font-bold">Profil</h1>

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

    <form x-ref="form" method="POST" action="{{ route('profil.update') }}"
          class="mt-4 space-y-4 rounded-3xl bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-xl font-semibold text-blue-600">
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-lg font-semibold">{{ $user->name }}</p>
                <p class="text-sm capitalize text-gray-500">{{ $user->role }}</p>
            </div>
        </div>

        <div>
            <label for="name" class="mb-1 block text-sm font-semibold">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="{{ $kotak }}">
        </div>

        <div>
            <label for="contact" class="mb-1 block text-sm font-semibold">No. Telp</label>
            <input type="text" id="contact" name="contact" value="{{ old('contact', $user->contact) }}" required class="{{ $kotak }}">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Email</label>
            <input type="text" value="{{ $user->email }}" disabled
                   class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-gray-500">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Peran</label>
            <input type="text" value="{{ ucfirst($user->role) }}" disabled
                   class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-gray-500">
        </div>

        <p class="text-xs text-gray-400">Email dan peran hanya bisa diubah oleh admin.</p>

        <div>
            <label for="password" class="mb-1 block text-sm font-semibold">Password baru</label>
            <div class="relative">
                <input :type="lihat ? 'text' : 'password'" id="password" name="password" x-model="password"
                       placeholder="Kosongkan kalau tidak ingin mengubah" minlength="8" autocomplete="new-password"
                       class="{{ $kotak }} pe-28">
                <button type="button" @click="lihat = !lihat"
                        class="absolute inset-y-0 inset-e-0 px-4 text-sm text-gray-500 hover:text-gray-700"
                        x-text="lihat ? 'Sembunyikan' : 'Lihat'"></button>
            </div>
            <p class="mt-1 text-xs text-gray-400">
                Minimal 8 karakter. Isi untuk bisa login lewat form email dan password (misalnya kalau kamu daftar lewat Google).
            </p>
        </div>

        <button type="button" @click="$refs.form.reportValidity() && (konfirmasi = true)"
                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">
            Simpan
        </button>

        <div x-show="konfirmasi" style="display:none" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="konfirmasi = false"></div>

            <div class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
                <div class="px-6 pt-6">
                    <h3 class="text-lg font-semibold">Simpan perubahan?</h3>
                    <p class="mt-1 text-sm text-gray-500">Perubahan pada profilmu akan disimpan.</p>
                    <p class="mt-2 text-sm text-gray-500" x-show="password.length > 0">
                        Password kamu juga akan <span class="font-semibold text-gray-800">diganti</span>.
                    </p>
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-gray-200 px-6 py-4">
                    <button type="button" @click="konfirmasi = false"
                            class="rounded-lg border border-gray-300 px-4 py-2 font-medium hover:bg-gray-50">Batal</button>
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection