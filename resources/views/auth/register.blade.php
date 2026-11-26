@extends('layouts.guest')
@section('title', 'Daftar Akun')

@section('content')
    <x-google-button :href="route('google.redirect', ['mode' => 'register'])">Daftar dengan Google</x-google-button>

    <p class="text-center text-sm text-gray-400 my-4">atau</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <x-input label="Nama" name="name" required />
        <x-input label="No. Telp" name="contact" inputmode="tel" required />
        <x-input label="Email" name="email" type="email" required />
        <x-password required minlength="8" />
        <button class="w-full rounded-lg bg-blue-600 text-white py-2 font-medium hover:bg-blue-700">Daftar</button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-medium">Masuk</a>
    </p>
@endsection