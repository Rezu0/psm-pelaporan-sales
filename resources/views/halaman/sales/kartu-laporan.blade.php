<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan {{ $item->company_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 p-4 text-gray-700 sm:p-8">

    <div class="mx-auto flex w-full max-w-5xl flex-col overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-lg md:flex-row">

        {{-- Foto --}}
        @if ($item->foto)
            <div class="flex items-center justify-center bg-gray-100 p-4 md:w-2/5 md:p-6">
                <img src="{{ asset('storage/' . $item->foto->path) }}"
                     alt="Foto {{ $item->company_name }}"
                     data-lightbox
                     class="max-h-[70vh] w-auto max-w-full cursor-zoom-in rounded-2xl object-contain shadow-md">
            </div>
        @endif

        {{-- Data --}}
        <div class="flex-1 p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Laporan Sales</p>

            <h1 class="mt-1 text-2xl font-bold text-gray-900">{{ $item->company_name }}</h1>

            <span class="mt-2 inline-block rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                {{ $item->segment->nama ?? '-' }}
            </span>

            <dl class="mt-6 grid gap-x-8 gap-y-5 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-xs text-gray-400">Nama sales</dt>
                    <dd class="mt-0.5 font-semibold text-gray-900">{{ $item->user->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs text-gray-400">Tanggal kunjungan</dt>
                    <dd class="mt-0.5 font-semibold text-gray-900">{{ $item->market_date->format('d M Y, H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-xs text-gray-400">Nama kontak</dt>
                    <dd class="mt-0.5 font-semibold text-gray-900">{{ $item->contact_name ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs text-gray-400">No. telepon</dt>
                    <dd class="mt-0.5 font-semibold text-gray-900">
                        @if ($item->contact_phone)
                            <a href="tel:{{ $item->contact_phone }}" class="hover:text-blue-600">{{ $item->contact_phone }}</a>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-xs text-gray-400">Email</dt>
                    <dd class="mt-0.5 break-all font-semibold text-gray-900">
                        @if ($item->contact_email)
                            <a href="mailto:{{ $item->contact_email }}" class="hover:text-blue-600">{{ $item->contact_email }}</a>
                        @else
                            -
                        @endif
                    </dd>
                </div>
            </dl>

            <div class="mt-6 border-t border-gray-200 pt-5">
                <p class="text-xs text-gray-400">Deskripsi</p>
                <p class="mt-1 whitespace-pre-line wrap-break-word text-sm leading-relaxed text-gray-800">{{ $item->description ?: '-' }}</p>
            </div>
        </div>
    </div>

    <x-lightbox />
</body>
</html>