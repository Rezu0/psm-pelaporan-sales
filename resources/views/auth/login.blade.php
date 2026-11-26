@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <x-input label="Email" name="email" type="email" required autofocus />
        <x-password required />
        <button class="w-full rounded-lg bg-blue-600 text-white py-2 font-medium hover:bg-blue-700">Masuk</button>
    </form>

    <p class="text-center text-sm text-gray-400 my-4">atau</p>
    <x-google-button :href="route('google.redirect', ['mode' => 'login'])">Masuk dengan Google</x-google-button>

    <p class="text-center text-sm text-gray-600 mt-6">
        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-medium">Daftar</a>
    </p>

    <x-popup />
@endsection