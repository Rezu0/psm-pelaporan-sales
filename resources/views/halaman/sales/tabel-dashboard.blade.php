<div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-sm text-gray-700 [&_th]:border [&_th]:border-gray-200 [&_td]:border [&_td]:border-gray-200">
            <thead>
                <tr class="bg-white">
                    <th class="px-3 py-2 font-semibold whitespace-nowrap">Perusahaan</th>
                    <th class="px-3 py-2 font-semibold">Segmen</th>
                    <th class="px-3 py-2 font-semibold whitespace-nowrap">Nama kontak</th>
                    <th class="px-3 py-2 font-semibold whitespace-nowrap">No. telepon</th>
                    <th class="px-3 py-2 font-semibold">Email</th>
                    <th class="px-3 py-2 font-semibold">Gambar</th>
                    <th class="px-3 py-2 font-semibold whitespace-nowrap">Tgl kunjungan</th>
                    <th class="px-3 py-2 font-semibold">Deskripsi</th>
                    <th class="px-3 py-2 font-semibold whitespace-nowrap">Dikirim</th>
                    @if ($tombol ?? false)
                        <th class="px-3 py-2 font-semibold">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse ($laporan as $r)
                    @php $foto = $r->images->sortByDesc('id')->first(); @endphp

                    <tr class="odd:bg-gray-50 even:bg-white hover:bg-gray-100">
                        <td class="px-3 py-2 whitespace-nowrap">
                            {{ $r->company_name }}
                            @if (($r->diubah ?? 0) > 0)
                                <span class="ms-1 rounded-full bg-amber-100 px-2 py-0.5 text-[11px] text-amber-700">Diedit</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $r->segment?->nama }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $r->contact_name }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $r->contact_phone ?: '-' }}</td>
                        <td class="px-3 py-2">{{ $r->contact_email }}</td>
                        <td class="px-3 py-2">
                            @if ($foto)
                                <img src="{{ asset('storage/'.$foto->path) }}" alt="Foto laporan" loading="lazy"
                                    class="h-10 w-10 rounded object-cover">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded bg-gray-100 text-xs text-gray-400">-</div>
                            @endif
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $r->market_date->format('d M Y H:i') }}</td>
                        <td class="px-3 py-2"><p class="line-clamp-2 max-w-xs">{{ $r->description }}</p></td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $r->created_at->format('d M Y H:i') }}</td>

                        @if ($tombol ?? false)
                            <td class="px-3 py-2 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('laporan.edit', $r->id) }}"
                                        class="font-medium text-blue-600 hover:underline">Edit</a>

                                    <form method="POST" action="{{ route('laporan.destroy', $r->id) }}"
                                        onsubmit="return confirm({{ Js::from('Hapus laporan '.$r->company_name.'?') }})">
                                        @csrf
                                        @method('DELETE')
                                        <button class="font-medium text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ ($tombol ?? false) ? 10 : 9 }}" class="p-6 text-center text-gray-500">
                            {{ $pesanKosong ?? 'Belum ada laporan. Klik "Isi Laporan" untuk membuat yang pertama.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>