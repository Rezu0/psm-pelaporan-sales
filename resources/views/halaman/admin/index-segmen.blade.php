@extends('layouts.app')
@section('judul', 'Kelola Segmen')

@section('content')
@php
    $pesan = session('success') ? ['tipe' => 'sukses', 'teks' => session('success')]
           : (session('error')  ? ['tipe' => 'gagal',  'teks' => session('error')]
           : ($errors->any()    ? ['tipe' => 'gagal',  'teks' => $errors->first()] : null));

    $bukaEdit = old('_edit_id') ? ['id' => (int) old('_edit_id'), 'nama' => old('nama')] : null;
@endphp

<div x-data="{
        toast: {{ Js::from($pesan) }},
        edit: {{ Js::from($bukaEdit) }},
        hapus: null,
        urlEdit(id)  { return '{{ route('segmen.update', '__ID__') }}'.replace('__ID__', id) },
        urlHapus(id) { return '{{ route('segmen.destroy', '__ID__') }}'.replace('__ID__', id) },
     }"
     x-init="if (toast) setTimeout(() => toast = null, 3500)"
     @keydown.escape.window="edit = null; hapus = null">

     <a href="{{ route('dashboard') }}"
        class="text-sm text-blue-600 hover:underline">&larr; Kembali ke dashboard</a>
    <h1 class="text-2xl font-bold">Kelola Segmen</h1>

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

    <form method="POST" action="{{ route('segmen.store') }}"
          class="mt-4 rounded-2xl bg-white p-6 shadow-sm">
        @csrf
        <label for="nama-baru" class="text-sm font-semibold">Tambah segmen</label>
        <div class="mt-2 flex gap-3">
            <input type="text" id="nama-baru" name="nama" placeholder="Contoh: Otomotif" required maxlength="100"
                   value="{{ old('_edit_id') ? '' : old('nama') }}"
                   class="min-w-0 flex-1 rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            <button class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">Tambah</button>
        </div>
    </form>

    <div class="mt-6 overflow-x-auto rounded-2xl bg-white shadow-sm">
        <table class="w-full text-left">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-4 font-medium">Nama segmen</th>
                    <th class="px-6 py-4 font-medium">Dipakai laporan</th>
                    <th class="px-6 py-4 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($segmen as $s)
                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $s->nama }}</td>
                        <td class="px-6 py-4">{{ $s->reports_count }} laporan</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <button type="button"
                                    @click="edit = { id: {{ $s->id }}, nama: {{ Js::from($s->nama) }} }; $nextTick(() => $refs.namaEdit.select())"
                                    class="font-medium text-blue-600 hover:underline">Edit</button>

                            @if ($s->reports_count === 0)
                                <button type="button"
                                        @click="hapus = { id: {{ $s->id }}, nama: {{ Js::from($s->nama) }} }"
                                        class="ms-4 font-medium text-red-600 hover:underline">Hapus</button>
                            @else
                                <span class="ms-4 cursor-not-allowed text-gray-400" title="Masih dipakai laporan">Hapus</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-6 text-center text-gray-500">Belum ada segmen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="edit" style="display:none" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="edit = null"></div>

        <form method="POST" :action="edit ? urlEdit(edit.id) : '#'"
              class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
            @csrf
            @method('PUT')
            <input type="hidden" name="_edit_id" :value="edit && edit.id">

            <div class="px-6 pt-6">
                <h3 class="text-lg font-semibold">Edit segmen</h3>
                <p class="mt-1 text-sm text-gray-500">Ubah nama segmen. Laporan yang sudah memakai segmen ini ikut berganti nama.</p>
                <input type="text" name="nama" x-ref="namaEdit" :value="edit ? edit.nama : ''" @input="edit.nama = $event.target.value" required maxlength="100"
                       class="mt-4 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-gray-200 px-6 py-4">
                <button type="button" @click="edit = null"
                        class="rounded-lg border border-gray-300 px-4 py-2 font-medium hover:bg-gray-50">Batal</button>
                <button class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>

    <div x-show="hapus" style="display:none" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="hapus = null"></div>

        <form method="POST" :action="hapus ? urlHapus(hapus.id) : '#'"
              class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
            @csrf
            @method('DELETE')

            <div class="px-6 pt-6">
                <h3 class="text-lg font-semibold">Yakin hapus segmen ini?</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Segmen <span class="font-semibold text-gray-800" x-text="hapus && hapus.nama"></span>
                    akan dihapus dan tidak bisa dikembalikan.
                </p>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-gray-200 px-6 py-4">
                <button type="button" @click="hapus = null"
                        class="rounded-lg border border-gray-300 px-4 py-2 font-medium hover:bg-gray-50">Batal</button>
                <button class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700">Ya, hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection