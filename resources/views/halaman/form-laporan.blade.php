{{-- Form bersama untuk isi dan edit laporan --}}
@php
    $nilai = fn ($kolom) => old($kolom, $laporan?->{$kolom});
    $kotak = 'mt-1 w-full rounded-xl border border-gray-300 px-3 py-2';
@endphp

<form action="{{ $aksi }}" method="POST" enctype="multipart/form-data" autocomplete="off"
      x-data="{
          kirim: false, pratinjau: null, fotoError: '',
          // jam sekarang di komputer sales, format yang dimengerti kolom tanggal+jam
          sekarang() { const d = new Date(); return new Date(d - d.getTimezoneOffset() * 60000).toISOString().slice(0, 16); },
      }" @submit="kirim = true"
      class="mt-4 space-y-4 rounded-3xl bg-white p-6 shadow-sm">
    @csrf
    @if ($metode !== 'POST')
        @method($metode)
    @endif

    <p class="text-sm text-gray-500">Tanda <span class="text-red-500">*</span> artinya wajib diisi. Email dan No. telepon boleh dikosongkan.</p>

    <div>
        <label class="text-sm font-semibold">Segmen <span class="text-red-500">*</span></label>
        <select name="segment_id" class="{{ $kotak }}" required>
            <option value="">Pilih segmen</option>
            @foreach ($segmen as $s)
                <option value="{{ $s->id }}" @selected($nilai('segment_id') == $s->id)>{{ $s->nama }}</option>
            @endforeach
        </select>
        @error('segment_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-sm font-semibold">Perusahaan <span class="text-red-500">*</span></label>
        <input type="text" name="company_name" autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" value="{{ $nilai('company_name') }}" class="{{ $kotak }}" required>
        @error('company_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold">Nama kontak <span class="text-red-500">*</span></label>
            <input type="text" name="contact_name" autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" value="{{ $nilai('contact_name') }}" class="{{ $kotak }}" required>
            @error('contact_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-semibold">Email kontak <span class="font-normal text-gray-400">(boleh kosong)</span></label>
            <input type="text" inputmode="email" name="contact_email" autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" value="{{ $nilai('contact_email') }}" class="{{ $kotak }}">
            @error('contact_email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold">No. telepon <span class="font-normal text-gray-400">(boleh kosong)</span></label>
            <input type="text" name="contact_phone" autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" value="{{ $nilai('contact_phone') }}" class="{{ $kotak }}">
            @error('contact_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-semibold">Tanggal dan jam kunjungan <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="market_date" max="{{ now()->format('Y-m-d\TH:i') }}"
                   x-ref="waktu" @focus="$el.max = sekarang()" @change="$el.max = sekarang()"
                   value="{{ old('market_date', $laporan?->market_date?->format('Y-m-d\TH:i')) }}"
                   class="{{ $kotak }}" required>
            @error('market_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="text-sm font-semibold">Deskripsi <span class="text-red-500">*</span></label>
        <textarea name="description" autocomplete="off" rows="4" class="{{ $kotak }}" required>{{ $nilai('description') }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-sm font-semibold">
            Foto @if (! $laporan)<span class="text-red-500">*</span>@endif
            @if ($laporan) <span class="font-normal text-gray-400">(kosongkan jika tidak ingin mengganti)</span> @endif
        </label>
        <input type="file" name="foto" accept="image/*" class="{{ $kotak }}" @required(! $laporan)
               @change="
                   fotoError = '';
                   const f = $event.target.files[0];
                   if (f && f.size > 15 * 1024 * 1024) {
                       fotoError = 'Ukuran foto ' + (f.size / 1024 / 1024).toFixed(1) + ' MB, melebihi batas 15 MB. Pilih foto lain.';
                       $event.target.value = '';
                       pratinjau = null;
                   } else {
                       pratinjau = f ? URL.createObjectURL(f) : null;
                   }">
        <p class="mt-1 text-xs text-gray-400">Format JPG, PNG, atau WebP. Maksimal 15 MB. Foto otomatis dikecilkan saat dikirim.</p>
        <p x-show="fotoError" x-text="fotoError" style="display:none" class="mt-1 text-sm text-red-600"></p>
        @error('foto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

        @if ($foto)
            <img src="{{ asset('storage/'.$foto->path) }}" x-show="!pratinjau" class="mt-3 h-32 rounded-xl object-cover" alt="Foto saat ini">
        @endif
        <img x-show="pratinjau" :src="pratinjau" class="mt-3 h-32 rounded-xl object-cover" alt="Pratinjau" style="display:none">
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" :disabled="kirim" @click="$refs.waktu.max = sekarang()"
                class="rounded-xl bg-blue-600 px-6 py-2.5 font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
            <span x-show="!kirim">{{ $tombol }}</span>
            <span x-show="kirim" style="display:none">Mengirim...</span>
        </button>
        <a href="{{ $batal }}" class="rounded-xl bg-gray-100 px-6 py-2.5 font-semibold hover:bg-gray-200">Batal</a>
    </div>
</form>