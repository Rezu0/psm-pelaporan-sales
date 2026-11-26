<?php

namespace App\Support;

use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\Segment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RiwayatLaporan
{
    private const LABEL = [
        'segment_id'    => 'Segmen',
        'company_name'  => 'Nama perusahaan',
        'contact_name'  => 'Nama kontak',
        'contact_email' => 'Email kontak',
        'contact_phone' => 'No. telepon',
        'market_date'   => 'Tanggal kunjungan',
        'description'   => 'Deskripsi',
    ];

    // Hasil: daftar bagian yang berubah (kosong kalau tidak ada)
    public static function cariPerubahan(Report $laporan, array $data): array
    {
        $namaSegmen = Segment::pluck('nama', 'id');
        $hasil      = [];

        foreach (self::LABEL as $field => $label) {
            [$lama, $baru] = self::samakan($field, $laporan->{$field}, $data[$field] ?? null);

            if ($lama === $baru) {
                continue;
            }

            $hasil[] = [
                'kolom'    => $label,
                'isi_lama' => self::tampil($field, $lama, $namaSegmen),
                'isi_baru' => self::tampil($field, $baru, $namaSegmen),
            ];
        }

        return $hasil;
    }

    public static function catatUbah(Report $laporan, array $perubahan): void
    {
        foreach ($perubahan as $baris) {
            ReportHistory::create($baris + [
                'report_id'  => $laporan->id,
                'changed_by' => Auth::id(),
                'aksi'       => 'ubah',
            ]);
        }
    }

    public static function catatHapus(Report $laporan): void
    {
        ReportHistory::create([
            'report_id'  => $laporan->id,
            'changed_by' => Auth::id(),
            'aksi'       => 'hapus',
        ]);
    }

    private static function samakan(string $field, $lama, $baru): array
    {
        return match ($field) {
            'market_date' => [Carbon::parse($lama)->format('Y-m-d H:i'), Carbon::parse($baru)->format('Y-m-d H:i')],
            'segment_id'  => [(int) $lama, (int) $baru],
            default       => [trim((string) $lama), trim((string) $baru)],
        };
    }

    private static function tampil(string $field, $nilai, $namaSegmen): ?string
    {
        return match ($field) {
            'segment_id'  => $namaSegmen[$nilai] ?? null,
            'market_date' => Carbon::parse($nilai)->format('d M Y H:i'),
            default       => $nilai === '' ? null : $nilai,
        };
    }
}
