@php
    $format = $mulai->year === $tanggal->year ? 'j M' : 'j M Y';
    $judulRentang = $mulai->translatedFormat($format).' – '.$tanggal->translatedFormat('j M Y');

    $panjang = $mulai->diffInDays($tanggal) + 1;
    $mundurDari   = $mulai->copy()->subDays($panjang);
    $mundurSampai = $tanggal->copy()->subDays($panjang);
    $majuSampai   = $tanggal->copy()->addDays($panjang);
    if ($majuSampai->isFuture()) {
        $majuSampai = today();
    }
    $majuDari = $majuSampai->copy()->subDays($panjang - 1);

    $kotakTanggal = 'w-full min-w-0 rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200';
@endphp

<div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

    <h2 class="text-lg font-semibold leading-snug sm:text-2xl">
        Laporan masuk: <span class="whitespace-nowrap">{{ $judulRentang }}</span>
        <span class="ms-1 rounded-full bg-blue-100 px-2.5 py-0.5 align-middle text-sm text-blue-700">{{ $laporan->total() }}</span>
    </h2>

    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-2 lg:flex-row lg:items-end">

        <div class="grid grid-cols-2 gap-2 lg:flex lg:gap-2">
            <label class="block min-w-0">
                <span class="mb-1 block text-xs text-gray-500">Dari</span>
                <input type="date" name="dari" value="{{ $mulai->format('Y-m-d') }}" max="{{ today()->format('Y-m-d') }}"
                       onchange="this.form.submit()" class="{{ $kotakTanggal }}">
            </label>

            <label class="block min-w-0">
                <span class="mb-1 block text-xs text-gray-500">Sampai</span>
                <input type="date" name="sampai" value="{{ $tanggal->format('Y-m-d') }}" max="{{ today()->format('Y-m-d') }}"
                       onchange="this.form.submit()" class="{{ $kotakTanggal }}">
            </label>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('dashboard', ['dari' => $mundurDari->format('Y-m-d'), 'sampai' => $mundurSampai->format('Y-m-d')]) }}"
               class="flex-1 rounded-xl border border-gray-300 px-4 py-2 text-center text-sm text-gray-700 hover:bg-gray-50 lg:flex-none">&larr;</a>

            @if ($tanggal->isToday())
                <span class="flex-1 rounded-xl border border-gray-200 px-4 py-2 text-center text-sm text-gray-300 lg:flex-none">&rarr;</span>
            @else
                <a href="{{ route('dashboard', ['dari' => $majuDari->format('Y-m-d'), 'sampai' => $majuSampai->format('Y-m-d')]) }}"
                   class="flex-1 rounded-xl border border-gray-300 px-4 py-2 text-center text-sm text-gray-700 hover:bg-gray-50 lg:flex-none">&rarr;</a>
            @endif

            <a href="{{ route('dashboard') }}"
               class="flex-2 whitespace-nowrap rounded-xl bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700 lg:flex-none">7 hari terakhir</a>
        </div>
    </form>
</div>