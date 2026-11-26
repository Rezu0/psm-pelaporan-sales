@php
    $laporanBaru = session('bagikan')
        ? \App\Models\Report::with('segment')
            ->where('user_id', auth()->id())
            ->find(session('bagikan'))
        : null;
@endphp

@if ($laporanBaru)
    <div x-data="{ buka: true }" x-show="buka" x-transition.opacity
         @keydown.escape.window="buka = false"
         class="fixed inset-0 z-90 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="buka = false"></div>

        <div class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
            <div class="px-6 pt-6">
                <h3 class="text-lg font-semibold text-gray-900">Laporan tersimpan</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Laporan <span class="font-semibold text-gray-800">{{ $laporanBaru->company_name }}</span>
                    sudah masuk. Mau langsung dibagikan ke WhatsApp?
                </p>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-gray-200 px-6 py-4">
                <button type="button" @click="buka = false"
                        class="rounded-lg border border-gray-300 px-4 py-2 font-medium hover:bg-gray-50">
                    Nanti saja
                </button>

                <a href="https://wa.me/?text={{ rawurlencode($laporanBaru->teksBagikan()) }}"
                   target="_blank" rel="noopener" @click="buka = false"
                   class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                    <i class="fa-brands fa-whatsapp"></i> Bagikan ke WhatsApp
                </a>
            </div>
        </div>
    </div>
@endif