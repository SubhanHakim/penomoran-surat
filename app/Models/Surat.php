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
    public static function generateNextNumber(?string $kodeKlasifikasi = null): string
    {
        $query = static::query();

        if ($kodeKlasifikasi) {
            $query->where('kode_klafifikasi', $kodeKlasifikasi);
        }

        $last = $query->orderByDesc('created_at')->value('nomor_surat');

        $next = 1;

        if ($last) {
            // last may be "000.001" or "001" etc — extract trailing number
            if (preg_match('/(\d+)(?!.*\d)/', $last, $m)) {
                $next = intval($m[1]) + 1;
            } else {
                $next = $query->count() + 1;
            }
        } else {
            // if none, next = 1
            $next = 1;
        }

        return str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // jika nomor_surat belum diset, buat berdasarkan kode_klafifikasi + sequence
            if (empty($model->nomor_surat)) {
                if (! empty($model->kode_klafifikasi)) {
                    // gunakan generateNextNumber lalu cast ke int supaya tidak ada leading zeros
                    $seq = intval(static::generateNextNumber($model->kode_klafifikasi));
                    $model->nomor_surat = "{$model->kode_klafifikasi}.{$seq}";
                } else {
                    // fallback: hanya sequence
                    $seq = intval(static::generateNextNumber(null));
                    $model->nomor_surat = (string) $seq;
                }
            }

            // kosongkan field yang tidak relevan untuk surat keluar
            if (($model->jenis_surat ?? null) !== 'masuk') {
                $model->perihal = null;
                $model->ditujukan_kepada = null;
            }
        });

        static::updating(function ($model) {
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
            12 => 'XII', 11 => 'XI', 10 => 'X', 9 => 'IX',
            8 => 'VIII', 7 => 'VII', 6 => 'VI', 5 => 'V',
            4 => 'IV', 3 => 'III', 2 => 'II', 1 => 'I',
        ];

        return $map[(int)$bulan] ?? (string)$bulan;
    }

    // ...existing code...
}