<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportHistory extends Model
{
    protected $guarded = [];

    // Tabel ini hanya punya created_at
    const UPDATED_AT = null;

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function pengubah()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
