<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'nomor_surat',
        'perihal',
        "daftar_pengirim",
        "kode_klafifikasi",
        'ditujukan_kepada',
        'jenis_surat',
        'bulan'
    ];

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class);
    }

    public static function generateNextNumber(): string
    {
        $last = static::query()
            ->orderByDesc('created_at')
            ->value('nomor_surat');

        $next = 1;

        if ($last) {
            if (preg_match('/(\d+)$/', $last, $m)) {
                $next = intval($m[1]) + 1;
            } else {
                $next = static::count() + 1;
            }
        }

        return str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::creating(function (Surat $model) {
            if (empty($model->nomor_surat)) {
                $model->nomor_surat = static::generateNextNumber();
            }
        });
    }

    public function getBulanRomanAttribute(): ?string
    {
        $bulan = $this->bulan;
        if (! $bulan) {
            return null;
        }

        return self::intToRoman((int) $bulan);
    }

     public static function intToRoman(int $num): string
    {
        $map = [
            12 => 'XII',
            11 => 'XI',
            10 => 'X',
            9  => 'IX',
            8  => 'VIII',
            7  => 'VII',
            6  => 'VI',
            5  => 'V',
            4  => 'IV',
            3  => 'III',
            2  => 'II',
            1  => 'I',
        ];

        return $map[$num] ?? (string) $num;
    }
}
