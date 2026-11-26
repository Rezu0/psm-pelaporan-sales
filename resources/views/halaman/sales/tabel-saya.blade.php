@php
    $td = 'border border-gray-200 px-3 py-2';
    $kolom = ['Perusahaan', 'Segmen', 'Nama kontak', 'No. telepon', 'Email', 'Gambar', 'Tgl kunjungan', 'Deskripsi', 'Dikirim'];
    if ($tombol) $kolom[] = 'Aksi';
@endphp

<div
    x-data="{
        hapus: null,
        urlHapus(id) { return '{{ route('laporan.destroy', 0) }}'.replace(/\/0$/, '/' + id) },
    }"
    @keydown.escape.window="hapus = null"
>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 text-left text-[13px] text-gray-700">
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($kolom as $judul)
                        <th class="{{ $td }} font-semibold">{{ $judul }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @forelse ($laporan as $l)
                    <tr class="align-middle {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="{{ $td }} min-w-48">
                            {{ $l->company_name }}
                            @if ($l->diubah > 0)
                                <span class="ml-1 rounded-full bg-amber-100 px-1.5 py-0.5 text-[11px] text-amber-700">Diedit</span>
                            @endif
                        </td>
                        <td class="{{ $td }}">{{ $l->segment->nama ?? '-' }}</td>
                        <td class="{{ $td }} min-w-32">{{ $l->contact_name }}</td>
                        <td class="{{ $td }} whitespace-nowrap">{{ $l->contact_phone ?: '-' }}</td>
                        <td class="{{ $td }}">
                            <p class="max-w-40 truncate" title="{{ $l->contact_email }}">{{ $l->contact_email ?: '-' }}</p>
                        </td>
                        <td class="{{ $td }}">
                            @if ($l->foto)
                                <img src="{{ asset('storage/'.$l->foto->path) }}" alt="Foto" loading="lazy" data-lightbox
                                     class="h-9 w-12 cursor-zoom-in rounded object-cover">
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="{{ $td }} whitespace-nowrap">{{ $l->market_date->format('d/m/y H:i') }}</td>
                        <td class="{{ $td }}">
                            <p class="max-w-40 truncate" title="{{ $l->description }}">{{ $l->description }}</p>
                        </td>
                        <td class="{{ $td }} whitespace-nowrap">{{ $l->created_at->format('d/m H:i') }}</td>

                        @if ($tombol)
                            <td class="{{ $td }} whitespace-nowrap">
                                <button type="button"
                                    class="salin-laporan rounded-md bg-blue-50 px-2 py-1 font-semibold text-blue-600 hover:bg-blue-100"
                                    data-text="{{ $l->teksBagikan() }}">
                                    <i class="fa-regular fa-copy"></i> Copy
                                </button>

                                <a href="{{ route('laporan.edit', $l->id) }}"
                                   class="rounded-md bg-blue-50 px-2 py-1 font-semibold text-blue-600 hover:bg-blue-100">Edit</a>

                                <button type="button"
                                        @click="hapus = { id: {{ $l->id }}, nama: {{ Js::from($l->company_name) }} }"
                                        class="rounded-md bg-red-50 px-2 py-1 font-semibold text-red-600 hover:bg-red-100">Hapus</button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($kolom) }}" class="px-4 py-8 text-center text-gray-400">
                            {{ $pesanKosong ?? 'Belum ada laporan.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal konfirmasi hapus --}}
    @if ($tombol)
        <div x-show="hapus" style="display:none" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="hapus = null"></div>

            <form method="POST" :action="hapus ? urlHapus(hapus.id) : '#'"
                  class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
                @csrf
                @method('DELETE')

                <div class="px-6 pt-6">
                    <h3 class="text-lg font-semibold">Yakin hapus laporan ini?</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Laporan <span class="font-semibold text-gray-800" x-text="hapus && hapus.nama"></span>
                        akan dihapus dari daftarmu.
                    </p>
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-gray-200 px-6 py-4">
                    <button type="button" @click="hapus = null"
                            class="rounded-lg border border-gray-300 px-4 py-2 font-medium hover:bg-gray-50">Batal</button>
                    <button class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700">Ya, hapus</button>
                </div>
            </form>
        </div>
    @endif
</div>

<x-lightbox />