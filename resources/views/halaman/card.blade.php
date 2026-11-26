@php
    $dasar = 'block rounded-2xl bg-white p-6 shadow-sm';
@endphp

@if ($url)
    <a href="{{ $url }}" class="{{ $dasar }} transition hover:shadow-md hover-ring-1 hover:ring-blue-200">
        <p class="text-sm text-gray-500">{{ $judul }}</p>
        <p class="mt-1 text-4xl font-bold text-gray-900">{{ $angka }}</p>
        <span class="mt-2 inline-block text-xs text-blue-600">Lihat Detail &rarr;</span>
    </a>
@else
    <div class="{{ $dasar }}">
        <p class="text-sm text-gray-500">{{ $judul }}</p>
        <p class="mt-1 text-4xl font-bold text-gray-900">{{ $angka }}</p>
    </div>
@endif