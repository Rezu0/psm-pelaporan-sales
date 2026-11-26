@once
    @php
        $notif = session('success') ? ['tipe' => 'sukses', 'teks' => session('success')]
               : (session('error')  ? ['tipe' => 'gagal',  'teks' => session('error')]
               : ($errors->any()    ? ['tipe' => 'gagal',  'teks' => $errors->first()] : null));
    @endphp

    <div x-data="{ toast: {{ Js::from($notif) }} }"
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
@endonce

<div x-data="{
        nonaktif: null,
        urlStatus(uuid) { return '{{ route('akun.status', '__ID__') }}'.replace('__ID__', uuid) },
     }"
     @keydown.escape.window="nonaktif = null">

    <div class="relative overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="border-b border-gray-200 bg-gray-50 text-sm text-gray-600">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">Nama</th>
                    <th scope="col" class="px-6 py-3 font-medium">No. Telp</th>
                    <th scope="col" class="px-6 py-3 font-medium">Peran</th>
                    <th scope="col" class="px-6 py-3 font-medium">Tanggal daftar</th>
                    <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftar as $u)
                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-gray-50"
                        data-teks="{{ strtolower($u->name.' '.$u->email.' '.$u->contact) }}"
                        x-show="$el.dataset.teks.includes(cari.toLowerCase())">

                        <th scope="row" class="flex items-center whitespace-nowrap px-6 py-4 text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-600">
                                {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                            </div>
                            <div class="ps-3">
                                <div class="text-base font-semibold">{{ $u->name }}</div>
                                <div class="font-normal text-gray-500">{{ $u->email }}</div>
                            </div>
                        </th>

                        <td class="px-6 py-4">{{ $u->contact }}</td>
                        <td class="px-6 py-4 capitalize">{{ $u->role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $u->created_at->format('d M Y H:i') }}</td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            @if ($u->id === Auth::id())
                                <span class="text-gray-400">Akun kamu</span>
                            @elseif (! $u->is_active)
                                <form method="POST" action="{{ route('akun.status', $u->uuid) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role"
                                            class="rounded-lg border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                                        @foreach (['sales' => 'Sales', 'owner' => 'Owner', 'admin' => 'Admin'] as $nilai => $label)
                                            <option value="{{ $nilai }}" @selected($u->role === $nilai)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="font-medium text-blue-600 hover:underline">Setujui</button>
                                </form>
                            @else
                                <button type="button"
                                        @click="nonaktif = { uuid: {{ Js::from($u->uuid) }}, nama: {{ Js::from($u->name) }} }"
                                        class="font-medium text-red-600 hover:underline">Nonaktifkan</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">{{ $pesanKosong }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Popup nonaktifkan --}}
    <div x-show="nonaktif" style="display:none" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="nonaktif = null"></div>

        <form method="POST" :action="nonaktif ? urlStatus(nonaktif.uuid) : '#'" role="alertdialog"
              class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
            @csrf
            @method('PATCH')

            <div class="px-6 pt-6">
                <h3 class="text-lg font-semibold">Yakin nonaktifkan akun ini?</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Akun <span class="font-semibold text-gray-800" x-text="nonaktif && nonaktif.nama"></span>
                    akan dinonaktifkan.
                </p>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-gray-200 px-6 py-4">
                <button type="button" @click="nonaktif = null"
                        class="rounded-lg border border-gray-300 px-4 py-2 font-medium hover:bg-gray-50">Batal</button>
                <button class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700">Ya, nonaktifkan</button>
            </div>
        </form>
    </div>
</div>