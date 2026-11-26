@php
    $teks = [
        'belum_terdaftar' => 'Akun Google ini belum terdaftar. Mau daftar sekarang?',
        'belum_aktif'     => 'Akunmu belum aktif. Tunggu persetujuan admin, atau hubungi admin kalau sudah lama.',
        'berhasil'        => 'Pendaftaran berhasil. Akunmu menunggu persetujuan admin.',
    ];
    $popup = session('popup');
    $pesan = $teks[$popup] ?? null;
@endphp

@if ($pesan)
    <div x-data="{ open: true }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.outside="open = false" class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
            <p class="mb-6">{{ $pesan }}</p>
            <div class="flex justify-end gap-2">
                @if ($popup === 'belum_terdaftar')
                    <button @click="open = false" class="px-4 py-2 rounded-lg border">Batal</button>
                    <a href="{{ route('register.google') }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Lanjut</a>
                @else
                    <button @click="open = false" class="px-4 py-2 rounded-lg bg-blue-600 text-white">OK</button>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- Mode konfirmasi: dipanggil dengan $dispatch('konfirmasi', { jenis: 'hapus' | 'edit' | 'kirim', ok: () => ... }) --}}
<div x-data="{
        buka: false,
        jenis: 'hapus',
        ok: null,
        isi: {
            hapus: { pesan: 'Hapus laporan ini?',                 tombol: 'Ya, hapus',   kelas: 'bg-red-600' },
            edit:  { pesan: 'Simpan perubahan pada laporan ini?', tombol: 'Ya, simpan',  kelas: 'bg-blue-600' },
            kirim: { pesan: 'Kirim laporan ini?',                 tombol: 'Ya, kirim',   kelas: 'bg-blue-600' },
        },
        tampil(d) { this.jenis = d.jenis; this.ok = d.ok; this.buka = true; },
        setuju() { this.buka = false; if (this.ok) this.ok(); },
     }"
     @konfirmasi.window="tampil($event.detail)"
     @keydown.escape.window="buka = false"
     x-show="buka" x-cloak style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div @click.outside="buka = false" class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
        <p class="mb-6" x-text="isi[jenis].pesan"></p>
        <div class="flex justify-end gap-2">
            <button type="button" @click="buka = false" class="px-4 py-2 rounded-lg border">Batal</button>
            <button type="button" @click="setuju()" x-text="isi[jenis].tombol"
                    class="px-4 py-2 rounded-lg text-white" :class="isi[jenis].kelas"></button>
        </div>
    </div>
</div>