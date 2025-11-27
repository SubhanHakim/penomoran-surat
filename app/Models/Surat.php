<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    // ...existing code...
    protected $fillable = [
        'nomor_surat',
        'perihal',
        'daftar_pengirim',
        'kode_klafifikasi',
        'ditujukan_kepada',
        'jenis_surat',
        'bulan',
        'tahun',
    ];

    /**
     * Generate next sequence number (3 digits string) per kode_klafifikasi.
     * Returns padded string like "001". You can cast to int to remove padding.
     */
    public static function generateNextNumber(?string $kode = null): int
    {
        $query = static::query();
        if ($kode) {
            $query->where('kode_klafifikasi', $kode);
        }
        $last = $query->orderByDesc('created_at')->value('nomor_surat');
        if (! $last) {
            return 1;
        }
        if (preg_match('/(\d+)(?!.*\d)/', $last, $m)) {
            return intval($m[1]) + 1;
        }
        return $query->count() + 1;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->nomor_surat)) {
                // nomor hanya urut angka tanpa kode
                $model->nomor_surat = (string) static::generateNextNumber($model->kode_klafifikasi ?? null);
            }
            if (($model->jenis_surat ?? null) !== 'masuk') {
                $model->perihal = null;
                $model->ditujukan_kepada = null;
            }
        });
    }

    public function getBulanRomanAttribute(): ?string
    {
        $bulan = $this->bulan;
        if (! $bulan) {
            return null;
        }

        $map = [
            12 => 'XII',
            11 => 'XI',
            10 => 'X',
            9 => 'IX',
            8 => 'VIII',
            7 => 'VII',
            6 => 'VI',
            5 => 'V',
            4 => 'IV',
            3 => 'III',
            2 => 'II',
            1 => 'I',
        ];

        return $map[(int)$bulan] ?? (string)$bulan;
    }

    // ...existing code...
}
