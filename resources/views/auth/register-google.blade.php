@extends('layouts.guest')
@section('title', 'Lengkapi Pendaftaran')

@section('content')
    <form method="POST" action="{{ route('register.google') }}" class="space-y-4">
        @csrf
        <x-input label="Email" name="email" :value="$pending['email']" readonly class="bg-gray-100 text-gray-500" />
        <x-input label="Nama" name="name" :value="$pending['name']" required />
        <x-input label="No. Telp" name="contact" inputmode="tel" required />
        <x-password label="Password (boleh dikosongkan)" minlength="8" />
        <p class="text-xs text-gray-500">

        </p>
        <button class="w-full rounded-lg bg-blue-600 text-white py-2 font-medium hover:bg-blue-700">Selesai Daftar</button>
    </form>

    <p class="text-center text-sm mt-6"><a href="{{ route('login') }}" class="text-blue-600">Batal, kembali ke login</a></p>
@endsection