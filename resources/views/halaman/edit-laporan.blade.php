@extends('layouts.app')
@section('judul', 'Edit Laporan')

@section('content')
<x-popup />

<h1 class="text-2xl font-bold">Edit Laporan</h1>

@include('halaman.form-laporan', [
    'aksi'    => route('laporan.update', $laporan->id),
    'metode'  => 'PUT',
    'laporan' => $laporan,
    'foto'    => $foto,
    'batal'   => $batal,
    'tombol'  => 'Simpan Perubahan',
])
@endsection