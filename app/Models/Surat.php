<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'nomor_surat',
        'perihal',
        'tujuan_id',
        'ditujukan_kepada',
        'jenis_surat',
        'tanggal_surat',
    ];

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class);
    }
}
