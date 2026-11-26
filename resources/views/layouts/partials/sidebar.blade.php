
@php
    $user = auth()->user();
    $menu = [];

    if (in_array($user->role, ['admin', 'owner'])) {
        $menu[] = ['Dashboard', 'dashboard', 'dashboard'];
    } else {
        $menu[] = ['Dashboard', 'beranda', 'beranda'];
        $menu[] = ['Isi Laporan', 'laporan.create', 'laporan.create'];
        $menu[] = ['Laporan Saya', 'laporan.saya', 'laporan.saya'];
    }

    // Menu khusus Owner
    if ($user->role === 'owner') {
        $menu[] = ['Kelola Akun', 'akun.index', 'akun.*'];
    }

    // Menu khusus Admin
    if ($user->role === 'admin') {
        $menu[] = ['Kelola Segmen', 'segmen.index', 'segmen.*'];
    }
@endphp


<div x-show="open" x-cloak @click="open = false"
     class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

{{-- Sidebar --}}
<aside x-cloak
       :class="open ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg flex flex-col transition-transform duration-200">

    <div class="h-14 flex items-center justify-between px-4 border-b">
        <span class="font-bold text-blue-600">Pelaporan Sales</span>
        <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none"
                aria-label="Tutup sidebar">&times;</button>
    </div>

    <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
        @foreach ($menu as [$judul, $route, $pola])
            <a href="{{ route($route) }}"
               class="block rounded-lg px-3 py-2 text-sm
                      {{ request()->routeIs($pola) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                {{ $judul }}
            </a>
        @endforeach
    </nav>

    <div class="p-3 border-t">
        <a href="{{ route('profil.edit') }}"
            class="mb-2 block rounded-lg px-3 py-2 {{ request()->routeIs('profil.*') ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                <p class="truncate text-sm font-medium {{ request()->routeIs('profil.*') ? 'text-blue-600' : 'text-gray-900' }}">{{ $user->name }}</p>
                <p class="text-xs capitalize text-gray-400">{{ $user->role }}</p>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
        </form>
    </div>
</aside>