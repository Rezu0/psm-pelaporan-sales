@extends('layouts.app')
@section('judul', 'Isi Laporan')

@section('content')
<x-popup />

<h1 class="text-2xl font-bold">Isi Laporan</h1>

@include('halaman.form-laporan', [
    'aksi'    => route('laporan.store'),
    'metode'  => 'POST',
    'laporan' => null,
    'foto'    => null,
    'batal'   => route('beranda'),
    'tombol'  => 'Kirim Laporan',
])
@endsection
