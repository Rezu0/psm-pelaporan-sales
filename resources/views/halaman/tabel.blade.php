<section class="space-y-3">

    @include('halaman.pilih-tanggal')

    @include('halaman.tabel-laporan', [
        'aksi'        => route('dashboard'),
        'tersembunyi' => ['tanggal' => $tanggal->format('Y-m-d')],
        'resetUrl'    => route('dashboard', ['tanggal' => $tanggal->format('Y-m-d'), 'baris' => $perHalaman]),
        'kelompok'    => false,
        'pesanKosong' => 'Belum ada laporan masuk di tanggal ini.',
    ])
</section>