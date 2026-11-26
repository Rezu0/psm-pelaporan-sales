<div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">

    <form method="GET" action="{{ $aksi }}"
          class="mb-3 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-700">
        @foreach ($tersembunyi as $nama => $nilai)
            <input type="hidden" name="{{ $nama }}" value="{{ $nilai }}">
        @endforeach

        <label class="flex items-center gap-2">
            Show
            <select name="baris" onchange="this.form.submit()"
                    class="rounded border border-gray-300 px-2 py-1 focus:border-blue-500 focus:outline-none">
                @foreach ([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" @selected($perHalaman === $n)>{{ $n }}</option>
                @endforeach
            </select>
            entries
        </label>

        <label class="flex items-center gap-2">
            Search:
            <input type="text" name="cari" value="{{ $kata }}"
                   class="w-48 rounded border border-gray-300 px-2 py-1 focus:border-blue-500 focus:outline-none">
            @if ($kata !== '')
                <a href="{{ $resetUrl }}" class="text-xs text-gray-500 hover:underline">Reset</a>
            @endif
        </label>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-xs text-gray-700 [&_th]:border [&_th]:border-gray-200 [&_td]:border [&_td]:border-gray-200">
            <thead>
                <tr class="bg-white align-bottom">
                    <th class="px-2 py-2 font-semibold">Nama sales</th>
                    <th class="px-2 py-2 font-semibold">Kontak sales</th>
                    <th class="px-2 py-2 font-semibold">Segmen</th>
                    <th class="px-2 py-2 font-semibold">Perusahaan</th>
                    <th class="px-2 py-2 font-semibold">Nama kontak</th>
                    <th class="px-2 py-2 font-semibold">No. telepon</th>
                    <th class="px-2 py-2 font-semibold">Email</th>
                    <th class="px-2 py-2 font-semibold">Gambar</th>
                    <th class="px-2 py-2 font-semibold">Tgl kunjungan</th>
                    <th class="px-2 py-2 font-semibold">Deskripsi</th>
                    <th class="px-2 py-2 font-semibold">Dikirim</th>
                    <th class="px-2 py-2 font-semibold">Dihapus</th>
                </tr>
            </thead>

            <tbody>
                @php $tanggalSebelumnya = null; @endphp

                @forelse ($laporan as $r)
                    @if ($kelompok)
                        @php $tanggalInput = $r->created_at->toDateString(); @endphp
                        @if ($tanggalInput !== $tanggalSebelumnya)
                            @php $tanggalSebelumnya = $tanggalInput; @endphp
                            <tr class="bg-blue-50">
                                <td colspan="12" class="px-2 py-1.5 text-sm font-semibold text-blue-700">
                                    {{ $r->created_at->translatedFormat('l, d F Y') }}
                                </td>
                            </tr>
                        @endif
                    @endif

                    @php
                        $foto = $r->images->sortByDesc('id')->first();

                        $urlDetail = request()->fullUrlWithQuery(['detail' => $r->id]);
                    @endphp

                    <tr class="cursor-pointer align-middle hover:bg-gray-100
                               {{ $r->trashed() ? 'text-gray-400' : '' }}
                               {{ $kelompok ? 'bg-white' : 'odd:bg-gray-50 even:bg-white' }}"
                        onclick="window.location='{{ $urlDetail }}'">
                        <td class="px-2 py-1.5">{{ $r->user?->name }}</td>
                        <td class="whitespace-nowrap px-2 py-1.5">{{ $r->user?->contact ?: '-' }}</td>
                        <td class="px-2 py-1.5">{{ $r->segment?->nama }}</td>
                        <td class="px-2 py-1.5">{{ $r->company_name }}</td>
                        <td class="px-2 py-1.5">{{ $r->contact_name }}</td>
                        <td class="whitespace-nowrap px-2 py-1.5">{{ $r->contact_phone ?: '-' }}</td>
                        <td class="px-2 py-1.5"><p class="max-w-36 truncate" title="{{ $r->contact_email }}">{{ $r->contact_email ?: '-' }}</p></td>
                        <td class="px-2 py-1.5">
                            @if ($foto)
                                <img src="{{ asset('storage/'.$foto->path) }}" alt="Foto laporan" loading="lazy"
                                     class="h-8 w-8 rounded object-cover">
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded bg-gray-100 text-gray-400">-</div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-2 py-1.5">{{ $r->market_date->format('d/m/y H:i') }}</td>
                        <td class="px-2 py-1.5"><p class="max-w-36 truncate" title="{{ $r->description }}">{{ $r->description }}</p></td>
                        {{-- Rentang 7 hari: tampilkan tanggal + jam supaya jelas masuk hari apa --}}
                        <td class="whitespace-nowrap px-2 py-1.5">{{ $r->created_at->format('d/m H:i') }}</td>
                        <td class="whitespace-nowrap px-2 py-1.5">{{ $r->deleted_at?->format('d/m H:i') ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="p-6 text-center text-gray-500">
                            {{ $kata !== '' ? 'Tidak ada data yang cocok.' : $pesanKosong }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs text-gray-700">
        <p>
            Showing {{ $laporan->firstItem() ?? 0 }} to {{ $laporan->lastItem() ?? 0 }}
            of {{ $laporan->total() }} entries
        </p>

        {{ $laporan->links('halaman.datatables') }}
    </div>
</div>

@include('halaman.detail', ['detail' => $detail ?? null])