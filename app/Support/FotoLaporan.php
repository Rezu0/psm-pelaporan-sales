<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class FotoLaporan
{
    public static function simpan(UploadedFile $file): array
    {
        $asli = @imagecreatefromstring(file_get_contents($file->path()));

        if (! $asli) {
            throw new RuntimeException('Foto tidak bisa dibaca. Coba pilih foto lain.');
        }

        if (in_array($file->extension(), ['jpg', 'jpeg']) && function_exists('exif_read_data')) {
            $arah = @exif_read_data($file->path())['Orientation'] ?? 1;

            $asli = match ($arah) {
                3       => imagerotate($asli, 180, 0),
                6       => imagerotate($asli, -90, 0),
                8       => imagerotate($asli, 90, 0),
                default => $asli,
            };
        }

        $lebar  = imagesx($asli);
        $tinggi = imagesy($asli);
        $skala  = min(1, 1600 / max($lebar, $tinggi));

        $lebarBaru  = (int) round($lebar * $skala);
        $tinggiBaru = (int) round($tinggi * $skala);

        $baru = imagecreatetruecolor($lebarBaru, $tinggiBaru);
        imagefill($baru, 0, 0, imagecolorallocate($baru, 255, 255, 255));
        imagecopyresampled($baru, $asli, 0, 0, 0, 0, $lebarBaru, $tinggiBaru, $lebar, $tinggi);

        ob_start();
        imagejpeg($baru, null, 85);
        $hasil = ob_get_clean();

        $path = 'laporan/'.now()->format('Y/m').'/'.Str::uuid().'.jpg';
        Storage::disk('public')->put($path, $hasil);

        return [$path, strlen($hasil)];
    }

    /**
     * Pindahkan foto ke subfolder "delete" di folder bulannya.
     * laporan/2026/11/abc.jpg -> laporan/2026/11/delete/abc.jpg
     */
    public static function pindahKeDihapus(string $path): string
    {
        // sudah di folder delete, jangan dipindah lagi
        if (str_contains($path, '/delete/')) {
            return $path;
        }

        return self::pindah($path, dirname($path).'/delete/'.basename($path));
    }

    public static function pindah(string $dari, string $ke): string
    {
        $disk = Storage::disk('public');

        if ($dari === $ke || ! $disk->exists($dari)) {
            return $dari;
        }

        $disk->makeDirectory(dirname($ke));
        $disk->move($dari, $ke);

        return $ke;
    }

    public static function hapus(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}