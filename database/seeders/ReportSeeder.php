<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Segment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Pengisi laporan: akun sales yang aktif
        $sales  = User::where('role', 'sales')->where('is_active', true)->get();
        $segmen = Segment::all();

        if ($sales->isEmpty() || $segmen->isEmpty()) {
            $this->command->warn('Belum ada akun sales aktif atau segmen, seeder dibatalkan.');
            return;
        }

        $faker = fake('id_ID');

        $deskripsi = [
            'Kunjungan perkenalan produk, pemilik tertarik dan minta penawaran harga.',
            'Follow-up penawaran minggu lalu, masih menunggu persetujuan pimpinan.',
            'Presentasi katalog terbaru, calon pelanggan meminta contoh produk.',
            'Kunjungan rutin, pelanggan memesan tambahan stok bulan depan.',
            'Calon pelanggan masih membandingkan dengan pemasok lain.',
        ];

        for ($i = 1; $i <= 30; $i++) {
            // Waktu laporan dikirim: tersebar dalam 7 hari terakhir
            $waktuInput = now()->subDays(rand(0, 6))->subMinutes(rand(10, 300));

            // Waktu kunjungan: sebelum laporan dikirim (tidak boleh di masa depan)
            $waktuKunjungan = $waktuInput->copy()->subHours(rand(1, 48));

            Report::create([
                'user_id'       => $sales->random()->id,
                'segment_id'    => $segmen->random()->id,
                'company_name'  => $faker->company(),
                'contact_name'  => $faker->name(),
                'contact_phone' => $i % 4 === 0 ? null : $faker->phoneNumber(),
                'contact_email' => $faker->safeEmail(),
                'market_date'   => $waktuKunjungan,
                'description'   => $deskripsi[array_rand($deskripsi)],
                'created_at'    => $waktuInput,
                'updated_at'    => $waktuInput,
                // Tiga laporan contoh yang berstatus "dihapus", untuk tes tampilan nanti
                'deleted_at'    => $i % 10 === 0 ? now() : null,
            ]);
        }
    }
}