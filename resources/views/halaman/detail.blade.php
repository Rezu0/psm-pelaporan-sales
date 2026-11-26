@if ($detail)
    @php
        $foto  = $detail->images->sortByDesc('id')->first();
        $tutup = request()->fullUrlWithQuery(['detail' => null]);

        // Cek bentuk foto: portrait kalau tingginya lebih besar dari lebarnya
        $potret = false;
        if ($foto) {
            $lokasi = \Illuminate\Support\Facades\Storage::disk('public')->path($foto->path);
            $ukuran = is_file($lokasi) ? @getimagesize($lokasi) : false;
            $potret = $ukuran && $ukuran[1] > $ukuran[0];
        }
    @endphp

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <a href="{{ $tutup }}" class="absolute inset-0 bg-black/40" aria-label="Tutup"></a>

        <div class="relative max-h-[85vh] w-full max-w-md overflow-y-auto rounded-3xl bg-white shadow-xl">

            <div class="sticky top-0 z-10 flex items-center justify-between bg-white px-5 py-3">
                <h3 class="text-base font-semibold">Detail Laporan</h3>
                <a href="{{ $tutup }}"
                   class="flex h-7 w-7 items-center justify-center rounded-full text-xl leading-none text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                   aria-label="Tutup">&times;</a>
            </div>

            @if ($detail->trashed())
                <div class="px-5 pb-3">
                    <div class="rounded-2xl bg-red-50 px-3 py-2 text-xs text-red-700">
                        Laporan ini dihapus pada {{ $detail->deleted_at->format('d M Y H:i') }}.
                    </div>
                </div>
            @endif

            {{-- Foto --}}
            @if ($foto && $potret)
                {{-- Portrait: penuh selebar popup --}}
                <img src="{{ asset('storage/'.$foto->path) }}" alt="Foto laporan"
                     class="block w-full">
            @elseif ($foto)
                {{-- Landscape: selebar popup, ada batas terang di atas dan bawah --}}
                <div class="border-y border-gray-200 bg-gray-100 py-4">
                    <img src="{{ asset('storage/'.$foto->path) }}" alt="Foto laporan"
                         class="block w-full">
                </div>
            @else
                <div class="flex h-24 items-center justify-center border-y border-gray-200 bg-gray-100 text-xs text-gray-400">
                    Tidak ada foto
                </div>
            @endif

            <div class="space-y-4 px-5 pb-4 pt-4 text-xs text-gray-700">

                <dl class="grid grid-cols-2 gap-x-4 gap-y-3">
                    <div>
                        <dt class="text-[11px] text-gray-500">Nama sales</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->user?->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-gray-500">Kontak sales</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->user?->contact ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-gray-500">Segmen</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->segment?->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-gray-500">Nama perusahaan</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->company_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-gray-500">Nama kontak</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->contact_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-gray-500">No. telepon</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->contact_phone ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-gray-500">Email</dt>
                        <dd class="break-all font-medium text-gray-900">{{ $detail->contact_email ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] text-gray-500">Tanggal kunjungan</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->market_date->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-[11px] text-gray-500">Laporan dikirim</dt>
                        <dd class="font-medium text-gray-900">{{ $detail->created_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>

                <div>
                    <p class="text-[11px] text-gray-500">Deskripsi</p>
                    <p class="mt-0.5 whitespace-pre-line font-medium text-gray-900">{{ $detail->description }}</p>
                </div>
            </div>

            <div class="sticky bottom-0 flex justify-end bg-white px-5 py-3">
                <a href="{{ $tutup }}"
                   class="rounded-full border border-gray-300 px-5 py-1.5 text-xs text-gray-700 hover:bg-gray-50">Tutup</a>
            </div>
        </div>
    </div>
@endif