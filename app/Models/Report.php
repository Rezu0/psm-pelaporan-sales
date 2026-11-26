<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'segment_id', 'company_name', 'contact_name',
        'contact_email', 'contact_phone', 'market_date', 'description',
    ];

    protected $casts = ['market_date' => 'datetime'];

    public function segment()
    {
        return $this->belongsTo(Segment::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ReportImage::class);
    }

    public function histories()
    {
        return $this->hasMany(ReportHistory::class);
    }

    public function getFotoAttribute()
    {
        return $this->images->sortByDesc('id')->first();
    }

    /**
     * Teks laporan + link kartu, dipakai tombol Copy dan popup WhatsApp.
     */
    public function teksBagikan(): string
    {
        return implode("\n", [
            'LAPORAN SALES',
            '',
            'Perusahaan: ' . $this->company_name,
            'Segmen: ' . ($this->segment->nama ?? '-'),
            'Nama kontak: ' . ($this->contact_name ?: '-'),
            'No. telepon: ' . ($this->contact_phone ?: '-'),
            'Email: ' . ($this->contact_email ?: '-'),
            'Tanggal kunjungan: ' . $this->market_date->format('d/m/Y H:i'),
            'Deskripsi: ' . ($this->description ?: '-'),
            '',
            'Lihat lengkap dengan foto:',
            URL::signedRoute('laporan.bagikan', ['id' => $this->id]),
        ]);
    }

    public function scopeMilik($query, $userId)
    {
        return $query->with(['segment', 'images'])
            ->withCount(['histories as diubah' => fn ($q) => $q->where('aksi', 'ubah')])
            ->where('user_id', $userId)
            ->latest()
            ->latest('id');
    }

    /**
     * Pencarian longgar: tanda baca diabaikan, urutan kata bebas,
     * tiap kata cukup cocok sebagian.
     */
    public function scopeCari($query, ?string $kataKunci)
    {
        $kataKunci = trim((string) $kataKunci);

        if ($kataKunci === '') {
            return $query;
        }

        $kolom = ['company_name', 'contact_name', 'contact_email', 'contact_phone'];

        // buang titik, koma, dan strip di data supaya "PT." sama dengan "PT"
        $bersih = fn ($k) => "REPLACE(REPLACE(REPLACE({$k}, '.', ''), ',', ''), '-', '')";

        // buang tanda baca di kata kunci, lalu pecah per kata
        $kata = preg_split('/\s+/', preg_replace('/[.,\-]/', ' ', $kataKunci), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($kata as $k) {
            $k = addcslashes($k, '%_\\');

            // tiap kata harus ketemu di salah satu kolom atau nama segmen
            $query->where(function ($q) use ($k, $kolom, $bersih) {
                foreach ($kolom as $col) {
                    $q->orWhereRaw($bersih($col).' LIKE ?', ['%'.$k.'%']);
                }

                $q->orWhereHas('segment', fn ($s) => $s->where('nama', 'like', '%'.$k.'%'));
            });
        }

        return $query;
    }
}